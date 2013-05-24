

window.PhotoView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template($('#image-details-tpl').html());
        this.model.bind('change', this.render, this);
    },
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
    events: {
        'click #like': 'likePhoto',
        'click #send': 'commentPhoto',
        'click #delete': 'deletePhoto',
    },
    
    likePhoto: function(e){
        console.log($(e.target).attr('val'));
    },
    deletePhoto: function(e){
        this.model.destroy({
            success: function() {
                window.history.back();
            }
        });
        return false;
    },
    commentPhoto: function(e) {
        console.log($(e.target).attr('val'));
        return false;

        var self = this;
        this.model.set({
            name: $('#name').val(),
            about: $('#about').val(),
            country: $('#send-country').val(),
            city: $('#send-city').val(),
        });
        if (this.model.isNew()) {
            this.model.save(null, {
                success: function(model, response) {
                    app.navigate('new_group/success', true);
                },
                error: function(model, response) {
                    self.showErrors(response.responseText);
                    app.navigate('new_group/error', true);
                }
            });
        }
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
