
function update_form_qualification_quality_val() {
    var str = '';
    $("input.edit-quality-checkbox:checkbox:checked").each(function(){
        str += ($(this).val()) + ' ';
    });
    $("#ap-form-qualification-val-quality").val(str);
    console.log($("#ap-form-qualification-val-quality").val());
    return str;
}

function update_form_s_certification_quality_val() {
    var str = '';
    $("input.edit-s-process-checkbox:checkbox:checked").each(function(){
        str += ($(this).val()) + ' ';
    });
    $('.ap-form-s-certification-val').val(str);
    console.log($("#ap-form-qualification-val-quality").val());
    return str;
}

$(function() {
$('document').ready(function(){
    update_form_qualification_quality_val();
    update_form_s_certification_quality_val();
    $('input.edit-quality-checkbox').change(function() {
        var s = update_form_qualification_val();
    });
    $('input.edit-s-process-checkbox').change(function() {
        var s = update_form_s_certification_val();
    });
    $('.p3-edit-min-value-btn').click(function() {
        var id = $("#p3-" + $(this).val()).val().split("-")[0];
        var v = parseInt($("#p3-" + $(this).val()).val().split("-")[1]);
        if (v > 0) {
            v -= 1;
        }
        if (v == 0) {
            $("#p3-" + $(this).val()).attr('checked', false);
        }
        toggleDatePicker1(id, v);
        $("#p3-" + $(this).val()).val(id + "-" + v);
        $("#pval3-" + id).attr('src', 'img/C'+v+'.png');
        console.log($("#p3-" + $(this).val()).val())
        update_form_qualification_quality_val();
    });
    $('.p3-edit-add-value-btn').click(function() {
        var id = $("#p3-" + $(this).val()).val().split("-")[0];
        var v = parseInt($("#p3-" + $(this).val()).val().split("-")[1]);
        if(v < 4)
        {
            v += 1;
        }
        if (v > 0) {
            $("#p3-" + $(this).val()).attr('checked', true);
        }
        toggleDatePicker1(id, v);
        $("#p3-" + $(this).val()).val(id + "-" + v);
        $("#pval3-" + id).attr('src', 'img/C'+v+'.png');
        console.log($("#p3-" + $(this).val()).val())
        update_form_qualification_quality_val();
    });
    $('.mandatory-checkbox-quality').change(function() {
        var processId = $(this).attr('id').split('-')[1];
        var currentSkill = $('#p3-' + processId).val().split("-")[1];
        toggleDatePicker1(processId, currentSkill);
    });

    // Pastikan untuk memanggil toggleDatePicker ketika halaman dimuat untuk setiap proses
    $('.edit-quality-checkbox').each(function() {
        var processId = $(this).val().split("-")[0];
        var currentSkill = $(this).val().split("-")[1];
        toggleDatePicker1(processId, currentSkill);
    });
    
    function toggleDatePicker1(processId, currentSkill) {
        var minSkill = parseInt($('#pval3-add-btn-' + processId).next('img').attr('src').match(/\d+/)[0]);
        var isMandatoryChecked = $('#mandatory-quality-' + processId).is(':checked');
    
        if(currentSkill < minSkill && isMandatoryChecked) {
            $('#monthYearPickerQuality' + processId).prop('disabled', false);
            $('#monthYearPickerQuality' + processId).prop('required', true); 
        } else {
            $('#monthYearPickerQuality' + processId).prop('disabled', true).val('');
            $('#monthYearPicker' + processId).prop('required', false);
        }
    }
})});