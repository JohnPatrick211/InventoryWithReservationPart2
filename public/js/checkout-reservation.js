function getItems (data) {
    var html = "";
    html += '<div class="product">';
    html += '<div class="row">';
    html +=    '<div class="col-md-3">';
    html +=         '<img class="img-fluid mx-auto d-block image" src="images/'+data.image+'">'
    html +=     '</div>'
    html +=     '<div class="col-md-8">'
    html +=         '<div class="info">'
    html +=             '<div class="row">'
    html +=                 '<div class="col-md-5 product-name">'
    html +=                     '<div class="product-name">'
    html +=                         '<a product-name='+data.description+'>'+data.description+'</a>'
    html +=                         '<div class="product-info">'
    html +=                             '<div>Price: <span class="value">₱'+data.selling_price+'</span></div>'
    html +=                             '<div>Unit: <span class="value">'+data.unit+'</span></div>'
    html +=                         '</div>'
    html +=                     '</div>'
    html +=                 '</div>'
    html +=                 '<div class="col-md-3 quantity">'
    html +=                     '<label for="quantity">Qty</label>'
    html +=                     '<div data-id='+data.id+'>'+data.qty+'</div>'
    html +=                 '</div>'
    html +=                 '<div class="col-md-4 price">'
    html +=                     '<span>Amount: ₱<span class="amount-'+data.id+'">'+data.amount+'</span></span><br>'
 //   html +=                     '<button class="btn btn-remove-item" data-id='+data.id+' style="cursor:pointer;"><i class="fa fa-trash mt-2"></i></button>'
    html +=                 '</div>'
    html +=             '</div>'
    html +=         '</div>'
    html +=     '</div>'
    html += '</div>'
    html += '</div>'
    
    return html;
    
}
           

async function readCart() {
    $.ajax({
        url: '/reservationbook/read-items',
        type: 'GET',
        success:function(data){

                if ($('.reservation-count:first').text() == 0) {
                    $('#btn-place-order-reservation').addClass('d-none');
                }
                $('.lds-default').css('display', 'none');
                $.each(data,function(i,v){
                    var html = "";
                    setTimeout(function() {
                        html += getItems(data[i]);
                        $('#cart-items').append(html);
                    },(i)*300)
                })
                

        }
    });
}

async function cartTotal() {
    $.ajax({
        url: '/reservation-total',
        type: 'GET',
        success:function(data){
            $('#subtotal').text('₱'+data);
            $('#total').text('₱'+data);
        }
    });
}

$(document).on('click', '.btn-remove-item', async function(){ 
    var $this = $(this);
    var id = $(this).attr('data-id');
    removeFromCart(id, $this)
});


$(document).on('change', '[name=optpayment-method]', async function(){ 

    var $this = $(this);
    var total = $('#total-amount').val();
    var productname = $('#product-name').val();
 
    
    if ($this.val() == 'card') {
        // $('#btn-place-order').removeClass('d-none');
        // $('#invalid-amount-message').addClass('d-none');
        $('#btn-place-order-reservation').attr("href", "create-checkout-re?payment_method=card&product_name="+productname+"&total="+(Math.round(total * 100) / 100).toFixed(2));
    }
    else if ($this.val() == 'gcash') {
        $('#btn-place-order-reservation').attr("href", "create-source-re?payment_method=gcash&total="+(Math.round(total * 100) / 100).toFixed(2));
    }
    else if ($this.val() == 'paymaya') {
        $('#btn-place-order-reservation').attr("href", "create-payment-method-re?payment_method=paymaya&total="+(Math.round(total * 100) / 100).toFixed(2));
    }
    console.log($('#btn-place-order-reservation').attr('href'))
    
    validateAmount();  
});

$(document).on('click', '#btn-place-order-reservation', async function(){ 
    var $this = $(this);
    var payment_method = "GCash";
    var total = $('#total-amount').val();
    if ($('#opt-paymaya').is(':checked')) {
        payment_method = "PayMaya"
    }
    if ($('#opt-card').is(':checked')) {
        payment_method = "Card"
    }

  
    // $.ajax({
    //     url: '/place-order',
    //     type: 'POST',
    //     data: {
    //         payment_method:payment_method
    //     },
        
    //     beforeSend:function(){
    //         $this.attr("disabled", true);
    //         $this.html('<i class="fas fa-spinner fa-pulse"></i>');
    //     },

    //     success:async function(order_no){
    //         $this.html('Place order');
    //         let message = 'Payment Successfully, your payment amounting of ₱'+total+' is success'
    //         // window.location.href = '/order-info/'+order_no+'/'+payment_method+'?success='+message;
    //     }
    // });
});

function validateAmount() { console.log($('#total-amount').val())
    /*if ($('#total-amount').val() < 100) { console.log('disabled')
        $('#btn-place-order-reservation').addClass('d-none');
        $('#invalid-amount-message').removeClass('d-none');
        $('#invalid-amount-message').text('Online payments only accept amounts over 100 PHP');
    }
    else { 
         $('#btn-place-order-reservation').removeClass('d-none');
    $('#invalid-amount-message').addClass('d-none');
}
    }*/

    $('#btn-place-order-reservation').removeClass('d-none');
    $('#invalid-amount-message').addClass('d-none');
}

async function renderConponents() { 
    await readCart();
    await cartTotal();
    validateAmount();
    var $this = $(this);
    var total = $('#total-amount').val();
    var productname = $('#product-name').val();
    $('#btn-place-order-reservation').attr("href", "create-checkout-re?payment_method=card&product_name="+productname+"&total="+(Math.round(total * 100) / 100).toFixed(2));
    console.log($('#btn-place-order-reservation').attr('href'))

}
                             
renderConponents();