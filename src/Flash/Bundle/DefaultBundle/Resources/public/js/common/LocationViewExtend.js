

Backbone.View.prototype.getCountries = function(country_id, city_id) {

    var self = this;
    this.city_id = city_id;
    this.countries = new CountryCollection();
    this.countries.fetch({success: function(data) {
            if (typeof (country_id) != 'undefined') {
                 self.fillContryInput(data.get(country_id)); // set input value to first country name
            } else {
                self.fillContryInput(data.models[0]);
            }
            $('#country .btn-group').append(new CountryListView({model: self.countries}).render().el);
            self.getCities(self.city_id);
        }});
};

Backbone.View.prototype.getCities = function(city_id) {
    var self = this;
    this.cities = new CityCollection();
    this.cities.url = "api/country/" + $("#send-country").val();
    this.clearCityInput();
    this.cities.fetch({success: function(data) {
            console.log(city_id);
            self.clearCityList();
            $('#group-block').hide();
            if (typeof (city_id) != 'undefined ') {
                 self.fillCityInput(data.get(city_id)); // set input value to first country name
            }
            $('#city .btn-group').append(new CityListView({model: self.cities}).render().el);
        }});

};

Backbone.View.prototype.getSimilarCities = function() {
    var self = this;
    this.cities = new CityCollection();
    this.cities.url = "api/country/" + $('#send-country').val() + "/city/" + $('#city-input').val();
    this.cities.parse = function(response) {
        var resp = Array();
        for (var i = 0; i < response.length; i++) {
            var model = {id: response[i][0], name: response[i][1]};
            resp[i] = model;
        }
        return resp;
    };
    self.showCityList();

    this.cities.fetch({success: function() {
            self.clearCityList();
            $('#city .btn-group').append(new CityListView({model: self.cities}).render().el);
            self.showCityList();
        }});
    return false;
};


/*===========================  Extra methods  ================================*/

Backbone.View.prototype.prepareCityInfoToSend = function() {
    console.log('prepare city to send');
   // this.getGroups();
    this.getLeaders();
};
Backbone.View.prototype.switchDropdown = function(e) {
    console.log('Switch');
    $(e.target).next().css('display', (($(e.target).next().css('display') === 'block') ? 'none' : 'block'));
    return false;
};
Backbone.View.prototype.hideDropdown = function() {
    $('.dropdown-menu').hide();
};
Backbone.View.prototype.fillContryInput = function(model) {
    $('#country-input').val(this.removeElements(model.get('name'), 'b'));
    $('#send-country').val(model.get('id'));
};
Backbone.View.prototype.fillCityInput = function(model) {
    if (null != model){
        $('#city-input').val(this.removeElements(model.get('name'), 'b'));
        $('#send-city').val(model.get('id'));
    }
};
Backbone.View.prototype.changeCountryInput = function(e) {
    console.log('Change country');
    $(e.target).parent().parent().parent().find('input').val($(e.target).html());
    $('#send-country').val($(e.target).val());
    this.getCities();
};
Backbone.View.prototype.changeCityInput = function(e) {
    console.log('Change city');
    $(e.target).parent().parent().parent().find('input').val(this.removeElements($(e.target).html(), 'b'));
    $('#send-city').val($(e.target).val());
    this.prepareCityInfoToSend();
};
Backbone.View.prototype.clearCityInput = function() {
    $('#city-input').val('');
};
Backbone.View.prototype.clearCityList = function() {
    $('#city ul').remove();
};
Backbone.View.prototype.clearGroupList = function() {
    $('#group select').remove();
};
Backbone.View.prototype.clearLeadersList = function() {
    $('#leaders select').remove();
};
Backbone.View.prototype.showCityList = function() {
    $('#city .dropdown-menu').show();
};

Backbone.View.prototype.changeListBackground = function(e) {
    $(e.target).removeClass('deselected').addClass('selected');
    $(e.target).siblings("li").removeClass('selected').addClass('deselected');
};