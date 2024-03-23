async function fetchReservation(date_from, date_to, order_from, payment_method) {
    $('.tbl-reservation').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/path/to/script',
        scrollY: 470,
        scroller: {
            loadingIndicator: true
        },

        ajax: {
            url: "/read-reservations",
            type: "GET",
            data: {
                date_from: date_from,
                date_to: date_to,
                payment_method: payment_method,
                order_from: order_from,
            }
        },

        columnDefs: [{
            targets: 0,
            searchable: true,
            orderable: true,
            changeLength: false
        }],
        order: [[0, 'desc']],

        columns: [
            { data: 'product_code', name: 'product_code' },
            { data: 'description', name: 'description' },
            { data: 'qty', name: 'qty' },
            { data: 'created_at', name: 'created_at' },
            {
                data: 'status',
                'render': function (data, type, full, meta) {
                    let status = '';
                    if (data == 1) {
                        status = 'Pending'
                    } else if (data == 2) {
                        status = 'Prepared'
                    } else if (data == 3) {
                        status = 'Shipped'
                    } else if (data == 4) {
                        status = 'Completed'
                    } else if (data == 5) {
                        status = 'Reserved'
                    } else {
                        status = 'Cancelled'
                    }
                    return status
                }
                , name: 'status'
            },
        ]
    });
}

async function fetchTotalReservation(date_from, date_to, order_from, payment_method) {
    $('#txt-total-reservation').html('<i class="fas fa-spinner fa-spin"></i>');
    $.ajax({
        url: '/compute-total-reservation',
        type: 'GET',
        data: {
            date_from: date_from,
            date_to: date_to,
            payment_method: payment_method,
            order_from: order_from,
        },
        success: async function (total_reservation) {
            // if(total_reservation == 0){
            //     $('#txt-total-reservation').html('0.00');
            // }
            // else{
            //total_reservation = parseFloat(total_reservation)
            $('#txt-total-reservation').html(total_reservation);
            // }
        }
    });
}

function formatNumber(total) {
    console.log(total);
    var decimal = (Math.round(total * 100) / 100).toFixed(2);
    return money_format = parseFloat(decimal).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$(document).on('click', '.btn-preview-reservation', async function () {
    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val();
    var order_from = $('#order_from').val()
    var payment_method = $('#payment_method').val();
    window.open("/reports/preview-reservation/" + date_from + "/" + date_to + "/" + order_from + "/" + payment_method);
});

$(document).on('click', '.btn-download-reservation', async function () {
    var date_from = $('#date_from').val()
    var date_to = $('#date_to').val();
    var order_from = $('#order_from').val()
    var payment_method = $('#payment_method').val();
    window.open("/reports/download-reservation/" + date_from + "/" + date_to + "/" + order_from + "/" + payment_method);
});


async function on_Change() {
    $(document).on('change', '#date_from', async function () {
        var date_from = $(this).val();
        var date_to = $('#date_to').val();
        var order_from = $('#order_from').val()
        var payment_method = $('#payment_method').val();

        $('.tbl-reservation').DataTable().destroy();
       
        await fetchReservation(date_from, date_to, order_from, payment_method);

        await fetchTotalReservation(date_from, date_to, order_from, payment_method);
    });

    $(document).on('change', '#date_to', async function () {

        var date_to = $(this).val();
        var date_from = $('#date_from').val();
        var order_from = $('#order_from').val();
        var payment_method = $('#payment_method').val();

        $('.tbl-reservation').DataTable().destroy();

        await fetchReservation(date_from, date_to, order_from, payment_method);

        await fetchTotalReservation(date_from, date_to, order_from, payment_method);
    });

    $(document).on('change', '#order_from', async function () {

        var date_to = $('#date_to').val();
        var date_from = $('#date_from').val();
        var order_from = $('#order_from').val();
        var payment_method = $('#payment_method').val();

        $('.tbl-reservation').DataTable().destroy();

        await fetchReservation(date_from, date_to, order_from, payment_method);

        await fetchTotalReservation(date_from, date_to, order_from, payment_method);
    });

    $(document).on('change', '#payment_method', async function () {

        var date_to = $('#date_to').val();
        var date_from = $('#date_from').val();
        var order_from = $('#order_from').val();
        var payment_method = $('#payment_method').val();

        $('.tbl-reservation').DataTable().destroy();

        await fetchReservation(date_from, date_to, order_from, payment_method);

        await fetchTotalReservation(date_from, date_to, order_from, payment_method);
    });
}

var product_id;
$(document).on('click', '.btn-archive', function () {
    console.log('test')
    product_id = $(this).attr('data-id');
    var row = $(this).closest("tr"); console.log(product_id)
    var name = row.find("td:eq(2)").text();
    var invoice = row.find("td:eq(0)").text();
    $('#confirmModal').modal('show');
    $('.delete-success').hide();
    $('.delete-message').html('Are you sure do you want to archive <b>' + name + '</b> with invoice <b>#' + invoice + '</b>?');
});

$(document).on('click', '.btn-confirm-archive', function () {
    $.ajax({
        url: '/reports/archive/' + product_id,
        type: 'POST',

        beforeSend: function () {
            $('.btn-confirm-archive').text('Please wait...');
        },

        success: function () {
            setTimeout(function () {

                $('.btn-confirm-archive').text('Yes');
                $('.tbl-reservation').DataTable().ajax.reload();
                $('#confirmModal').modal('hide');
                $.toast({
                    text: 'Data was successfully archived.',
                    position: 'bottom-right',
                    showHideTransition: 'plain'
                })
            }, 1000);
        }
    });

});

async function renderComponents() {

    var date_from = $('#date_from').val();
    var date_to = $('#date_to').val();
    var order_from = $('#order_from').val()
    var payment_method = $('#payment_method').val();

    await fetchReservation(date_from, date_to, order_from, payment_method);

    await fetchTotalReservation(date_from, date_to, order_from, payment_method);

    await on_Change();
}

renderComponents();