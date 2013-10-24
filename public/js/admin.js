$(function(){

    window.sentEditForm = function(that){
        /*var form = $(that),
            form_display_name = form.find('#form_display_name').val(),
            form_seo_link = form.find('#form_seo_link').val();
        console.log(form_display_name, form_seo_link);*/

        var queryString = $('#edit-form').se;
        console.log();
        alert(queryString);
        //$.post()
        return false;
    }

    $('.edit-product-info').click(function(){
        var productId = $(this).attr('product-id');
        $.get('/admin/editproduct/'+productId+'/', {}, function(response){
            $('#editProduct .modal-body').html(response);
            $('#editProduct').modal('toggle');
        });
    });

    $('.edit-info').click(function(){

        var that = $(this),
            action = that.attr('action'),
            id = that.attr('rel');

        $('#edit-'+action+' .modal-body').html('<iframe src=/admin/edit/'+action+'/'+id+'/></iframe>');
        $('#edit-'+action).modal('toggle');

        /*var that = $(this),
            action = that.attr('action'),
            id = that.attr('rel');

        $.get('/admin/edit/'+action+'/'+id+'/', {}, function(response){
            $('#edit-'+action+' .modal-body').html('<iframe src="/admin/edut/subcategory/3/"></iframe>');
            $('#edit-'+action).modal('toggle');
        });*/
    });

    if($('#find-and-replace').is('form')){
        var frForm = document.getElementById('find-and-replace');
        frForm.onsubmit = function(){
            var self = $(this), search = self.find('#input-search').val(), replace = self.find('#input-replace').val(),
                options = {};

            options.name = self.find('#checkbox-name').is(':checked');
            options.shortInfo = self.find('#checkbox-short-info').is(':checked');
            options.details = self.find('#checkbox-details').is(':checked');

            $.get('/admin/replace', {search: search, replace: replace, options: options}, function(response){
                console.log(response);
                alert('Rows updated\nShort info: '+response.shortInfo+'\nDetails: '+response.details+'\nNames: '+response.name);
                //window.location.href = window.location.href;
            });
            return false;
        }
    }
});