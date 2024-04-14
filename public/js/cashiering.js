$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 

var data_storage;
var last_key = 0;

async function readAllProducts() {
    $('#product-container').html('');
    $('#product-loader').css('display', 'block');
    $.ajax({
        url: '/customer/product',
        type: 'GET',
        success:function(data){
            setTimeout(function() { 
                data_storage = data;

                var html = "";
                var enable_button = false;
                    last_key = 6;
                    
                    for (var i = 0; i < last_key; i++) {
                        if (typeof data_storage[i] != 'undefined') 
                            html += getItems(data_storage[i]);
                    }
                    if(data_storage.length >= last_key){
                        enable_button = true;
                    }
    
                    if (enable_button) {
                        html += '<div class="col-12 load-more-container">';
                            html +='<button class="btn btn-sm btn-outline-success btn-load-more">Load more</button>';
                        html += '</div>';
                    }
                
                $('#product-loader').css('display', 'none');
                $('#product-container').append(html);
            },300)

        }
    });
}

async function updateInvoiceNo(){
    console.log("try");
    $.ajax({
        url: '/update-invoice',
        type: 'GET',
        success:async function(data){
            // $('#discount-percentage').val(data.discount_percentage);
            // $('#minimum-purchase').val(data.minimum_purchase);
            $('#invoice-no').val(data);
            console.log("success");
            console.log(data);
        }
    });
}



function getItems (data) {
    var html = "";
    html += '<div class="grid-item col-sm-6">';
        html += '<div class="card">';
        if (!data.image) {
            data.image = "no-image.png";
        }
            html += '<div style="background-color:#C4BFC2;"><img class="card-img-top cover" src="../images/'+ data.image +'" alt="product image"></div>';
            html += '<div class="card-body">';
                var description = data.description.length > 60 ? data.description.substr(0, 50) + "..." : data.description;
                html += '<div title=\"'+data.description+'"\ class="description">'+ description  +'</div>';
                html += '<div class="d-flex">';
                html += '<div class="card-text mr-2 mb-2 price-'+data.id+'">₱'+ formatNumber(data.selling_price) +'</div>';
                var style = "";
                if (data.qty == 0) {
                    style = 'style="color:red"';    
                }
                html += '<div class="card-text"> Stock: <span class="stock-'+data.id+'" '+style+'>'+data.qty+'</span></div>';
                html += '</div>';
                html += '<div class="d-flex">';
                html += '<span>Qty</span><input class="qty-'+data.id+'" id="qty-onkey" onkeyup="if(this.value > '+data.qty+'){trigger(); this.value = 1;}" onkeydown="return (event.keyCode!=189);" type="number" min="1" value="1" max="'+data.qty+'" style="width:50px; margin-left:5px;">';
                html += '<button class="btn btn-sm btn-outline-success ml-2" data-id="'+data.id+'" data-product-code="'+data.product_code+'" id="btn-add-to-tray">';
                html += 'Add to tray</button>';
                html += '</div>';
            html +=' </div>';
        html += '</div>';
    html += '</div>'; 
    
    return html;
}

function trigger(){
    swal.fire({
        title: "Error!",
        icon: 'error',
        text: "Desired Qty is greater than in Product Qty",
        timer: 3000,
        showConfirmButton: true
      });
}

$('#qty-onkey').change(function(){
    console.log('sad');
    var info = $('#qty-onkey').val();
    if(info.length >3){alert('thank you');}
});

$("#qty-onkey").on('keyup', function(e) {
    console.log(e.keyCode, this.value)
  });



async function readTray() {
    
    await readDiscount();
    $('.tbl-tray tbody').html('');
    $('#tray-loader').css('display', 'block');
    $.ajax({
        url: '/read-tray',
        type: 'GET',
        success:async function(data){
            $('#tray-loader').hide();
            var html = "";
            var total = 0;
            if (data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                     html += await getTrayItems(data[i]);
                     total = parseFloat(total) + parseFloat(data[i].amount); 
                } 
                document.getElementById("proccess").disabled = false;
            }
            else {
                document.getElementById("proccess").disabled = true;
            }

            let discount_percentage = 0;
            let minimum_purchase = $('#minimum-purchase').val();
            let wholesale_discount_amount = 0;
            let senior_pwd_discount_amount = 0;
            let subtotal = total;
            
            if ($('input[name="rad_discount"]').is(":checked")) {
                discount_percentage = $('input[name="rad_discount"]:checked').val();
                senior_pwd_discount_amount = parseFloat(discount_percentage) * parseFloat(total);
                total = total - parseFloat(senior_pwd_discount_amount);
            }
            if (total >= minimum_purchase || $('input[name="rad_discount"]:checked').attr('data-force') == '1') { 
                wholesale_discount_amount = parseFloat($('#discount-percentage').val()) * parseFloat(total);
                total = total - parseFloat(wholesale_discount_amount);
            }

            // to get data for printing
            localStorage.setItem('wholesale_discount_amount', wholesale_discount_amount);
            localStorage.setItem('senior_pwd_discount_amount', senior_pwd_discount_amount);
           
            html += '</tr>';
            html += '<td></td>';
            html += '<td></td>';
            html += '<td></td>';
            html += '<td></td>';
            html += '<td><b>Total</b></td>';
            html += '<td><b id="total">₱'+ formatNumber(total) +'</b></td>'
            html += '</tr>';
            $('.tbl-tray tbody').append(html);

            if (data.length > 4) { 
                $(".tray-container").scrollTop($(".tray-container")[0].scrollHeight);
            }
        }
    });
}

async function getTrayItems (data) {
    var html = "";
    html += '<tr>';
    html += '<td>'+ data.product_code +'</td>';
    html += '<td>'+ data.description +'</td>';
    html += '<td>'+ data.unit +'</td>';
    html += '<td>'+ data.selling_price +'</td>';
    html += '<td>'+ data.qty_order +'</td>';
    html += '<td>₱'+ formatNumber(data.amount) +'</td>';
    html += '<td><a style="color:#1970F1;" class="btn btn-sm btn-void" data-id='+ data.id +'> Void</a></td>'
    return html;
}

function searchProduct () {
    
        var search_key = $('#input-search-product').val();
        $('#product-container').html('');
        $('#product-loader').css('display', 'block');
            console.log(search_key)
        $.ajax({
            url: '/customer/product/search',
            type: 'GET',
            data: {
                search_key : search_key
            },
            
            success:function(data){
             
                setTimeout(function() {
                    $('#product-loader').css('display', 'none');

                    var html = "";

                    if (data.length > 0) {
                        data_storage = data;
            
                        var enable_button = false;
                        for (var i = 0; i < last_key; i++) {
                            if (typeof data_storage[i] != 'undefined') 
                            html += getItems(data_storage[i]);
                        }
                        if(data_storage.length >= last_key){
                            enable_button = true;
                        }

                        if (enable_button) {
                            html += '<div class="col-12 load-more-container">';
                                html +='<button class="btn btn-sm btn-outline-success btn-load-more">Load more</button>';
                            html += '</div>';
                        }
                    }
                    else {
                        html += '<div class="col-12 mt-5 d-flex justify-content-center">';
                        html +='<p class="text-muted">No item found.</p>';
                        html += '</div>';
                    }

                    $('#product-container').append(html);

                    
                },500)
    
            }
        });   
}

function formatNumber(total)
{
  var decimal = (Math.round(total * 100) / 100).toFixed(2);
  return money_format = parseFloat(decimal).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

async function readDiscount() {
    $.ajax({
        url: '/read-discount',
        type: 'GET',
        success:async function(data){
            $('#discount-percentage').val(data.discount_percentage);
            $('#minimum-purchase').val(data.minimum_purchase);
        }
    });
}

$(document).on('click', '.btn-search-userid', function(){
    console.log('check');
    checklrn();
});

function checklrn() {
    var id = $('#input-search-lrn').val();
    console.log('check2');
    if(id === ""){
        swal.fire({
            title: "Error!",
            icon: 'error',
            text: "Please Input the Student LRN Number",
            type: "error",
            timer: 3000,
            showConfirmButton: true
          });
    }
    else{
        $.ajax({
            url: 'check-lrn/' + id,
            type: 'GET',
            success:async function(data){
                console.log('check3');
                if(data == 'success'){
                    setTimeout(async function(){
                        swal.fire({
                            title: "Success",
                            icon: 'success',
                            text: "Student LRN Verified Successfully",
                            timer: 4000,
                          });
                    },300);
                    console.log('check4');
                }
                else{
                    swal.fire({
                        title: "Error!",
                        icon: 'error',
                        text: "Student LRN Number Not Found",
                        type: "error",
                        timer: 3000,
                        showConfirmButton: true
                      });
                      console.log('check5');
    
                }
            }
        });
    }
}

async function getQtyInTray(product_code) {
    await $.ajax({
        url: 'cashiering/read-one-qty/'+product_code,
        type: 'GET',
        
        success:async function(data){
            var qty = isNaN(data.qty) ? 0 : data.qty;
            localStorage.setItem("qty_in_tray", qty);
        }
    });
}

function on_Click () {
    
    $(document).on('click', '#btn-add-to-tray',  function(){

        var product_code = $(this).attr('data-product-code');
        var id           = $(this).attr('data-id');
        var qty          = $('.qty-'+id).val();
        var price        = $('.price-'+id).text().slice(1).replace(",", ""); 
        var amount       = parseInt(qty) * parseFloat(price);
        var stock        = $('.stock-'+id).text();
        console.log(qty)
        getQtyInTray(product_code);

        var qty_in_tray = localStorage.getItem("qty_in_tray");
        var total_qty = parseInt(qty) + parseInt(qty_in_tray);
        console.log(qty)
        if (stock === '0') {
            alert("Not enough stock.")
        }
        else if(qty === '0'){
            alert("Quantity Cannot Be Zero")
        }
        else if(qty === null || qty === undefined || qty ===""){
            alert("Quantity Cannot Be Blank")
        }
        else {
            $.ajax({
                url: '/add-to-tray',
                type: 'POST',
                data: {
                    product_code : product_code,
                    qty : qty,
                    amount : amount
                },
                
                success: function(){
                    readTray();
                    localStorage.removeItem("qty_in_tray");
                }
            });
        }
    });

    $(document).on('click', '.btn-load-more', function(){
        $(this).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: '/customer/product',
            type: 'GET',
            
            success:function(data){
                setTimeout(function() {
                    $('.btn-load-more').hide();
                    data_storage = data;
                 
                    var html = "";
                    var enable_button = false;
                    var old_last_key = last_key;
                    last_key = old_last_key + 6;
                    for (var i = old_last_key; i < last_key; i++) {
                        if (typeof data_storage[i] != 'undefined') 
                            html += getItems(data_storage[i]);
                    }

                    if (data_storage.length >= last_key) {
                        enable_button = true;
                    }

                    if (enable_button) {
                        html += '<div class="col-12 load-more-container">';
                            html +='<button class="btn btn-sm btn-outline-success btn-load-more">Load more</button>';
                        html += '</div>';
                    }
                    $('#product-container').append(html);
                    
                    
                },300)
    
            }
        });
        
    });

    $(document).on('click', '.btn-search-product', function(){
        searchProduct ();
    });

    $(document).on('click', '#proccess', function(){
        var total = $('#total').text().slice(1).replace(",", ""); 
        var tendered = $('#tendered').val();
        var invoice_no = $('#invoice-no').val();
        var studname = $('#input-search-studentname').val();
        total = parseFloat(total);
        tendered = parseFloat(tendered);
    
        if(studname === ""){
            swal.fire({
                title: "Error!",
                icon: 'error',
                text: "Please Type Student Name",
                timer: 3000,
                showConfirmButton: true
              });
        }
        else{
            if (tendered) {
                if ($('#card-payment').is(":checked")) { // Check if Card Payment checkbox is checked
                    var paymentMethod = 'card'; // Define the payment method for card payment
                    var href = "create-checkout?payment_method=" + paymentMethod + "&total=" + total.toFixed(2);
                    $('#btn-place-order').attr("href", href); // Update the href attribute of the button
                }
                else {
                    if (invoice_no) {      
                        $(this).html("Plase wait...");
                        var payment_method = "Cash";
                        if ($('#cash-payment').is(":checked")) {
                            payment_method = "Cash";
                            document.getElementById("contactno").disabled = true;
                        }
                        if ($('#gcash-payment').is(":checked")) {
                            payment_method = "GCash"
                        }
                        if ($('#card-payment').is(":checked")) {
                            payment_method = "Card"
                        }
                        if ($('#paymaya-payment').is(":checked")) {
                            payment_method = "PayMaya"
                        }
                        $.ajax({
                            url: '/record-sale',
                            type: 'POST',
                            data: {
                                payment_method : payment_method,
                                invoice_no     : invoice_no
                            },
                            success:async function(res){
    
                                if (res == 'success') {
    
                                    await readAllProducts();
                                    await updateInvoiceNo();
                                    
                                    $('#change').val('');
                                    $('#tendered').val('');
                                    //$('#invoice-no').val('');
                                    $('#proccess').html("Proccess");
                                  
                                    $.toast({
                                        heading:'Transaction was successfully recorded.',
                                        text:'Generating Invoice...',
                                        position: 'bottom-right',
                                        showHideTransition: 'plain',
                                        hideAfter: 4000, 
                                    });
                                    let wholesale_discount_amount = localStorage.getItem('wholesale_discount_amount');
                                    let senior_pwd_discount_amount = localStorage.getItem('senior_pwd_discount_amount');
                                    setTimeout(async function()
                                    {
                                        window.open("/preview-invoice/"+wholesale_discount_amount +"/"+senior_pwd_discount_amount + "/" + studname);
                                        setTimeout(async function()
                                        {
                                            await readTray();
                                        },300);
                                    },3000);
    
                                }
                                else if (res == 'invoice_exists') {
                                    $('#proccess').html("Proccess")
                                    alert('Invoice # is already exists.')
                                }
                                else {
                                    $.toast({
                                        heading: 'Something went wrong',
                                        position: 'bottom-right',
                                        text:'Please contact the development team',
                                        showHideTransition: 'fade',
                                        icon: 'error',
                                        hideAfter: 4000, 
                                    });
                                }
                            }
                        });
                    }
                    else {
                        alert('Please enter the Invoice #');
                    }
                }
            }
            else {
                alert('Please enter the tendered amount.');
            }
        }
        
    });

    $(document).on('change', '[name=optpayment-method]', async function(){ 
        var $this = $(this);
        console.log("Payment method changed:", $this.val()); // Debug log for selected payment method
        var total = $('#total-amount').val();
        var productname = $('#product-name').val();
        
        if ($this.val() == 'card') {
            console.log("Card payment selected"); // Debug log for card payment selection
            // Update href attribute for card payment
            $('#btn-place-order').attr("href", "create-checkout?payment_method=card&product_name="+productname+"&total="+(Math.round(total * 100) / 100).toFixed(2));
        }
        
    });
    

    $(document).on('click', '#gcash-payment', function(){ console.log($(this).is(":checked"))
        if ($(this).is(":checked")) {
            $('.img-gcash-qr').css('display', 'block');
        }
        else {
            $('.img-gcash-qr').css('display', 'none');
        }
    });

    $(document).on('click', '#paymaya-payment', function(){ console.log($(this).is(":checked"))
        if ($(this).is(":checked")) {
            $('.img-gcash-qr').css('display', 'block');
        }
        else {
            $('.img-gcash-qr').css('display', 'none');
        }
    });

    $(document).on('click', '[name=rad_discount]',async function(){ 
        await readTray();
    });

    

    $(document).on('click', '.btn-void', function(){
        $('#void-modal').modal('show');
        var id = $(this).attr('data-id');  console.log(id)
        $('#btn-confirm-void').attr('data-id', id);
        
    });

    $(document).on('click', '#btn-confirm-void', function(){
        var id = $(this).attr('data-id');
        var username = $('#username').val();
        var password = $('#password').val();


        if (username && password) {
            $(this).html('Please wait...');
            $.ajax({
                url: '/void/'+id,
                type: 'POST',
                data: {
                    username : username,
                    password : password
                },
                success:async function(res){
                    $('#void-modal').modal('hide');
                    $('#btn-confirm-void').html('Void');
                    if (res == 'success') {
                        setTimeout(async function(){
                            $.toast({
                                text:'Item was successfully void.',
                                position: 'bottom-right',
                                showHideTransition: 'plain',
                                hideAfter: 4000, 
                            });
                            await readTray();
                        },300);
                    }
                    else {
                        alert('Invalid username or password');
                    }
                }
            });
        }
        else {
            alert('Please input the admin credential.')
        }
    });
}


function on_Keyup() {
    $(document).on('keydown', '#input-search-product', function(e){ 
        if (e.keyCode == 13) {
            e.preventDefault();
            searchProduct ();
            return false;
        }
    });
    
    $(document).on('keyup', '#tendered', function(){ 
        var tendered = $(this).val();
        var total = $('#total').text().slice(1).replace(",", ""); 
        var change = parseFloat(tendered) - parseFloat(total);
        $('#change').val(change.toFixed(2));
    });
}

async function render() {
    $('.img-gcash-qr').css('display', 'none');  
    await readTray();
    await readAllProducts();
    await updateInvoiceNo();
    on_Click();
    on_Keyup();
}

render();

