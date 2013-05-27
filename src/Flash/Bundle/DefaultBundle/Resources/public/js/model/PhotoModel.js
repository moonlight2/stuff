
window.PhototModel = Backbone.Model.extend({
    urlRoot: 'photos',
    defaults: {
        "id": null,
        "name": "",
        "path": "",
        "rating": "",
    }
});

window.PhotoCollection = Backbone.Collection.extend({
    model: PhototModel,
    url: 'photos',
    initialize: function() {
        //this.bindAll(this);
        this.setElement(this.at(0));
    },
    comparator: function(model) {
        return model.get("id");
    },
    getElement: function() {
        return this.currentElement;
    },
    setElement: function(model) {
        this.currentElement = model;
    },
    next: function() {
        this.setElement(this.at(this.indexOf(this.getElement()) + 1));
        return this;
    },
    prev: function() {
        this.setElement(this.at(this.indexOf(this.getElement()) - 1));
        return this;
    }
});
