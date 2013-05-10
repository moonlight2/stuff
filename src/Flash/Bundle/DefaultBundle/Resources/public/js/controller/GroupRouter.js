$(document).ready(function() {

    window.LoggedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents",
            "group_events": "showEvents",
            "new_event": "newEvent",
            "new_event/error": "showErrors",
            "new_event/success": "showEventSuccess",
        },
        initialize: function() {
            console.log('Starting router');
            
        },
        showEvents: function() {
            console.log('show group events');
            if (this.currentView)
                this.currentView.close();
            var eventList = new EventView();
            eventList.getEventList();
        },
        showEventSuccess: function() {
            alert('Buuuu');
        },
        newEvent: function() {
            var event = new EventView({'model': new EventModel()});
            this.showView('#events', event);
            event.getCountries();
            
            console.log('new event!');
            $("#date").datepicker();
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new LoggedRouter();
    Backbone.history.start();
});