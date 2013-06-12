
window.EventListView = Backbone.View.extend({
    tagName: 'div',
    initialize: function() {
        this.confirmed = false;
        console.log(this);
        this.model.bind('reset', this.render, this);
    },
    events: {
        'click #confirm-event': 'confirmEvent',
    },
    confirmEvent: function(e) {

        var id = $(e.currentTarget).attr('val');
        var self = this;
        this.event = this.model.get(id);
        this.event.set({is_confirmed: true});
        
         this.event.save(null, {
                success: function(model, response) {
                    self.event.trigger('destroy', event);
                    view = new EventListItemView({model: model});
                    $('#feed').attr('class', 'events-list').append(view.render().el);
                },
                error: function(model, response) {
                    app.navigate('error', true);
                }
            })
        return this;
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
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },
});