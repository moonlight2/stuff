
window.UploaderModel = new qq.FineUploader({
    element: $('#manual-fine-uploader')[0],
    request: {
        endpoint: '../logged/api/account/1/album/1/photos',
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
            app.photos.add(model);
        }
    }
});
