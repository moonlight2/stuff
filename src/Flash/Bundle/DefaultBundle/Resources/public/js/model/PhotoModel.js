
window.PhototModel = Backbone.Model.extend({
    urlRoot: 'rest/api/photos',
    defaults: {
        "id": null,
        "name": "",
        "path": "",
    }
});

window.PhotoCollection = Backbone.Collection.extend({
    model: PhototModel,
    url: 'rest/api/photos'
});