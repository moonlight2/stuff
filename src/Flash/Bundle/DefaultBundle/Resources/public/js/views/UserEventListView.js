
window.UserEventListView = Backbone.View.extend({
    tagName: 'div',
    initialize: function() {
        this.model.bind('reset', this.render, this);
        this.model.bind('change', this.render, this);
    },
    render: function() {
        _.each(this.model.models, function(event) {
            $(this.el).attr('class', 'event-list').append(new UserEventListItemView({model: event}).render().el);
        }, this);
        return this;
    }
});

window.UserEventListItemView = Backbone.View.extend({
    tagName: 'div',
    template: _.template($('#user-event-list-tpl').html()),
    render: function() {
        $(this.el).attr('class', 'user-event').html(this.template(this.model.toJSON()));
        return this;
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});