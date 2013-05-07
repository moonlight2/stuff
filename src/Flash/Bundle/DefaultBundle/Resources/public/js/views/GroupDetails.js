

window.GroupView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#new-group-tpl').html());
//        this.model.bind('change', this.render, this);
    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    getGroups: function() {
    },
    events: {
        'click #create-group': 'saveGroup',
        "click .dropdown-toggle": "switchDropdown",
        "click": "hideDropdown",
        "click #country li": "changeCountryInput",
        "click #city li": "changeCityInput",
        "input #city-input": "getSimilarCities",
        "mouseover .dropdown-menu li": "changeListBackground",
    },
    saveGroup: function() {
        var self = this;
        this.model.set({
            name: $('#name').val(),
            about: $('#about').val(),
            country: $('#send-country').val(),
            city: $('#send-city').val(),
        });
        if (this.model.isNew()) {
            this.model.save(null, {
                success: function(model, response) {
                    app.navigate('new_group/success', true);
                },
                error: function(model, response) {
                    self.showErrors(response.responseText);
                    app.navigate('new_group/error', true);
                }
            });
        }
        return false;
    },
    deleteGroup: function() {
        this.model.destroy({
            success: function() {
                alert('Destroyed!');
                console.log(this);
                app.navigate('/', false);
                //$('#main-content').html('Deleted');
                //window.history.back();
            }
        });
        return false;
    },
    change: function(event) {
        console.log('change');
    },
});
