
async function fetchStockAdjustment(date_from, date_to){
    $('.tbl-stock-adjustment').DataTable({
    
        processing: true,
        serverSide: true,
        ajax: '/path/to/script',
        scrollY: 470,
        scroller: {
            loadingIndicator: true
        },

        ajax:{
            url: "/reports/stock-adjustment",
            type:"GET",
            data:{
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

  $(document).on('change','#date_from', async function(){

    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val();
    $('.tbl-stock-adjustment').DataTable().destroy();
    await fetchStockAdjustment(date_from, date_to);
  });

  $(document).on('change','#date_to', async function(){

    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val();
    $('.tbl-stock-adjustment').DataTable().destroy();
    await fetchStockAdjustment(date_from, date_to);
  });

  


async function onClick() {

    $(document).on('click','.btn-preview-pdf', async function(){
      var date_from = $('#date_from').val()
      var date_to = $('#date_to').val();
      window.open("/reports/stock-adjustment/pdf/"+date_from+"/"+date_to);
    });

    $(document).on('click','.btn-download-pdf', async function(){
      var date_from = $('#date_from').val()
      var date_to = $('#date_to').val();
      window.open("/reports/stock-adjustment/download/"+date_from+"/"+date_to);
    });

}

var product_id;
$(document).on('click', '.btn-archive-stock-adjustment', function(){
  product_id = $(this).attr('data-id');
  $('#confirmModal').modal('show');
  $('.delete-success').hide();
  $('.delete-message').html('Are you sure do you want to archive this adjustment?');
}); 

$(document).on('click', '.btn-confirm-stock-adjustment', function(){
  $.ajax({
      url: '/reports/stockadjustment/archive/'+ product_id,
      type: 'POST',
    
      beforeSend:function(){
          $('.btn-confirm-stock-adjustment').text('Please wait...');
      },
      
      success:function(){
          setTimeout(function(){

              $('.btn-confirm-stock-adjustment').text('Yes');
              $('.tbl-stock-adjustment').DataTable().ajax.reload();
              $('.tbl-stock-adjustment').DataTable().destroy();
               await fetchStockAdjustment(date_from, date_to);
              $('#confirmModal').modal('hide');
              $.toast({
                  text: 'Stock Adjustment was successfully deleted.',
                  position: 'bottom-right',
                  showHideTransition: 'plain'
              })
          }, 1000);
      }
  });

});
  
async function render() {
    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val();
    await onClick();
    await fetchStockAdjustment(date_from, date_to);
}

render();