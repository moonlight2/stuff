
window.AvatarUploaderModel = new qq.FineUploader({
    element: $('#manual-fine-uploader')[0],
    request: {
        endpoint: 'files/avatar',
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
        uploadButton: '<i class="icon-plus icon-white"></i> Select Avatar'
    },
    callbacks: {
        onComplete: function(id, fileName, responseJSON) {
            var model = responseJSON.photo;
            $('#avatar').html("<img src='../image/avatar/14' />");
        }
    }
});