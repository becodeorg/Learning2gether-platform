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
var adminNs =
{
    initDraggableEntityRows: function () {
        var dragSrcEl = null; // the object being drug
        var startPosition = null; // the index of the row element (0 through whatever)
        var endPosition = null; // the index of the row element being dropped on (0 through whatever)
        var parent; // the parent element of the dragged item
        var entityId; // the id (key) of the entity
        function handleDragStart(e) {
            dragSrcEl = this;
            dragSrcEl.classList.add('beingDragged');
            entityId = $(this).attr('rel');
            dragSrcEl.style.opacity = '0.4';
            parent = dragSrcEl.parentNode;
            startPosition = Array.prototype.indexOf.call(parent.children, dragSrcEl);
            console.log("start: " + startPosition);
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
            console.log(entityId);
        }
        function handleDragOver(e) {
            //console.log('drag over: '+ e.target);
            if (e.preventDefault) {
                e.preventDefault(); // Necessary. Allows us to drop.
            }
            e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

            return false;
        }
        function handleDragEnter(e) {
            //console.log('drag enter: '+ e.target);
            this.classList.add('over');
        }
        function handleDragLeave(e) {
            //console.log('drag leave: '+ e.target);
            this.classList.remove('over');  // this / e.target is previous target element.
        }
        function handleDrop(e) {
            //console.log('drop: '+ e.target);
            //console.log(e.currentTarget);
            //console.log(dragSrcEl);

            if (e.stopPropagation) {
                e.stopPropagation(); // stops the browser from redirecting.
            }

            // Don't do anything if dropping the same column we're dragging.
            if (dragSrcEl != this) {
                endPosition = Array.prototype.indexOf.call(parent.children, this);
                console.log("end: " + endPosition);
                // Set the source column's HTML to the HTML of the column we dropped on.
                dragSrcEl.innerHTML = this.innerHTML;
                this.innerHTML = e.dataTransfer.getData('text/html');

                // do the ajax call to update the database
                fetch('/partner/dashboard/chapter/sort/' + entityId + '/' + endPosition)
                    .then(function (res) {
                        // $("table.sortable tbody").replaceWith($(res).find("table.sortable tbody"));

                    }).then(function () {
                        adminNs.initDraggableEntityRows();
                    }).catch(function () {
                        // This is where you run code if the server returns any errors
                        alert("An error occurred while sorting. Please refresh the page and try again.")

                    });
                // jQuery.ajax({
                //     url: '/admin/productcategory/sort/' + entityId + '/' + endPosition,
                // })
                //     .done(function (res) {
                //         $("table.sortable tbody").replaceWith($(res).find("table.sortable tbody"));

                //     })
                //     .fail(function (err) {
                //         alert("An error occurred while sorting. Please refresh the page and try again.")
                //     })
                //     .always(function () {
                //         adminNs.initDraggableEntityRows();
                //     });
            }

            return false;
        }

        function handleDragEnd(e) {
            //console.log('drag end: '+ e.target);
            this.style.opacity = '1';  // this / e.target is the source node.
            this.classList.remove('beingDragged');

            [].forEach.call(rows, function (row) {
                row.classList.remove('over');
            });
        }
        var rows = document.querySelectorAll('table.sortable > tbody tr');
        [].forEach.call(rows, function (row) {
            row.addEventListener('dragstart', handleDragStart, false);
            row.addEventListener('dragenter', handleDragEnter, false);
            row.addEventListener('dragover', handleDragOver, false);
            row.addEventListener('dragleave', handleDragLeave, false);
            row.addEventListener('drop', handleDrop, false);
            row.addEventListener('dragend', handleDragEnd, false);
        });
    },

    /**
     * Primary Admin initialization method.
     * @returns {boolean}
     */
    init: function () {
        this.initDraggableEntityRows();
        return true;
    }
};

$(function () {
    adminNs.init();
});
