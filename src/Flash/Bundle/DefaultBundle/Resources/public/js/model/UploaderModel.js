
window.UploaderModel = new qq.FineUploader({
    element: $('#manual-fine-uploader')[0],
    request: {
        endpoint: 'files',
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
        uploadButton: '<i class="icon-plus icon-white"></i> Select Files'
    },
    callbacks: {
        onComplete: function(id, fileName, responseJSON) {

            var model = responseJSON.photo;

            app.photos.add(model);

            console.log(responseJSON.photo);
            console.log(app.photos);
        }
    }
});
