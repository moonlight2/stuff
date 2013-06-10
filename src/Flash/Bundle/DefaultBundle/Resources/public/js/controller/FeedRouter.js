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
            
            details.getEventList();
            
            $('#feed').append(details.render().el);
            
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new FeedRouter();
    Backbone.history.start();
});