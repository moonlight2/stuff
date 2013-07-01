
window.ResponseListView = Backbone.View.extend({

    initialize: function() {
        this.model.bind('reset', this.render, this);
    },
    render: function() {
        console.log(this.model);
        _.each(this.model.models, function(event) {
            console.log('event');
            $(this.el).attr('name', 'event').append(new ResponseListItemView({model: event}).render().el);
        }, this);
        return this;
    },
});

window.ResponseListItemView = Backbone.View.extend({

    template: _.template($('#response-event-list-tpl').html()),
    render: function() {
        $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
        return this;
    },
    events: {
        "click .delete": "delete",
    },
    delete: function() {
        this.close();
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
});