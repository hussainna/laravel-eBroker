


$(document).ready(function () {

    /// START :: ACTIVE MENU CODE
    $(".menu a").each(function () {
        var pageUrl = window.location.href.split(/[?#]/)[0];

        if (this.href == pageUrl) {
            $(this).parent().parent().addClass("active");
            $(this).parent().addClass("active"); // add active to li of the current link
            $(this).parent().parent().prev().addClass("active"); // add active class to an anchor
            $(this).parent().parent().parent().addClass("active"); // add active class to an anchor
            $(this).parent().parent().parent().parent().addClass("active"); // add active class to an anchor
        }

        var subURL = $("a#subURL").attr("href");
        if (subURL != 'undefined') {
            if (this.href == subURL) {

                $(this).parent().addClass("active"); // add active to li of the current link
                $(this).parent().parent().addClass("active");
                $(this).parent().parent().prev().addClass("active"); // add active class to an anchor

                $(this).parent().parent().parent().addClass("active"); // add active class to an anchor

            }
        }
    });
    /// END :: ACTIVE MENU CODE

});

$(document).ready(function () {

    $('.select2-selection__clear').hide();


});



/// START :: TinyMCE
document.addEventListener("DOMContentLoaded", () => {
    tinymce.init({
        selector: '#tinymce_editor',
        height: 400,
        menubar: true,
        plugins: [
            'advlist autolink lists link charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime table contextmenu paste code help wordcount'
        ],

        toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        setup: function (editor) {
            editor.on("change keyup", function (e) {
                //tinyMCE.triggerSave(); // updates all instances
                editor.save(); // updates this instance's textarea
                $(editor.getElement()).trigger('change'); // for garlic to detect change
            });
        }
    });
});

$('body').append('<div id="loader-container"><div class="loader"></div></div>');
$(window).on('load', function () {
    $('#loader-container').fadeOut('slow');
});

//magnific popup
$(document).on('click', '.image-popup-no-margins', function () {

    $(this).magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        closeBtnInside: false,
        fixedContentPos: true,

        image: {
            verticalFit: true
        },
        zoom: {
            enabled: true,
            duration: 300 // don't foget to change the duration also in CSS
        }

    }).magnificPopup('open');
    return false;
});



setTimeout(function () {
    $(".error-msg").fadeOut(1500)
}, 5000);

$(document).ready(function () {
    $('.select2').select2({
        theme: 'bootstrap-5',
        placeholder: {
            id: '-1', // the value of the option
            text: 'Select an option'
        },
        allowClear: false


    });
});

function show_error() {
    Swal.fire({
        title: 'Modification not allowed',
        icon: 'error',
        showDenyButton: true,

        confirmButtonText: 'Yes',
        denyCanceButtonText: `No`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */

    })
}
function confirmationDelete(e) {
    if (process.env.DEMO_MODE === 'true') {
        Swal.fire({
            title: 'Modification not allowed',
            icon: 'error',
            showDenyButton: true,

            confirmButtonText: 'Yes',
            denyCanceButtonText: `No`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */

        })
    }
    var url = e.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty

    $('#form-del').attr('action', url);
    Swal.fire({
        title: 'Are You Sure Want to Delete This Record??',
        icon: 'error',
        showDenyButton: true,

        confirmButtonText: 'Yes',
        denyCanceButtonText: `No`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $("#form-del").submit();
        } else {
            $('#form-del').attr('action', '');
        }
    })
    return false;
}
$("#category").change(function () {
    $('#parameter_type').empty();
    $('#facility').show();

    console.log("change");
    $('.parsley-error filled,.parsley-required').attr("aria-hidden", "true");

    var parameter_types = $(this).find(':selected').data('parametertypes');

    parameter_data = $.parseJSON($('#parameter_count').val());
    data_arr = (parameter_types + ',').split(",");


    $.each(data_arr, function (key, value) {
        let param = parameter_data.filter(parameter => parameter.id == value);

        var a = "";
        if (param[0]) {

            if (param[0].type_of_parameter == "radiobutton") {

                $('#parameter_type').append(

                    '<div class="form-group mandatory col-md-3 chk' +
                    '"id=' + param[0].id + '><label for="' +
                    param[0].name +
                    '" class="form-label col-12 ">' +
                    param[0].name +
                    '</label></div>');
                $.each(param[0].type_values, function (k, v) {

                    $('#' + param[0].id).append(
                        '<input name="' +
                        param[0].id +
                        '" type="radio" value="' +
                        v +
                        '" class="form-check-input ml-5"/><label class="form-label rd col-2">' +
                        v +
                        '</label>'
                    );
                });
            }
            if (param[0].type_of_parameter == "checkbox") {

                $('#parameter_type').append(

                    '<div class="form-group mandatory col-md-3 chk' +
                    '"id=' + param[0].id + '><label for="' +
                    param[0].name +
                    '" class="form-label col-12 ">' +
                    param[0].name +
                    '</label></div>');
                $.each(param[0].type_values, function (k, v) {

                    $('#' + param[0].id).append(
                        '<input name="' +
                        param[0].id +
                        '" type="checkbox" value="' +
                        v +
                        '" class="form-check-input ml-5"/><label class="form-label rd col-2">' +
                        v +
                        '</label>'
                    );
                });
            }

            if (param[0].type_of_parameter == "dropdown") {

                $('#parameter_type').append(

                    '<div class="form-group mandatory col-md-3"><label for="' +
                    param[0].name +
                    '" class="form-label col-12 ">' +
                    param[0].name +
                    '</label>' +
                    '<select id="mySelect" name="' +
                    param[0].id +
                    '" class="select2 form-select form-control-sm" data-parsley-required="true"><option>choose option</option></select></div>'
                );

                arr = (param[0].type_values);
                $.each(param[0].type_values,
                    function (key, val) {
                        $('#mySelect').append($(
                            '<option>', {
                            value: val,
                            text: val
                        }));
                    });
            }
            if (param[0].type_of_parameter == "textbox") {
                $('#parameter_type').append($(



                    '<div class="form-group mandatory col-md-3"><label for="' +
                    param[0].name +
                    '" class="form-label  col-12">' +
                    param[0].name +
                    '</label><input class="form-control" required type="text" id="' +
                    param[0].id + '" name="' + param[0]
                        .id +
                    '"></div>'


                ));
            }
            if (param[0].type_of_parameter == "number") {

                $('#parameter_type').append($(
                    '<div class="form-group mandatory col-md-3"><label for="' +
                    param[0].name +
                    '" class="form-label  col-12">' +
                    param[0].name +
                    '</label><input class="form-control" required type="number" id="' +
                    param[0].id + '" name="' + param[0]
                        .id +
                    '"></div>'


                ));
            }
            if (param[0].type_of_parameter == "file") {
                $('#parameter_type').append($(

                    '<div class="form-group mandatory col-md-3"><label for="' +
                    param[0].name +
                    '" class="form-label  col-12">' +
                    param[0].name +
                    '</label><input class="form-control" required type="file" id="' +
                    param[0].id + '" name="' + param[0]
                        .id +
                    '"></div>'


                ));

            }

        }
    });
});



function chk(checkbox) {
    console.log(event.target.id);
    if (checkbox.checked) {

        active(event.target.id);

    } else {

        disable(event.target.id);
    }
}

$(document).ready(function () {

    FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType);

    $('.filepond').filepond({
        credits: null,
        allowFileSizeValidation: "true",
        maxFileSize: '25MB',
        labelMaxFileSizeExceeded: 'File is too large',
        labelMaxFileSize: 'Maximum file size is {filesize}',
        allowFileTypeValidation: true,
        acceptedFileTypes: ['image/*'],
        labelFileTypeNotAllowed: 'File of invalid type',
        fileValidateTypeLabelExpectedTypes: 'Expects {allButLastType} or {lastType}',
        storeAsFile: true,
        allowPdfPreview: true,
        pdfPreviewHeight: 320,
        pdfComponentExtraParams: 'toolbar=0&navpanes=0&scrollbar=0&view=fitH',
        allowVideoPreview: true, // default true
        allowAudioPreview: true // default true
    });
});
