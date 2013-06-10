$(document).ready(function() {

    window.FeedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents",
        },
        initialize: function() {
            console.log('Starting Feed router');
        },
        showEvents: function() {
            var details = new FeedEventView({model: new EventModel()});
            $('#feed').append(details.render().el);
            console.log(details.render().el);
            var self = this;
//
//            this.events = new EventCollection();
//            this.events.url = 'logged/api/feed/events';
//            this.events.fetch({success: function(data) {
//                    $('#events').append(new EventListView({model: self.events}).render().el);
//                }});

        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new FeedRouter();
    Backbone.history.start();
});