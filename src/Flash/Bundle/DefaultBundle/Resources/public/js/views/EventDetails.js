

window.EventView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#event-tpl').html()),
                _.bindAll(this, 'switchDropdown', 'hideDropdown', 'removeElements');
    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    getGroups: function() {
    },
    events: {
        'click #create-event': 'saveEvent',
        "click .dropdown-toggle": "switchDropdown",
        "click": "hideDropdown",
        "click #country li": "changeCountryInput",
        "click #city li": "changeCityInput",
        "input #city-input": "getSimilarCities",
        "mouseover .dropdown-menu li": "changeListBackground",
        "change #date": "prepareDateToSend",
        "change #hour": "prepareDateToSend",
        "change #minutes": "prepareDateToSend"
    },
    getEventList: function() {
        var self = this;
        this.events = new EventCollection();
        this.events.fetch({success: function(data) {
                $('#events').append(new EventListView({model: self.events}).render().el);
            }});

    },
    saveEvent: function() {
        var self = this;
        this.model.set({
            name: $('#name').val(),
            description: $('#description').html(),
            country: $('#send-country').val(),
            city: $('#send-city').val(),
            date: $('#send-date').val(),
        });
        if (this.model.isNew()) {
            this.model.save(null, {
                success: function(model, response) {
                    //alert('Event has been created');
                    app.navigate('group_events', true);
                },
                error: function(model, response) {
                    self.showErrors(response.responseText);
                    app.navigate('error', true);
                }
            });
        }
        return false;
    },
    deleteEvent: function() {
    },
    change: function(event) {
        console.log('change');
    },
});
