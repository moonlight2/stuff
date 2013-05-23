
window.PhototModel = Backbone.Model.extend({
    urlRoot: 'logged/rest/api/photos',
    defaults: {
        "id": null,
        "name": "",
        "path": "",
    }
});

window.PhotoCollection = Backbone.Collection.extend({
    model: PhototModel,
    url: 'logged/rest/api/photos'
});