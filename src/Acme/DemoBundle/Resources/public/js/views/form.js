window.FormView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template(tpl.get('form'));
        
    },
    render: function(eventName) {
        $(this.el).html(this.template());
        return this;
    },
    events: {
        "click .send": "saveAccount"
    },
    checkData: function() {

        alert('Check data');
        //app.navigate('wines/new', true);
        return false;
    },
    saveAccount: function() {
        this.model.set({
            name: $('#name').val(),
            email: $('#email').val(),
            about: $('#about').val(),
            password: $('#password').val(),
        });
        this.model.save({
            success: function() {
                alert('Model ' + this.model.name + 'updated');
            }
        });
        return false;
    },
});
