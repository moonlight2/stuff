
window.CountryListView = Backbone.View.extend({
    tagName: 'ul',
    render: function() {
        _.each(this.model.models, function(country) {
            $(this.el).attr('class', 'dropdown-menu').append(new CountryListItemView({model: country}).render().el);
        }, this);
        return this;
    },
});

window.CountryListItemView = Backbone.View.extend({
    tagName: 'li',
    template: _.template($('#country-tpl').html()),
    render: function() {
        $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
        return this;
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});
