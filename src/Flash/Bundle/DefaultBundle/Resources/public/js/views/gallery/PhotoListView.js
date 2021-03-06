window.PhotoListView = Backbone.View.extend({
    tagName: 'ul',
    initialize: function() {
        this.hash = window.location.hash.substring(1);
        this.model.bind('reset', this.render, this);
        this.model.bind('add', this.appendLast, this);
        if (acc_id == own_id) {
            UploaderModel.setEndpoint('../logged/api/account/' + acc_id + '/albums/' + (this.hash.split('/'))[1] + '/photos');
        }
    },
    appendLast: function() {
        var photo = this.model.models[this.model.length - 1];
        $(this.el).attr('class', 'thumbs').append(new PhotoListItemView({model: photo}).render().el);
        this.addPhotoToStorage(photo);
        return this;
    },
    addPhotoToStorage: function(model) {
        var url = '../image/thumb64/' + acc_id + '/' + model.get('path');
        var g = $.get(url, function(data) {
            localStorage.setItem(model.get('path'), data);
        })
    },
    render: function() {

        var self = this;
        var flag = false;

        _.each(this.model.models, function(photo) {
            if (localStorage.length > 0) {
                for (var key in localStorage) {
                    if (photo.get('path') == key) {

                        photo.set("dataurl", localStorage.getItem(key));
                        var view = new PhotoListItemView({model: photo});
                        view.template = _.template($('#image-list-storage-tpl').html());
                        $(this.el).attr('class', 'thumbs').append(view.render().el);
                        flag = true;
                        break;
                    }
                }
            } else {
                this.appendModel(photo);
                this.addPhotoToStorage(photo);
            }
        }, this);

        if (flag == false && localStorage.length > 0) {
            localStorage.clear();
            _.each(this.model.models, function(photo) {
                this.appendModel(photo);
                this.addPhotoToStorage(photo);
            }, this);
        }

        return this;
    },
    appendModel: function(model) {
        $(this.el).attr('class', 'thumbs').append(new PhotoListItemView({model: model}).render().el);
    }
});

window.PhotoListItemView = Backbone.View.extend({
    tagName: 'li',
    template: _.template($('#image-list-tpl').html()),
    render: function() {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    events: {
        "click": "imageDetails"
    },
    imageDetails: function() {
        var hash = window.location.hash.substring(1);
        var alb_id = (hash.split('/'))[1];
        app.navigate('album/' + alb_id + '/photo/' + this.model.get('id'), true);
        this.scroll('album', 500);
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});