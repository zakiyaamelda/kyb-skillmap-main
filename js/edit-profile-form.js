function update_form_ws_val() {
    var items = $("#ap-form-ws").val();
    var str = '';
    if (items != null) {
        if (Array.isArray(items)) {
            items.forEach(element => {
                str += element + " ";
            });
        } else {
            str = items.toString();
        }
    }
    $('#ap-form-ws-val').attr('value', str.trim());
    console.log($("#ap-form-ws").val());
}

function update_form_ws_options() {
    var selected_dept = $('#ap-form-dept').val();
    var enabled_optgroup_class = "form-sws-d-" + selected_dept;
    $('.form-ws-optgroup').each(function () {
        if (!$(this).hasClass(enabled_optgroup_class)) {
            $(this).attr('hidden', 'hidden');
        } else {
            $(this).removeAttr('hidden');
        }
    });
    console.log(enabled_optgroup_class);
}

$(document).ready(function () {
    update_form_ws_options();

    var roleValue = $("#ap-form-role").val();
    if (roleValue != 0) {
        $('#ap-form-ws').removeAttr('multiple');
    } else {
        $('#ap-form-ws').attr('multiple', 'multiple');
    }

    $("#ap-form-ws").val($("#ap-form-ws").find("option:selected").map(function () {
        return this.value;
    }).get());

    update_form_ws_val();

    $('#ap-form-ws option').mousedown(function (e) {
        e.preventDefault();
        var originalScrollTop = $(this).parent().scrollTop();
        $(this).prop('selected', $(this).prop('selected') ? false : true);
        var self = this;
        $(this).parent().focus();
        setTimeout(function () {
            $(self).parent().scrollTop(originalScrollTop);
        }, 0);
        update_form_ws_val();
        return false;
    });

    $('select#ap-form-ws').on('change', function () {
        update_form_ws_val();
    });

    $('select#ap-form-dept').on('change', function () {
        update_form_ws_options();
        $("#ap-form-ws").val($("#ap-form-ws").find("option:not(:hidden):eq(0)").val());
        update_form_ws_val();
    });

    $('select#ap-form-role').on('change', function () {
        if (this.value == 0) {
            $('#ap-form-ws').attr('multiple', 'multiple');
        } else {
            $('#ap-form-ws').removeAttr('multiple');
        }
        $("#ap-form-ws").val($("#ap-form-ws").find("option:not(:hidden):eq(0)").val());
        update_form_ws_val();
    });
});