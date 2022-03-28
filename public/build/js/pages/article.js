
$('input[type=checkbox]').change(function () {
    let element=$(this).is(":checked");
    let idpost = $(this).val();
    console.log(element)
    switch (element)
    {
        case false:
            $("label[for='"+$(this).attr("id")+"']").text('Désactiver')
            element=0
            break;
        case true:
            $("label[for='"+$(this).attr("id")+"']").text('Actif')
            element=1
            break;
    }
    $.post('/P5/updateactif', {id:idpost, actif: element})
        .done(data => data && data === 'true'?toastr.success('L\'enregistrement c\'est bien effectuer !</b>', 'Success'):toastr.error('Une erreur est subvenue !</b>', 'Erreur'))
        .fail()
        .always();
});
function Redirect()
{
    window.location=document.location.href;
}

function Redirectnewpost(id)
{
    window.location='http://localhost/P5/post/'+id+'?vue=false';
}

$('a').click(function (e) {
    let idpost = e.target.attributes.value.value;
    $.post('/P5/suppost', {id:idpost})
        .done(data => data && data === 'true'?(toastr.success('Post Supprimer !</b>', 'Success'),setTimeout('Redirect()', 2000)):toastr.error('Une erreur est subvenue !</b>', 'Erreur'))
        .fail()
        .always();
});

$('#newpost').click(function (e){
    console.log('save post')
    let title = $('#title').val();
    let summary = $('#summary').val();

    $.post('/P5/newpost', {title:title,summary :summary})
        .done(function (data) {
            let val = JSON.parse(data);
            if(val.result===true)
            {
                toastr.success('Post Enregistrer !</b>', 'Success'),
                    setTimeout(Redirectnewpost(val.newid), 2000)
            }
            else
            {
                toastr.error('Une erreur est subvenue !</b>', 'Erreur')
            }
        })
        .fail()
        .always();
})