$(document).ready(function() {

    window.LoggedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents",
            "new_group": "newGroup",
            "new_event": "newEvent",
            "new_event/error": "showErrors"
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
            var event = new EventView({'model': new EventModel()});
            this.showView('#events', event);
            event.getCountries();
            console.log('new event');
        },
        showErrors: function() {
            console.log('There is some errors');
        },
        showView: function(selector, view) {
            if (this.currentView)
                this.currentView.close();
            $(selector).html(view.render().el);
            this.currentView = view;
            return view;
        },
    });

    app = new LoggedRouter();
    Backbone.history.start();
});