
Backbone.View.prototype.close = function() {
    console.log('Closing view :');
    console.log(this);
    if (this.beforeClose) {
        this.beforeClose();
    }
    $(this.el).unbind();
    $(this.el).remove();
};


Backbone.View.prototype.showErrors = function(data) {
    var resp = $.parseJSON(data);
    $('.help-inline').hide();
    $('.control-group').removeClass('error');
    $.each(resp, function(i, e) {
        var el = $('#error-' + i).html(e[0]);
        $(el).parent().addClass('error');
        $(el).show();
    });
};

Backbone.View.prototype.removeElements = function(text, selector) {
    var wrapped = $("<div>" + text + "</div>");
    wrapped.find(selector).each(function(index) {
        var text = $(this).text();
        $(this).replaceWith(text);
    });
    return wrapped.html();
};