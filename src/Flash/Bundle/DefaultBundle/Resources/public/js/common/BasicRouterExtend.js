
Backbone.Router.prototype.showView = function(selector, view) {
    if (this.currentView)
        this.currentView.close();
    $(selector).html(view.render().el);
    this.currentView = view;
    return view;
}