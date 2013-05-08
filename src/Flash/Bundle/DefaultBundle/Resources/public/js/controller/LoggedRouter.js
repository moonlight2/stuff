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

            var eventList = new UserEventView();
            eventList.getEventList();
        },
        showGroupSuccess: function(){
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