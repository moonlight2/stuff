
window.FeedEventView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#feed-event-tpl').html());
        this.url = 'logged/api/feed/events';
        this.modUrl = 'moderator/api/feed/events';
        this.rendered = false;
    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        this.rendered = true;
        return this;
    },
    events: {
        'click #create-event': 'saveEvent',
        'click #show-pre-feed': 'switchFeed',
    },
    
    
    switchFeed: function() {
        if (Backbone.history.fragment == '') {
            app.navigate('not_confirmed', true);
        } else {
            app.navigate('', true);
        }
    },
    getEventList: function() {

        var self = this;
        this.events = new EventCollection();
        this.events.url = this.url;
        this.events.fetch({success: function(data) {
                var view = new EventListView({model: self.events});
                view.confirmed = true;
                $('#feed').append(view.render().el);
                self.getPreEventList();
            }});
    },
    getPreEventList: function() {

        if ($('#pre-feed').length == 1) {
            var self = this;
            this.events = new EventCollection();
            this.events.url = this.modUrl;
            this.events.fetch({success: function(data) {
                    var view = new EventListView({model: self.events});
                    view.confirmed = false;
                    $('#pre-feed').append(view.render().el);
                }});
        }
        return false;
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
