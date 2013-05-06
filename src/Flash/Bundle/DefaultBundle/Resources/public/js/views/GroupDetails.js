

window.GroupView = Backbone.View.extend({
    
    initialize: function() {
        this.template =  _.template($('#group-tpl').html()),
        this.model.bind('change', this.render, this);
    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    events: {
        'click .save': 'saveGroup',
        'click .delete': 'deleteGroup'
    },
    saveGroup: function() {
//        this.model.set({
//            name: $('#name').val(),
//            grapes: $('#grapes').val(),
//            country: $('#country').val(),
//            region: $('#region').val(),
//            year: $('#year').val(),
//            description: $('#description').val()
//        });
//        if (this.model.isNew()) { // if id of wine = null
//            var self = this;
//            app.wines.create(this.model, {
//                success: function() {
//                    app.navigate('wines/' + self.model.id, false);
//                }
//            });
//        } else {
//            this.model.save({
//                success: function() {
//                    alert('Model ' + this.model.name + 'updated');
//                }
//            });
//        }
        return false;
    },
    deleteGroup: function() {
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
        console.log('change');
    },
});
