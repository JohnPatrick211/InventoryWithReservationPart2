$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 

var user_id;
$(document).on('click', '.btn-archive-user', function(){
    user_id = $(this).attr('data-id');
    var row = $(this).closest("tr");
    var name = row.find("td:eq(0)").text();
    $('#confirmModal').modal('show');
    $('.delete-success').hide();
    $('.delete-message').html('Are you sure do you want to archive <b>'+ name +'</b> ?');
  }); 
  
$(document).on('click', '.btn-confirm-archive', function(){
    $.ajax({
        url: '/user/archive/'+ user_id,
        type: 'POST',
      
        beforeSend:function(){
            $('.btn-confirm-archive').text('Please wait...');
        },
        
        success:function(){
            setTimeout(function(){

                $('.btn-confirm-archive').text('Yes');
                $( ".tbl-user").load( "users .tbl-user" );
                $('#confirmModal').modal('hide');
                $.toast({
                    text: 'User was successfully archived.',
                    position: 'bottom-right',
                    showHideTransition: 'plain'
                })
            }, 1000);
        }
    });
  
});

$(document).on('click', '#btn-change-password', function(){
    $(this).hide();
    $('.new-password-container').removeClass('d-none');
    $('#password').prop('required',true);
    //$('#password').prop('minLength',8);
});

$(document).on('click', '#cancel', function(){
    $('.new-password-container').addClass('d-none');
    $('#password').val(''); 
    $('#password').removeAttr("required");
    //$('#password').removeAttr("minLength");  
    $('#btn-change-password').show();
});

$(document).on('change', '#contactno', function(){
    var contactno = $(this).val();
    console.log(contactno);
    var phoneRGEX = /((\+[0-9]{2})|0)[.\- ]?9[0-9]{2}[.\- ]?[0-9]{3}[.\- ]?[0-9]{4}/;
    var res = phoneRGEX.test(contactno);
    if(res == false){
        document.getElementById("contactno").disabled = true
    }
    else{

    }
    
}); 

$(document).on('change', 'select[name=province]', function(){
    var province = $(this).val();
       console.log(province)
    getMunicipalityByProvince(province);
    
});  

$(document).on('change', 'select[name=municipality]', function(){
    var municipality = $(this).val();
       console.log(municipality)
    getBrgyByMunicipality(municipality);
    
});

function getMunicipalityByProvince(province) {

    $.ajax({
        url: '/get-municipality/'+province,
        tpye: 'GET',
        success:function(data){ console.log(data)
            populateDropdown2(data, 'municipality');
        }
      });
}

function getBrgyByMunicipality(municipality) {

    $.ajax({
        url: '/get-brgy/'+municipality,
        tpye: 'GET',
        success:function(data){ console.log(data)
            populateDropdown(data, 'brgy');
        }
      });
}

function populateDropdown(data, object){ 
    var selected = ""; 
    var brgy = $('select[name='+ object +'] :selected').val();
    if(!brgy){
        brgy= $('select[name='+ object +'] option:first').val();
    }
    if(data.length > 0) {
        $('select[name='+ object +']').empty();
        for (var i = 0; i < data.length; i++) 
        {
            selected = data[i].brgyDesc == brgy ? "selected" : "";

            $('select[name='+ object +']').append('<option '+selected+' value="' + data[i].brgyDesc + '">' + data[i].brgyDesc + '</option>');
     
        }
    }
    else {
        $('select[name='+ object +']').empty()
    }
       
}

function populateDropdown2(data, object){ 
    var selected = ""; 
    var municipality = $('select[name='+ object +'] :selected').val();
    if(!municipality){
        municipality= $('select[name='+ object +'] option:first').val();
    }
    if(data.length > 0) {
        $('select[name='+ object +']').empty();
        for (var i = 0; i < data.length; i++) 
        {
            selected = data[i].provDesc == municipality ? "selected" : "";

            $('select[name='+ object +']').append('<option '+selected+' value="' + data[i].citymunCode + '">' + data[i].citymunDesc + '</option>');
     
        }
    }
    else {
        $('select[name='+ object +']').empty()
    }
       
}

getBrgyByMunicipality($('select[name=municipality]').val());