window.CityListView = Backbone.View.extend({
    tagName: 'ul',
    initialize: function() {
        this.model.bind('reset', this.render, this);
        this.model.bind('change', this.render, this);
    },
    render: function() {
        _.each(this.model.models, function(city) {
            $(this.el).attr('class', 'dropdown-menu').append(new CityListItemView({model: city}).render().el);
        }, this);
        return this;
    }
});

window.CityListItemView = Backbone.View.extend({
    tagName: 'li',
    template: _.template($('#city-tpl').html()),
    render: function() {
        $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
        return this;
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});