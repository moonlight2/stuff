var CalendarEventModel = Backbone.Model.extend({
    urlRoot: 'events',
    defaults: {
        "id": null,
        "title": "",
        "text": "",
        "start": "",
        "end": "",
    }
});

var CalendarEventsCollection = Backbone.Collection.extend({
    model: CalendarEventModel,
    url: 'events',
    parse: function(response) {
        
        var self = this;

        if ((response.today).length > 0) {
            this.collection = new CalendarEventsCollection();
            _.each(response.today, function(event) {
                self.collection.add(event);
            }, this);
            new DialogTodayView({collection: this.collection}).render();
        }
        return response.events;
    }
});