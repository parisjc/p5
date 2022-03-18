var editor;
ClassicEditor
    .create( document.querySelector( '#editor' ),
        {
            // filebrowserUploadUrl:'http://localhost/P5/updatepost',
            // filebrowserUploadMethod:'form',
            simpleUpload: {
                uploadUrl: 'http://example.com'
            }
        })
    .then( neweditor => {
        editor = neweditor;
        console.log(editor);

    })
    .catch( error => {
        console.error( error );
    } );

$('#save').click(function (event) {
    let id = event.target.attributes.value.value;
    $.post('/P5/updatepost', {id: id, content: editor.getData()})
.done(data => data && data === 'true'?document.location.href="/P5":console.log('kos'))
        .fail()
        .always();
});