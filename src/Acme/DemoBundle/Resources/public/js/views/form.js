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
        "input #country-input": "getCities",
        "click .selector-dropdown": "switchDropdown",
        "click": "hideDropdown",
        "click #country li": "fillCountryInput",
        "click #city li": "fillCityInput",
        "mouseover #city li": "changeListBackground",
        "blur #city-input": "prepareCityInfoToSend",
        "click #last-option": "getAllCountries"
    },
    checkData: function() {
        alert('Check data');
        return false;
    },
    changeListBackground: function(e) {
        $(e.target).removeClass('deselected').addClass('selected');
        $(e.target).siblings("li").removeClass('selected').addClass('deselected');
    },
    fillCountryInput: function(e) {
        $(e.target).parent().parent().find('input').val($(e.target).html());
        $('#send-country').val($(e.target).val());
        this.getCities();
    }, 
    fillCityInput: function(e) {
        $(e.target).parent().parent().find('input').val($(e.target).html());
        $('#send-city').val($(e.target).val());
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
    switchDropdown: function(e) {
        $(e.target).next().css('display', (($(e.target).next().css('display') == 'block') ? 'none' : 'block'));
        return false;
    },
    hideDropdown: function() {
        $('.dropdown-menu').hide();
        return false;
    },
    getCountries: function() {
        var self = this;
        $.getJSON("api/countries/1",
                function(data) {
                    $('#country-input').val(data.countries[0][1]);
                    $('#send-country').val(data.countries[0][0]);
                    $.each(data.countries, function(item, val) {
                        $("#country").append('<li value="' + val[0] + '">' + val[1] + '</li>');
                    });
                    $("#country").append('<li><div id="last-option" ><b>Другие страны</b></div></li>');
                    self.getCities();
                });
    },
    getAllCountries: function() {
        alert('click');
        this.clearList();
        $.getJSON("api/countries/0",
                function(data) {
                    console.log(data);
                    $.each(data.countries, function(item, val) {
                        $("#country").append('<li value="' + val[0] + '">' + val[1] + '</li>');
                    });
                });
    },
    getSimilarCities: function() {

        var self = this;
        var url = "api/country/" + $('#send-country').val() + "/city/" + $('#city-input').val();

        self.clearList();
        self.showList();

        $.get(url, function(data) {
            var obj = jQuery.parseJSON((data.replace(/'/g, "\"")));

            if (obj.length < 1) {
                self.clearInput();
                self.clearList();
                $("#city").append('<li value="0">Город не найден</li>');
                $('#send-city').val(0);
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
        var url = "api/country/" + $('#send-country').val() + "/city/" + $('#send-city').val();
        $.get(url, function(data) {
            var obj = jQuery.parseJSON((data.replace(/'/g, "\"")));
            $.each(obj, function(item, val) {
                if (val[1] == $('#city-input').val()) {
                    $('#send-city').val(val[0]);
                    return false;
                }
                //$('#send-city').val(0);
            });
        });
    },
    getCities: function() {

        var url = "api/country/" + $("#send-country").val();
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
        $('#send-city').val(0);
    },
    saveAccount: function() {
        this.model.set({
            username: $('#username').val(),
            email: $('#email').val(),
            about: $('#about').val(),
            password: $('#password').val(),
            country: $('#send-country').val(),
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
