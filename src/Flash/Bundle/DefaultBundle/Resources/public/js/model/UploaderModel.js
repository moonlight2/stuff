
window.UploaderModel = new qq.FineUploader({
    element: $('#manual-fine-uploader')[0],
    request: {
        endpoint: 'logged/rest/api/files',
        customHeaders: {
            Accept: 'application/json'
        }
    },
    sizeLimit: 3000,
    autoUpload: false,
    showButton: false,
    text: {
        uploadButton: '<i class="icon-plus icon-white"></i> Select Files'
    }
});
