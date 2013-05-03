
window.UserEventModel = Backbone.Model.extend({
    urlRoot: 'logged/rest/api/user_events',
    defaults: {
        "id": null,
        "title": "",
        "description": "",
        "date": "",
    }
});

window.UserEventCollection = Backbone.Collection.extend({
    model: UserEventModel,
    url: 'logged/rest/api/user_events'
});