function loadCategories() {
    fetch("/book/categories", { headers: { response: "application/json" } })
        .then(response => { return response.json() })
        .then(data => populateSelect(document.getElementById('category'), data));
}

function loadAuthors() {
    fetch("/book/authors", { headers: { response: "application/json" } })
        .then(response => { return response.json() })
        .then(data => populateSelect(document.getElementById('author'), data));
}


function loadRoles() {
    fetch("/user/roles", { headers: { response: "application/json" } })
        .then(response => { return response.json() })
        .then(data => populateSelect(document.getElementById('role'), data));
}

function populateSelect(target, data) {
    for (var i = 0; i < data.length; i++) {
        target.innerHTML = target.innerHTML +
            '<option value="' + data[i]['code'] + '">' + data[i]['value'] + '</option>';
    }
}