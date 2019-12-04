let shown = false;

document.getElementById('profiledrop').addEventListener("click", function () {
    if(shown === false){
        document.getElementById('reveal').classList.remove('gone');
        document.getElementById('screen').classList.remove('gone');
    }
    if(shown === true){
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