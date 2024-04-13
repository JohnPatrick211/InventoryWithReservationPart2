async function fetchPurchasedOrders(supplier_id, date_from, date_to){
    $('#po-table').DataTable({
    
        processing: true,
        serverSide: true,
        ajax: '/path/to/script',
        scrollY: 470,
        scroller: {
            loadingIndicator: true
        },

        ajax:{
            url: "/purchased-order",
            type:"GET",
            data:{
                supplier_id :supplier_id,
                date_from   :date_from,
                date_to     :date_to,
            }
        },
   
        columnDefs: [{
          targets: 0,
          searchable: true,
          orderable: false,
          changeLength: false
       }],
       order: [[7, 'desc']],
            
        columns:[       
            {data: 'po_num', name: 'po_num'},
            {data: 'product_code', name: 'product_code'},
            {data: 'description', name: 'description'},   
            {data: 'supplier', name: 'supplier'},   
            {data: 'unit', name: 'unit'},  
            {data: 'qty_order', name: 'qty_order'},
            {data: 'amount', name: 'amount'},
            {data: 'date_order', name: 'date_order'},
            {data: 'remarks', name: 'remarks',orderable: false},
            {data: 'action', name: 'action',orderable: false},
        ]
       });
}

async function fetchPendingOrders(supplier_id, date_from, date_to){
    $('#pen-table').DataTable({
    
        processing: true,
        serverSide: true,
        ajax: '/path/to/script',
        scrollY: 470,
        scroller: {
            loadingIndicator: true
        },

        ajax:{
            url: "/pending-order",
            type:"GET",
            data:{
                supplier_id :supplier_id,
                date_from   :date_from,
                date_to     :date_to,
            }
        },
   
        columnDefs: [{
          targets: 0,
          searchable: true,
          orderable: false,
          changeLength: false
       }],
       order: [[7, 'desc']],
            
        columns:[       
            {data: 'po_num', name: 'po_num'},
            {data: 'product_code', name: 'product_code'},
            {data: 'description', name: 'description'},   
            {data: 'supplier', name: 'supplier'},   
            {data: 'unit', name: 'unit'},  
            {data: 'qty_order', name: 'qty_order'},
            {data: 'amount', name: 'amount'},
            {data: 'date_order', name: 'date_order'},
            {data: 'remarks', name: 'remarks',orderable: false},
            {data: 'action', name: 'action',orderable: false},
        ]
       });
}

async function fetchTotalReservationQty(product_id) {
    console.log(product_id);
    $('#txt-total-sales').html('<i class="fas fa-spinner fa-spin"></i>');
    $.ajax({
        url: '/compute-reservation-qty',
        type: 'GET',
        data: {
            product_id        :product_id
        },
        success:async function(total_sales){

            console.log(total_sales);
            
            $('#txt-total-sales').html(total_sales);
        }
    });
}

$(document).on('change','#inv_product', async function(){

    var product_id = $('#inv_product').val();

    await fetchTotalReservationQty(product_id);
});

async function readSupplierDeliveryPartial(supplier_id, date_from, date_to){
    $('#pa-table').DataTable({
    
        processing: true,
        serverSide: true,
        ajax: '/path/to/script',
        scrollY: 470,
        scroller: {
            loadingIndicator: true
        },

        ajax:{
            url: "/read-supplier-delivery-partial",
            type:"GET",
            data:{
                supplier_id :supplier_id,
                date_from   :date_from,
                date_to     :date_to,
            }
        },
   
        columnDefs: [{
          targets: 0,
          searchable: true,
          orderable: false,
          changeLength: false
       },
       {
        targets: 9,
          visible: false,
          searchable: false
    }
],
       order: [[9, 'desc']],
            
        columns:[  
            {data: 'del_no', name: 'del_no'},     
            {data: 'po_no', name: 'po_no'},
            {data: 'product_code', name: 'product_code'},
            {data: 'description', name: 'description'},   
            {data: 'supplier', name: 'supplier'},   
            {data: 'unit', name: 'unit'},  
            {data: 'qty_order', name: 'qty_order'},
            {data: 'qty_delivered', name: 'qty_delivered'},
            {data: 'date_delivered', name: 'date_delivered'},
            // {data: 'updated', name: 'updated'},
            {data: 'remarks', name: 'remarks',orderable: false},
            {data: 'action', name: 'action',orderable: false},
        ]
       });
}

async function readSupplierDelivery(supplier_id, date_from, date_to){
    $('#sd-table').DataTable({
    
        processing: true,
        serverSide: true,
        ajax: '/path/to/script',
        scrollY: 470,
        scroller: {
            loadingIndicator: true
        },

        ajax:{
            url: "/read-supplier-delivery",
            type:"GET",
            data:{
                supplier_id :supplier_id,
                date_from   :date_from,
                date_to     :date_to,
            }
        },
   
        columnDefs: [{
          targets: 0,
          searchable: true,
          orderable: false,
          changeLength: false
       },{
        targets: 9,
          visible: false,
          searchable: false
    }],
       order: [[9, 'desc']],
            
        columns:[  
            {data: 'del_no', name: 'del_no'},     
            {data: 'po_no', name: 'po_no'},
            {data: 'product_code', name: 'product_code'},
            {data: 'description', name: 'description'},   
            {data: 'supplier', name: 'supplier'},   
            {data: 'unit', name: 'unit'},  
            {data: 'qty_order', name: 'qty_order'},
            {data: 'qty_delivered', name: 'qty_delivered'},
            {data: 'date_delivered', name: 'date_delivered'},
            {data: 'updated', name: 'updated'},
            {data: 'remarks', name: 'remarks',orderable: false}
        ]
       });
}

 

async function on_Click() {

    $(document).on('click', '#po-tab', function(){
        console.log("1");
        $('#po-table').DataTable().ajax.reload();
        var tab_object = 'po';
        on_Change(tab_object);
    });
    $(document).on('click', '#pa-tab', function(){
        console.log("2");
        $('#pa-table').DataTable().ajax.reload();
        var tab_object = 'pa';
        on_Change(tab_object);
    });
    $(document).on('click', '#pen-tab', function(){
        console.log("3");
        $('#pen-table').DataTable().ajax.reload();
        var tab_object = 'pen';
        on_Change(tab_object);
    });
    $(document).on('click', '#delivered-tab', function(){
        console.log("4");
        $('#sd-table').DataTable().ajax.reload();
        var tab_object = 'sd';
        on_Change(tab_object);
    });

    $(document).on('click', '.btn-show-order', function(){
        var $this = $(this);
        var row             = $this.closest("tr");

        localStorage.setItem('data-id', $this.attr('data-id'));
        var active_tab = $('[class="nav-link active"]').attr('aria-controls');
        var po_eq = 0;
        var pcode_eq = 1;
        var desc_eq = 2;
        var supp_eq = 3;
        var unit_eq = 4;
        var qty_eq = 5;
        var delivered_eq = 7;
        if (active_tab == 'delivered' || active_tab == 'partial') {
            po_eq = 1;
            pcode_eq = 2;
            desc_eq = 3;
            supp_eq = 4;
            unit_eq = 5;
            qty_eq = 6;
            
        }
        var po_no           = row.find("td:eq("+po_eq+")").text();
        var product_code    = row.find("td:eq("+pcode_eq+")").text();
        var description     = row.find("td:eq("+desc_eq+")").text();
        var supplier        = row.find("td:eq("+supp_eq+")").text();
        var unit            = row.find("td:eq("+unit_eq+")").text();
        var qty_order       = row.find("td:eq("+qty_eq+")").text();
        var qty_delivered       = row.find("td:eq("+delivered_eq+")").text();

        $('#po_no').text(po_no);
        $('#po_product_code').text(product_code);
        $('#po_description').text(description);
        $('#supplier').text(supplier);
        $('#unit').text(unit);
        $('#qty_ordered').text(qty_order);
        $('#qty_partialdelivered').text(qty_delivered);

    });

    $(document).on('click', '#btn-add', function(){
        var btn = $(this);
        var data_id = localStorage.getItem('data-id');
        var po_no           = $('#po_no').text();
        var product_code    = $('#po_product_code').text();
        var qty_delivered   = $('#qty_delivered').val();
        var date_recieved   = $('#date_recieved').val();
        var qty_ordered     = $('#qty_partialdelivered').text();
        var qty_orderedreq     = $('#qty_ordered').text();
        var date_reservation   = $('#date_deliver_reservation').val();
        var total_delivered = qty_orderedreq - qty_ordered;
        console.log(qty_ordered);
        console.log(qty_delivered);
        console.log(total_delivered);
        var active_tab = $('[class="nav-link active"]').attr('aria-controls');
        console.log(product_code + " " + po_no)
        if(qty_delivered > total_delivered){
            alert('Error, Quantity Delivered is Greater Than Quantity Current Ordered');
        }
        else{
            $.ajax({
                url: '/create-delivery',
                type: 'POST',
                data:{
                    data_id         :data_id,
                    po_no           :po_no,
                    product_code    :product_code,
                    qty_delivered   :qty_delivered,
                    date_recieved   :date_recieved,
                    date_reservation   :date_reservation,
                    active_tab      : active_tab
                },
                beforeSend:function(){
                    btn.text('Please wait...');
                },
                
                success:function(data){
                    console.log(data)
                    setTimeout(function(){
                        btn.text('Add deliver');
                        $('#po-table').DataTable().ajax.reload();
                        $('#pa-table').DataTable().ajax.reload();
                        $('#sd-table').DataTable().ajax.reload();
                        $('#delivery-modal').modal('hide');
                        product_code = product_code.substr(7);
                        console.log(product_code);
                        fetchTotalReservationQty(product_code);
                        $.toast({
                            text:'Delivery was successfully added.',
                            position: 'bottom-right',
                            showHideTransition: 'plain',
                            hideAfter: 5000, 
                        })
                    }, 500);
                }
            });
        }
    });

}

async function renderDataTables(tab_object, supplier_id, date_from, date_to) {
    if (tab_object == 'po') {
        await fetchPurchasedOrders(supplier_id, date_from, date_to);
    }
    else if(tab_object == 'pa'){
        await readSupplierDeliveryPartial(supplier_id, date_from, date_to);
    }
    else if(tab_object == 'pen'){
        await fetchPendingOrders(supplier_id, date_from, date_to);
    }
    else {
        await readSupplierDelivery(supplier_id, date_from, date_to);
    }
}

async function on_Change(tab_object = 'po'){

    $(document).on('change','#'+ tab_object +'_supplier', async function(){

        var date_from   = $('#'+ tab_object +'_date_from').val()
        var date_to     = $('#'+ tab_object +'_date_to').val();
        var supplier_id = $('#'+ tab_object +'_supplier').val();

        $('#'+ tab_object +'-table').DataTable().destroy();
       
        renderDataTables(tab_object, supplier_id, date_from, date_to);
      });
    
      $(document).on('change','#'+ tab_object +'_date_from', async function(){
    
        var date_from   = $('#'+ tab_object +'_date_from').val()
        var date_to     = $('#'+ tab_object +'_date_to').val();
        var supplier_id = $('#'+ tab_object +'_supplier').val();

        $('#'+ tab_object +'-table').DataTable().destroy();

        renderDataTables(tab_object, supplier_id, date_from, date_to);
      });
    
      $(document).on('change','#'+ tab_object +'_date_to', async function(){
    
        var date_from   = $('#'+ tab_object +'_date_from').val()
        var date_to     = $('#'+ tab_object +'_date_to').val();
        var supplier_id = $('#'+ tab_object +'_supplier').val();

        $('#'+ tab_object +'-table').DataTable().destroy();

        renderDataTables(tab_object, supplier_id, date_from, date_to);
      });

}

async function renderComponents() {
    var po_supplier_id  = $('#po_supplier').val();
    var po_date_from    = $('#po_date_from').val()
    var po_date_to      = $('#po_date_to').val();
    var sd_supplier_id  = $('#sd_supplier').val();
    var sd_date_from    = $('#sd_date_from').val()
    var sd_date_to      = $('#sd_date_to').val();
    var pa_supplier_id  = $('#pa_supplier').val();
    var pa_date_from    = $('#pa_date_from').val()
    var pa_date_to      = $('#pa_date_to').val();
    var pen_supplier_id  = $('#pen_supplier').val();
    var pen_date_from    = $('#pen_date_from').val()
    var pen_date_to      = $('#pen_date_to').val();
    var product_id      = $('#inv_product').val();

    await CSRF_TOKEN();

    await on_Change();

    await on_Click();
    
    await fetchTotalReservationQty(product_id);

    await fetchPurchasedOrders(po_supplier_id, po_date_from, po_date_to);

    await readSupplierDeliveryPartial(pa_supplier_id, pa_date_from, pa_date_to);

    await readSupplierDelivery(sd_supplier_id, sd_date_from, sd_date_to);

    await fetchPendingOrders(pen_supplier_id, pen_date_from, pen_date_to);
}

async function CSRF_TOKEN() {
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
}

renderComponents();
