window.DialogTaskView = Backbone.View.extend({
    initialize: function() {
        this.el = $('#taskEventDialog');
        _.bindAll(this);

    },
    render: function() {

        var self = this;
        this.buttons = {};
        _.extend(self.buttons, {'Cancel': this.closeDialog});

        this.el.dialog({
            modal: true,
            title: 'Новое событие от лидера:',
            buttons: self.buttons,
            open: this.onOpen,
            height: 'auto',
            width: 540
        });

        return this;
    },
    sliderInit: function() {
        $(".noUiSlider").noUiSlider({
            range: [0, 100],
            start: [0],
            step: 1,
            handles: 1,
            slide: function() {
                var values = $(this).val();
                var el = this[0];
                var parent = $(el).parent().parent();
                parent.find("span").text(values);
            }
        });
    },
    onOpen: function() {
        $('#task-events-list').append(new TaskListView({model: this.collection}).render().el);
        this.sliderInit();
    },
    closeDialog: function() {
        this.el.dialog('close');
    },
});