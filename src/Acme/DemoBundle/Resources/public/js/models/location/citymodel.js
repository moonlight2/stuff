window.City = Backbone.Model.extend({
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