$(document).ready(function() {

    window.EventFeedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents",
            "success": "showSuccess",
            "not_confirmed": "showNotConfirmed"
        },
        initialize: function() {
            console.log('Starting Feed2 router');
        },
        showNotConfirmed: function() {
            $('#pre-feed').css('display', 'block');
            $('#feed').css('display', 'none');
            return false;
        },
        showEvents: function() {

            if (!this.details) {
                this.details = new FeedEventView({model: new EventModel()});
                this.details.getEventList();
                $('#feed').append(this.details.render().el);
            }
        },
        showSuccess: function(){
            $('#success').show();
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new FeedRouter();
    Backbone.history.start();
});