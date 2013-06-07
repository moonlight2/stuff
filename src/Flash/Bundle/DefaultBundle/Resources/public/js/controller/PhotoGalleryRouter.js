$(document).ready(function() {

    window.PhotoGalleryRouter = Backbone.Router.extend({
        routes: {
            "": "showPhotos",
            "#": "showPhotos",
            "photo/:id": "photoDetails"
        },
        initialize: function() {
            console.log('Starting gallery router2');
            console.log(this);
            this.uploaderInit();

            this.acc_id = $('#acc_id').val();
            this.own_id = $('#own_id').val();
        },
        galleryInit: function() {
        },
        showPhotos: function() {
            var self = this;
            this.photos = new PhotoCollection();
            this.photos.url = '../logged/api/account/' + this.acc_id + '/photos';
            $('#thumbs').html('');
            this.photos.fetch({success: function(data) {
                    $('#thumbs').append(new PhotoListView({model: self.photos}).render().el);
                }});
        },
        photoDetails: function(id) {
            this.before(function() {
                var photo = this.photos.get(id);
                photo.rootUrl = '../logged/api/account/' + this.acc_id + '/photos';
                this.showView('#thumbs', new PhotoView({model: photo}));
            });
        },
        uploaderInit: function() {
            var self = this;
            $('#triggerUpload').click(function() {
                UploaderModel.uploadStoredFiles();
            });
        },
        before: function(callback) {
            if (this.photos) {
                if (callback)
                    callback.call(this);
            } else {
                this.photos = new PhotoCollection();
                var self = this;
                this.photos.url = '../logged/api/account/' + this.acc_id + '/photos';
                this.photos.fetch({
                    success: function(data) {
                        $('#thumbs').html(new PhotoListView({model: self.photos}).render().el);
                        self.galleryInit();
                        if (callback)
                            callback.call(self);
                    }});
            }
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new PhotoGalleryRouter();
    Backbone.history.start();
});
