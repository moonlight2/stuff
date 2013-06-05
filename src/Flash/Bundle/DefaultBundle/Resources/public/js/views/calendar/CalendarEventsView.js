var CalendarEventsView = Backbone.View.extend({
    initialize: function() {
        this.el = $('#calendar');
        _.bindAll(this);
        this.collection = new CalendarEventsCollection();
        this.collection.bind('reset', this.addAll);
        this.collection.bind('add', this.addOne);
        this.collection.bind('change', this.change);
        this.collection.bind('destroy', this.destroy);
    },
    render: function() {

        this.showLoader();

        this.el.fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month, agendaDay',
                prev: 'circle-triangle-w',
                next: 'circle-triangle-e'
            },
            eventClick: this.eventClick,
            eventDrop: this.eventDropOrResize,
            eventResize: this.eventDropOrResize,
            selectable: true,
            selectHelper: true,
            editable: true,
            select: this.select,
        });

        var self = this;
        this.collection.fetch({success: function(data) {
                console.log(data);
               self.hideLoader();
            }});
    },
    eventClick: function(e) {

        var eventView = new DialogEventView({
            model: this.collection.get(e.id),
            collection: this.collection
        });

        eventView.render();
    },
    addOne: function(event) {

        this.el.fullCalendar('renderEvent', event.toJSON());
    },
    change: function(e) {
        var fcEvent = this.el.fullCalendar('clientEvents', e.get('id'))[0];
        fcEvent.title = e.get('title');
        fcEvent.color = e.get('text');
        this.el.fullCalendar('updateEvent', fcEvent);
    },
    select: function(start, end, allDay) {

        var timeStamp = new Date();
        var startDate = new Date(start);

        if (startDate >= timeStamp) {
            var event = new CalendarEventModel({
                start: start,
                end: end,
                allDay: allDay
            });
            console.log('My model');
            console.log(event);
            var eventView = new DialogEventView({
                model: event,
                collection: this.collection
            });
            eventView.render();
        }
        return false;
    },
    addAll: function() {
        this.el.fullCalendar('addEventSource', this.collection.toJSON());
    },
    destroy: function(event) {
        this.el.fullCalendar('removeEvents', event.id);
    },
    eventDropOrResize: function(e) {
        this.collection.get(e.id).save({start: e.start, end: e.end});
    },
    showLoader: function() {
        $('#loader').show();
    },
    hideLoader: function() {
        $('#loader').hide();
    }
});