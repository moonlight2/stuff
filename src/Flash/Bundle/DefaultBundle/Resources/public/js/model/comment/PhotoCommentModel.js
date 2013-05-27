
window.PhotoCommentModel = Backbone.Model.extend({
    urlRoot: 'photo/comment',
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
    url: 'photo/comment'
});