window.CountryModel = Backbone.Model.extend({
    defaults: {
        "id": "",
        "name": "",
    },
    parse: function(response) {
        return response;
    }
});

window.CountryCollection = Backbone.Collection.extend({
    model: CountryModel,
    url: 'api/countries/1',
    parse: function(response) {
        var resp = Array();
        for (var i = 0; i < response.countries.length; i++) {
            var model = {id: response.countries[i][0], name: response.countries[i][1]};
            resp[i] = model;
        }
        return resp;
    }
});