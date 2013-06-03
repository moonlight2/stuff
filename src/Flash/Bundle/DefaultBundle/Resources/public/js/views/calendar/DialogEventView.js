window.DialogEventView = Backbone.View.extend({
    initialize: function() {
        this.el = $('#eventDialog');
        _.bindAll(this);
    },
    render: function() {
        this.el.dialog({
            modal: true,
            title: 'New Event',
            buttons: {'Ok': this.save, 'Cancel': this.closeDialog},
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

        this.model.save(null, {
            success: function(model, response) {
                self.collection.add(model, {success: self.closeDialog()});
            },
            error: function(model, response) {
                self.showErrors(response.responseText);
                app.navigate('error', true);
            }
        });
    },
    closeDialog: function() {
        this.el.dialog('close');
    }
});