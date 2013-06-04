var CalendarEventModel = Backbone.Model.extend({
    urlRoot: 'events',
    defaults: {
        "id": null,
        "title":"",
        "text": "",
        "start": "",
        "end": "",
        "allDay": "",
    }

});

var CalendarEventsCollection = Backbone.Collection.extend({
    model: CalendarEventModel,
    url: 'events'
});