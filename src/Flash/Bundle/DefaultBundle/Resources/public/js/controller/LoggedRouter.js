$(document).ready(function() {

    window.LoggedRouter = Backbone.Router.extend({
        routes: {
            "": "someRoute"
        },
        initialize: function() {
            var eventList = new UserEventView();
            this.showView('#events', eventList);
            eventList.getEventList();
        },
        someRoute: function() {},
        showView: function(selector, view) {
            if (this.currentView)
                this.currentView.close();
            $(selector).html(view.render().el);
            this.currentView = view;
            return view;
        },
    });

    loggedRouter = new LoggedRouter();
    Backbone.history.start();
});