function loadUserDetails(userId) {
    fetch("/user/id/" + userId, { headers: { response: "application/json" } })
        .then(response => { return response.json() })
        .then(data => {
            updateUserDetails(document.getElementById('userdetails'), data)
            document.getElementById('userId').value = userId;
        });
}

function loadBookDetails(bookId) {
    fetch("/book/id/" + bookId, { headers: { response: "application/json" } })
        .then(response => { return response.json() })
        .then(data => {
            updateBookDetails(document.getElementById('bookdetails'), data)
            document.getElementById('bookId').value = bookId;
        });
}

function updateUserDetails(elem, data) {
    let value = ""; //data['userName'];
    if (data['condition'] == false) {
        elem.innerHTML = "<span style='color:red'>This user already lent maximum number of books (" + data['lent'] + " books)</span>"
        document.getElementById('user-condition').value = 0;
    } else {
        value += data['fullName'];
        value += "<br>" + data['mobile'];
        value += "<br>" + data['email'];
        value += "<br><span style='color:red'>lent books " + data['lent'] + "</span>"
        elem.innerHTML = value;
        document.getElementById('user-condition').value = 1;
    }
}

function updateBookDetails(elem, data) {
    let value = '';
    let imgContainer, txtContainer, imgElem;
    elem.innerHTML = '';
    if (data['available'] == 0) {
        elem.innerHTML = "<span style='color:red'>This book is not available</span>";
        document.getElementById('book-condition').value = 0;
    } else {
        imgContainer = document.createElement('div');
        imgContainer.setAttribute('class', 'img-wrapper');
        txtContainer = document.createElement('div');
        txtContainer.setAttribute('class', 'text-wrapper');
        imgElem = document.createElement('img');
        imgElem.src = "/upload/book/" + data['coverPic'];
        value += data['name'];
        value += "<br>location: " + data['location'];
        value += "<br>" + data['publication'];
        available = data['available'] == 0 ? 'no' : data['available'];
        available += data['available'] == 1 ? ' copy' : ' copies';
        value += "<br>" + available + " available";
        txtContainer.innerHTML = value;
        imgContainer.appendChild(imgElem);
        elem.appendChild(imgContainer);
        elem.appendChild(txtContainer);
        document.getElementById('book-condition').value = 1;
    }
}