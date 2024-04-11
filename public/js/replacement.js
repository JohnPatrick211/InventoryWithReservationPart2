$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 

// async function fetchData(){
//     $('.tbl-replacement').DataTable({
    
//        processing: true,
//        serverSide: true,
//        ajax: '/path/to/script',
//        scrollY: 470,
//        scroller: {
//            loadingIndicator: true
//        },
      
//        ajax:"/replacementData",
 
//        columnDefs: [{
//          targets: 0,
//          searchable: false,
//          orderable: false,
//          changeLength: false,
//        //  render: function (data, type, full, meta){
//        //      return '<input type="checkbox" name="checkbox[]" value="' + $('<div/>').text(data).html() + '">';
//        //  }
//       }],
//       order: [[0, 'desc']],
           
//        columns:[       
//             {data: 'id', name: 'id',orderable: true},
//             {data: 'product_name', name: 'product_name'},
//             {data: 'reason', name: 'reason'},
//             {data: 'status', name: 'status'},     
//             {data: 'action', name: 'action',orderable: false},
//        ]
//       });
 
     
// }

async function fetchReplacement(){
  $('#replacement-table').DataTable({
  
     processing: true,
     serverSide: true,
    
    
     ajax:"/replacementData",

     columnDefs: [{
       targets: 0,
       searchable: false,
       changeLength: false,
    },{
      targets: 4,
      orderable: true,
      changeLength: true,
      className: 'dt-body-center',
      render: function (data, type, full, meta){
        if(full.status === '0'){
          return 'Pending';
        }
        else if(full.status === '1'){
          return 'Approved';
        }
        else{
          return 'Rejected';
        }
          
      }
   },{
    targets: 5,
    orderable: true,
    changeLength: true,
    className: 'dt-body-center',
    render: function (data, type, full, meta){
      if(full.remarks === null){
        return 'No Remarks';
      }
      else{
        return full.remarks;
      }
        
    }
 }],
    order: [[0, 'desc']],
         
     columns:[       
          {data: 'id', name: 'id',orderable: true},
          {data: 'product_name', name: 'product_name'},
          {data: 'qty', name: 'qty'},
          {data: 'reason', name: 'reason'},
          {data: 'status', name: 'status'},
          {data: 'remarks', name: 'remarks'},
          {data: 'action', name: 'action',orderable: false},  
     ]
    });

   
}

// $(document).on('keyup', '#markup', async function() {
//   var markup = $(this).val();
//   await computeSellingPrice(markup);
// });


// $.ajaxSetup({
//   headers: {
//       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//   }
// }); 

var product_id;
$(document).on('click', '.btn-archive-product', function(){
  product_id = $(this).attr('data-id');
  var row = $(this).closest("tr");
  var name = row.find("td:eq(1)").text();
  $('#confirmModal').modal('show');
  $('.delete-success').hide();
  $('.delete-message').html('Are you sure do you want to delete this request?');
}); 

$(document).on('click', '.btn-confirm-archive', function(){
  $.ajax({
      url: '/replacement/delete/'+ product_id,
      type: 'POST',
    
      beforeSend:function(){
          $('.btn-confirm-archive').text('Please wait...');
      },
      
      success:function(){
          setTimeout(function(){

              $('.btn-confirm-archive').text('Yes');
              $('#replacement-table').DataTable().ajax.reload();
              $('#confirmModal').modal('hide');
              $.toast({
                  text: 'Product Request was successfully deleted.',
                  position: 'bottom-right',
                  showHideTransition: 'plain'
              })
          }, 1000);
      }
  });

});

// async function computeSellingPrice(markup){

//   var orig_price = $('#orig_price').val();
//   var markup = orig_price * markup;
//   var selling_price = parseFloat(markup) + parseFloat(orig_price);

//   return $('#selling_price').val(selling_price);
// }


  async function renderReplacement() {
    if ($('#replacement-table').length > 0) {
      await fetchReplacement();
    }
    else {
      //await fetchData();  
    }
  }

  renderReplacement();