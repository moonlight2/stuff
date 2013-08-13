$(document).ready(function() {

    window.hash = window.location.hash.substring(1);

    window.PhotoGalleryRouter = Backbone.Router.extend({
        routes: {
            "": "showAlbums",
            "#": "showAlbums",
            "album/:a_id/photo/:id": "photoDetails",
            "album/:id": "showPhotos"
        },
        initialize: function() {
            var self = this;
            this.uploaderInit();
            $('#show-album-form').click(function() {
                self.showAlbumForm();
            });
            
        },
        showAlbums: function() {

            //localStorage.clear();
            $('#uploader').hide();
            $('#album-form').show();
            $('.nav-pills').show();

            if (this.form)
                this.form.close();

            var self = this;
            this.albums = new PhotoAlbumCollection();
            this.albums.url = '../logged/api/account/' + acc_id + '/albums';
            $('#thumbs').html('');
            this.albums.fetch({success: function(data) {
                    $('#thumbs').append(new PhotoAlbumListView({model: self.albums}).render().el);
                }});
        },
        showAlbumForm: function() {

            var self = this;
            if (this.form) {
                this.form.close();
                this.form = false;
            } else {
                this.form = new AlbumView({model: new PhototAlbumModel()});
                $('#album-form').append(self.form.render().el);
            }
        },
        showPhotos: function(id) {

            $('#uploader').show();
            $('#album-form').hide();
            $('.nav-pills').hide();

            var self = this;
            this.photos = new PhotoCollection();
            this.photos.url = '../logged/api/account/' + acc_id + '/albums/' + id + '/photos';
            $('#thumbs').html('');
            this.photos.fetch({success: function(data) {
                    $('#thumbs').append(new PhotoListView({model: self.photos}).render().el);
                }});
        },
        photoDetails: function(a_id, id) {

            console.log('Photo details');
            $('#uploader').hide();

            this.before(a_id, function() {
                var photo = this.photos.get(id);
                photo.rootUrl = '../logged/api/account/' + acc_id + '/albums/' + a_id + '/photos' + id;
                this.showView('#thumbs', new PhotoView({model: photo}));
            });
        },
        uploaderInit: function() {

            $('#triggerUpload').click(function() {
                UploaderModel.uploadStoredFiles();
            });
        },
        before: function(a_id, callback) {
            if (this.photos) {
                if (callback)
                    callback.call(this);
            } else {
                this.photos = new PhotoCollection();
                var self = this;
                this.photos.url = '../logged/api/account/' + acc_id + '/albums/'+ a_id +'/photos';
                this.photos.fetch({
                    success: function(data) {
                        $('#thumbs').html(new PhotoListView({model: self.photos}).render().el);
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
