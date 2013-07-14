$(document).ready(function() {

    window.FeedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents",
            "success": "showSuccess",
            "not_confirmed": "showNotConfirmed"
        },
        initialize: function() {
            this.uploaderInit();
        },
        showNotConfirmed: function() {
            $('#pre-feed').css('display', 'block');
            $('#feed').css('display', 'none');
            return false;
        },
        uploaderInit: function() {
    
            var self = this;
            self.photo = new PhotoModel();
            
            this.uploader = new qq.FineUploader({
                element: $('#manual-fine-uploader')[0],
                request: {
                    endpoint: 'logged/api/account/' + own_id + '/photos/album/garbage',
                    customHeaders: {
                        Accept: 'application/json'
                    }
                },
                autoUpload: false,
                validation: {
                    allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
                    sizeLimit: 512000 // 50 kB = 50 * 1024 bytes
                },
                text: {
                    uploadButton: '<i class="icon-plus icon-white"></i> Выберите файл'
                },
                callbacks: {
                    onComplete: function(id, fileName, responseJSON) {
                        var model = responseJSON.photo;
                        self.photo.set({id: model.id});
                        self.photo.set({name: model.name});
                        self.photo.set({path: model.path});
                        self.imgView = new PhotoView({model: self.photo});
                         $('#image').append(self.imgView.render().el);
                    }
                }
            });

            $('#triggerUpload').click(function() {
                self.uploader.uploadStoredFiles();
            });

        },
        showEvents: function() {
            $('#pre-feed').css('display', 'none');
            $('#feed').css('display', 'block');

            if (!this.details) {
                this.details = new FeedEventView({model: new EventModel()});
                this.details.getEventList();
                $('#feed-form').append(this.details.render().el);
            }
        },
        showSuccess: function() {
            $('#success').show();
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new FeedRouter();
    Backbone.history.start();
});