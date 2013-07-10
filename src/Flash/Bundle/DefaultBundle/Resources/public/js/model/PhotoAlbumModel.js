
window.PhototAlbumModel = Backbone.Model.extend({
    urlRoot: 'logged/api/albums',
    defaults: {
        "id": null,
        "name": "",
        "date_regist": "",
        "rating": "",
        "preview": ""
    }
});

window.PhotoAlbumCollection = Backbone.Collection.extend({
    model: PhototAlbumModel,
    url: 'logged/api/albums',
})
