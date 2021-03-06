
window.EventListView = Backbone.View.extend({
    tagName: 'div',
    initialize: function() {
        this.confirmed = false;
        this.collection.bind('reset', this.render, this);
        this.collection.bind('add', this.addOne, this);
        this.url = 'api/feed/events';
        this.modUrl = 'moderator/api/feed/events';
        this.initEvents();
        this.count = 0;
    },
    events: {
        'click .confirm-event': 'confirmEvent',
        'click #reject-event': 'rejectEvent',
    },
    initEvents: function() {
        var self = this;
        $('#more').click(function() {
            console.log('More');
            self.addAFew();
        });
    },
    rejectEvent: function(e) {
        var id = $(e.currentTarget).attr('val');
        var event = this.collection.get(id);
        event.url = this.modUrl + "/" + id;
        event.destroy();
    },
    addOne: function() {

        var event = this.collection.models[this.collection.length - 1];
        view = new EventListItemView({model: event});
        view.template = _.template($('#pre-events-list-tpl').html());
        $(this.el).attr('class', 'events-list').append(view.render().el);
        return this;
    },
    addAFew: function() {

        this.count = this.count + 2;
        this.collection.url = this.url + "/" + this.count + "/2";
        this.collection.fetch();
    },
    confirmEvent: function(e) {

        var id = $(e.currentTarget).attr('val');
        var self = this;
        this.event = this.collection.get(id);
        this.event.set({is_confirmed: true});
                console.log(this.event);
       // return false;
        this.event.url = this.modUrl + "/" + id;
        this.event.save(null, {
            success: function(model, response) {
                self.event.trigger('destroy', event);
                self.collection.add(model);
            },
            error: function(model, response) {
                app.navigate('error', true);
            }
        })
        return this;
    },
    render: function() {

        if (true == this.confirmed) {
            _.each(this.collection.models, function(event) {
                view = new EventListItemView({model: event});
                $(this.el).attr('class', 'events-list').append(view.render().el);
            }, this);
        } else {
            _.each(this.collection.models, function(event) {
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
    events: {
        'click .delete-event': 'deleteEvent'
    },
    deleteEvent: function() {
        this.model.url = 'moderator/api/feed/events/' + this.model.get('id');
        this.model.destroy();
    },
    render: function() {
        $(this.el).attr('class', 'event').html(this.template(this.model.toJSON()));
        return this;
    },
    initialize: function() {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },
});