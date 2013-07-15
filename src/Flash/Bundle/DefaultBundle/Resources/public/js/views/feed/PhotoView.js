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

        this.model.url = 'logged/api/account/' + acc_id + '/albums/garbage/photos/' + this.model.get('id');
        this.model.destroy({
            success: function() {
                //window.history.back();
            }
        });
        return false;
    },
})