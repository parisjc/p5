$('#savecontact').click(function (event) {
    let nom = $("#nom").val();
    let email = $("#emailAddress").val();
    let object = $("#object").val();
    let message = $("#message").val();
    $.post('/contactmail', {nom: nom,email: email, object: object,message: message})
        .done(data => data && data === 'true'?toastr.success('<b>L\'enregistrement s\'est bien effectué !</b>', 'Success'):toastr.error('<b>Oupsss...! Une erreur est subvenue !</b>', 'Erreur'))
        .fail()
        .always();
});