window.EventModel = Backbone.Model.extend({
    urlRoot: 'rest/api/events',
    defaults: {
        "id": null,
        "name": "",
        "description": "",
        "date": "",
        "country": "",
        "city": "",
    }
});

window.EventCollection = Backbone.Collection.extend({
    model: EventModel,
    url: 'rest/api/events'
});