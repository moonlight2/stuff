Date.prototype.sameDateAs = function(pDate) {
    return ((this.getFullYear() == pDate.getFullYear()) && (this.getMonth() == pDate.getMonth()) && (this.getDate() == pDate.getDate()));
}

var CalendarEventsView = Backbone.View.extend({
    initialize: function() {
        this.el = $('#calendar');
        _.bindAll(this);

        this.acc_id = $('#acc_id').val();
        this.own_id = $('#own_id').val();

        this.collection = new CalendarEventsCollection();
        this.collection.bind('reset', this.addAll);
        this.collection.bind('add', this.addOne);
        this.collection.bind('change', this.change);
        this.collection.bind('destroy', this.destroy);
    },
    render: function() {

        this.showLoader();
        var self = this;
        this.el.fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month, agendaDay',
            },
            buttonText: {
                prev: "<span class='fc-text-arrow'>&lsaquo;</span>",
                next: "<span class='fc-text-arrow'>&rsaquo;</span>",
                prevYear: "<span class='fc-text-arrow'>&laquo;</span>",
                nextYear: "<span class='fc-text-arrow'>&raquo;</span>",
                today: 'Сегодня',
                month: 'месяц',
                week: 'неделя',
                day: 'день'
            },
            eventColor: 'green',
            eventClick: this.eventClick,
            eventDrop: this.eventDropOrResize,
            eventResize: this.eventDropOrResize,
            selectable: true,
            dayClick: this.onDayClick,
            selectHelper: true,
            editable: true,
            select: this.select,
            theme: false,
            allDayText: 'Весь день',
            dayNamesShort: this.getShortDayNames(),
            monthNames: this.getMonthNames(),
            monthNamesShort: this.getShortMonthNames(),
            dayNames: this.getDayNames()
        });

        var self = this;
        this.collection.url = 'logged/api/account/' + this.acc_id + '/calendar/events';
        this.collection.fetch({success: function(collection) {
                self.hideLoader();
                self.showTodayDialog(collection);
            }});
    },
    showTodayDialog: function(collection) {

        var self = this;
        var timeStamp = new Date();

        this.todayCollection = new CalendarEventsCollection();
        _.each(collection.models, function(event) {
            var date = new Date(event.get('start'));
            if (timeStamp.sameDateAs(date) && false === event.get('isShown')) {
                self.todayCollection.add(event)
            }
        }, this);
        if (this.todayCollection.length > 0) {
            new DialogTodayView({collection: self.todayCollection}).render();
        }
    },
    getMonthNames: function() {
        return Array (
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь'
        );
    },
    getShortMonthNames: function(){
        return ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июль', 'Июнь', 'Авг', 'Сен', 'Окт', 'Нояб', 'Дек'];
    },
    getDayNames: function(){
        return ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];
    },
    getShortDayNames: function(){
        return ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Суб'];
    },
    eventClick: function(e) {
        var eventView = new DialogEventView({
            model: this.collection.get(e.id),
            collection: this.collection
        });

        eventView.render();
    },
    addOne: function(event) {
        this.el.fullCalendar('renderEvent', event.toJSON());
    },
    change: function(e, d) {
        var fcEvent = this.el.fullCalendar('clientEvents', e.get('id'))[0];
        fcEvent.title = e.get('title');
        fcEvent.text = e.get('text');
        fcEvent.color = '#FF0000';
        this.el.fullCalendar('updateEvent', fcEvent);
    },
    onDayClick: function(date, allDay, jsEvent, view) {

        var timeStamp = new Date();
        var startDate = new Date(date);
        var self = this;

        if (view.name != 'month' || startDate <= timeStamp)
            return;

        self.el.fullCalendar('changeView', 'agendaDay')
                .fullCalendar('gotoDate', date);

    },
    select: function(start, end, allDay, jsEvent, view) {

        if (view.name != 'month') {

            var timeStamp = new Date();
            var startDate = new Date(start);

            if (startDate >= timeStamp && this.acc_id == this.own_id) {
                var event = new CalendarEventModel({
                    start: start,
                    end: end,
                    allDay: allDay
                });
                event.urlRoot = 'logged/api/account/' + this.acc_id + '/calendar/events';
                var eventView = new DialogEventView({
                    model: event,
                    collection: this.collection
                });
                eventView.render();
            }
        }
        return false;
    },
    addAll: function() {
        this.el.fullCalendar('addEventSource', this.collection.toJSON());
    },
    destroy: function(event) {
        this.el.fullCalendar('removeEvents', event.id);
    },
    eventDropOrResize: function(e, b) {
        this.collection.get(e.id).save({start: e.start, end: e.end, allDay: e.allDay});
    },
    showLoader: function() {
        $('#loader').show();
    },
    hideLoader: function() {
        $('#loader').hide();
    }
});