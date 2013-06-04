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

        this.el.dialog({
            modal: true,
            title: (this.model.isNew() ? 'Новое' : 'Редактипрвать') + ' событие',
            buttons: self.buttons,
            open: this.onOpen,
        });

        return this;
    },
    onOpen: function() {

        console.log('Model of dialog:');
        console.log(this.model);

        $('#title').val(this.model.get('title'));
        $('#text').val(this.model.get('text'));
    },
    save: function() {

        this.model.set({title: $('#title').val()});
        this.model.set({text: $('#text').val()});

        var self = this;
        if (this.model.isNew()) {
            this.model.save(null, {
                success: function(model, response) {
                    self.collection.add(model, {success: self.closeDialog()});
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
                    alert(response.error);
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