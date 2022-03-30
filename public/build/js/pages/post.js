var editor;
ClassicEditor
    .create( document.querySelector( '#editor' ))
    .then( neweditor => {
        editor = neweditor;
    })
    .catch( error => {
        console.error( error );
    } );

$('#save').click(function (event) {
    let id = event.target.attributes.value.value;
    let title = $("#title").val();
    $.post('/P5/updatepost', {id: id,title: title, content: editor.getData()})
.done(data => data && data === 'true'?toastr.success('<b>L\'enregistrement s\'est bien effectué !</b>', 'Success'):toastr.error('<b>Oupsss...! Une erreur est subvenue !</b>', 'Erreur'))
        .fail()
        .always();
});


$(document).ready(function() {
    $("#btn_uploadfile").click(function() {
        var fd = new FormData();
        var files = $('#file')[0].files[0];
        var il_post = $('#btn_uploadfile').val();
        console.log(files)
        fd.append('file', files);
        fd.append('post', il_post);

        $.ajax({
            url: '/P5/uploadfile',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                console.log(response)
                if(response != 0){
                    toastr.success('<b>L\'enregistrement s\'est bien effectué !</b>', 'Success')
                    $('#imageResult').attr('src','/P5/build/imgs/upload/'+files.name);
                }
                else{
                    toastr.error('<b>Oupsss...! Une erreur est subvenue !</b>', 'Erreur');
                }
            },
        });
    });
});
