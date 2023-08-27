


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
/// END :: TinyMCE
// $("form").submit(function (e) {
//     e.preventDefault();

//     swal.fire({
//         // title: "Finished!",
//         imageUrl: "https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif",

//         showConfirmButton: false,
//         timer: 1000
//     });
// });
document.onreadystatechange = function () {
    if (document.readyState !== "complete") {


        swal.fire({
            // title: "Finished!",
            imageUrl: "https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif",

            showConfirmButton: false,
            timer: 1000
        });
        // }, 1000);

    }
};

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
        allowClear: true

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
