<script>
    
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 

cartCount();
reservationCount()
function cartCount() {
    $.ajax({
        url: '/cart-count',
        type: 'GET',

        success:async function(data){ 
            $('.cart-count').text(data);
        }
    });
}

function reservationCount() {
    $.ajax({
        url: '/reservationbook-count',
        type: 'GET',

        success:async function(data){ 
            $('.reservation-count').text(data);
        }
    });
}

</script>