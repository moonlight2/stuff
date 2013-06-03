

window.PhotoView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#image-details-tpl').html());
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
        'click .comment-delete': 'deleteComment',
        'click .comment-answer': 'answerComment',
        'click .thumb': 'nextPhoto',
    },
    showComments: function() {

        var self = this;
        this.comments = new PhotoCommentCollection();

        var hash = window.location.hash.substring(1);
        this.comments.url = 'photo/' + (hash.split('/'))[1] + '/comment';

        $('#comments').html('');
        this.comments.fetch({success: function(data) {
                $('#comments').append(new PhotoCommentListView({model: self.comments}).render().el);
            }});

    },
    answerComment: function(e) {

        var id = $(e.currentTarget).attr('val');
        var acc = this.comments.get(id).attributes.account;
        
        console.log(acc.first_name);
        console.log(acc.last_name);
        $('textarea#comment').val('');
        $('textarea#comment').val(acc.first_name + ' ' + acc.last_name + ', ');
        
    },
    deleteComment: function(e) {

        var id = $(e.target).attr('val');
        var comment = this.comments.get(id);
        var hash = window.location.hash.substring(1);

        comment.url = 'photo/' + (hash.split('/'))[1] + '/comment/' + id;

        comment.destroy({
            success: function() {
                console.log('destroyed');
            }
        });
        return false;
    },
    likePhoto: function(e) {

        this.model.save(null, {
            success: function(model, response) {
                $('#rating').html(response.rating.length);
            },
            error: function(model, response) {
                console.log("error");
            }
        });
        return false;
    },
    deletePhoto: function(e) {
        this.model.destroy({
            success: function() {
                window.history.back();
            }
        });
        return false;
    },
    nextPhoto: function() {
        app.photos.setElement(this.model);
        var id = app.photos.next().getElement().id;
        app.navigate('#photo/' + id, true);
        return false;
    },
    commentPhoto: function(e) {

        var self = this;
        this.photoComment = new PhotoCommentModel();
        this.button = e.target;
        this.button.disabled = true;
        
        this.photoComment.set({
            text: $('textarea#comment').val(),
            photo_id: $(e.target).attr('val')
        });

        this.photoComment.save(null, {
            success: function(model, response) {
                self.button.disabled = false;
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
