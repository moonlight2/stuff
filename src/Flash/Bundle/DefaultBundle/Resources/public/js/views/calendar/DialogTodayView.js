window.DialogTodayView = Backbone.View.extend({
    initialize: function() {
        this.el = $('#todayEventDialog');
        _.bindAll(this);
    },
    render: function() {
я
        var self = this;
        this.buttons = {'Ok': this.confirm};

        console.log('Current today dialog collection');
        console.log(this.collection);

        this.el.dialog({
            modal: true,
            title: 'Сегодняшние события:',
            buttons: self.buttons,
            open: this.onOpen,
        });

        return this;
    },
    onOpen: function() {
        _.each(this.collection.models, function(event) {
            console.log(event);
            var d = new Date(event.attributes.start);
            $('#today-events-list').append(
                    '<p>' + event.attributes.title
                    + ' at '
                    + d.getDate()
                    + '-' + d.getMonth()
                    + '-' + d.getFullYear()
                    + ' in '
                    + d.getHours()
                    + ':' + d.getMinutes()
                    + '</p>');
        }, this);
    },
    confirm: function(e) {
        _.each(this.collection.models, function(event) {
            event.set({isShown: true});
            event.save();
        }, this);
        this.closeDialog();
    },
    closeDialog: function() {
        this.el.dialog('close');
    },
});