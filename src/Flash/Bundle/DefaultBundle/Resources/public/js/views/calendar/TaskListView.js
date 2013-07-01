
window.TaskListView = Backbone.View.extend({
    initialize: function() {
        this.model.bind('reset', this.render, this);
    },
    render: function() {
        _.each(this.model.models, function(event) {
            $(this.el).attr('name', 'event').append(new TaskListItemView({model: event}).render().el);
        }, this);
        return this;
    }
});

window.TaskListItemView = Backbone.View.extend({
    template: _.template($('#task-event-list-tpl').html()),
    render: function() {
        $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
        return this;
    },
    events: {
        "click .confirm": "confirm",
        "click .reject": "reject",
    },
    confirm: function() {

        var el = $(this.el).find(".noUiSlider");
        var percent = el.val();
        var data = '{"percent": ' + percent + '}';
        var self = this;

        $.ajax({
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json"
            },
            type: "POST",
            url: 'logged/api/account/' + own_id + '/calendar/events/' + this.model.get('id') + '/confirm',
            data: data,
            dataType: "json",
            success: function(response) {
                self.close();
            }});
    },
    reject: function() {
        
        var self = this;
        $.ajax({
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json"
            },
            type: "POST",
            url: 'logged/api/account/' + own_id + '/calendar/events/' + this.model.get('id') + '/reject',
            dataType: "json",
            success: function(response) {
                self.close();
            }});
    },
    initialize: function() {
        this.model.bind('change', this.render, this);
    },
            
});