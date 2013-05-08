$(document).ready(function() {
    window.LoggedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents",
            "new_group": "newGroup",
            "new_event": "newEvent",
            "new_event/error": "showErrors",
            "new_event/success": "showEventSuccess",
            "new_group/success": "showGroupSuccess",
            "events_list": "showGroupEvents"
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
        showGroupEvents: function(){
            alert('Show group events');
        },
        showEventSuccess: function(){
            alert('Buuuu');
        },
        showGroupSuccess: function(){
            alert('Group has been created!!!!');
        },
        newGroup: function() {
            var group = new GroupView({'model': new GroupModel()});
            this.showView('#events', group);
            group.getCountries();
            console.log('new group:');
        },
        newEvent: function() {
            var event = new EventView({'model': new EventModel()});
            this.showView('#events', event);
            event.getCountries();
            console.log('new event');
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new LoggedRouter();
    Backbone.history.start();
});