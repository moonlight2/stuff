window.PhotoView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#photo-tpl').html());
    },
    render: function() {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    events: {
        'click #delete-photo': 'deletePhoto',
    },
    deletePhoto: function(e) {

        var id = $(e.target).attr('val');
        var comment = this.comments.get(id);
        var hash = window.location.hash.substring(1);

        comment.url = '../logged/api/account/' + acc_id + '/photo/' + (hash.split('/'))[3] + '/comment/' + id;

        comment.destroy({
            success: function() {
                console.log('destroyed');
            }
        });
        return false;
    },
})