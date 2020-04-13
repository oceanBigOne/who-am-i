let toastr = require("toastr");

$(document).ready(function() {
    let $body=$('body');

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

    window.onbeforeunload = function(){
        if($('#gamePage').length>0){
            fetch("/exit");
        }

    };

    $body.on('click','.jsForceRefresh',function(){
        if($('#gamePage').length>0){
            $('#gamePage').remove();
        }
        window.location.reload();
    });


    //keep session alive every 2 minutes
    setInterval(function(){
        fetch("/");
    },60000*2);

    $body.on('click','.autoselect',function(){
        $(this).select();
    });

    $body.on('click','.copy-to-clipboard',function(){
        copyToClipboard($(this).attr("data-target"));
    });
});

function copyToClipboard(selector) {
    let $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(selector).val()).select();
    toastr["success"]("Lien copié, vous pouvez maintenant le partager !");

    document.execCommand("copy");
    $temp.remove();
}