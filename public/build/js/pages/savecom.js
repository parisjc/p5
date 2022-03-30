$('#savecom').click(function (event) {
    let id = event.target.attributes.value.value;
    let nom = $("#nom").val();
    let prenom = $("#prenom").val();
    let com = $("#com").val();

    $.post('/P5/savecom', {id_post: id,nom: nom, prenom: prenom,com:com})
        .done(data => data && data === 'true'?toastr.success('<b>L\'enregistrement s\'est bien effectué !</b>', 'Success'):toastr.error('<b>Oupsss...! Une erreur est subvenue !</b>', 'Erreur'))
        .fail()
        .always();
});