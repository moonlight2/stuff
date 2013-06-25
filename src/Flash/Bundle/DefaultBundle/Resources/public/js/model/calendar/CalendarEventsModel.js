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
        "isShared": false,
        "color": 'green',
        "sharedFor": ""
    },
    validate: function(attrs) {
        var errors = [];

        if (!attrs.title) {
            errors.push({name: 'title', message: 'Please fill title field.'});
        }
        if (!attrs.text) {
            errors.push({name: 'text', message: 'Please fill text field.'});
        }
        return errors.length > 0 ? errors : false;
    }
});

var CalendarEventsCollection = Backbone.Collection.extend({
    model: CalendarEventModel,
    url: 'calendar/events'
});