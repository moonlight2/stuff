$(document).ready(function() {

    window.ProfileRouter = Backbone.Router.extend({
        routes: {
            "": "uploaderInit",
            "success": "showSuccess",
            "error": "showErrors",
        },
        initialize: function() {
            console.log('Starting Profile router');
            var self = this;
            this.acc = new Account();
            this.acc.urlRoot = '../logged/api/accounts/own';
            this.acc.fetch({success: function(model) {
                    console.log(model);
                     var form = new ProfileFormView({model: model});
                    self.showView('#form', form);
                    form.getCountries(model.attributes.country_id, model.attributes.city_id);
                }});
        },
        showSuccess: function(){
            $('#success').show();
        },
        galleryInit: function() {
        },
        uploaderInit: function() {
            var self = this;
            $('#triggerUpload').click(function() {
                AvatarUploaderModel.uploadStoredFiles();
            });
        },
        showErrors: function() {
             $('#success').hide();
        }
    });

    app = new ProfileRouter();
    Backbone.history.start();
});
