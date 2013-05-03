window.CityModel = Backbone.Model.extend({
    defaults: {
        "id": "",
        "name": "",
        "area": "",
    },
    parse: function(response) {
        return response;
    }
});

window.CityCollection = Backbone.Collection.extend({
    model: CityModel,
    url: 'api/country',
    parse: function(response) {
        var resp = Array();
        for (var i = 0; i < response.cities.length; i++) {
            var model = {id: response.cities[i][0], name: response.cities[i][1]};
            resp[i] = model;
        }
        return resp;
    }
});