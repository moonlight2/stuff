
window.PhotoCommentModel = Backbone.Model.extend({
    urlRoot: 'comment/photo',
    defaults: {
        "id": null,
        "text": "",
        "photo_id": "",
        "account": "",
        "rating": ""
    }
});

window.PhotoCommentCollection = Backbone.Collection.extend({
    model: PhotoCommentModel,
    url: 'comment/photo'
});