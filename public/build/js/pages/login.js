let val;
$('#submit').click(e=> {
    e.preventDefault();
    $.post('/P5/validlogin', {username: $('#formusername').val(), pwd: $('#formpassword').val()})
        .done(data => data && data === 'true'?document.location.href="/P5":$('#message_login').removeClass('visually-hidden'))
        .fail()
        .always();
});