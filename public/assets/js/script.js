let shown = false;

document.getElementById('profiledrop').addEventListener("click", function () {
    if (shown === false) {
        document.getElementById('reveal').classList.remove('gone');
        document.getElementById('screen').classList.remove('gone');
    }
    if (shown === true) {
        document.getElementById('reveal').classList.add('gone');
        document.getElementById('screen').classList.add('gone');
    }
    shown = shown === false;
});

document.getElementById('screen').addEventListener("click", function () {

    document.getElementById('reveal').classList.add('gone');
    document.getElementById('screen').classList.add('gone');

    shown = false;
});


/* DRAGGABLE UI */
if (document.getElementById('js-sortable-chapters')) {

    /**
     * Dragable Chapters
     */
    var el = document.getElementById('js-sortable-chapters');
    var sortable = Sortable.create(el, {
        animation: 150,
        ghostClass: "ghost",
        chosenClass: "chosen",
        // Element dragging ended
        onEnd: function (evt) {

            let endPosition = evt.newIndex;  // element's new index within new parent
            let entityId = evt.item.getAttribute('data-id');
            console.log("Entity id = ", entityId)
            console.log("endPosition = ", endPosition)
            // do the ajax call to update the database
            fetch('/partner/dashboard/chapter/sort/' + entityId + '/' + endPosition)
                .then(function (res) {

                    // nothing to do really. We Could update the #id number displayed.

                }).catch(function () {
                    alert("An error occurred while sorting. Please refresh the page and try again.")
                });
        },

    });

}

if (document.getElementById('js-sortable-pages')) {

    /**
     * Dragable Chapters
     */
    var el = document.getElementById('js-sortable-pages');
    var sortable = Sortable.create(el, {
        animation: 150,
        ghostClass: "ghost",
        chosenClass: "chosen",
        // Element dragging ended
        onEnd: function (evt) {

            let endPosition = evt.newIndex;  // element's new index within new parent
            let entityId = evt.item.getAttribute('data-id');
            let moduleId = evt.item.getAttribute('data-module')
            console.log("Entity id = ", entityId)
            console.log("endPosition = ", endPosition)
            // do the ajax call to update the database
            fetch('/partner/page/sort/' + entityId + '/' + endPosition)
                .then(function (res) {

                    // nothing to do really. We Could update the #id number displayed.

                }).catch(function () {
                    alert("An error occurred while sorting. Please refresh the page and try again.")
                });
        },

    });

}
