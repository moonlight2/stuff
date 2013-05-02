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
            //this.model.bind('reset', this.render, this);
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
            "group": 0,
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
                    _.bindAll(this, 'switchDropdown', 'hideDropdown', 'removeElements');
        },
        render: function() {
            $(this.el).html(this.template(this.model.toJSON()));
            return this;
        },
        events: {
            "click .dropdown-toggle": "switchDropdown",
            "click": "hideDropdown",
            "click #send": "saveAccount",
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
                    $('#country .btn-group').append(new CountryListView({model: self.countries}).render().el);
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
                    $('#group-block').hide();

                    $('#city .btn-group').append(new CityListView({model: self.cities}).render().el);
                }});

        },
        getGroups: function() {
            console.log('Get groups');
            var self = this;
            this.groups = new GroupCollection();
            this.groups.url = "rest/api/groups/country/" + $("#send-country").val() + "/city/" + $("#send-city").val();
            this.groups.fetch({processData: true,
                success: function(data) {
                    self.clearGroupList();
                    $('#group').append(new GroupListView({model: self.groups}).render().el);
                    $('#group-block').show();
                }, error: function() {
                    $('#group-block').hide();
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
                    $('#city .btn-group').append(new CityListView({model: self.cities}).render().el);
                    self.showCityList();
                }});
            return false;
        },
        prepareCityInfoToSend: function() {
            console.log('prepare city to send');
            this.getGroups();
        },
        switchDropdown: function(e) {
            console.log('Switch');
            $(e.target).next().css('display', (($(e.target).next().css('display') == 'block') ? 'none' : 'block'));
            return false;
        },
        hideDropdown: function() {
            $('.dropdown-menu').hide();
        },
        fillContryInput: function(model) {
            $('#country-input').val(this.removeElements(model.get('name'), 'b'));
            $('#send-country').val(model.get('id'));
        },
        changeCountryInput: function(e) {
            console.log('Change country');
            $(e.target).parent().parent().parent().find('input').val($(e.target).html());
            $('#send-country').val($(e.target).val());
            this.getCities();
        },
        changeCityInput: function(e) {
            console.log('Change city');
            $(e.target).parent().parent().parent().find('input').val(this.removeElements($(e.target).html(), 'b'));
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
        removeElements: function(text, selector) {
            var wrapped = $("<div>" + text + "</div>");
            wrapped.find(selector).each(function(index) {
                var text = $(this).text();//get span content
                $(this).replaceWith(text);//replace all span with just content
            });
            return wrapped.html();
        },
        showErrors: function(data) {
            var resp = $.parseJSON(data);
            $('.help-inline').hide();
            $('.control-group').removeClass('error'); 
            $.each(resp, function(i, e) {
                var el = $('#error-' + i).html(e[0]);
                $(el).parent().addClass('error');
                $(el).show();
            });
        },
        saveAccount: function() {
            var self = this;
            this.model.set({
                username: $('#username').val(),
                email: $('#email').val(),
//                about: $('#about').val(),
                password: $('#password').val(),
                country: $('#send-country').val(),
                city: $('#send-city').val(),
                group: $('#group select').val()
            });
            
            if (this.model.isNew()) {
                
                this.model.save(null, {
                    success: function(model, response) {
                        app.navigate('success', true);
                    },
                    error: function(model, response) {
                        self.showErrors(response.responseText);
                        app.navigate('error', true);
                    }
                });
            }
        },
    });

    /*=============================== ROUTER =====================================*/

    window.AppRouter = Backbone.Router.extend({
        routes: {
            "": "formView",
            "success": "showSuccess",
            "error": "showErrors",
        },
        initialize: function() {
            var form = new FormView({model: new Account()});
            this.showView('#form', form);
            form.getCountries();
        },
        showSuccess: function() {
            $('#form').hide();
            $('#success').show();
        },
        showErrors: function() {
            console.log('Error!');
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
