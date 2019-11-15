// create new editor, on default it binds itself to the first textarea on the page
let editor = new SimpleMDE();

// bool for eventlisteners
let remove = false;

// the next lines remove their guide manual from the toolbar so we can add our own personal one.
editor.gui.toolbar.remove();
editor.toolbar.splice(editor.toolbar.length - 2, 2);
editor.toolbar.push(
    {
        name: "Link",
        action: function showImageModal() {
            $("#imageModal").modal('show');
            setupImages();
        },
        className: "fa fa-picture-o",
        title: "Add image",
    },
    {
        name: "youtube link",
        action: function addYoutubeEmbed() {
            const cm = editor.codemirror;
            let stat = editor.getState(cm);
            let url = prompt('youtube link:');

            const regex = /(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^?&"'> \r\n]+)(?![^<]*>)/;
            let m;

            if ((m = regex.exec(url)) !== null) {
                // The result can be accessed through the `m`-variable.
                m.forEach((match, groupIndex) => {
                    console.log(`Found match, group ${groupIndex}: ${match}`);
                });
                let videoId = m[1];
                console.log(videoId);

                cm.replaceSelection('!!{embed}(' + videoId + ')');
            }

        },
        className: "fa fa-star",
        title: "Youtube",
    },
    {
        name: "Link",
        action: "http://google.com", // here the link to our markdown manual would be
        className: "fa fa-question-circle",
        title: "Our Markdown Guide",
    },
);
editor.createToolbar();

let changes = false;
// onchange function to look for changes to prevent leaving without saving
editor.codemirror.on("change", function () {
    if (changes === false){
        window.onbeforeunload = function(event){
            event.returnValue = true;
        };
        changes = true;
    }
});

function insertImage(src) {
    let cm = editor.codemirror;
    cm.replaceSelection("![image](" + src + ")");
    $("#imageModal").modal('hide');
}

function setupImages() {
    let images = document.querySelectorAll('.imageClick');
    console.log(images);
    if (remove === false) {
        images.forEach(function (item) {
            item.addEventListener('click', function () {
                insertImage(item.currentSrc);
            })
        });
        remove = true;
    }
}