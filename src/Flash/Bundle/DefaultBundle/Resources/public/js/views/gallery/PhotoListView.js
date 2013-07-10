window.PhotoListView = Backbone.View.extend({
    tagName: 'ul',
    initialize: function() {
        this.hash = window.location.hash.substring(1);
        this.model.bind('reset', this.render, this);
        this.model.bind('add', this.appendLast, this);
        UploaderModel.setEndpoint('../logged/api/account/' + acc_id + '/albums/' + (this.hash.split('/'))[1] + '/photos');
    },
    appendLast: function() {
        var photo = this.model.models[this.model.length - 1];
        $(this.el).attr('class', 'thumbs').append(new PhotoListItemView({model: photo}).render().el);
        return this;
    },
    render: function() {
        _.each(this.model.models, function(photo) {
            $(this.el).attr('class', 'thumbs').append(new PhotoListItemView({model: photo}).render().el);
        }, this);
        return this;
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
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});