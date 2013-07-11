

window.AlbumView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#photo-album-tpl').html())
    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    events: {
        'click #create-album': 'createAlbum',
    },
    createAlbum: function() {

        var self = this;
        this.model.set({
            name: $('#name').val(),
        });
        this.model.url = '../logged/api/account/' + acc_id + '/albums/' + this.model.get('name');
        if (this.model.isNew()) {
            app.albums.create(self.model, {
                success: function() {
                    app.navigate('album/' + self.model.id, true);
                    $('#name').val('');
                }
            });
        }
        return false;
    }
})