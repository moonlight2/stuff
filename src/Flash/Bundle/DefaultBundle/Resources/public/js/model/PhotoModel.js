
window.PhototModel = Backbone.Model.extend({
    urlRoot: 'photos',
    defaults: {
        "id": null,
        "name": "",
        "path": "",
        "rating": "",
    }
});

window.PhotoCollection = Backbone.Collection.extend({
    model: PhototModel,
    url: 'photos'
});