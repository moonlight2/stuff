window.DialogTaskView = Backbone.View.extend({
    initialize: function() {
        this.el = $('#taskEventDialog');
        _.bindAll(this);
        this.sliderInit();
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
            width: 500
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
                $(".count").text(values);
            }
        });
    },
    onOpen: function() {

        $('#div .ui-menu').width(300);
        _.each(this.collection.models, function(event) {
            console.log(event);

            var d = new Date(event.attributes.start);
            $('#task-events-list').append(
                    '<p>' + event.attributes.title
                    + ' at '
                    + d.getDate()
                    + '-' + d.getMonth()
                    + '-' + d.getFullYear()
                    + ' in '
                    + d.getHours()
                    + ':' + d.getMinutes()
                    + '<div class="input-append"><div class="noUiSlider"></div><div class="btn-group"><button class="btn" type="button">Принять</button><button class="btn" type="button">Отказаться</button></div></div>');
        }, this);

        this.sliderInit();
    },
    confirm: function(e) {
        _.each(this.collection.models, function(event) {
//            event.set({isShown: true});
//            event.save();
        }, this);
        this.closeDialog();
    },
    closeDialog: function() {
        this.el.dialog('close');
    },
});