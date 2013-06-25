window.DialogEventView = Backbone.View.extend({
    initialize: function() {
        this.el = $('#eventDialog');
        _.bindAll(this);
        this.url = 'logged/api/account/' + acc_id + '/calendar/events';
    },
    render: function() {

        var self = this;
        this.buttons = {'Ok': this.save};
        _.extend(self.buttons, {'Cancel': this.closeDialog});
        if (!this.model.isNew()) {
            _.extend(self.buttons, {'Delete': this.destroy});
            if (1 == is_leader && false == this.model.get('isShared')) {
                _.extend(self.buttons, {'Share': this.share});
            }
        }
        this.el.dialog({
            modal: true,
            title: (this.model.isNew() ? 'Новое' : 'Редактипрвать') + ' событие',
            buttons: self.buttons,
            open: this.onOpen,
        });
        this.getFollowers();
        return this;
    },
    onOpen: function() {
        $('#start').val(this.model.get('start'));
        $('#end').val(this.model.get('end'));
        $('#title').val(this.model.get('title'));
        $('#text').val(this.model.get('text'));
    },
    getFollowers: function() {

        var self = this;
        console.log(app.followers);
        if (typeof(app.followers) == 'undefined') {
            app.followers = new AccountList();
            app.followers.url = 'logged/api/account/' + acc_id + '/followers';
            app.followers.fetch({success: function(data) {
                    $('#followers').append(new AccountListView({model: app.followers}).render().el);
                }});
        }
        return false;
    },
    save: function(e) {

        this.model.set({title: $('#title').val()});
        this.model.set({text: $('#text').val()});
        this.model.set({start: $('#start').val()});
        this.model.set({end: $('#end').val()});
        this.model.set({isShown: false});

        var self = this;
        if (this.model.isNew()) {
            this.model.save(null, {
                success: function(model, response) {
                    self.collection.add(model, {success: self.closeDialog()});
                    app.navigate('success', true);
                },
                error: function(model, response) {
                    self.showErrors(response.responseText);
                    app.navigate('error', true);
                }
            })
        } else {
            this.model.save({}, {success: this.closeDialog()});
        }
    },
    closeDialog: function() {
        this.el.dialog('close');
    },
    destroy: function() {

        var self = this;
        this.model.destroy({
            success: function(model, response) {
                self.closeDialog();
                if (response.error) {
                    app.navigate('error', true);
                } else if (response.success) {
                    console.log(response.success);

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
        
        $("input:checkbox[name=followers]:checked").each(function()
        {
            followers.push($(this).val());
        });

        this.model.url = 'logged/api/account/' + own_id + '/calendar/events/' + this.model.get('id') + '/share';
        var self = this;
        this.model.set({color: '#FF0000'});
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