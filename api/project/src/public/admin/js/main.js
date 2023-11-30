$(document).ready(function() {
    
//    $('#od').datepicker({
//          defaultDate: "-1w",
//          changeMonth: true,
//          numberOfMonths: 1,
//          dateFormat: "yy-mm-dd"
//        })
    
    $('#included').change(function(event){
        var incl_val = jQuery('#included').val();
        if (incl_val==0)
        {
            jQuery('#cena').prop( "disabled", false );
        }
        else
        {
            jQuery('#cena').prop( "disabled", true );
            jQuery('#cena').val('');
        }
    });
    
    $('.show_btn').click(function(event){
        jQuery('#form_box_add').removeClass( "hidden" );
        jQuery('#form_box_add_btn').removeClass( "hidden" );
        jQuery('.show_btn').addClass( "hidden" );
        jQuery('.hide_btn').removeClass( "hidden" );
        
    });
    
    $('.hide_btn').click(function(event){
        jQuery('#form_box_add').addClass( "hidden" );
        jQuery('#form_box_add_btn').addClass( "hidden" );
        jQuery('.show_btn').removeClass( "hidden" );
        jQuery('.hide_btn').addClass( "hidden" );
        
    });
    
    $('#phone_chkbx_all').click(function(event){
        if ($('#phone_chkbx_all').is(':checked')) 
        {
            $('.phone_chkbx').prop('checked', true);
        }
        else
        {
            $('.phone_chkbx').prop('checked', false);
        }
    });
    
    $('#save_btn').click(function(event){
        $('#save_frm').submit();
    });
    
    $('#id_surovina_copy').change(function(event){
        var name_sk = $(this).find(':selected').data('name_sk');
        var name_en = $(this).find(':selected').data('name_en');
        jQuery('#nazov_sk').val(name_sk);
        jQuery('#nazov_en').val(name_en);
    });
    
});

function decision(message, url)
{
    if(confirm(message)) location.href = url;
}