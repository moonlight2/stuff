$(document).ready(function () {

Backbone.View.prototype.close = function() {
    console.log('Closing view ' + this);
    if (this.beforeClose) {
        this.beforeClose();
    }
    this.remove();
    this.unbind();
};

var Helper = {
    
    showAnimation: function(selector) {
        $(selector).show();
    },
    stopAnimation: function(selector) {
        $(selector).hide();
    }
}


var AppRouter = Backbone.Router.extend({
    
    routes: {
        "": "wineList",
        "wines/new": "newWine",
        "wines/:id": "wineDetails",
    },
    
    initialize: function() {
        this.account = new Account();
        var form = new FormView({model: this.account});
        $('#form').html(form.render().el);
        form.getCountries();
        
    },
                
    wineDetails: function(id) {
        this.before(function() {
            this.wine = app.wines.get(id); //model
            app.showView('#main-content', new WineView({model: this.wine}));
        });
    },
            
    showView: function(selector, view) {
        if (this.currentView)
            this.currentView.close();
        $(selector).html(view.render().el);
        this.currentView = view;
        Helper.stopAnimation('#preload');
        return view;
    },

});


tpl.loadTemplates(['form', 'city-item', 'country-item'], function () {
    app = new AppRouter();
    Backbone.history.start();
});

});