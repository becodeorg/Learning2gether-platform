let visible = false;


document.getElementById('imagebutton').addEventListener("click", function () {
    if(visible === false){
        document.getElementById('show').classList.remove('gone');
    }
    if(visible === true){
        document.getElementById('show').classList.add('gone');
    }
    visible = visible === false;
});