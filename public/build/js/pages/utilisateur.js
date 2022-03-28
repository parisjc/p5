$('input[type=checkbox]').change(function () {
    let element=$(this).is(":checked");
    let iduser = $(this).val();
    console.log(element)
    switch (element)
    {
        case false:
            $("label[for='"+$(this).attr("id")+"']").text('Invalid')
            element=0
            break;
        case true:
            $("label[for='"+$(this).attr("id")+"']").text('Valider')
            element=1
            break;
    }
    $.post('/P5/updateusersactif', {id:iduser, actif: element})
        .done(data => data && data === 'true'?toastr.success('L\'enregistrement c\'est bien effectuer !</b>', 'Success'):toastr.error('Une erreur est subvenue !</b>', 'Erreur'))
        .fail()
        .always();
});

function Redirect()
{
    window.location=document.location.href;
}

$('a').click(function (e) {
    console.log('sup');
    let iduser = e.target.attributes.value.value;
    console.log(iduser)
    $.post('/P5/supuser', {id:iduser})
        .done(data => data && data === 'true'?(toastr.success('Post Supprimer !</b>', 'Success'),setTimeout('Redirect()', 2000)):toastr.error('Une erreur est subvenue !</b>', 'Erreur'))
        .fail()
        .always();
});