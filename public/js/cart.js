function getItems (data) {
    console.log(data);
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
    html +=                         '<a>'+data.description+'</a>'
    html +=                         '<div class="product-info">'
    html +=                             '<div>Price: <span class="value">₱'+data.selling_price+'</span></div>'
    html +=                             '<div>Unit: <span class="value">'+data.unit+'</span></div>'
    html +=                         '</div>'
    html +=                     '</div>'
    html +=                 '</div>'
    html +=                 '<div class="col-md-3 quantity">'
    html +=                     '<label for="quantity">Qty</label>'
    html +=                     '<input data-id='+data.id+' data-price="'+data.selling_price+'" data-product-code="'+data.product_code+'" type="number" min="1" onkeydown="return (event.keyCode!=189);" value="'+data.qty+'" class="form-control quantity-input">'
    html +=                 '</div>'
    html +=                 '<div class="col-md-4 price">'
    html +=                     '<span>Amount: ₱<span class="amount-'+data.id+'">'+data.amount+'</span></span><br>'
    html +=                     '<button class="btn btn-remove-item" data-id='+data.id+' style="cursor:pointer;"><i class="fa fa-trash mt-2"></i></button>'
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
        url: '/cart/read-items',
        type: 'GET',
        success:function(data){

            if ($('.cart-count:first').text() == 0) {
                $('#btn-checkout').addClass('d-none');
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
        url: '/cart-total',
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

$(document).on('change', '.quantity-input', async function(){
    var $this = $(this);
    var product_id   = $(this).attr('data-product-code');
    var id           = $(this).attr('data-id');
    var qty          = $(this).val();
    var price        = $(this).attr('data-price');
    var amount       = parseInt(qty) * parseFloat(price);
    
    $.ajax({
        url: '/cart/change-qty',
        type: 'POST',
        data: {
            id : id,
            qty : qty,
            amount : amount,
            product_id : product_id
        },
        success:async function(data){
            console.log(data);

            if (data.status == 'not_auth') {
                $.toast({
                    heading:'Please login first. ',
                    position: 'bottom-right',
                    showHideTransition: 'plain',
                    hideAfter: 6000, 
                });
            }
            else if(data.status == 'greater'){
                cartCount();
                $this.val(data.data);
                $.toast({
                    heading:'The Product Qty is Greater Than Stock in your Cart',
                    position: 'bottom-right',
                    showHideTransition: 'plain',
                    hideAfter: 6000, 
                });
            }
            else if(data.status == 'greater2'){
                cartCount();
                $this.val(data.data);
                $.toast({
                    heading:'The Product Qty is Greater Than Stock in your Cart',
                    position: 'bottom-right',
                    showHideTransition: 'plain',
                    hideAfter: 6000, 
                });
            }
            else if(data.status == 'invalid_quantity'){
                // Validation failed, reset the quantity to the previous value
                $this.val(data.data);
                $.toast({
                    heading:'Invalid Quantity. Please enter a valid quantity.',
                    position: 'bottom-right',
                    showHideTransition: 'plain',
                    hideAfter: 6000, 
                });
            }
            else {
                if (qty > 0) { 
                    await cartTotal();
                    $('.amount-'+id).text(amount.toFixed(2));
                }
                else {
                    cartCount();
                    removeFromCart(id, $this);
                } 
            }
        }
    });
});

function removeFromCart(id, $this = "") {
    $.ajax({
        url: '/cart/remove-item/'+id,
        type: 'POST',
        
        beforeSend:function(){
            $this.html('<i class="fa fa-spinner fa-pulse"></i>');
        },
        success:async function(data){ 
            $this.closest('.product').fadeOut();
            cartCount();
            await cartTotal();
            $.toast({
                heading:'Item was removed from cart. ',
                position: 'bottom-right',
                showHideTransition: 'plain',
                hideAfter: 4500, 
            });
        }
    });
}
 
async function renderConponents() {
    await readCart();
    await cartTotal();
}
                             
renderConponents();