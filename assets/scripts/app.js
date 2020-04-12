let toastr = require("toastr");

$(document).ready(function() {
    toastr.options = {
        closeButton: false,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-top-center",
        preventDuplicates: true,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "5000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "slideDown",
        hideMethod: "slideUp"
    };

    $(".flash-message").each(function() {
        toastr[$(this).data("type")]($(this).html());
    });
});