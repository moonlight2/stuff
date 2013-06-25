
window.AccountListView = Backbone.View.extend({
    tagName: 'ul',
    initialize: function() {
        this.model.bind('reset', this.render, this);
    },
    events: {
        "change": "getChecked"
    },
    render: function() {
        _.each(this.model.models, function(account) {
            $(this.el).attr('name', 'account').append(new AccountListItemView({model: account}).render().el);
        }, this);
        return this;
    },
    getChecked: function(el) {
        var checkbox = $(el)[0].target;
        if ($(checkbox).is(":checked")) {
            console.log('Checked');
        } else {
            console.log('Unchecked');
        }
    },
});

window.AccountListItemView = Backbone.View.extend({
    tagName: 'li',
    template: _.template($('#account-tpl').html()),
    render: function() {
        $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
        return this;
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});