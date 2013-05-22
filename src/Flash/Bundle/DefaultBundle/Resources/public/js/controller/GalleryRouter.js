$(document).ready(function() {

    window.GalleryRouter = Backbone.Router.extend({
        routes: {
            "": "showImages",
        },
        initialize: function() {
            console.log('Starting gallery router');
            console.log(this);
            this.uploaderInit();
        },
        galleryInit: function() {
            $('#thumbs').galleriffic({
                delay: 2500,
                numThumbs: 5,
                preloadAhead: 10,
                enableTopPager: true,
                enableBottomPager: false,
                imageContainerSel: '#slideshow',
                controlsContainerSel: '#controls',
                captionContainerSel: '#caption',
                loadingContainerSel: '#loading',
                renderSSControls: true,
                renderNavControls: true,
                playLinkText: 'Play Slideshow',
                pauseLinkText: 'Pause Slideshow',
                prevLinkText: '&lsaquo; Previous Photo',
                nextLinkText: 'Next Photo &rsaquo;',
                nextPageLinkText: 'Next &rsaquo;',
                prevPageLinkText: '&lsaquo; Prev',
                enableHistory: true,
                autoStart: false,
                syncTransitions: true,
                defaultTransitionDuration: 900,
            });
        },
        showImages: function() {
            var self = this;
            this.photos = new PhotoCollection();
            this.photos.fetch({success: function(data) {
                    $('#thumbs').append(new PhotoListView({model: self.photos}).render().el);
                    self.galleryInit();
                }});
        },
        uploaderInit: function() {
            var self = this;
            $('#triggerUpload').click(function() {
                UploaderModel.uploadStoredFiles();
                $('#thumbs').html('');
                self.showImages();
            });
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new GalleryRouter();
    Backbone.history.start();
});