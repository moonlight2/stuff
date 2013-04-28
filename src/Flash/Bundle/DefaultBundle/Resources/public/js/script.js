$(document).ready(function() {


/*=============================== CITIES =====================================*/

    var CityModel = Backbone.Model.extend({
        defaults: {
            "id": "",
            "name": "",
            "area": "",
        },
        parse: function(response) {
            return response;
        }
    });
    
    var CityCollection = Backbone.Collection.extend({
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

    window.CityListView = Backbone.View.extend({
        tagName: 'ul',
        initialize: function() {
            this.model.bind('reset', this.render, this);
        },
        render: function() {
            _.each(this.model.models, function(city) {
                $(this.el).append(new CityListItemView({model: city}).render().el);
            }, this);
            return this;
        }
    });

    window.CityListItemView = Backbone.View.extend({
        tagName: 'li',
        template: _.template($('#city').html()),
        render: function() {
            $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
            return this;
        },
        initialize: function() {
            this.model.bind('change', this.render, this);
        },
    });
    
    
/*=============================== COUNTRIES ==================================*/   

    var CountryModel = Backbone.Model.extend({
        defaults: {
            "id": "",
            "name": "",
        },
        parse: function(response) {
            return response;
        }
    });
    
    var CountryCollection = Backbone.Collection.extend({
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

    window.CountryListView = Backbone.View.extend({
        tagName: 'ul',
        initialize: function() {
            this.model.bind('reset', this.render, this);
        },
        render: function() {
            _.each(this.model.models, function(country) {
                $(this.el).append(new CountryListItemView({model: country}).render().el);
            }, this);
            return this;
        }
    });

    window.CountryListItemView = Backbone.View.extend({
        tagName: 'li',
        template: _.template($('#country').html()),
        render: function() {
            $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
            return this;
        },
        initialize: function() {
            this.model.bind('change', this.render, this);
        },
    });

/*=============================== COUNTRIES ==================================*/ 

    

    var self = this;
    this.cities = new CityCollection({url: '/some/other/url'});
    this.cities.url = 'api/country/2';

    this.cities.fetch({success: function() {
            $('#city-list').html(new CityListView({model: self.cities}).render().el);
        }});
    
    this.countries = new CountryCollection();
    this.countries.fetch({success: function() {
            $('#country-list').html(new CountryListView({model: self.countries}).render().el);
        }});




});

