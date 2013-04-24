window.FormView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template(tpl.get('form'));
        _.bindAll(this, 'showDropdown', 'hideDropdown');

    },
    render: function() {
        $(this.el).html(this.template());
        return this;
    },
    events: {
        "click .send": "saveAccount",
        "input #city-input": "getSimilarCities",
        "change #country": "getCities",
        "click .selector-dropdown": "showDropdown",
        "click": "hideDropdown",
        "click #city li": "fillInput",
        "mouseover #city li": "changeListBackground",
    },
    checkData: function() {
        alert('Check data');
        return false;
    },
    changeListBackground: function(event) {
        el = event.target;
        $(el).css("background-color", "#FFCC00");
        $(el).children().css("background-color", "#FFCC00");
        $(el).siblings("li").css("background", "#fff");
        $(el).siblings("li").children().css("background", "#fff");
    },
    fillInput: function(event) {
        el = event.target;
        $('#city-input').val($(el).html());
    },
    showDropdown: function() {
        $('#city').show();
        return false;
    },
    hideDropdown: function() {
        $('#city').hide();
        return false;
    },
    getCountries: function() {
        $.getJSON("api/countries",
                function(data) {
                    $.each(data.countries, function(item, val) {
                        $("#country").append('<option value="' + val[0] + '">' + val[1] + '</option>');
                    });
                });
    },
    getSimilarCities: function() {
        var url = "api/country/" + $('#country').val() + "/city/" + $('#city-input').val();
        $('#city').show();

        $("#city").html("");
        $.get(url, function(data) {
            self.obj = jQuery.parseJSON((data.replace(/'/g, "\"")));

            $.each(self.obj, function(item, val) {
                $("#city").append('<li value="' + val[0] + '">' + val[1] + '</li>');
            });

        });
        
    },
    getCities: function() {
        var url = "api/country/" + $("#country").val();
        $("#city").html("");
        $('#city-input').val('');

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
