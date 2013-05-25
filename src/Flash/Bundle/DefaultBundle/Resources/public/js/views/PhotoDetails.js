

window.PhotoView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#image-details-tpl').html());
        this.model.bind('change', this.render, this);
    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        this.showComments();
        return this;
    },
    events: {
        'click #like': 'likePhoto',
        'click #send': 'commentPhoto',
        'click #delete': 'deletePhoto',
    },
    showComments: function() {

        var self = this;
        this.comments = new PhotoCommentCollection();

        var hash = window.location.hash.substring(1);
        this.comments.url = 'comment/photo/' + (hash.split('/'))[1];

        $('#comments').html('');
        this.comments.fetch({success: function(data) {
                $('#comments').append(new PhotoCommentListView({model: self.comments}).render().el);
            }});

    },
    likePhoto: function(e) {
        console.log($(e.target).attr('val'));
    },
    deletePhoto: function(e) {
        this.model.destroy({
            success: function() {
                window.history.back();
            }
        });
        return false;
    },
    commentPhoto: function(e) {

        var self = this;
        this.photoComment = new PhotoCommentModel();

        this.photoComment.set({
            text: $('textarea#comment').val(),
            photo_id: $(e.target).attr('val')
        });

        this.photoComment.save(null, {
            success: function(model, response) {
                $('textarea#comment').val('');
                self.comments.add(self.photoComment);
            },
            error: function(model, response) {
                alert(response);
            }
        });
        return false;
    }
});
