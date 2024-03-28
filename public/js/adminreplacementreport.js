async function fetchApprovedReplacement(){
    $('.tbl-replacement-report').DataTable({
    
        processing: true,
        serverSide: true,

        ajax:{
            url: "/reports/replacement-report",
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
}

$(document).on('click','.btn-preview-replacement-report', async function(){
    window.open("/reports/replacement/preview");
});

$(document).on('click','.btn-download-replacement-report', async function(){
    window.open("/reports/replacement/download");
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 
  
async function render() {
    await onClick();
    await fetchApprovedReplacement();
}

render();