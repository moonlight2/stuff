
window.UserEventModel = Backbone.Model.extend({
    urlRoot: 'user_events',
    defaults: {
        "id": null,
        "title": "",
        "description": "",
        "edate": "",
    }
});

window.UserEventCollection = Backbone.Collection.extend({
    model: UserEventModel,
    url: 'user_events'
});