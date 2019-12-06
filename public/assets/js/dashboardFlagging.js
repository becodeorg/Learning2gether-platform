function checkModule(moduleId) {

    let url = Routing.generate('api_module', {module: moduleId}, true);
    console.log(url);

    fetch(url)
        .then(function (response) {
            return response.json();
        }).then(function (data) {
        console.log(data);
    });
    console.log(url)
}