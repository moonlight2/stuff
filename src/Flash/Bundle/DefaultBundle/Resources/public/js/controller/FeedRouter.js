$(document).ready(function() {

    window.FeedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents",
            "success": "showSuccess",
            "not_confirmed": "showNotConfirmed"
        },
        initialize: function() {
            console.log('Starting Feed router');
            this.uploaderInit();
        },
        showNotConfirmed: function() {
            $('#pre-feed').css('display', 'block');
            $('#feed').css('display', 'none');
            return false;
        },
        uploaderInit: function() {
            var self = this;
            $('#triggerUpload').click(function() {
                UploaderModel.setEndpoint('logged/api/account/'+own_id+'/photos/album/garbage');
                UploaderModel.uploadStoredFiles();
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
        showSuccess: function(){
            $('#success').show();
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new FeedRouter();
    Backbone.history.start();
});