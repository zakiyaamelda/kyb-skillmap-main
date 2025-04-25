function update_form_ws_val() {
    var items = $("#ap-form-ws").val();
    var str = '';
    if (items != null)

    if (Array.isArray(items))
        items.forEach(element => {
        str += element + " ";
    }); else {
        str = items.toString();
    }
    $('#ap-form-ws-val').attr('value', str);
}

$(function() {
$('document').ready(function(){
    document.getElementById('ap-form-date').valueAsDate = new Date();
    $("#ap-form-ws").val($("#ap-form-ws option:first").val());
    update_form_ws_val();

    $('#ap-form-ws option').mousedown(function(e) {
        e.preventDefault();
        var originalScrollTop = $(this).parent().scrollTop();
        $(this).prop('selected', $(this).prop('selected') ? false : true);
        var self = this;
        $(this).parent().focus();
        setTimeout(function() {
            $(self).parent().scrollTop(originalScrollTop);
        }, 0);

        update_form_ws_val();
        
        return false;
    });

    $('select#ap-form-ws').on('change', function() {
        update_form_ws_val();
    });

    $('select#ap-form-role').on('change', function() {
        if (this.value == 0) 
            $('#ap-form-ws').attr('multiple', 'multiple');
        else 
            $('#ap-form-ws').removeAttr('multiple');
        $("#ap-form-ws").val($("#ap-form-ws option:first").val());
        update_form_ws_val();
      });
})});
