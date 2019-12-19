const form = document.getElementById('fetchForm');
const url = form.getAttribute('action');

let checkboxes = document.querySelectorAll("input[type=radio]");
let questions = document.getElementsByClassName("chapcontainerquiz");

//On refresh check if all boxes are checked
let checked = document.querySelectorAll("input[type=radio]:checked");
if(checked.length === questions.length){
    document.getElementById('send-quiz').classList.remove('noClick');
    document.getElementById('completeText').classList.add('gone');
}

//On radio click check if all boxes are checked
for(let i=0; i<checkboxes.length; i++){
    checkboxes[i].addEventListener( 'change', function() {
        let checked = document.querySelectorAll("input[type=radio]:checked");
        if(checked.length === questions.length){
            document.getElementById('send-quiz').classList.remove('noClick');
            document.getElementById('completeText').classList.add('gone');
        }
    });
}


form.addEventListener("submit", function(event){
    event.preventDefault();

    const data = new URLSearchParams();
    document.getElementById('send-quiz').classList.remove('spinner-border', 'spinner-border-sm', 'btn', 'btn-default', 'button_5quiz', 'noClick');
    document.getElementById('send-quiz').classList.add('spinner-border', 'spinner-border-sm', 'spinnerstyle');
    document.getElementById('send-quiz').innerHTML = "";
    for (const pair of new FormData(form)) {
        data.append(pair[0], pair[1]);
    }
    fetch(url, {
        method: 'post',
        body: data,
    })
        .then(function (content) {
           return content.json();
        })

        .then(function (json) {
            let route = json.route;
            let status = json.status;

            if(status === "FINISHED_CHAPTER"){
                document.getElementById('black').classList.remove('gone');
                document.getElementById('chapter-pop').classList.add('shown');
            }
            else if(status === "FAIL"){
                document.getElementById('black').classList.remove('gone');
                document.getElementById('fail-pop').classList.add('shown');
                document.getElementById('process-percentage').innerHTML = json.percentage;
            }
            else if(status === "FINISHED_MODULE"){
                document.getElementById('black').classList.remove('gone');
                document.getElementById('module-pop').classList.add('shown');
                document.getElementById('badgr-image').src = json.image;
            }
            document.getElementById('send-quiz').innerHTML = "Submit test";
            document.getElementById('send-quiz').classList.remove('spinner-border', 'spinner-border-sm', 'spinnerstyle');
            document.getElementById('send-quiz').classList.add('btn', 'btn-default', 'button_5quiz', 'noClick');

        })
});
