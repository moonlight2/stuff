window.hash = window.location.hash.substring(1);

window.PhotoView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#image-details-tpl').html());
        this.alb_id = (hash.split('/'))[1];
        console.log(this.alb_id);
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
        'click #save': 'saveToAlbum',
        'click .comment-delete': 'deleteComment',
        'click .comment-answer': 'answerComment',
        'click .thumb': 'nextPhoto',
    },
    showComments: function() {

        var self = this;
        var hash = window.location.hash.substring(1);
        this.comments = new PhotoCommentCollection();
        this.comments.url = '../logged/api/account/'
                + acc_id +
                '/photo/' +
                this.model.get('id') +
                '/comment';
        console.log(this.comments);

        $('#comments').html('');
        this.comments.fetch({success: function(data) {
                $('#comments').append(new PhotoCommentListView({model: self.comments}).render().el);
            }});

    },
    answerComment: function(e) {

        var id = $(e.currentTarget).attr('val');
        var acc = this.comments.get(id).attributes.account;

        $('textarea#comment').val('');
        $('textarea#comment').val(acc.first_name + ' ' + acc.last_name + ', ');

    },
    deleteComment: function(e) {

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
        console.log('Next photo');
        app.photos.setElement(this.model);
        var id = app.photos.next().getElement().id;
        app.navigate('#album/' + this.alb_id + '/photo/' + id, true);
        return false;
    },
    commentPhoto: function(e) {

        var self = this;

        var hash = window.location.hash.substring(1);

        this.photoComment = new PhotoCommentModel();
        this.photoComment.url = '../logged/api/account/' + acc_id + '/photo/' + (hash.split('/'))[3] + '/comment';

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
    },
    saveToAlbum: function() {
         this.alb_id = (hash.split('/'))[1];
         console.log(this.alb_id);

        var url = '../logged/api/account/' + acc_id + '/albums/' + this.alb_id + '/photos/' + this.model.get('id') + '/save';
        //alert('Save');
        $.ajax({
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json"
            },
            type: "POST",
            url: url,
            dataType: "json",
            success: function(response) {
                console.log(response);
            }});

    }
});
