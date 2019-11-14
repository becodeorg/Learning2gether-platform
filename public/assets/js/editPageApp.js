// create new editor, on default it binds itself to the first textarea on the page
let editor = new SimpleMDE();

let remove = false; // bool for eventlisteners

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

            const regex = /(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'> \r\n]+)(?![^<]*>)/;
            let m;

            if ((m = regex.exec(url)) !== null) {
                // The result can be accessed through the `m`-variable.
                m.forEach((match, groupIndex) => {
                    console.log(`Found match, group ${groupIndex}: ${match}`);
                });
                let videoId = m[1];
                console.log(videoId);

                let iframe = ['<iframe width=560 height=315 src="https://www.youtube.com/embed/', videoId, '" allowfullscreen></iframe>'];
                _replaceSelection(cm, stat.image, iframe, url);
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

// onchange function for the editor, for testing purposes
editor.codemirror.on("change", function () {
    console.log(editor.value());
});

function _replaceSelection(cm, active, startEnd, url) {
    if (/editor-preview-active/.test(cm.getWrapperElement().lastChild.className))
        return;

    let text;
    let start = startEnd[0];
    let mid = startEnd[1];
    let end = startEnd[2];
    let startPoint = cm.getCursor("start");
    let endPoint = cm.getCursor("end");
    if (url) {
        mid = mid.replace("#url#", url);
    }
    if (active) {
        text = cm.getLine(startPoint.line);
        start = text.slice(0, startPoint.ch);
        mid = text.slice(startPoint.ch);
        cm.replaceRange(start + mid, {
            line: startPoint.line,
            ch: 0
        });
    } else {
        text = cm.getSelection();
        cm.replaceSelection(start + text + mid + end);
        startPoint.ch += start.length;
        if (startPoint !== endPoint) {
            endPoint.ch += start.length;
        }
    }
    cm.setSelection(startPoint, endPoint);
    cm.focus();
}

function insertImage(src) {
    let cm = editor.codemirror;
    cm.replaceSelection("![image](" + src + ")");
    $("#imageModal").modal('hide');
}

function setupImages() {
    let images = document.querySelectorAll('.imageClick');
    console.log(images);
    if (remove === false){
        images.forEach(function (item) {
            item.addEventListener('click', function () {
                insertImage(item.currentSrc);
            })
        });
        remove = true;
    }
}