window.GroupModel = Backbone.Model.extend({
    urlRoot: 'rest/api/groups',
    defaults: {
        "id": null,
        "name": "",
        "about": "",
        "rating": "",
        "city": "",
        "country": ""
    }
});

window.GroupCollection = Backbone.Collection.extend({
    model: GroupModel,
    url: 'rest/api/groups'
});