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
            this.model.bind('change', this.render, this);
        },
        render: function() {
            _.each(this.model.models, function(city) {
                $(this.el).attr('class', 'dropdown-menu').append(new CityListItemView({model: city}).render().el);
            }, this);
            return this;
        }
    });

    window.CityListItemView = Backbone.View.extend({
        tagName: 'li',
        template: _.template($('#city-tpl').html()),
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
                $(this.el).attr('class', 'dropdown-menu').append(new CountryListItemView({model: country}).render().el);
            }, this);
            return this;
        },
    });

    window.CountryListItemView = Backbone.View.extend({
        tagName: 'li',
        template: _.template($('#country-tpl').html()),
        render: function() {
            $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
            return this;
        },
        initialize: function() {
            this.model.bind('change', this.render, this);
        },
    });

    /*=============================== USERS ======================================*/

    window.Account = Backbone.Model.extend({
        urlRoot: 'rest/api/accounts',
        defaults: {
            "id": null,
            "username": "",
            "email": "",
            "roles": "",
            "about": "",
            "group": "",
            "country": "",
            "city": "",
            "password": ""
        }
    });

    window.AccountList = Backbone.Collection.extend({
        model: Account,
        url: 'rest/api/accounts'
    });


    /*=============================== GROUPS ======================================*/

    window.GroupModel = Backbone.Model.extend({
        urlRoot: 'rest/api/groups',
        defaults: {
            "id": null,
            "name": "",
            "rating": "",
        }
    });

    window.GroupCollection = Backbone.Collection.extend({
        model: GroupModel,
        url: 'rest/api/groups'
    });


    window.GroupListView = Backbone.View.extend({
        tagName: 'select',
        initialize: function() {
            this.model.bind('reset', this.render, this);
        },
        render: function() {
            _.each(this.model.models, function(country) {
                $(this.el).attr('name', 'group').append(new GroupListItemView({model: country}).render().el);
            }, this);
            return this;
        },
    });

    window.GroupListItemView = Backbone.View.extend({
        tagName: 'option',
        template: _.template($('#group-tpl').html()),
        render: function() {
            $(this.el).attr('value', this.model.get('id')).html(this.template(this.model.toJSON()));
            return this;
        },
        initialize: function() {
            this.model.bind('change', this.render, this);
        },
    });

    /*=============================== FORM =======================================*/

    window.FormView = Backbone.View.extend({
        initialize: function() {
            this.template = _.template($('#form-tpl').html()),
                    _.bindAll(this, 'switchDropdown', 'hideDropdown');
            this.model.bind('change', this.render, this);
        },
        render: function() {
            $(this.el).html(this.template(this.model.toJSON()));
            return this;
        },
        events: {
            "click .selector-dropdown": "switchDropdown",
            "click": "hideDropdown",
            "click .send": "saveAccount",
            "click #country li": "changeCountryInput",
            "click #city li": "changeCityInput",
            "input #city-input": "getSimilarCities",
            "mouseover .dropdown-menu li": "changeListBackground",
        },
        getCountries: function() {
            var self = this;
            this.countries = new CountryCollection();
            this.countries.fetch({success: function(data) {
                    self.fillContryInput(data.models[0]); // set input value to first country name
                    $('#country').append(new CountryListView({model: self.countries}).render().el);
                    self.getCities();
                }});
        },
        getCities: function() {
            var self = this;
            this.cities = new CityCollection();
            this.cities.url = "api/country/" + $("#send-country").val();
            this.clearCityInput();
            this.cities.fetch({success: function() {
                    self.clearCityList();
                    $('#city').append(new CityListView({model: self.cities}).render().el);
                }});

        },
        getGroups: function() {
            console.log('Get groups');
            var self = this;
            this.groups = new GroupCollection();
            this.groups.url = "rest/api/groups/country/" + $("#send-country").val() + "/city/" + $("#send-city").val();
            this.groups.fetch({success: function() {
                    self.clearGroupList();
                    $('#group').append(new GroupListView({model: self.groups}).render().el);
                }});
        },
        getSimilarCities: function() {
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
                    $('#city').append(new CityListView({model: self.cities}).render().el);
                    self.showCityList();
                }});
            return false;
        },
        prepareCityInfoToSend: function() {
            console.log('prepare city to send');
            this.getGroups();
        },
        switchDropdown: function(e) {
            $(e.target).next().css('display', (($(e.target).next().css('display') == 'block') ? 'none' : 'block'));
            return false;
        },
        hideDropdown: function() {
            $('.dropdown-menu').hide();
            return false;
        },
        fillContryInput: function(model) {
            $('#country-input').val(model.get('name'));
            $('#send-country').val(model.get('id'));
        },
        changeCountryInput: function(e) {
            $(e.target).parent().parent().find('input').val($(e.target).html());
            $('#send-country').val($(e.target).val());
            this.getCities();
        },
        changeCityInput: function(e) {
            $(e.target).parent().parent().find('input').val($(e.target).html());
            $('#send-city').val($(e.target).val());
            this.prepareCityInfoToSend();
        },
        clearCityInput: function() {
            $('#city-input').val('');
        },
        clearCityList: function() {
            $('#city ul').remove();
        },
        clearGroupList: function() {
            $('#group select').remove();
        },
        showCityList: function() {
            $('#city .dropdown-menu').show();
        },
        changeListBackground: function(e) {
            $(e.target).removeClass('deselected').addClass('selected');
            $(e.target).siblings("li").removeClass('selected').addClass('deselected');
        },
        saveAccount: function() {
            this.model.set({
                username: $('#username').val(),
                email: $('#email').val(),
                about: $('#about').val(),
                password: $('#password').val(),
                country: $('#send-country').val(),
                city: $('#send-city').val(),
                group: $('#group select').val()
            });
            if (this.model.isNew()) {
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
            }
            return false;
        },
    });

    /*=============================== ROUTER =====================================*/

    window.AppRouter = Backbone.Router.extend({
        routes: {
            "": "formView",
        },
        initialize: function() {
            var form = new FormView({model: new Account()});
            this.showView('#form', form);
            form.getCountries();
        },
        showView: function(selector, view) {
            if (this.currentView)
                this.currentView.close();
            $(selector).html(view.render().el);
            this.currentView = view;
            return view;
        },
    });


    app = new AppRouter();
    Backbone.history.start();


});

