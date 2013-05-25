window.PhotoCommentListView = Backbone.View.extend({
    tagName: 'ul',
    initialize: function() {
        this.model.bind('reset', this.render, this);
        this.model.bind('add', this.appendLast, this);
    },
    render: function() {
        _.each(this.model.models, function(comment) {
            $(this.el).attr('class', 'comment-list').append(new PhotoCommentListItemView({model: comment}).render().el);
        }, this);
        return this;
    },
    appendLast: function() {
        var comment = this.model.models[this.model.length - 1];
        $(this.el).attr('class', 'comment-list').append(new PhotoCommentListItemView({model: comment}).render().el);
        return this;
    },
});

window.PhotoCommentListItemView = Backbone.View.extend({
    tagName: 'li',
    template: _.template($('#comment-list-tpl').html()),
    render: function() {
        console.log(this.model);
        $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
        return this;
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});