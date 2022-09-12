$(document).ready(() => {
    $("#newsletter-button").click(() => {
        console.log($("#category").length);
        addAlert('success', $("body"), 'Iscrizione avvenuta con successo');
        window.scrollTo(0, 0);
    });
});

function addAlert(type, parent, message) {
    let color;
    switch (type) {
        case 'error':
            color = '#c02323';
            break;
        case 'success':
            color = '#23c02b';
            break;
        default:
            color = 'black';
    }

    $("body").after('' +
        '<div class="alert alert-dismissible fade show m-0 form custom-modal" role="alert" style="color:' + color + ';">'
        + '<p class="text-primary subtitle">' + message + '</p>' +
        '<button type="button" class="btn btn-primary w-100 br-sm" data-bs-dismiss="alert" aria-hidden="true" style="display: block; margin: 0 auto;" onclick="$(\'div.alert\').slideUp(200).alert(\'close\');">Ok</button>' +
        '</div>'
    );

    setTimeout(function () {
        $('div.alert').slideUp(200).alert('close');
    }, 3000);
}