async function fetchApprovedReplacement(supplier_id){
    $('.tbl-replacement-report').DataTable({
    
        processing: true,
        serverSide: true,

        ajax:{
            url: "/reports/replacement-report",
            type:"GET",
            data:{
                supplier_id :supplier_id
            }
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

$(document).on('change','#status', async function(){

    var supplier_id = $('#status').val();
    $('.tbl-replacement-report').DataTable().destroy();
    await fetchApprovedReplacement(supplier_id);
  
  });

var replacement_id;
async function onClick() {
    $(document).on('click','.btn-replacement-archive', async function(){
        replacement_id         = $(this).attr('data-id');
        // var image_receipt = $(this).attr('data-image');
        // var row         = $(this).closest("tr");
        // var studentname        = row.find("td:eq(1)").text();
        // var productname    = row.find("td:eq(2)").text();
        // var qty    = row.find("td:eq(3)").text();
        // var reason       = row.find("td:eq(4)").text();
        // var status       = row.find("td:eq(5)").text();

        $('#confirmModal').modal('show');
        $('.delete-success').hide();
        $('.delete-message').html('Are you sure do you want to archive this Replacement Request with ID# <b>'+ replacement_id +'?');


      }); 
}

$(document).on('click', '.btn-confirm-archive-replacement', function(){
    $.ajax({
        url: '/reports/replacement/archive/'+ replacement_id,
        type: 'POST',
      
        beforeSend:function(){
            $('.btn-confirm-archive-replacement').text('Please wait...');
        },
        
        success:function(){
            setTimeout(function(){
  
                $('.btn-confirm-archive-replacement').text('Yes');
                $('.tbl-replacement-report').DataTable().ajax.reload();
                $('#confirmModal').modal('hide');
                $.toast({
                    text: 'Replacement Request was successfully archived.',
                    position: 'bottom-right',
                    showHideTransition: 'plain'
                })
            }, 1000);
        }
    });
  
  });

$(document).on('click','.btn-preview-replacement-report', async function(){
    var supplier_id = $('#status').val();
    window.open("/reports/replacement/preview/" + supplier_id);
});

$(document).on('click','.btn-download-replacement-report', async function(){
    var supplier_id = $('#status').val();
    window.open("/reports/replacement/download/" + supplier_id);
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 
  
async function render() {
    var supplier_id = $('#status').val();

    await onClick();
    
    await fetchApprovedReplacement(supplier_id);
}

render();