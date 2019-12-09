$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

function checkModule(moduleId) {
    let url = Routing.generate('api_module', {module: moduleId}, true);
    let btn = document.getElementById('flagbtn-' + moduleId);
    let dump = document.getElementById('dump-' + moduleId);
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
            dump.style.display = 'inline';
            let listDump = '<ul style="width: 100%">';
            for (let i = 0; i < data.length; i++){
                listDump += '<li>' + data[i] + '</li>';
            }
            listDump += '</ul>';
            dump.innerHTML = listDump;
        }
    });
}