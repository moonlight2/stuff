window.DialogEventView = Backbone.View.extend({
    initialize: function() {
        this.el = $('#eventDialog');
        _.bindAll(this);
        this.url = 'logged/api/account/' + acc_id + '/calendar/events';
    },
    render: function() {

        $('#response-events-list').html('');
        
        var self = this;
        this.isActive = this.model.get('isActive');
        this.buttons = {};

        if (this.isActive) {
            _.extend(self.buttons, {'Ok': this.save});
        }
        _.extend(self.buttons, {'Cancel': this.closeDialog});
        if (!this.model.isNew()) {
            _.extend(self.buttons, {'Delete': this.destroy});
            if (1 == is_leader && false == this.model.get('isShared') && true == self.isActive) {
                _.extend(self.buttons, {'Share': this.share});
            }
        }
        this.el.dialog({
            modal: true,
            title: (this.model.isNew() ? 'Новое' : 'Редактипрвать') + ' событие',
            buttons: self.buttons,
            open: this.onOpen,
        });
        if (1 == is_leader) {
            this.getFollowers();
            this.getEventStatus();
        }

        return this;
    },
    onOpen: function() {
        $('#start').val(this.model.get('start'));
        $('#end').val(this.model.get('end'));
        $('#title').val(this.model.get('title'));
        $('#text').val(this.model.get('text'));
    },
    getEventStatus: function() {

        var self = this;
        this.accList = new AccountList();

        if (true == this.model.get('isShared') && true == this.model.get('isActive')) {
            $.ajax({
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json"
                },
                type: "POST",
                url: 'logged/api/calendar/events/' + this.model.get('id') + '/check_status',
                dataType: "json",
                success: function(response) {
                    _.each(response.event, function(data) {
                        var acc = new Account();
                        console.log(data.account_id);
                        if (own_id != data.account_id) {
                            acc.set({id: data.account_id});
                            if (typeof (data.confirmed) != 'undefined') {
                                acc.set({confirmed: data.confirmed});
                            } else {
                                acc.set({confirmed: -1});
                            }
                            if (typeof (data.confirmed) != 'undefined') {
                                acc.set({rejected: data.rejected});
                            } else {
                                acc.set({rejected: -1});
                            }
                            if (typeof (data.percent) != 'undefined') {
                                acc.set({percent: data.percent});
                            } else {
                                acc.set({percent: -1});
                            }
                            acc.set({first_name: data.first_name});
                            acc.set({last_name: data.last_name});

                            self.accList.add(acc);
                        }
                    }, this);
                    $('#response-events-list').html('');
                    $('#response-events-list').append(new ResponseListView({model: self.accList}).render().el);
                }});


        }
    },
    getFollowers: function() {

        var self = this;

        if (typeof(app.followers) == 'undefined') {
            app.followers = new AccountList();
            app.followers.url = 'logged/api/account/' + acc_id + '/followers';
            app.followers.fetch({success: function(data) {
                    $('#followers').append(new AccountListView({model: app.followers}).render().el);
                }});
        }
        return false;
    },
    showErrors: function(errors) {
        this.hideErrors();
        _.each(errors, function(error) {
            var controlGroup = $('.' + error.name);
            controlGroup.addClass('error');
            controlGroup.find('.help-inline').text(error.message);
        }, this);
    },
    hideErrors: function() {
        $('.control-group').removeClass('error');
        $('.help-inline').text('');
    },
    closeDialog: function() {
        this.el.dialog('close');
        this.hideErrors();
    },
    save: function(e) {

        var self = this;

        var options = {
            success: function(model, response) {
                self.collection.add(model, {success: self.closeDialog()});
                self.hideErrors();
            },
            error: function(model, errors) {
                self.showErrors(errors);
            }
        };

        var feedback = {
            title: $('#title').val(),
            text: $('#text').val(),
            start: $('#start').val(),
            end: $('#end').val(),
            isShown: false
        };

        this.model.save(feedback, options);
    },
    destroy: function() {

        var self = this;
        this.model.destroy({
            success: function(model, response) {
                self.closeDialog();
                if (response.error) {
                    app.navigate('error', true);
                }
            },
            error: function(model, response) {
                self.showErrors(response.responseText);
                app.navigate('error', true);
            }
        })
    },
    share: function() {

        var followers = [];

        $("input:checkbox[name=followers]:checked").each(function() {
            followers.push($(this).val());
        });

        this.model.url = 'logged/api/account/' + own_id + '/calendar/events/' + this.model.get('id') + '/share';
        var self = this;
        this.model.set({color: '#FF0000'});
        this.model.set({isShared: true});
        this.model.set({sharedFor: followers});

        this.model.save(null, {
            success: function(model, response) {
                self.closeDialog();
                self.model.url = self.url + '/' + self.model.get('id');
                app.navigate('success', true);

            },
            error: function(model, response) {
                self.showErrors(response.responseText);
                app.navigate('error', true);
            }
        })
    }
});