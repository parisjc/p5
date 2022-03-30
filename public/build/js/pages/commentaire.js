$('input[type=checkbox]').change(function () {
    let element=$(this).is(":checked");
    let idcom = $(this).val();
    switch (element)
    {
        case false:
            $("label[for='"+$(this).attr("id")+"']").text('Invalide')
            element=0;
            break;
        case true:
            $("label[for='"+$(this).attr("id")+"']").text('Valider')
            element=1;
            break;
    }
    $.post('/P5/updatecomsactif', {id:idcom, actif: element})
        .done(data => data && data === 'true'?(toastr.success('<b>L\'enregistrement s\'est bien effectué !</b>', 'Success'),setTimeout('Redirect()', 2000)):toastr.error('<b>Oupsss...! Une erreur est subvenue !</b>', 'Erreur'))
        .fail()
        .always();
});

function Redirect()
{
    window.location=document.location.href;
}

$('a').click(function (e) {
    console.log('sup');
    let idcom = e.target.attributes.value.value;
    console.log(idcom)
    $.post('/P5/supcom', {id:idcom})
        .done(data => data && data === 'true'?(toastr.success('<b>Commentaire Supprimé !</b>', 'Success'),setTimeout('Redirect()', 2000)):toastr.error('<b>Oupsss...! Une erreur est subvenue !</b>', 'Erreur'))
        .fail()
        .always();
});