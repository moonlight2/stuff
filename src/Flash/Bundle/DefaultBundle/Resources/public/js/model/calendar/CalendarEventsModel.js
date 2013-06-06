var CalendarEventModel = Backbone.Model.extend({
    //urlRoot: 'events',
    urlRoot: function() {
        return '15/urlRoot';
    },
    defaults: {
        "id": null,
        "title": "",
        "text": "",
        "start": "",
        "end": "",
        "allDay": "",
        "isShown": false,
    }
});

var CalendarEventsCollection = Backbone.Collection.extend({
    model: CalendarEventModel,
    url: 'events',
//    parse: function(response) {
//        
//        var self = this;
//
//        if ((response.today).length > 0) {
//            this.collection = new CalendarEventsCollection();
//            _.each(response.today, function(event) {
//                self.collection.add(event);
//            }, this);
//             llection}).render();
//        }
//        return response.events;
//    }
});