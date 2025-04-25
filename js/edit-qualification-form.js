function update_form_qualification_val() {
    var str = '';
    $("input.edit-process-checkbox:checkbox:checked").each(function(){
        str += ($(this).val()) + ' ';
    });
    $("#ap-form-qualification-val").val(str);
    console.log($("#ap-form-qualification-val").val());
    return str;
}

function update_form_s_certification_val() {
    var str = '';
    $("input.edit-s-process-checkbox:checkbox:checked").each(function(){
        str += ($(this).val()) + ' ';
    });
    $('.ap-form-s-certification-val').val(str);
    console.log($("#ap-form-qualification-val").val())
    return str;
}

$(function() {
$('document').ready(function(){
    update_form_qualification_val();
    update_form_s_certification_val();
    $('input.edit-process-checkbox').change(function() {
        var s = update_form_qualification_val();
    });
    $('input.edit-s-process-checkbox').change(function() {
        var s = update_form_s_certification_val();
    });
    $('.p-edit-min-value-btn').click(function() {
        var id = $("#p-" + $(this).val()).val().split("-")[0];
        var v = parseInt($("#p-" + $(this).val()).val().split("-")[1]);
        if (v > 0) {
            v -= 1;
        }
        if (v == 0) {
            $("#p-" + $(this).val()).attr('checked', false);
        }
        toggleDatePicker(id, v);
        $("#p-" + $(this).val()).val(id + "-" + v);
        $("#pval-" + id).attr('src', 'img/C'+v+'.png');
        console.log($("#p-" + $(this).val()).val())
        update_form_qualification_val();
    });
    $('.p-edit-add-value-btn').click(function() {
        var id = $("#p-" + $(this).val()).val().split("-")[0];
        var v = parseInt($("#p-" + $(this).val()).val().split("-")[1]);
        if(v < 4)
        {
            v += 1;
          }
        if (v > 0) {
            $("#p-" + $(this).val()).attr('checked', true);
        }
        toggleDatePicker(id, v);
        $("#p-" + $(this).val()).val(id + "-" + v);
        $("#pval-" + id).attr('src', 'img/C'+v+'.png');
        console.log($("#p-" + $(this).val()).val())
        update_form_qualification_val();
    });
    $('.mandatory-checkbox').change(function() {
        var processId = $(this).attr('id').split('-')[1];
        var currentSkill = $('#p-' + processId).val().split("-")[1];
        toggleDatePicker(processId, currentSkill);
    });

    // Pastikan untuk memanggil toggleDatePicker ketika halaman dimuat untuk setiap proses
    $('.edit-process-checkbox').each(function() {
        var processId = $(this).val().split("-")[0];
        var currentSkill = $(this).val().split("-")[1];
        toggleDatePicker(processId, currentSkill);
    });
    
    function toggleDatePicker(processId, currentSkill) {
        var minSkill = parseInt($('#pval-add-btn-' + processId).next('img').attr('src').match(/\d+/)[0]);
        var isMandatoryChecked = $('#mandatory-' + processId).is(':checked');
    
        if(currentSkill < minSkill && isMandatoryChecked) {
            $('#monthYearPicker' + processId).prop('disabled', false);
            $('#monthYearPicker' + processId).prop('required', true); // Menambahkan atribut required
        } else {
            $('#monthYearPicker' + processId).prop('disabled', true).val('');
            $('#monthYearPicker' + processId).prop('required', false); // Menghapus atribut required
        }
    }

})});