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
            console.log('Starting gallery router2');
            console.log(this);
            this.uploaderInit();

        },
        galleryInit: function() {
        },
        showAlbums: function() {
            var self = this;
            this.albums = new PhotoAlbumCollection();
            this.albums.url = '../logged/api/account/' + acc_id + '/albums';
            $('#thumbs').html('');
            this.albums.fetch({success: function(data) {
                    console.log(self.albums);
                    $('#thumbs').append(new PhotoAlbumListView({model: self.albums}).render().el);
                }});
        },
        showPhotos: function(id) {
            
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
            this.before(function() {
                var photo = this.photos.get(id);
                photo.rootUrl = '../logged/api/account/' + acc_id + '/albums/' + a_id + '/photos' + id;
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
