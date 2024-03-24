$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 

async function fetchData(){
    $('.tbl-product').DataTable({
    
       processing: true,
       serverSide: true,
       ajax: '/path/to/script',
       scrollY: 470,
       scroller: {
           loadingIndicator: true
       },
      
       ajax:"/product",
 
       columnDefs: [{
         targets: 0,
         searchable: false,
         orderable: false,
         changeLength: false,
       //  render: function (data, type, full, meta){
       //      return '<input type="checkbox" name="checkbox[]" value="' + $('<div/>').text(data).html() + '">';
       //  }
      }],
      order: [[0, 'desc']],
           
       columns:[       
            {data: 'product_code', name: 'product_code',orderable: true},
            {data: 'description', name: 'description'},
            // {data: 'qty', name: 'qty'},
            // {data: 'reorder', name: 'reorder'},
            {data: 'unit', name: 'unit'},
            {data: 'category', name: 'category'},
            {data: 'supplier', name: 'supplier'},
            // {data: 'orig_price',name: 'orig_price'},
            // {data: 'selling_price',name: 'selling_price'},    
            {data: 'action', name: 'action',orderable: false},
       ]
      });
 
     
}

async function fetchDataManagement(){
  $('.tbl-product-management').DataTable({
  
     processing: true,
     serverSide: true,
     ajax: '/path/to/script',
     scrollY: 470,
     scroller: {
         loadingIndicator: true
     },
    
     ajax:"/product-management",

     columnDefs: [{
       targets: 0,
       searchable: false,
       orderable: false,
       changeLength: false,
     //  render: function (data, type, full, meta){
     //      return '<input type="checkbox" name="checkbox[]" value="' + $('<div/>').text(data).html() + '">';
     //  }
    },{
      targets: 2,
      searchable: false,
      orderable: true,
      changeLength: true,
      className: 'dt-body-center',
      render: function (data, type, full, meta){
        if(full.qty === 0){
          return 'No Qty';
        }
        else{
          return data;
        }
      }
   },{
    targets: 3,
    searchable: false,
    orderable: true,
    changeLength: true,
    className: 'dt-body-center',
    render: function (data, type, full, meta){
      if(full.reorder === 0){
        return 'No Reorder';
      }
      else{
        return data;
      }
    }
 },{
  targets: 7,
  searchable: false,
  orderable: true,
  changeLength: true,
  className: 'dt-body-center',
  render: function (data, type, full, meta){
    if(full.orig_price === ' <div class="text-right"></div>'){
      return 'No Original Price';
    }
    else{
      return data;
      
    }
  }
},{
  targets: 8,
  searchable: false,
  orderable: true,
  changeLength: true,
  className: 'dt-body-center',
  render: function (data, type, full, meta){
    if(full.selling_price === ' <div class="text-right"></div>'){
      return 'No Selling Price';
    }
    else{
      return data;
    }
  }
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
          {data: 'orig_price',name: 'orig_price'},
          {data: 'selling_price',name: 'selling_price'},    
          {data: 'action', name: 'action',orderable: false},
     ]
    });

   
}

async function fetchProductSearch(){
  $('#product-search-table').DataTable({
  
     processing: true,
     serverSide: true,
     ajax: '/path/to/script',
     scrollY: 470,
     scroller: {
         loadingIndicator: true
     },
    
     ajax:"/product",

     columnDefs: [{
       targets: 0,
       searchable: false,
       orderable: false,
       changeLength: false,
     //  render: function (data, type, full, meta){
     //      return '<input type="checkbox" name="checkbox[]" value="' + $('<div/>').text(data).html() + '">';
     //  }
    }],
    order: [[0, 'desc']],
         
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
     ]
    });

   
}

$(document).on('keyup', '#markup', async function() {
  var markup = $(this).val();
  await computeSellingPrice(markup);
});

$(document).on('keyup', '#orig_price', async function() {
  var orig_price = $(this).val();
  await computeSellingPriceOrig(orig_price);
});

$(document).on('keyup', '#avg', async function() {
  var avg = $(this).val();
  await computeReorderPoint(avg);
});

$(document).on('keyup', '#lead_days', async function() {
  var lead_days = $(this).val();
  await computeReorderPointLead(lead_days);
});

$(document).on('keyup', '#safety_stocks', async function() {
  var safety_stocks = $(this).val();
  await computeReorderPointSafe(saafety_stocks);
});


$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
}); 

var product_id;
$(document).on('click', '.btn-archive-product', function(){
  product_id = $(this).attr('data-id');
  var row = $(this).closest("tr");
  var name = row.find("td:eq(1)").text();
  $('#confirmModal').modal('show');
  $('.delete-success').hide();
  $('.delete-message').html('Are you sure do you want to archive <b>'+ name +'</b>?');
}); 

$(document).on('click', '.btn-confirm-archive', function(){
  $.ajax({
      url: '/product/archive/'+ product_id,
      type: 'POST',
    
      beforeSend:function(){
          $('.btn-confirm-archive').text('Please wait...');
      },
      
      success:function(){
          setTimeout(function(){

              $('.btn-confirm-archive').text('Yes');
              $('.tbl-product').DataTable().ajax.reload();
              $('#confirmModal').modal('hide');
              $.toast({
                  text: 'Product was successfully archived.',
                  position: 'bottom-right',
                  showHideTransition: 'plain'
              })
          }, 1000);
      }
  });

});

async function computeSellingPrice(markup){

  var orig_price = $('#orig_price').val();
  var markup = parseFloat(orig_price)*parseFloat(markup);
  var selling_price = parseFloat(markup) + parseFloat(orig_price);

  return $('#selling_price').val(selling_price);
}

async function computeSellingPriceOrig(orig_price){

  var markup = $('#markup').val();
  // var orig_price = orig_price * markup;
  var selling_price = (parseFloat(orig_price) * parseFloat(markup)) + parseFloat(orig_price);

  return $('#selling_price').val(selling_price);
}

//Reorder Point Formula
async function computeReorderPoint(avg){

  var lead_days = $('#lead_days').val();
  var safety_stocks = $('#safety_stocks').val();
  var reorder_point = (avg * lead_days) + safety_stocks;

  return $('#reorder').val(reorder_point);
}

async function computeReorderPointLead(lead_days){

  var avg = $('#avg').val();
  var safety_stocks = $('#safety_stocks').val();
  var reorder_point = (avg * lead_days) + safety_stocks;

  return $('#reorder').val(reorder_point);
}

async function computeReorderPointSafe(safety_stocks){

  var avg = $('#avg').val();
  var lead_days = $('#lead_days').val();
  var reorder_point = (avg * lead_days) + safety_stocks;

  return $('#reorder').val(reorder_point);
}

  async function renderProducts() {
    if ($('#product-search-table').length > 0) {
      await fetchProductSearch();
    }
    else {
      await fetchData();
      await fetchDataManagement();    
    }
  }

  renderProducts();