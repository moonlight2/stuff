$(document).ready(function() {

    window.AppRouter = Backbone.Router.extend({
        routes: {
            "success": "showSuccess",
            "error": "showErrors",
        },
        initialize: function() {
            var form = new FormView({model: new Account()});
            this.showView('#form', form);
            form.getCountries();
        },
        showSuccess: function() {
            $('#form').hide();
            $('#success').show();
        },
        showErrors: function() {
            console.log('Error!');
        },
        showView: function(selector, view) {
            if (this.currentView)
                this.currentView.close();
            $(selector).html(view.render().el);
            this.currentView = view;
            return view;
        },
    });

    app = new AppRouter();
    Backbone.history.start();
});