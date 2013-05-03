
window.UserEventView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#user-event-tpl').html());
    },
    render: function() {
        $(this.el).html(this.template());
        return this;
    },
    events: {
    },
    getEventList: function() {
        var self = this;
        this.events = new UserEventCollection();
        this.events.fetch({success: function(data) {
                $('#events').append(new UserEventListView({model: self.events}).render().el);
            }});
    }

});
