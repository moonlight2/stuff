
window.AccountListView = Backbone.View.extend({
    tagName: 'select',
    initialize: function() {
        this.model.bind('reset', this.render, this);
    },
    render: function() {
        _.each(this.model.models, function(account) {
            $(this.el).attr('name', 'account').append(new AccountListItemView({model: account}).render().el);
        }, this);
        return this;
    },
});

window.AccountListItemView = Backbone.View.extend({
    tagName: 'option',
    template: _.template($('#account-tpl').html()),
    render: function() {
        $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
        return this;
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});