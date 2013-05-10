
window.EventListView = Backbone.View.extend({
    tagName: 'div',
    initialize: function() {
        this.model.bind('reset', this.render, this);
    },
    render: function() {
        console.log('Here is EventListView')
        _.each(this.model.models, function(event) {
            $(this.el).attr('class', 'events-list').append(new EventListItemView({model: event}).render().el);
        }, this);
        return this;
    },
});

window.EventListItemView = Backbone.View.extend({
    tagName: 'div',
    template: _.template($('#events-list-tpl').html()),
    render: function() {
        $(this.el).attr('class', 'event').html(this.template(this.model.toJSON()));
        return this;
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});