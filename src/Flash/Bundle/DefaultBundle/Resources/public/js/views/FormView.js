
window.FormView = Backbone.View.extend({
    
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
        "click #send": "saveAccount",
        "click #country li": "changeCountryInput",
        "click #city li": "changeCityInput",
        "input #city-input": "getSimilarCities",
        "mouseover .dropdown-menu li": "changeListBackground",
    },
    
    getGroups: function() {
        console.log('Get groups');
        var self = this;
        this.groups = new GroupCollection();
        this.groups.url = "api/groups/country/" + $("#send-country").val() + "/city/" + $("#send-city").val();
        this.groups.fetch({processData: true,
            success: function(data) {
                self.clearGroupList();
                $('#group').append(new GroupListView({model: self.groups}).render().el);
                $('#group-block').show();
            }, error: function() {
                $('#group-block').hide();
            }});
    },
    saveAccount: function() {
        var self = this;
        this.model.set({
            firstName: $('#firstName').val(),
            lastName: $('#lastName').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            country: $('#send-country').val(),
            city: $('#send-city').val(),
            group: $('#group select').val()
        });

        if (this.model.isNew()) {

            this.model.save(null, {
                success: function(model, response) {
                    app.navigate('success', true);
                },
                error: function(model, response) {
                    self.showErrors(response.responseText);
                    app.navigate('error', true);
                }
            });
        }
    },
});
