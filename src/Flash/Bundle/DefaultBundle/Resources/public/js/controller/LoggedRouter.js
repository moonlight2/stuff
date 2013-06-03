$(document).ready(function() {

    window.LoggedRouter = Backbone.Router.extend({
        routes: {
            "": "showEvents",
            "new_group": "newGroup",
            "new_event/error": "showErrors",
            "new_event/success": "showEventSuccess",
        },
        initialize: function() {
            console.log('Starting router');
            console.log(this);
            
        },
        showEvents: function() {

            var self = this;

            this.events = new UserEventCollection();
            this.events.url = 'p' + $('#acc_id').val() + '/user_events';
            this.events.fetch({success: function(data) {
                    $('#events').append(new UserEventListView({model: self.events}).render().el);
                }});

            this.calendarInit();
        },
        calendarInit: function() {
            new CalendarEventsView().render();
        },
        showGroupSuccess: function() {
            alert('Group has been created!!!!');
        },
        newGroup: function() {
            var group = new GroupView({'model': new GroupModel()});
            this.showView('#events', group);
            group.getCountries();
            console.log('new group:');
        },
        showErrors: function() {
            console.log('There is some errors');
        }
    });

    app = new LoggedRouter();
    Backbone.history.start();
});