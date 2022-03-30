let val;
$('#submit').click(e=> {
    e.preventDefault();
    if($('#mdp').val()===$('#mdp2').val()) {
        $.post('/P5/saveusers', {
            nom: $('#nom').val(),
            prenom: $('#prenom').val(),
            email: $('#email').val(),
            username: $('#username').val(),
            mdp: $('#mdp').val()
        })
            .done((function (data) {
                let val = JSON.parse(data);
                if(val===true)
                {

                    toastr.success('<b>Information enregistrée, votre compte sera validé par l\'administration !</b>', 'Success'),
                        setTimeout(Redirect(), 10000)
                }
                else
                {
                    toastr.error('<b>Oupsss...! Une erreur est subvenue !</b>', 'Erreur')
                }
            }))
            .fail()
            .always();
    }
    else {
        toastr.error('<b>Vos mot de passe ne correspondent pas !</b>', 'Erreur')
    }
});

function Redirect()
{
    window.location='http://localhost/P5/login';
}