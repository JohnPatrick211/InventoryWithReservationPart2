$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 

async function fetchSales(){
    $('#sales-archive-table').DataTable({
       processing: true,
       serverSide: true,
       scrollY: true,
       scrollCollapse: true,
       stateSave: false,
       ajax:{
        url: "/archive/sales",
        type:"GET",
        },
       columns:[
        {data: 'id', name: 'id'},       
        {data: 'invoice_no', name: 'invoice_no'},
        {data: 'product_code', name: 'product_code'},
        {data: 'description', name: 'description'},  
        {data: 'unit', name: 'unit'}, 
        {data: 'selling_price', name: 'selling_price'},
        {data: 'qty', name: 'qty'},
        {data: 'amount', name: 'amount'},
        {data: 'payment_method', name: 'payment_method'},
        {data: 'order_from', name: 'order_from'},
        {data: 'updated_at', name: 'updated_at'},
        {data: 'action', name: 'action'},
       ]
      });
}

async function fetchReplacement(){
    $('#tbl-replacement-archive').DataTable({
       processing: true,
       serverSide: true,
       scrollY: true,
       scrollCollapse: true,
       stateSave: false,
       ajax:{
        url: "/archive/replacement",
        type:"GET",
        },
        columnDefs: [{
            targets: 0,
            searchable: true,
            orderable: true,
            changeLength: false
         }],
         order: [[0, 'desc']],
          columns:[       
              {data: 'id', name: 'id'},       
              {data: 'studentName', name: 'studentName'},
              {data: 'productName', name: 'productName'},
              {data: 'replacement_qty', name: 'replacement_qty'},   
              {data: 'reason', name: 'reason'},       
              {data: 'status', name: 'status'},
              {data: 'action', name: 'action', orderable:false},
          ]
      });
}

async function fetchStockAdjustment(){
    $('#tbl-stock-report').DataTable({
    
        processing: true,
        serverSide: true,
        scrollY: true,
        scrollCollapse: true,
        stateSave: false,
        ajax: '/path/to/script',
        scrollY: 470,
        scroller: {
            loadingIndicator: true
        },

        ajax:{
            url: "/archive/stock-adjustment",
            type:"GET",
        },
   
        columnDefs: [{
          targets: 0,
          searchable: true,
          orderable: false,
          changeLength: false
       }],
       order: [[8, 'desc']],
        columns:[       
            {data: 'product_code', name: 'product_code'},
            {data: 'description', name: 'description'}, 
            {data: 'unit', name: 'unit'},      
            {data: 'category', name: 'category'},  
            {data: 'supplier', name: 'supplier'},  
            {data: 'qty_adjusted', name: 'qty_adjusted'},
            {data: 'remarks', name: 'remarks',orderable: false},
            {data: 'date_adjusted', name: 'date_adjusted'},
            {data: 'action', name: 'action'},
        ]
       });
}

async function fetchSupplierDelivery(){
    $('#tbl-supplier-archive').DataTable({
    
        processing: true,
       serverSide: true,
       scrollY: true,
       scrollCollapse: true,
       stateSave: false,
        ajax: '/path/to/script',
        scrollY: 470,
        scroller: {
            loadingIndicator: true
        },

        ajax:{
            url: "/archive/supplier-delivery",
            type:"GET",
        },
   
        columnDefs: [{
          targets: 0,
          searchable: true,
          orderable: false,
          changeLength: false
       }],
       order: [[0, 'desc']],
            
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
            {data: 'remarks', name: 'remarks',orderable: false},
            {data: 'action', name: 'action'},
        ]
       });
}


async function fetchProduct(date_from, date_to){
    $('#product-archive-table').DataTable({
       processing: true,
       serverSide: true,
       ajax:{
        url: "/archive/products",
        type:"GET",
        data:{
            date_from   :date_from,
            date_to     :date_to
            }
        },
       columns:[       
        {data: 'product_code', name: 'product_code',orderable: true},
        {data: 'description', name: 'description'},
        {data: 'qty', name: 'qty'},
        {data: 'reorder', name: 'reorder'},
        {data: 'unit', name: 'unit'},
        {data: 'category', name: 'category'},
        {data: 'supplier', name: 'supplier'},
        {data: 'orig_price',name: 'orig_price'},
        {data: 'selling_price',name: 'selling_price'}, 
        {data: 'updated_at',name: 'updated_at'},    
        {data: 'action', name: 'action',orderable: false},
       ]
      });
}

async function fetchUser(date_from, date_to){
    $('#user-archive-table').DataTable({
       processing: true,
       serverSide: true,
       ajax:{
        url: "/archive/users",
        type:"GET",
        data:{
            date_from   :date_from,
            date_to     :date_to
            }
        },
       columns:[       
        {data: 'name', name: 'name',orderable: true},
        {data: 'email', name: 'email'},
        {data: 'access_level', name: 'access_level'},
        {data: 'updated_at',name: 'updated_at'},   
        {data: 'action', name: 'action',orderable: false},
       ]
      });
}

var replacement_id;
var stock_id;
var supplier_id;
var sales_id;

$(document).on('click', '.btn-restore-sales', function(){
    sales_id = $(this).attr('data-id');
    $('#restoreModal-sales').modal('show');
    $('.delete-success').hide();
    $('.delete-message').html('Are you sure do you want to restore this Sales Report with Invoice ID# <b>'+ sales_id +'</b>?');
  });

$(document).on('click', '.btn-restore-replacement', function(){
  replacement_id = $(this).attr('data-id');
  $('#restoreModal-replacement').modal('show');
  $('.delete-success').hide();
  $('.delete-message').html('Are you sure do you want to restore this Replacement Request with ID# <b>'+ replacement_id +'</b>?');
});

$(document).on('click', '.btn-restore-stockadjustment', function(){
    stock_id = $(this).attr('data-id');
    $('#restoreModal-stockadjustment').modal('show');
    $('.delete-success').hide();
    $('.delete-message').html('Are you sure do you want to restore this Stock Adjustment with ID# <b>'+ stock_id +'</b>?');
  });
  
  $(document).on('click', '.btn-restore-supplierdelivery', function(){
    supplier_id = $(this).attr('data-id');
    $('#restoreModal-supplierdelivery').modal('show');
    $('.delete-success').hide();
    $('.delete-message').html('Are you sure do you want to restore this Supplier Delivery with ID# D-000<b>'+ supplier_id +'</b>?');
  });   
  

  $(document).on('click', '.btn-confirm-restore-sales', function(){
    $.ajax({
        url: '/archive/sales-restore/'+ sales_id,
        type: 'POST',  
        beforeSend:function(){
            $('.btn-confirm-restore-sales').text('Please wait...');
        },
        
        success:async function(){
  
                $('.btn-confirm-restore-sales').text('Yes');
                $('#sales-archive-table').DataTable().ajax.reload();
                $('#restoreModal-sales').modal('hide');
                $.toast({
                    text: 'Sales Report was successfully restored.',
                    position: 'bottom-right',
                    showHideTransition: 'plain'
                })
        }
    });

});

$(document).on('click', '.btn-confirm-restore-replacement', function(){
    $.ajax({
        url: '/archive/replacement-restore/'+ replacement_id,
        type: 'POST',  
        beforeSend:function(){
            $('.btn-confirm-restore-replacement').text('Please wait...');
        },
        
        success:async function(){
  
                $('.btn-confirm-restore-replacement').text('Yes');
                $('#tbl-replacement-archive').DataTable().ajax.reload();
                $('#restoreModal-replacement').modal('hide');
                $.toast({
                    text: 'Replacement Request was successfully restored.',
                    position: 'bottom-right',
                    showHideTransition: 'plain'
                })
        }
    });

});

$(document).on('click', '.btn-confirm-restore-stockadjustment', function(){
    $.ajax({
        url: '/archive/stockadjustment-restore/'+ stock_id,
        type: 'POST',  
        beforeSend:function(){
            $('.btn-confirm-restore-stockadjustment').text('Please wait...');
        },
        
        success:async function(){
  
                $('.btn-confirm-restore-stockadjustment').text('Yes');
                $('#tbl-stock-report').DataTable().ajax.reload();
                $('#restoreModal-stockadjustment').modal('hide');
                $.toast({
                    text: 'Stock Adjustment was successfully restored.',
                    position: 'bottom-right',
                    showHideTransition: 'plain'
                })
        }
    });

});

$(document).on('click', '.btn-confirm-restore-supplierdelivery', function(){
    $.ajax({
        url: '/archive/supplierdelivery-restore/'+ supplier_id,
        type: 'POST',  
        beforeSend:function(){
            $('.btn-confirm-restore-supplierdelivery').text('Please wait...');
        },
        
        success:async function(){
  
                $('.btn-confirm-restore-supplierdelivery').text('Yes');
                $('#tbl-supplier-archive').DataTable().ajax.reload();
                $('#restoreModal-supplierdelivery').modal('hide');
                $.toast({
                    text: 'Supplier Delivery was successfully restored.',
                    position: 'bottom-right',
                    showHideTransition: 'plain'
                })
        }
    });

});
  
$(document).on('change','#date_from', async function(){
 
    var date_from = $(this).val();
    var date_to = $('#date_to').val();

    $('#product-archive-table').DataTable().destroy();
    if ($('.nav-item').find('.active').attr('aria-controls') == 'pending') {
        await fetchProduct(date_from, date_to);
    }
    else {
        await fetchUser(date_from, date_to);
    }
});

$(document).on('change','#date_to', async function(){

    var date_to = $(this).val();
    var date_from = $('#date_from').val();

    $('#product-archive-table').DataTable().destroy();
    if ($('.nav-item').find('.active').attr('aria-controls') == 'pending') {
        await fetchProduct(date_from, date_to);
    }
    else {
        await fetchUser(date_from, date_to);
    }
});

$(document).on('click','.nav-item', async function(){

    var date_to = $('#date_to').val();
    var date_from = $('#date_from').val();

    if ($('.nav-item').find('.active').attr('aria-controls') == 'pending') {
        $('#product-archive-table').DataTable().destroy();
        await fetchProduct(date_from, date_to);
    }
    else {
        $('#user-archive-table').DataTable().destroy();
        await fetchUser(date_from, date_to);
    }
    $('#sales-archive-table').DataTable().destroy();
        await fetchSales();
});

  async function render() {
    await fetchSales();
    await fetchReplacement();
    await fetchStockAdjustment();
    await fetchSupplierDelivery();
  }

  render();