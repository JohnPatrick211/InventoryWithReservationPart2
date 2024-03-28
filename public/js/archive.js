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
$(document).on('click', '.btn-restore-replacement', function(){
  replacement_id = $(this).attr('data-id');
  $('#restoreModal-replacement').modal('show');
  $('.delete-success').hide();
  $('.delete-message').html('Are you sure do you want to restore this Replacement Request with ID# <b>'+ replacement_id +'</b>?');
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
                $('#restoreModal-replacement').modal('hide');
                $.toast({
                    text: 'Replacement Request was successfully restored.',
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
  }

  render();