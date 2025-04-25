
function update_form_qualification_ehs_val() {
    var str = '';
    $("input.edit-ehs-checkbox:checkbox:checked").each(function(){
        str += ($(this).val()) + ' ';
    });
    $("#ap-form-qualification-val-ehs").val(str);
    console.log($("#ap-form-qualification-val-ehs").val());
    return str;
}

function update_form_s_certification_ehs_val() {
    var str = '';
    $("input.edit-s-process-checkbox:checkbox:checked").each(function(){
        str += ($(this).val()) + ' ';
    });
    $('.ap-form-s-certification-val').val(str);
    console.log($("#ap-form-qualification-val-ehs").val());
    return str;
}

$(function() {
$('document').ready(function(){
    update_form_qualification_ehs_val();
    update_form_s_certification_ehs_val();
    $('input.edit-ehs-checkbox').change(function() {
        var s = update_form_qualification_val();
    });
    $('input.edit-s-process-checkbox').change(function() {
        var s = update_form_s_certification_val();
    });
    $('.p2-edit-min-value-btn').click(function() {
        var id = $("#p2-" + $(this).val()).val().split("-")[0];
        var v = parseInt($("#p2-" + $(this).val()).val().split("-")[1]);
        if (v > 0) {
            v -= 1;
        }
        if (v == 0) {
            $("#p2-" + $(this).val()).attr('checked', false);
        }
        toggleDatePicker(id, v);
        $("#p2-" + $(this).val()).val(id + "-" + v);
        $("#pval2-" + id).attr('src', 'img/C'+v+'.png');
        console.log($("#p2-" + $(this).val()).val())
        update_form_qualification_ehs_val();
    });
    $('.p2-edit-add-value-btn').click(function() {
        var id = $("#p2-" + $(this).val()).val().split("-")[0];
        var v = parseInt($("#p2-" + $(this).val()).val().split("-")[1]);
        if(v < 4)
        {
            v += 1;
        }
        if (v > 0) {
            $("#p2-" + $(this).val()).attr('checked', true);
        }
        toggleDatePicker(id, v);
        $("#p2-" + $(this).val()).val(id + "-" + v);
        $("#pval2-" + id).attr('src', 'img/C'+v+'.png');
        console.log($("#p2-" + $(this).val()).val())
        update_form_qualification_ehs_val();
    });
    $('.mandatory-checkbox-ehs').change(function() {
        var processId = $(this).attr('id').split('-')[1];
        var currentSkill = $('#p2-' + processId).val().split("-")[1];
        toggleDatePicker(processId, currentSkill);
    });

    // Pastikan untuk memanggil toggleDatePicker ketika halaman dimuat untuk setiap proses
    $('.edit-ehs-checkbox').each(function() {
        var processId = $(this).val().split("-")[0];
        var currentSkill = $(this).val().split("-")[1];
        toggleDatePicker(processId, currentSkill);
    });
    
    function toggleDatePicker(processId, currentSkill) {
        var minSkill = parseInt($('#pval2-add-btn-' + processId).next('img').attr('src').match(/\d+/)[0]);
        var isMandatoryChecked = $('#mandatory-ehs-' + processId).is(':checked');
    
        if(currentSkill < minSkill && isMandatoryChecked) {
            $('#monthYearPickerEhs' + processId).prop('disabled', false);
            $('#monthYearPickerEhs' + processId).prop('required', true); 
        } else {
            $('#monthYearPickerEhs' + processId).prop('disabled', true).val('');
            $('#monthYearPickerEhs' + processId).prop('required', false); // Menghapus atribut required
        }
    }

})});