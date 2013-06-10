

window.FeedEventView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#feed-event-tpl').html());
    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    events: {
        'click #create-event': 'saveEvent',
    },
    getEventList: function() {
        var self = this;
        this.events = new EventCollection();
        this.events.fetch({success: function(data) {
                $('#events').append(new EventListView({model: self.events}).render().el);
            }});

    },
    saveEvent: function() {

        alert('Save event');
        return false;

        var self = this;
        this.model.set({
            name: $('#name').val(),
            description: $('#description').val(),
            country: $('#send-country').val(),
            city: $('#send-city').val(),
            date: $('#send-date').val(),
        });
        if (this.model.isNew()) {
            this.model.save(null, {
                success: function(model, response) {
                    //alert('Event has been created');
                    app.navigate('group_events', true);
                },
                error: function(model, response) {
                    self.showErrors(response.responseText);
                    app.navigate('error', true);
                }
            });
        }
        return false;
    },
});
