
window.FeedEventView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#feed-event-tpl').html());
        this.url = 'logged/api/feed/events';
    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    events: {
        'click #create-event': 'saveEvent',
    },
    getEventList: function() {

        console.log('Get event list');
        var self = this;
        this.events = new EventCollection();
        this.events.url = this.url;
        this.events.fetch({success: function(data) {
                $('#feed').append(new EventListView({model: self.events}).render().el);
            }});

    },
    saveEvent: function() {

        var self = this;
        this.model.url = this.url;
        this.model.set({
            name: $('#name').val(),
            description: $('#description').val(),
        });
        if (this.model.isNew()) {
            this.model.save(null, {
                success: function(model, response) {
                    //alert('Event has been created');
                    app.navigate('success', true);
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
