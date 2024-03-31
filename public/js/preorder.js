async function fetchPreOrder(){
    $('.tbl-preorder-report').DataTable({
    
        processing: true,
        serverSide: true,

        ajax:{
            url: "/reports/preorder-report",
            type:"GET"
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
            {data: 'preorder_qty', name: 'preorder_qty'},   
            {data: 'amount', name: 'amount'},       
            {data: 'preorder_date', name: 'preorder_date'},
        ]
       });
}

// var replacement_id;
// async function onClick() {
//     $(document).on('click','.btn-replacement-archive', async function(){
//         replacement_id         = $(this).attr('data-id');
//         // var image_receipt = $(this).attr('data-image');
//         // var row         = $(this).closest("tr");
//         // var studentname        = row.find("td:eq(1)").text();
//         // var productname    = row.find("td:eq(2)").text();
//         // var qty    = row.find("td:eq(3)").text();
//         // var reason       = row.find("td:eq(4)").text();
//         // var status       = row.find("td:eq(5)").text();

//         $('#confirmModal').modal('show');
//         $('.delete-success').hide();
//         $('.delete-message').html('Are you sure do you want to archive this Replacement Request with ID# <b>'+ replacement_id +'?');


//       }); 
// }

// $(document).on('click', '.btn-confirm-archive-replacement', function(){
//     $.ajax({
//         url: '/reports/replacement/archive/'+ replacement_id,
//         type: 'POST',
      
//         beforeSend:function(){
//             $('.btn-confirm-archive-replacement').text('Please wait...');
//         },
        
//         success:function(){
//             setTimeout(function(){
  
//                 $('.btn-confirm-archive-replacement').text('Yes');
//                 $('.tbl-replacement-report').DataTable().ajax.reload();
//                 $('#confirmModal').modal('hide');
//                 $.toast({
//                     text: 'Replacement Request was successfully archived.',
//                     position: 'bottom-right',
//                     showHideTransition: 'plain'
//                 })
//             }, 1000);
//         }
//     });
  
//   });

$(document).on('click','.btn-preview-preorder-report', async function(){
    window.open("/reports/preorder/preview");
});

$(document).on('click','.btn-download-preorder-report', async function(){
    window.open("/reports/preorder/download");
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 
  
async function render() {
    await onClick();
    await fetchPreOrder();
}

render();