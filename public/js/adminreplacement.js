
async function fetchPendingReplacement(){
    $('.tbl-unverified-users').DataTable({
    
        processing: true,
        serverSide: true,

        ajax:{
            url: "/product-replacement",
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
            {data: 'replacement_qty', name: 'replacement_qty'},  
            {data: 'reason', name: 'reason'},       
            {data: 'status', name: 'status'},  
            {data: 'action', name: 'action', orderable:false},
        ]
       });
}

async function fetchApprovedReplacement(){
    $('.tbl-verified-users').DataTable({
    
        processing: true,
        serverSide: true,

        ajax:{
            url: "/approved-replacement",
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
            {data: 'replacement_qty', name: 'replacement_qty'},   
            {data: 'reason', name: 'reason'},       
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable:false},
        ]
       });
}


async function onClick() {
    var user_id;
    $(document).on('click','.btn-full-view', async function(){
        $('#userInfoModal').modal('show');
        user_id         = $(this).attr('data-id');
        var image_receipt = $(this).attr('data-image');
        var row         = $(this).closest("tr");
        var studentname        = row.find("td:eq(1)").text();
        var productname    = row.find("td:eq(2)").text();
        var qty    = row.find("td:eq(3)").text();
        var reason       = row.find("td:eq(4)").text();
        var status       = row.find("td:eq(5)").text();

       
        $('#studentname').val(studentname);
        $('#productname').val(productname);
        $('#qty').val(qty);
        $('#reason').val(reason);
        $('#status').val(status);
        $('#receipt').attr('src', image_receipt);

        if(status == 'Approved'){
            document.getElementById("btn-approve").disabled = true;
            document.getElementById("btn-reject").disabled = true;
        }
      }); 
      
    $(document).on('click', '#btn-approve', function(){
        var btn_verify = $('#btn-approve');
        $.ajax({
            url: '/do-approve-request/'+ user_id,
            type: 'POST',
            beforeSend:function(){
                btn_verify.text('Please wait...');
            },
            
            success:function(){
                setTimeout(function(){
    
                    btn_verify.text('Approve');
                    $('.tbl-unverified-users').DataTable().ajax.reload();
                    $('#userInfoModal').modal('hide');
                    $.toast({
                        text: 'Product Replacement was successfully Approved.',
                        showHideTransition: 'plain',
                        position: 'bottom-right',
                        hideAfter: 4500, 
                    })
                }, 1000);
            }
        });
      
    });

    $(document).on('click', '#btn-reject', function(){
        var btn_verify = $('#btn-reject');
        var remarks = $('#remarks').val();
        console.log(remarks);

        // $.ajax({
        //     url: '/do-reject-request/'+ user_id,
        //     type: 'POST',
        //     beforeSend:function(){
        //         btn_verify.text('Please wait...');
        //     },
            
        //     success:function(){
        //         setTimeout(function(){
    
        //             btn_verify.text('Reject');
        //             $('.tbl-unverified-users').DataTable().ajax.reload();
        //             $('#userInfoModal').modal('hide');
        //             $.toast({
        //                 text: 'Product Replacement was successfully Rejected.',
        //                 position: 'bottom-right',
        //                 showHideTransition: 'plain',
        //                 hideAfter: 4500, 
        //             })
        //         }, 1000);
        //     }
        // });
      
    });

    $(document).on('click', '#verified-tab', function(){
        $('.tbl-verified-users').DataTable().ajax.reload();
    });
    
    $(document).on('click', '#unverified-tab', function(){
        $('.tbl-unverified-users').DataTable().ajax.reload();
    });
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 
  
async function render() {
    await onClick();
    await fetchPendingReplacement();
    await fetchApprovedReplacement();
}

render();