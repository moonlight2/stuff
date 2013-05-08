

window.EventView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#event-tpl').html()),
                _.bindAll(this, 'switchDropdown', 'hideDropdown', 'removeElements');
        //this.model.bind('change', this.render, this);
    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    getGroups: function(){},
    events: {
        'click #create-event': 'saveEvent',
        "click .dropdown-toggle": "switchDropdown",
        "click": "hideDropdown",
        "click #country li": "changeCountryInput",
        "click #city li": "changeCityInput",
        "input #city-input": "getSimilarCities",
        "mouseover .dropdown-menu li": "changeListBackground",
    },
    saveEvent: function() {
        var self = this;
        this.model.set({
            name: $('#name').val(),
            description: $('#description').val(),
            country: $('#send-country').val(),
            city: $('#send-city').val(),
            data: $('#data').val(),
        });
        if (this.model.isNew()) {
            this.model.save(null, {
                success: function(model, response) {
                    //alert('Event has been created');
                    app.navigate('events_list', true);
                },
                error: function(model, response) {
                    self.showErrors(response.responseText);
                    app.navigate('new_event/error', true);
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
