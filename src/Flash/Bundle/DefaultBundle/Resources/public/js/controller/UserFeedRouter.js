$(document).ready(function() {

    window.UserFeedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents"
        },
        initialize: function() {
            this.url = 'api/logged/events';
            console.log('Starting User Feed router');
        },
        showNotConfirmed: function() {
            $('#pre-feed').css('display', 'block');
            $('#feed').css('display', 'none');
            return false;
        },
        showEvents: function() {

            var self = this;
            this.events = new UserEventCollection();

            this.events.url = this.url + "/0/15";
            this.events.fetch({success: function(data) {
                    var view = new UserEventListView({model: self.events});
                    view.confirmed = true;
                    $('#feed').append(view.render().el);
                    self.getPreEventList();
                }});
        },
        showSuccess: function() {
            $('#success').show();
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new UserFeedRouter();
    Backbone.history.start();
});