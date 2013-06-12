
window.EventListView = Backbone.View.extend({
    tagName: 'div',
    initialize: function() {
        this.confirmed = false;
        console.log(this);
        this.model.bind('reset', this.render, this);
    },
            
            
            
    render: function() {
        console.log('Here is EventListView')
        if (true == this.confirmed) {
            _.each(this.model.models, function(event) {
                view = new EventListItemView({model: event});
                $(this.el).attr('class', 'events-list').append(view.render().el);
            }, this);
        } else {
            _.each(this.model.models, function(event) {
                view = new EventListItemView({model: event});
                view.template = _.template($('#pre-events-list-tpl').html());
                $(this.el).attr('class', 'events-list').append(view.render().el);
            }, this);
        }
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