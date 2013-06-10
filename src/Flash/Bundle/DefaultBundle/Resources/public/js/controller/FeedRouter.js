$(document).ready(function() {

    window.FeedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents",
        },
        initialize: function() {
            console.log('Starting Feed router');
        },
        showEvents: function() {

            var self = this;

            this.events = new UserEventCollection();
            this.events.url = 'p' + $('#acc_id').val() + '/user_events';
            this.events.fetch({success: function(data) {
                    $('#events').append(new UserEventListView({model: self.events}).render().el);
                }});

        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new FeedRouter();
    Backbone.history.start();
});