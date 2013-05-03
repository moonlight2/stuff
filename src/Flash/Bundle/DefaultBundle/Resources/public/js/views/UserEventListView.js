
window.UserEventListView = Backbone.View.extend({
    tagName: 'ul',
    initialize: function() {
        this.model.bind('reset', this.render, this);
        this.model.bind('change', this.render, this);
    },
    render: function() {
        _.each(this.model.models, function(event) {
            $(this.el).attr('class', '').append(new UserEventListItemView({model: event}).render().el);
        }, this);
        return this;
    }
});

window.UserEventListItemView = Backbone.View.extend({
    tagName: 'li',
    template: _.template($('#user-event-list-tpl').html()),
    render: function() {
        $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
        return this;
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});