var CalendarEventModel = Backbone.Model.extend({
    urlRoot: 'calendar/events',
    defaults: {
        "id": null,
        "title": "",
        "text": "",
        "start": "",
        "end": "",
        "allDay": "",
        "isShown": false,
        "color": 'green'
    }
});

var CalendarEventsCollection = Backbone.Collection.extend({
    model: CalendarEventModel,
    url: 'calendar/events'
});