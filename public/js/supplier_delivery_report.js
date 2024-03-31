
async function fetchSupplierDelivery(supplier_id, date_from, date_to){
    $('.tbl-supplier-delivery-report').DataTable({
    
        processing: true,
        serverSide: true,
        ajax: '/path/to/script',
        scrollY: 470,
        scroller: {
            loadingIndicator: true
        },

        ajax:{
            url: "/reports/supplier-delivery",
            type:"GET",
            data:{
                supplier_id :supplier_id,
                date_from   :date_from,
                date_to     :date_to
            }
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

$(document).on('change','#supplier', async function(){

    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val();
    var supplier_id = $('#supplier').val();
    $('.tbl-supplier-delivery-report').DataTable().destroy();
    await fetchSupplierDelivery(supplier_id, date_from, date_to);

  });

  $(document).on('change','#date_from', async function(){

    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val();
    var supplier_id = $('#supplier').val();
    $('.tbl-supplier-delivery-report').DataTable().destroy();
    await fetchSupplierDelivery(supplier_id, date_from, date_to);
  });

  $(document).on('change','#date_to', async function(){

    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val();
    var supplier_id = $('#supplier').val();
    $('.tbl-supplier-delivery-report').DataTable().destroy();
    await fetchSupplierDelivery(supplier_id, date_from, date_to);
  });
  
  $(document).on('click','.btn-preview-supplier-delivery-report', async function(){
    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val();
    var supplier_id = $('#supplier').val();
    window.open("/supplier-delivery/preview/"+supplier_id+"/"+date_from+"/"+date_to);
  });

  $(document).on('click','.btn-download-supplier-delivery-report', async function(){
    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val();
    var supplier_id = $('#supplier').val();
    window.open("/supplier-delivery/download/"+supplier_id+"/"+date_from+"/"+date_to);
  });


  var supplier_id;
$(document).on('click', '.btn-archive-supplier-delivery', function(){
  supplier_id = $(this).attr('data-id');
  $('#confirmModal').modal('show');
  $('.delete-success').hide();
  $('.delete-message').html('Are you sure do you want to archive this supplier delivery?');
}); 

$(document).on('click', '.btn-confirm-supplier-delivery', function(){
  $.ajax({
      url: '/reports/supplierdelivery/archive/'+ supplier_id,
      type: 'POST',
    
      beforeSend:function(){
          $('.btn-confirm-supplier-delivery').text('Please wait...');
      },
      
      success:function(){
          setTimeout(function(){

              $('.btn-confirm-supplier-delivery').text('Yes');
              $('.tbl-supplier-delivery-report').DataTable().ajax.reload();
              $('#confirmModal').modal('hide');
              $.toast({
                  text: 'Supplier Delivery was successfully deleted.',
                  position: 'bottom-right',
                  showHideTransition: 'plain'
              })
          }, 1000);
      }
  });

});

async function render() 
{

    var supplier_id = $('#supplier').val();
    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val();

    await fetchSupplierDelivery(supplier_id, date_from, date_to);
}

render();