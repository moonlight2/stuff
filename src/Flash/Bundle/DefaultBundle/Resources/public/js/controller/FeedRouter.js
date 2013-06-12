$(document).ready(function() {

    window.FeedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents",
            "not_confirmed": "showNotConfirmed"
        },
        initialize: function() {
            console.log('Starting Feed router');
        },
        showNotConfirmed: function() {
            $('#pre-feed').css('display', 'block');
            return false;
        },
        showEvents: function() {
            //$('#feed').html('');
            //$('#pre-feed').html('');
            $('#pre-feed').css('display', 'none');

            if (!this.details) {
                this.details = new FeedEventView({model: new EventModel()});
                this.details.getEventList();
                $('#feed-form').append(this.details.render().el);
            }
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new FeedRouter();
    Backbone.history.start();
});