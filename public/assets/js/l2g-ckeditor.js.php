<?php

$lang = 'en';
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $lang = $_GET['lang'];
}
?>

ClassicEditor.create(document.querySelector('#edit_page_translation_content'), {

toolbar: {
items: [
'heading',
'|',
'bold',
'italic',
'link',
'bulletedList',
'numberedList',
'horizontalLine',
'|',
'indent',
'outdent',
'|',
'imageUpload',
'blockQuote',
'mediaEmbed',
'|',
'undo',
'redo'
]
},
language: '<?= $lang ?>',
image: {
toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side']
},
licenseKey: ''

}).then(editor => {
window.editor = editor;


}).catch(error => {
console.error(error);
});
