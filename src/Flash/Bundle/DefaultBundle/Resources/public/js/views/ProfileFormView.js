
window.ProfileFormView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#form-tpl').html()),
                _.bindAll(this, 'switchDropdown', 'hideDropdown', 'removeElements');
    },
    render: function() {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    events: {
        "click .dropdown-toggle": "switchDropdown",
        "click": "hideDropdown",
        "click #change": "saveAccount",
        "click #country li": "changeCountryInput",
        "click #city li": "changeCityInput",
        "input #city-input": "getSimilarCities",
        "mouseover .dropdown-menu li": "changeListBackground",
    },
    getGroups: function() {
        console.log('Get groups');
    },
    saveAccount: function() {
        var self = this;
        console.log('Save changes');
        this.model.urlRoot = 'accounts/own/update';
        this.model.set({
            firstName: $('#firstName').val(),
            lastName: $('#lastName').val(),
            email: $('#email').val(),
            country: $('#send-country').val(),
            city: $('#send-city').val(),
        });
        console.log(this.model);

        this.model.save(null, {
            success: function(model, response) {
                app.navigate('success', true);
            },
            error: function(model, response) {
                self.showErrors(response.responseText);
                app.navigate('error', true);
            }
        });
    },
});
