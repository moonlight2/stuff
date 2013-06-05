window.DialogEventView = Backbone.View.extend({
    initialize: function() {
        this.el = $('#eventDialog');
        _.bindAll(this);
    },
    render: function() {

        var self = this;
        this.buttons = {'Ok': this.save};
        _.extend(self.buttons, {'Cancel': this.closeDialog});
        if (!this.model.isNew()) {
            _.extend(self.buttons, {'Delete': this.destroy});
        }

        console.log('Current dialog model');
        console.log(this.model);

        this.el.dialog({
            modal: true,
            title: (this.model.isNew() ? 'Новое' : 'Редактипрвать') + ' событие',
            buttons: self.buttons,
            open: this.onOpen,
        });

        return this;
    },
    onOpen: function() {
        $('#start').val(this.model.get('start'));
        $('#end').val(this.model.get('end'));
        $('#title').val(this.model.get('title'));
        $('#text').val(this.model.get('text'));
    },
    save: function(e) {

        this.model.set({title: $('#title').val()});
        this.model.set({text: $('#text').val()});
        this.model.set({start: $('#start').val()});
        this.model.set({end: $('#end').val()});
        this.model.set({is_shown: false});

        var self = this;
        if (this.model.isNew()) {

            console.log('Model of dialog:');
            console.log(this.model);

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
            this.model.set({is_shown: true});
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



    }
});