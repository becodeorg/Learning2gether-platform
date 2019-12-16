function checkModule(moduleId) {
    let url = Routing.generate('api_module', {module: moduleId}, true);
    let btn = document.getElementById('flagbtn-' + moduleId);
    let dump = document.getElementById('dump-' + moduleId);
    let publish = document.getElementById('publish-' + moduleId);
    dump.style.display = 'none';
    btn.innerText = '';
    btn.classList.remove('badge', 'badge-primary', 'badge-danger', 'spinner-border', 'spinner-border-sm');
    btn.classList.add('spinner-border', 'spinner-border-sm');
    fetch(url)
        .then(function (response) {
            return response.json();
        }).then(function (data) {
        if (data.length){
            btn.classList.remove('spinner-border','spinner-border-sm');
            btn.classList.add('badge', 'badge-danger');
            btn.innerText = data.length + ' problems found!';
            dump.style.display = 'block';
            let listDump = '<ul style="width: 100%">';
            for (let i = 0; i < data.length; i++){
                listDump += '<li>' + data[i] + '</li>';
            }
            listDump += '</ul>';
            dump.innerHTML = listDump;
        } else {
            btn.classList.remove('spinner-border', 'spinner-border-sm');
            btn.classList.add('badge', 'badge-primary');
            btn.innerText = 're-check';
            let publishUrl = Routing.generate('publish_module', {module: moduleId}, true);
            publish.innerHTML = '<a id="publishLink" class="flagbtn badge badge-success">Click here to publish!</a>';
            let publishLink = document.getElementById('publishLink');
            publishLink.setAttribute('href', publishUrl);
            // I should probably stop the user from republishing already published modules
        }
    });
}

//modal checking

let modal = document.querySelector(".modal");
let trigger = document.querySelector(".trigger");
let closeButton = document.querySelector(".close-button");

function toggleModal() {
    modal.classList.toggle("show-modal");
}

function windowOnClick(event) {
    if (event.target === modal) {
        toggleModal();
    }
}

trigger.addEventListener("click", toggleModal);
closeButton.addEventListener("click", toggleModal);
window.addEventListener("click", windowOnClick);