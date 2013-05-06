$(document).ready(function() {

    Backbone.View.prototype.close = function() {
        console.log('Closing view :');
        console.log(this);
        if (this.beforeClose) {
            this.beforeClose();
        }
        this.remove();
        this.unbind();
    };

    window.LoggedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents",
            "new_group": "newGroup",
            "new_event": "newEvent"
        },
        initialize: function() {
            console.log('Starting router');
            console.log(this);
        },
        showEvents: function() {
            var eventList = new UserEventView();
            this.showView('#events', eventList);
            eventList.getEventList();
        },
        newGroup: function() {
            var group = new GroupView({'model': new GroupModel()});
            this.showView('#events', group);
            console.log('new group');
        },
        newEvent: function() {
            console.log('new event');
        },
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