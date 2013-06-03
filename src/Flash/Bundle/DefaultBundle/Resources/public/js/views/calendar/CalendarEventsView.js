var CalendarEventsView = Backbone.View.extend({
    initialize: function() {
        this.el = $('#calendar');
        _.bindAll(this);
        this.collection = new CalendarEventsCollection();
        this.collection.bind('reset', this.addAll);
        this.collection.bind('add', this.addOne);
    },
    events: {
        "click .fc-event": "showEvent",
    },
    showEvent: function(e) {
        alert('e');
        console.log(e);
    },
    render: function() {

        this.el.fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month, basicDay',
                ignoreTimezone: false,
            },
            eventClick: this.eventClick,
            selectable: true,
            selectHelper: true,
            editable: true,
            select: this.select,
        });

        this.collection.fetch();
    },
    eventClick: function(e) {

        console.log(e.id);
        var eventView = new DialogEventView({
            model: this.collection.get(e.id),
            collection: this.collection
        });
        console.log('EventView:');
        console.log(eventView);
        eventView.render();
    },
    addOne: function(event) {
        console.log(event);
        this.el.fullCalendar('renderEvent', event.toJSON());
    },
    select: function(startDate, endDate) {

        var eventView = new DialogEventView({
            model: new CalendarEventModel({
                start: startDate,
                end: endDate,
                }), collection: this.collection
        });
        eventView.render();
    },
    addAll: function() {
        this.el.fullCalendar('addEventSource', this.collection.toJSON());
    }
});