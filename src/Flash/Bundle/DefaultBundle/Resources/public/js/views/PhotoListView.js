window.PhotoListView = Backbone.View.extend({
    tagName: 'ul',
    initialize: function() {
        this.model.bind('reset', this.render, this);
        this.model.bind('change', this.render, this);
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
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});