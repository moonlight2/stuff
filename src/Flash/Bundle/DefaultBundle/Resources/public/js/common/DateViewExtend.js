
Backbone.View.prototype.myDate = Array('00/00/0000','00','00');

Backbone.View.prototype.prepareDateToSend = function(el) {

    var val = $(el.target).val();
    var id = $(el.target).attr('id');

    switch (id) {
        case 'date':
            this.myDate[0] = val;
            break;
        case 'hour':
            this.myDate[1] = val;
            break;
        case 'minutes':
            this.myDate[2] = val;
            break;
        default:
            console.error('Incorrect date');
    }
    var sendDate = this.myDate[0] + ' ' + this.myDate[1] + ':' + this.myDate[2];
    console.log(this.myDate);
    console.log(sendDate);
    $('#send-date').val(sendDate);
};
