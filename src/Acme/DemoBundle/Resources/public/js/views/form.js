window.FormView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template(tpl.get('form'));
        _.bindAll(this, 'switchDropdown', 'hideDropdown');

    },
    render: function() {
        $(this.el).html(this.template());
        return this;
    },
    events: {
        "click .send": "saveAccount",
        "input #city-input": "getSimilarCities",
        "change #country": "getCities",
        "click .selector-dropdown": "switchDropdown",
        "click": "hideDropdown",
        "click #city li": "fillInput",
        "mouseover #city li": "changeListBackground",
        "blur #city-input": "prepareCityInfoToSend"
    },
    checkData: function() {
        alert('Check data');
        return false;
    },
    changeListBackground: function(event) {
        $(event.target).css("background-color", "#FFCC00");
        $(event.target).siblings("li").css("background", "#fff");
    },
    fillInput: function(event) {
        $('#city-input').val($(event.target).html());
        this.prepareCityInfoToSend();
    },
    clearInput: function() {
        $('#city-input').val('');
    },
    clearList: function() {
        $("#city").html('');
    },
    showList: function() {
        $('#city').show();
    },
    switchDropdown: function() {
        $('#city').css('display', (($('#city').css('display') == 'block') ? 'none' : 'block'));
        return false;
    },
    hideDropdown: function() {
        $('#city').hide();
        return false;
    },
    getCountries: function() {
        var self = this;
        $.getJSON("api/countries/1",
                function(data) {
                    $.each(data.countries, function(item, val) {
                        $("#country").append('<option value="' + val[0] + '">' + val[1] + '</option>');
                    });
                    self.getCities();
                });
    },
    getSimilarCities: function() {

        var self = this;
        var url = "api/country/" + $('#country').val() + "/city/" + $('#city-input').val();

        self.clearList();
        self.showList();

        $.get(url, function(data) {
            var obj = jQuery.parseJSON((data.replace(/'/g, "\"")));

            if (obj.length < 1) {
                self.clearInput();
                self.clearList();
                $("#city").append('<li value="0">Город не найден</li>');
                return false;
            } else {
                self.clearList();
                $.each(obj, function(item, val) {
                    $("#city").append('<li value="' + val[0] + '">' + val[1] + '</li>');
                });
            }
        });
    },
    prepareCityInfoToSend: function() {
        var url = "api/country/" + $('#country').val() + "/city/" + $('#city-input').val();
        $.get(url, function(data) {
            var obj = jQuery.parseJSON((data.replace(/'/g, "\"")));
            $.each(obj, function(item, val) {
                if (val[1] == $('#city-input').val()) {
                    $('#send-city').val(val[0]);
                    return false;
                }
                $('#send-city').val(0);
            });
        });
    },
    getCities: function() {

        var url = "api/country/" + $("#country").val();
        this.clearInput();
        this.clearList();

        $.getJSON(url,
                function(data) {
                    console.log(data);
                    $.each(data.cities, function(item, val) {
                        $("#city").append('<li value="' + val[0] + '">' + val[1] + '</li>');
                    });
                });
        $('#city-block').show();
    },
    saveAccount: function() {
        this.model.set({
            username: $('#username').val(),
            email: $('#email').val(),
            about: $('#about').val(),
            password: $('#password').val(),
            country: $('#country').val(),
            city: $('#send-city').val(),
        });
        this.model.save(null, {
            success: function(model, response) {
                console.log(response);
                alert('Account saved');
            },
            error: function(model, response) {
                console.log(response);
                alert('Error!');
            }
        });
        return false;
    },
});
