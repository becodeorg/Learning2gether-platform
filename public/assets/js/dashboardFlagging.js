function checkModule(moduleId) {
    let overlay = document.getElementById('overlay');
    let url = Routing.generate('api_module', { module: moduleId }, true);
    let btn = document.getElementById('flagbtn-' + moduleId);
    let dump = document.getElementById('dump-' + moduleId);
    let publish = document.getElementById('publish-' + moduleId);
    overlay.style.display = "none";
    dump.style.display = 'none';
    btn.innerText = '';
    btn.classList.remove('badge', 'badge-primary', 'badge-danger', 'spinner-border', 'spinner-border-sm');
    btn.classList.add('spinner-border', 'spinner-border-sm');
    fetch(url)
        .then(function (response) {
            return response.json();
        }).then(function (data) {
            if (data.length) {
                btn.classList.remove('spinner-border', 'spinner-border-sm');
                btn.classList.add('badge', 'badge-danger');
                btn.innerText = data.length + ' problems found!';
                dump.style.display = 'inline';
                overlay.style.display = 'block';
                let listDump = '<ul style="width: 100%">';
                for (let i = 0; i < data.length; i++) {
                    listDump += '<li>' + data[i] + '.&nbsp</li>';
                }
                listDump += '</ul>';
                dump.innerHTML = listDump;
            } else {
                btn.classList.remove('spinner-border', 'spinner-border-sm');
                btn.classList.add('badge', 'badge-primary');
                btn.innerText = 'No errors found';
                let publishUrl = Routing.generate('publish_module', { module: moduleId }, true);
                publish.innerHTML = '<a id="publishLink" class="flagbtn badge badge-success">Click here to publish!</a>';
                let publishLink = document.getElementById('publishLink');
                publishLink.setAttribute('href', publishUrl);
            }
        });
}
