

window.PhotoAlbumListView = Backbone.View.extend({
    tagName: 'div',
    initialize: function() {
        this.model.bind('reset', this.render, this);
        this.model.bind('add', this.appendLast, this);
    },
    render: function(eventName) {
        _.each(this.model.models, function(album) {
            $(this.el).attr('class', 'photo-albums').append(new PhotoAlbumListItemView({model: album}).render().el);
        }, this);
        return this;
    },
    appendLast: function() {
        var album = this.model.models[this.model.length - 1];
        $(this.el).attr('class', 'photo-albums').append(new PhotoAlbumListItemView({model: album}).render().el);
        return this;
    },
});

window.PhotoAlbumListItemView = Backbone.View.extend({
    tagName: 'div',
    template: _.template($('#photo-album-list-tpl').html()),
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


