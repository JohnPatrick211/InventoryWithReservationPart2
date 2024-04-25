$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 


async function fetchOrder(object = 'reservation'){
    $('#tbl-'+object+'-order').DataTable({
    
        processing: true,
        serverSide: true,
        ajax: '/path/to/script',
        scrollY: 470,
        scroller: {
            loadingIndicator: true
        },

        ajax:{
            url: "/read-orders",
            type:"GET",
            data: {
                object : object
            },
        },
   
        columnDefs: [{
          targets: 0,
          searchable: true,
          orderable: false,
          changeLength: false
      }],
           
       columns:[       
            {data: 'order_no', name: 'order_no'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'date_order', name: 'date_order'},
            {data: 'action', name: 'action',orderable: false},
       ]
      });
 
     
}


async function readOneOrder(order_no, id_type) {

    $('#orders-container').html('');
    $('#reservation-container').html('');
    $.ajax({
        url: '/read-one-order/'+order_no,
        type: 'GET',
        success:function(data){
            let total = 0;
            $.each(data,function(i,v){
                console.log(data);
                var html = "";
                setTimeout(function() {
                    total = parseFloat(total) + (parseFloat(data[i].selling_price) * parseFloat(data[i].qty));
                    html += getItems(data[i]);
                    if (data.length-1 == i) {
                        html += getComputation(total, id_type);
                    }
                    $('#orders-container').append(html);
                    $('#reservation-container').append(html);
                },(i)*100)
            });
        }
    });
}

function getItems (data) {
    var html = "";
    html += '<tr>';
        html += '<td>'+data.product_code+'</td>';
        html += '<td>'+data.description+'</td>';
        html += '<td>'+data.unit+'</td>';
        html += '<td>₱'+data.selling_price+'</td>';
        html += '<td>'+data.qty+'</td>';
        html += '<td style="text-align:right;">₱'+data.amount+'</td>';
    html += '</tr>';
    
    return html;
}

function getComputation(total, id_type) {
    let total_amount = total;
    var html = "";  

    var _text = "";

    console.log(total_amount); 

    let discount_percentage = 0;
    let minimum_purchase = $('#minimum_purchase').val();
    let wholesale_discount_amount = 0;
    let senior_pwd_discount_amount = 0;
    let subtotal = total;

    console.log(subtotal); 

    if (id_type == "Senior Citizen ID/Booklet") {
        _text = "Senior Citizen";
        discount_percentage = $('#senior_percentage').val();
        senior_pwd_discount_amount = parseFloat(discount_percentage) * parseFloat(total);
        total_amount = total - parseFloat(senior_pwd_discount_amount);
    }
    else if (id_type == "PWD ID") {
        _text = "PWD";
        discount_percentage = $('#pwd_percentage').val();
        senior_pwd_discount_amount = parseFloat(discount_percentage) * parseFloat(total);
        total_amount = total - parseFloat(senior_pwd_discount_amount);
    }  
     
    
    if (id_type == "Senior Citizen ID/Booklet" || id_type == "PWD ID") {
        html += '<tr>';
            html += '<td></td><td></td><td></td><td></td>';
            html += '<td>'+_text+' discount:</td>';
            html += '<td style="text-align:right;">₱'+formatNumber(senior_pwd_discount_amount.toFixed(2))+'</td>';
        html += '</tr>';  
    }

    html += '<tr>';
        html += '<td></td><td></td><td></td><td></td>';
        html += '<td>Total:</td>';
    html += '<td style="text-align:right;">₱'+formatNumber(total_amount.toFixed(2))+'</td>';
html += '</tr>';    
    return html;                    
}

async function readShippingAddress(user_id) {
    $.ajax({
        url: '/read-shipping-address/'+user_id,
        type: 'GET',
        success:function(data){
            let html = '';
            html += '<label>Shipping Address</label>';
            html += '<div>'+data.municipality+', '+data.brgy+' '+data.street+'</div>';
            html += '<div>Nearest landmark: '+data.notes+'</div>';
            $('#shipping-info-container').html(html);
        }
    });
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

async function on_Click() {
    
    $(document).on('click','.btn-show-order', async function(){
        let active_pill = $('.nav-pills .active').attr('aria-controls');
        let btn_text = ''
        let status = 1;
        if (active_pill == 'pending' || active_pill == 'pre-order') {
            btn_text = 'Prepare';
            status = 2;
        }
        else if (active_pill == 'prepared') {
            btn_text = 'Ready To Pickup';
            status = 3;
        }
        else if (active_pill == 'shipped') {
            btn_text = 'Completed';
            status = 4;
        }

        let order_no = $(this).attr('data-order-no');
        let customer_name = $(this).attr('data-name');
        let id_type = $(this).attr('data-id-type');
        let phone = $(this).attr('data-phone');
        let email = $(this).attr('data-email');
        let payment_method = $(this).attr('data-payment');
        let user_id = $(this).attr('data-user-id');
        let delivery_date = $(this).attr('data-delivery-date');
        let supplier_name = $(this).attr('data-supplier');
        let latlong = $(this).attr('data-longlat');
        let btn = '<button class="btn btn-sm btn-outline-dark" id="btn-print" type="button">Print</button>';
        if (active_pill != 'completed' && active_pill != 'cancelled') {
            btn += '<button class="btn btn-sm btn-success" id="btn-change-status" data-active-pill="'+active_pill+'" data-status="'+status+'"  type="button">'+btn_text+'</button>';
        }

                  
        let html = '<div class="col-sm-12 col-md-6">';
        var verified_text = "Verified";
            if (id_type == "Senior Citizen ID/Booklet") {
                verified_text = "Verified Senior Citizen";
            }
            else if (id_type == "PWD ID") {
                verified_text = "Verified PWD";
            }
            html += '<div><span class="badge badge-success">'+verified_text+'</span></div>';
            html += '<div>Customer name: '+customer_name+'</div>';
            html += '<div>Contact #: '+phone+'</div>';
            html += '<div>Email: '+email+'</div>';
            if(active_pill == 'pending' || active_pill == 'reservation' || active_pill == 'preorder'){

            }
            else{
                html += '<div><b>Supplier Name: </b>'+supplier_name+'</div>';
            }
            html += '</div>';
            html += '<div class="col-sm-12 col-md-6">';
            html += '<div class="float-right">Order #: <b>'+order_no+'</b><div>Payment method: '+payment_method+'</div></div>';
            if (active_pill == 'pending' || active_pill == 'reservation' || active_pill == 'pre-order') {
                html += '<div class="float-right" style="margin-right:55px;"><b>Estimated Pickup Date:</b> <input id="delivery_date" type="date" class="form-control"></div>';
                document.getElementsByClassName('supplier')[0].style.display = "block";
            }
            else {
                html += '<div class="float-right" style="margin-right:65px;"><b>Estimated Pickup Date:</b><br> '+delivery_date+'</div>';
                document.getElementsByClassName('supplier')[0].style.display = "none";
            }
            html += '</div>';
        $('#show-orders-modal').modal('show');
        $('#show-orders-modal').find('#user-info').html(html);
        $('#show-orders-modal').find('.modal-footer').html(btn);

        await readOneOrder(order_no, id_type);
        await readShippingAddress(user_id);

        
        $('#btn-change-status').attr('data-order-no', order_no);

        if (latlong.length > 0) {
            await initMap(latlong);
        }
        
    });

    $(document).on('click','.btn-show-reservation', async function(){
        let active_pill = $('.nav-pills .active').attr('aria-controls');
        let btn_text = ''
        let status = 1;

        if (active_pill == 'pending' || active_pill == 'reservation') {
            btn_text = 'Prepare';
            status = 2;
        }
        else if (active_pill == 'prepared') {
            btn_text = 'Ready To Pickup';
            status = 3;
        }
        else if (active_pill == 'Ready To Pickup') {
            btn_text = 'Completed';
            status = 4;
        }

        let order_no = $(this).attr('data-order-no');
        let customer_name = $(this).attr('data-name');
        let id_type = $(this).attr('data-id-type');
        let phone = $(this).attr('data-phone');
        let email = $(this).attr('data-email');
        let payment_method = $(this).attr('data-payment');
        let user_id = $(this).attr('data-user-id');
        let delivery_date = $(this).attr('data-delivery-date');
        let latlong = $(this).attr('data-longlat');
        let btn = '<button class="btn btn-sm btn-outline-dark" id="btn-print" type="button">Print</button>';
        if (active_pill != 'completed' && active_pill != 'cancelled') {
            btn += '<button class="btn btn-sm btn-success" id="btn-change-status" data-active-pill="'+active_pill+'" data-status="'+status+'"  type="button">'+btn_text+'</button>';
        }

                  
        let html = '<div class="col-sm-12 col-md-6">';
        var verified_text = "Verified";
            if (id_type == "Senior Citizen ID/Booklet") {
                verified_text = "Verified Senior Citizen";
            }
            else if (id_type == "PWD ID") {
                verified_text = "Verified PWD";
            }
            html += '<div><span class="badge badge-success">'+verified_text+'</span></div>';
            html += '<div>Customer name: '+customer_name+'</div>';
            html += '<div>Contact #: '+phone+'</div>';
            html += '<div>Email: '+email+'</div>';
            html += '</div>';
            html += '<div class="col-sm-12 col-md-6">';
            html += '<div class="float-right">Order #: <b>'+order_no+'</b><div>Payment method: '+payment_method+'</div></div>';
            if (active_pill == 'pending') {
                html += '<div class="float-right" style="margin-right:55px;"><b>Estimated Pickup Date:</b> <input id="delivery_date" type="date" class="form-control"></div>';
            }
            else if(active_pill == 'reservation'){
                
            }
            else {
                html += '<div class="float-right" style="margin-right:65px;"><b>Estimated Pickup Date:</b><br> '+delivery_date+'</div>';
            }
            html += '</div>';
        $('#show-reservation-modal').modal('show');
        $('#show-reservation-modal').find('#user-info').html(html);
        $('#show-reservation-modal').find('.modal-footer').html(btn);

        console.log(order_no);

        await readOneOrder(order_no, id_type);
        await readShippingAddress(user_id);

        
        $('#btn-change-status').attr('data-order-no', order_no);

        if (latlong.length > 0) {
            await initMap(latlong);
        }
        
    });

    $(document).on('click','#btn-change-status', function(){
        let order_no = $(this).attr('data-order-no');
        let data_status = $(this).attr('data-status');
        let supplier_id = $('#supplier_id').val();
        let active_pill = $(this).attr('data-active-pill');
        let delivery_date = "";
        if($('#delivery_date').length > 0) {
            if ($('#delivery_date').val().length > 0) {
                delivery_date  = $('#delivery_date').val();
            }
            else {
                alert('Please input the estimated pickup date.');
                return;
            }
        }
        let btn = $(this);
        $.ajax({
            url: '/order-change-status/'+order_no,
            type: 'POST',
            data: {
                status : data_status,
                supplier_id : supplier_id,
                delivery_date : delivery_date
            },
            beforeSend:function(){
               
            },
            success:function(data){
                // console.log(data);
                if(data.status == 'error_qty'){
                    console.log(data);
                    setTimeout(async function(){
                        swal.fire({
                            title: "Error",
                            icon: 'error',
                            text: data.message,
                            timer: 4000,
                          });
                    },300);
                }
                else{
                    console.log(data);
                    if(active_pill == 'pre-order'){
                        active_pill = active_pill.substring(0, 3);
                    }
                    $('#tbl-'+active_pill+'-order').DataTable().ajax.reload();
                    console.log(active_pill);
                    //$('#tbl-pre-order').DataTable().ajax.reload();
                    $('#show-orders-modal').modal('hide');
                    $('#show-reservation-modal').modal('hide');
                    $.toast({
                        text: 'Order was successfully changed status.',
                        position: 'bottom-right',
                        showHideTransition: 'plain',
                        hideAfter: 4500, 
                    })
                }
            }
        });
      });

      $(document).on('click','#btn-print', function(){
        printElement(document.getElementById("printable-order-info"));
      });

      $(document).on('click','#reservation-tab', function(){
        $('#tbl-reservation-order').DataTable().destroy();
        fetchOrder('reservation');  
      });

      $(document).on('click','#pre-order-tab', function(){
        $('#tbl-pre-order').DataTable().destroy();
        fetchOrder('pre');  
      });

      $(document).on('click','#pending-tab', function(){
        $('#tbl-pending-order').DataTable().destroy();
        fetchOrder('pending');  
      });

      $(document).on('click','#prepared-tab', function(){
        $('#tbl-prepared-order').DataTable().destroy();
        fetchOrder('prepared');  
      });

      $(document).on('click','#shipped-tab', function(){
        $('#tbl-shipped-order').DataTable().destroy();
        fetchOrder('shipped');  
      });

      $(document).on('click','#completed-tab', function(){
        $('#tbl-completed-order').DataTable().destroy();
        fetchOrder('completed');  
      });

      $(document).on('click','#cancelled-tab', function(){
        $('#tbl-cancelled-order').DataTable().destroy();
        fetchOrder('cancelled');  
      });
  
  
}


    
function printElement(elem) {
    var domClone = elem.cloneNode(true);
    
    var $printSection = document.getElementById("printSection");
    
    if (!$printSection) {
        var $printSection = document.createElement("div");
        $printSection.id = "printSection";
        document.body.appendChild($printSection);
    }
    
    $printSection.innerHTML = "";
    $printSection.appendChild(domClone);
    window.print();
}

async function initMap(latlong) { 

    $('#map').height(400);
    //let latlong = document.getElementsByName('map')[0].value;

    let myLatlng =  { lat: 13.9376, lng: 120.7005 };

    latlong = latlong.replace(/[()\ \s-]+/g, '');
    let d = latlong.split(",");

    myLatlng =  { lat: parseFloat(d[0]), lng: parseFloat(d[1]) };

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 16,
        center: myLatlng,
    });


    const image = "https://img.icons8.com/color/48/000000/place-marker--v2.png";
    const beachMarker = new google.maps.Marker({
        position: myLatlng,
        map,
        icon: image,
    });

    infoWindow.open(map);
}

  async function render() {
    await fetchOrder();  
    await on_Click();
  }

  render();