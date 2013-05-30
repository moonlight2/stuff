$(document).ready(function() {

    window.ProfileRouter = Backbone.Router.extend({
        routes: {
            "photo/:id": "photoDetails"
        },
        initialize: function() {
            console.log('Starting Profile router');
            var self = this;
            console.log(this);
            this.uploaderInit();
            this.acc = new Account();
            this.acc.urlRoot = 'accounts/own';
            this.acc.fetch({success: function(model) {
                    
                    console.log(model);
                     var form = new FormView({model: model});
                    self.showView('#form', form);
                    form.getCountries();
                }});
           
            //console.log(form);
            
            
        },
        galleryInit: function() {
        },
        photoDetails: function(id) {
//            this.before(function() {
//                var photo = this.photos.get(id);
//                this.showView('#thumbs', new PhotoView({model: photo}));
//            });
        },
        uploaderInit: function() {
            var self = this;
            $('#triggerUpload').click(function() {
                UploaderModel.uploadStoredFiles();
            });
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new ProfileRouter();
    Backbone.history.start();
});
