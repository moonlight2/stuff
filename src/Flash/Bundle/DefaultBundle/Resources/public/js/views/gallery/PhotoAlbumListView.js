

window.PhotoAlbumListView = Backbone.View.extend({
    tagName: 'div',
    render: function(eventName) {
        _.each(this.model.models, function(album) {
            $(this.el).attr('class', 'photo-albums').append(new PhotoAlbumListItemView({model: album}).render().el);
        }, this);
        return this;
    },
});

window.PhotoAlbumListItemView = Backbone.View.extend({
    tagName: 'div',
    template: _.template($('#photo-album-tpl').html()),
    render: function() {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
        this.model.bind('destroy', this.close, this);
    },
    events: {
        'click .album-delete': 'deleteAlbum',
        'click .album-open': 'openAlbum'
    },
    deleteAlbum: function(e) {

        console.log(this);
        this.model.url = '../logged/api/account/' + acc_id + '/albums/' + this.model.get('id');
        this.model.destroy({
            success: function() {
                console.log('destroyed');
            }
        });
        return false;
    },
    openAlbum: function() {
        app.navigate('album/' + this.model.get('id'), true);
    },
});


