
window.FeedEventView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#feed-event-tpl').html());
        this.url = 'api/feed/events';
        this.modUrl = 'moderator/api/feed/events';
        this.rendered = false;

    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        this.rendered = true;
        return this;
    },
    events: {
        "click": "hideDropdown",
        'click #create-event': 'saveEvent',
        'click #show-pre-feed': 'switchFeed',
        "change #date": "prepareDateToSend",
        "change #hour": "prepareDateToSend",
        "change #minutes": "prepareDateToSend",
        "click #country li": "changeCountryInput",
        "click #city li": "changeCityInput",
        "input #city-input": "getSimilarCities",
        "mouseover .dropdown-menu li": "changeListBackground",
        "click .dropdown-toggle": "switchDropdown",
        
    },
    switchFeed: function() {

        if (Backbone.history.fragment == 'not_confirmed') {
            app.navigate('', true);
        } else {
            app.navigate('not_confirmed', true);
        }
    },
    getEventList: function() {

        var self = this;
        this.events = new EventCollection();
        this.events.url = this.url + "/0/2";
        this.events.fetch({success: function(data) {
                var view = new EventListView({collection: self.events});
                view.confirmed = true;
                $('#feed').append(view.render().el);
                self.getPreEventList();
            }});
    },
    getPreEventList: function() {

        if ($('#pre-feed').length == 1) {
            var self = this;
            this.preEvents = new EventCollection();
            this.preEvents.url = this.modUrl;
            this.preEvents.fetch({success: function(data) {
                    var view = new EventListView({collection: self.preEvents});
                    view.confirmed = false;
                    $('#pre-feed').append(view.render().el);
                }});
        }
        return false;
    },
    saveEvent: function() {

        var self = this;

        this.model.url = this.url;
        this.model.set({
            name: $('#name').val(),
            description: $('#description').val(),
            image: $('#photo-path').val(),
            country: $('#send-country').val(),
            city: $("#send-city").val(),
            date: $('#send-date').val(),
        });

        if (this.model.isNew()) {
            this.model.save(null, {
                success: function(model, response) {
                    app.navigate('success', true);
                    if ($('#pre-feed').length == 1) {
                        self.preEvents.add(model);
                    }
                    self.close();
                    $('#feed-form').hide();
                },
                error: function(model, response) {
                    self.showErrors(response.responseText);
                    app.navigate('error', true);
                }
            });
        }
        return false;
    },
});
