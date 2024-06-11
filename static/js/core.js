function addBaseURL() {

}

function hasClass(elem, className) {
    return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
}

function addClass(elem, className) {
    if (!hasClass(elem, className)) {
        elem.className += ' ' + className;
    }
}

function removeClass(elem, className) {
    if (hasClass(elem, className)) {
        if (elem.className == className) {
            elem.className = "";
        } else {
            elem.className = elem.className.replace(" " + className, "");
        }
    }
}

function menucontrol() {
    if (hasClass(document.getElementById('menu'), "closed-nav")) {
        removeClass(document.getElementById("menu"), "closed-nav");
        addClass(document.getElementById("open-menu"), "closed-nav");
        removeClass(document.getElementById("close-menu"), "closed-nav");
    } else {
        addClass(document.getElementById("menu"), "closed-nav");
        removeClass(document.getElementById("open-menu"), "closed-nav");
        addClass(document.getElementById("close-menu"), "closed-nav");
    }
}


window.onclick = function(event) {
    let index = event.target.className.indexOf("drop-down");
    if (index < 0) {
        let elems = document.getElementsByClassName("drop-down");
        for (let i = 0; i < elems.length; i++) {
            elems[i].style.display = "none";
        }
    }
};

function toggleClass(element, className) {
    // let element = document.getElementById(name);
    if (element.classList) {
        element.classList.toggle(className);
    } else {
        let classes = element.className.split(" ");
        let i = classes.indexOf(className);
        if (i >= 0)
            classes.splice(i, 1);
        else
            classes.push(className);
        element.className = classes.join(" ");
    }
}

function openModal(ele, img, captcha) {
    ele = document.getElementById(ele);
    ele.style.display = "block";
    if (img != null) {
        document.getElementById(img).src = captcha;
    }
}

function dropDownMenuClick(ele) {
    ele = document.getElementById(ele);
    if (ele.style.display == "block")
        ele.style.display = "none";
    else
        ele.style.display = "block";
}

function closeModal(ele) {
    document.getElementById(ele).style.display = "none";
}

function AskConfirm(title, ok = function ok() {}, cancel = function cancel() {}, msg = '') {
    var alertWindow = document.createElement("div");
    code = '<div id="modal" class="Askmodal"><div id="alert-modal" class="alert-modal">';
    code = code + '<span class="close-modal" onclick="this.parentNode.parentNode.remove()">&#x2716;</span>';
    code = code + '<h5> ' + title + ' </h5><span> ' + msg + '  </span>';
    code = code + '<div><button class="alert-btn positive" id="alert_confirmed">OK</button><button class="alert-btn negative" id="alert_canceled">Cancel</button>'
    code = code + '</div></div></div>';
    alertWindow.innerHTML = code;
    document.body.appendChild(alertWindow);
    document.getElementById('alert_confirmed').addEventListener("click", function() {
        this.parentNode.parentNode.parentNode.remove();
        ok();
    });
    document.getElementById('alert_canceled').addEventListener("click", function() {
        this.parentNode.parentNode.parentNode.remove();
        cancel();
    });
}

function toast(msg = '', theme = '', title = "") {
    var ele = document.getElementById('toast-container');
    var symbol = '';
    // var title = '';
    switch (theme) {
        case 'info':
            title = title != '' ? title : "Info";
            symbol = '<i class="fa fa-info toast-symbols" aria-hidden="true"></i>';
            break;
        case 'warning':
            title = title != '' ? title : "Warning";
            symbol = '<i class="fa fa-exclamation-triangle toast-symbols" aria-hidden="true"></i>';
            break;
        case 'danger':
            title = title != '' ? title : "Danger";
            symbol = '<i class="fa fa-shield toast-symbols" aria-hidden="true"></i>';
            break;
        case 'success':
            title = title != '' ? title : "Success";
            symbol = '<i class="fa fa-check toast-symbols" aria-hidden="true"></i>';
            break;
        default:
            title = title != '' ? title : "New Message";
            symbol = '<i class="fa fa-bell toast-symbols" aria-hidden="true"></i>';
            break;

    }
    code = '<div class="toast ' + theme + '"><span class="close-toast" onclick="this.parentNode.style.display=\'none\'">&#x2716;</span>'
    code = code + '<h3>' + symbol + title + '</h3>';
    code = code + '<span>' + msg + '</span>';
    code = code + '</div>';
    if (ele == null) {
        var Toast = document.createElement("div");
        Toast.id = "toast-container";
        Toast.innerHTML = code;
        document.body.appendChild(Toast);
    } else {
        ele.innerHTML = ele.innerHTML + code;
    }
    setTimeout(hideToast, 20000);
}

function hideToast() {
    toasts = document.getElementsByClassName('toast');
    toasts[0].remove();
    if (toasts.length > 1) {
        setTimeout(hideToast, 20000);
    }
}

function showElement(id) {
    document.getElementById(id).style.display = 'block';
}

function loaded() {
    document.getElementsByClassName('left-cover')[0].style.left = '100%';
    document.getElementsByClassName('right-cover')[0].style.right = "100% ";
    document.getElementById('loader').className += " loaded";
}
let offset = 12;
let limit = 12;

function loadMoreBooks(event, url, searchKey) {
    url = (url != undefined) ? url : '/books/load';
    url += "?offset=" + offset + "&limit=" + limit;
    url += (searchKey != undefined) ? "&search=" + searchKey : '';
    fetch(url, { headers: { response: "application/json" } })
        .then(response => { return response.json() })
        .then(data => {
            let divElem;
            let target = document.getElementById('books-list');
            if (data.books.length > 0) {
                for (const book of data.books) {
                    divElem = document.createElement('div');
                    code = `<a href="/book/` + book.id + `">
                            <book-element
                                cover="/upload/book/` + book.coverPic + `"
                                book="` + book.name + `"
                                author="` + book.authors + `"
                                id="` + book.id + `">
                            </book-element>
                            </a>
                            <div class="card-content">
                                <h3>` + book.name + `
                                </h3>
                                <div class="text-author">` + book.authors + `
                                </div>
                                <p>` + book.description + `
                                </p>
                                <p>`;
                    code += book.available == 0 ? "no copy" : (('only ' + book.available) + (
                        (book.available == 1) ? " copy" : " copies"
                    ))
                    code += ` available</p>
                                <div class="btn-container">
                                    <a class="btn-link"
                                        href="/book/` + book.id + `">View
                                        Book</a>
                                </div>
                            </div>
                        `;
                    divElem.className = "card cols";
                    divElem.innerHTML = code;
                    target.appendChild(divElem);
                }

            } else {
                // toast("Unabel to fetch data..!", 'danger');
                event.target.remove();
            }
        });
    offset += 12;
}
window.onload = function() {
    setTimeout(loaded(), 10000);
}