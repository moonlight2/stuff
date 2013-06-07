window.Account = Backbone.Model.extend({
    urlRoot: 'api/accounts',
    defaults: {
        "id": null,
        "username": "",
        "first_name": "",
        "last_name": "",
        "email": "",
        "roles": "",
        "group": 0,
        "country": "",
        "city": "",
        "password": ""
    }
});

window.AccountList = Backbone.Collection.extend({
    model: Account,
    url: 'api/accounts'
});