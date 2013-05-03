
window.GroupListView = Backbone.View.extend({
    tagName: 'select',
    initialize: function() {
        this.model.bind('reset', this.render, this);
    },
    render: function() {
        _.each(this.model.models, function(country) {
            $(this.el).attr('name', 'group').append(new GroupListItemView({model: country}).render().el);
        }, this);
        return this;
    },
});

window.GroupListItemView = Backbone.View.extend({
    tagName: 'option',
    template: _.template($('#group-tpl').html()),
    render: function() {
        $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
        return this;
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});