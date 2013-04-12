window.WineView = Backbone.View.extend({
    
    initialize: function() {
        this.template = _.template(tpl.get('wine-details'));
        this.model.bind('change', this.render, this);
    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    events: {
        //'change input': 'change',
        'click .save': 'saveWine',
        'click .delete': 'deleteWine'
    },
    saveWine: function() {
        this.model.set({
            name: $('#name').val(),
            grapes: $('#grapes').val(),
            country: $('#country').val(),
            region: $('#region').val(),
            year: $('#year').val(),
            description: $('#description').val()
        });
        if (this.model.isNew()) { // if id of wine = null
            var self = this;
            app.wines.create(this.model, {
                success: function() {
                    app.navigate('wines/' + self.model.id, false);
                }
            });
        } else {
            this.model.save({
                success: function() {
                    alert('Model ' + this.model.name + 'updated');
                }
            });
        }
        return false;
    },
    deleteWine: function() {
        this.model.destroy({
            success: function() {
                alert('Destroyed!');
                console.log(this);
                app.navigate('/', false);
                //$('#main-content').html('Deleted');
                //window.history.back();
            }
        });
        return false;
    },
    change: function(event) {
        var target = event.target;
        console.log('changing ' + target.id + ' from: ' + target.defaultValue + ' to: ' + target.value);
        this.model.set({'name': target.value});
        if (this.model.isNew()) {
            app.wines.create(this.model);
        } else {
            this.model.save();
        }
    },
});
