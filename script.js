jQuery(document).ready(function(){
    // alert('hh');
    jQuery('#custom_time').click(function(){
        jQuery('#showTime').show();
    });
    jQuery('#evening').click(function(){
        jQuery('#showTime').hide();
    });
    jQuery('#morning').click(function(){
        jQuery('#showTime').hide();
    });

    jQuery('#choose_time').on('change',function(){
        var times= jQuery(this).val();
          jQuery('#custom_time').val(times);
       });
})

