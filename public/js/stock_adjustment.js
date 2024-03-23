$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 


var product_id;

$('#remarks').change(function()
       {
         let maintenance = $('#remarks').val()
         if(maintenance == 'Others')
         {
          var test = $('#others').val('');
          $('.hide-others').css('display', 'inline');
         }
         else
         {
          var test = $('#others').val('none');
          $('.hide-others').css('display', 'none');
          console.log(test)
         }
       });

$(document).on('click', '.btn-adjust-qty', function(){

    $('#ajustQtyModal').modal('show');
    product_id          = $(this).attr('data-id');
    var row             = $(this).closest("tr");
    var product_code    = row.find("td:eq(0)").text();
    var description     = row.find("td:eq(1)").text();
    var qty             = row.find("td:eq(2)").text();

    $('#product_id').val(product_id);
    $('#product_code').val(product_code);
    $('#description').val(description);
    $('#qty').val(qty);
    $('#qty_to_adjust').val("");
  }); 
  
$(document).on('click', '.btn-confirm-adjust', function(){
    var product_id      = $('#product_id').val();
    var product_code    = $('#product_code').val();
    var qty_to_adjust   = $('#qty_to_adjust').val();
    var remarks         = $('#remarks').val();
    var others          =$('#others').val();
    var rdo_addless     = $("input[name='rdo-addless']:checked").val(); console.log(remarks)
    $.ajax({
        url: '/stock-adjustment/adjust/'+ product_id,
        type: 'POST',
        data:{
            product_id      :product_id,
            product_code    :product_code,
            qty_to_adjust   :qty_to_adjust,
            remarks         :remarks,
            others         :others,
            rdo_addless     :rdo_addless,
        },
        beforeSend:function(){
            $('.btn-confirm-adjust').text('Adjusting...');
        },
        
        success:function(){
            setTimeout(function(){

                $('.btn-confirm-adjust').text('Adjust');
                $('.tbl-stock-adjustment').DataTable().ajax.reload();
                $('#ajustQtyModal').modal('hide');
                $.toast({
                    text: $('#description').val()+' was successfully adjusted.',
                    position: 'bottom-right',
                    showHideTransition: 'plain',
                    hideAfter: 4500, 
                })
            }, 1000);
        }
    });
  
});

async function fetchData(){
    $('.tbl-stock-adjustment').DataTable({
    
       processing: true,
       serverSide: true,
       ajax: '/path/to/script',
       scrollY: 470,
       scroller: {
           loadingIndicator: true
       },
      
       ajax:"/stock-adjustment",
 
       columnDefs: [{
         targets: 0,
         searchable: false,
         orderable: false,
         changeLength: false,
       //  render: function (data, type, full, meta){
       //      return '<input type="checkbox" name="checkbox[]" value="' + $('<div/>').text(data).html() + '">';
       //  }
      }],
      order: [[1, 'asc']],
           
       columns:[       
            {data: 'product_code', name: 'product_code',orderable: true},
            {data: 'description', name: 'description'},
            {data: 'qty', name: 'qty'},
            {data: 'reorder', name: 'reorder'},
            {data: 'unit', name: 'unit'},
            {data: 'category', name: 'category'},
            {data: 'supplier', name: 'supplier'}, 
            {data: 'action', name: 'action',orderable: false},
       ]
      });
 
     
}
  
  
  async function renderProducts() {
      await fetchData();
  }

  renderProducts();