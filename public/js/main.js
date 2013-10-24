$(function(){

    var showCartWidget = function(){
        $.get('/widget/cart', {}, function(response){
            $('#cart-widget').hide();
            $('#cart-widget').html(response);
            $('#cart-widget').show('fast');
        });
    }

    $("[id^='add-product']").click(function(){
        var id = $(this).attr('id').substr(12);
        id = parseInt(id, 0);
        $.get('/cart/add/'+id, {}, function(response){
            alert('Добавлено "'+response+'"');
            showCartWidget();
        });
    });

    //showCartWidget();

    $('.input-qty').click(function(){
        $(this).select();
    });


    /*$('.input-qty').bind('keyup mouseup', function(){
    //$('.input-qty').change(function(){
        var val = parseInt($(this).val(), 0);
        if(val >= 1){

        } else {
            val = 1;
            $(this).val(val);
        }

        // recount
        var priceCont = $(this).parent().next();
        priceCont.text(parseInt(priceCont.text(), 0) * val);

        var total = {price: 0};
        $.each($('.cart-price'), function(i, o){
            var price = parseInt($(o).text(), 0);
            total.price += price;
        });

        $('td.total').html(total.price + ' руб.');
    });*/

    var recountAll = function(){
        var total = {price: 0};
        $.each($('table#cart-positions tbody tr'), function(i, o){
            var row = $(o);
            row.find('td.loop-index').text(i + 1);
            var price = parseInt(row.find('td.cart-price span.counted-price').text(), 0);
            total.price += price;
        });

        $('td.total').html(total.price + ' руб.');
    }

    if($('table#cart-positions').is('table')){

        $.each($('table#cart-positions tbody tr'), function(i, o){
            var row = $(o), qty = row.find('input.input-qty').val(),
                basePrice = row.find('.base-price').text();
            row.find('.counted-price').text(basePrice * qty);
        });

        recountAll();
    }

    $('.input-qty').spinner({
        max: 10,
        min: 1,
        spin: function(event, ui){
            var that = $(this), posId = that.attr('rel'),
                posRow = $('table#cart-positions tr#position-'+posId),
                basePrice = parseInt(posRow.find('.base-price').text(),0);

            // set qty on server
            $.get('/cart/setqty/'+posId+'/'+ui.value, {}, function(response){
                if(undefined === response.error){
                    // set new price
                    posRow.find('.counted-price').text(basePrice * ui.value);
                    recountAll();
                } else {
                    alert(response.error);
                }
            });
        }
    });

    $('.delete-cart-position').bind({
        mouseover: function(){
            $(this).addClass('circle-color');
        },
        mouseout: function(){
            $(this).removeClass('circle-color');
        },
        click: function(){
            var id = $(this).attr('rel');
            $(this).find('i').addClass('circle-color');
            var element = $(this).parent().parent();
            $.get('/cart/delete/'+id, {}, function(response){
                if('ok' === response){
                    element.hide('slow', function(){
                        $(this).remove();
                        recountAll();
                    });
                }
            })
        }
    });
});