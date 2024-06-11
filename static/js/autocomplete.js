function autocomplete(inputElem, destination, url, callFun, hiddenTag, isDiv = false) {
    var currentFocus;
    var divValue = inputElem.innerHTML;
    var spans = document.getElementsByClassName('removeItems');
    var onclickListener = function(event) {
        let remove = event.target.getAttribute('data-id') + ",";
        let iElem = hiddenInput == undefined ? event.target.parentElement.parentElement.getElementsByTagName('input')[0] : hiddenInput;
        let authors = iElem.value;
        iElem.value = authors.replace(remove, "");
        event.target.parentElement.remove();
        divValue = destination.innerHTML;
    };
    var hiddenInput = document.getElementById(hiddenTag);
    for (const span of spans) {
        span.addEventListener('click', onclickListener);
    }
    inputElem.addEventListener('input', function(e) {
        var divElem, innerDiv, i;
        var val = isDiv ? this.innerText.split(" X ") : this.value;
        val = Array.isArray(val) ? val[val.length - 1].trim() : val;
        closeList();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        divElem = document.createElement("div");
        divElem.setAttribute("class", "autocomplete-items");
        divElem.setAttribute("id", this.id + "autocomplete-list");
        this.parentNode.appendChild(divElem);
        let selected = hiddenInput == null ? (destination == null ? "" : ("/" + destination.getElementsByTagName('input')[0].value)) : "/" + hiddenInput.value;
        fetch(url + val + selected, { headers: { response: "application/json" } })
            .then(response => { return response.json() })
            .then(data => {
                for (i = 0; i < data.result.length; i++) {
                    innerDiv = document.createElement("div");
                    innerDiv.innerHTML += data.result[i]['value'];
                    innerDiv.innerHTML += "<input type='hidden' value='" + data.result[i]['code'] + "'>";
                    innerDiv.innerHTML += "<input type='hidden' value='" + data.result[i]['value'] + "'>";
                    innerDiv.addEventListener("click", function(e) {
                        let selectedValues = selected;
                        let dataCode = this.getElementsByTagName("input")[0].value;
                        let dataValue = this.getElementsByTagName("input")[1].value;
                        let code;
                        let newDiv;
                        if (destination != null) {
                            if (isDiv) {
                                destination.innerHTML = divValue;
                            } else {
                                inputElem.value = "";
                            }
                            if (!selectedValues.includes(dataCode + ",")) {
                                code = '<span contenteditable="false" class="list-group-item" id="list-group-item-' + dataCode + '" data-value="' + dataCode + '">' + dataValue + ' <span class="badge removeItems" id="removeItem-' + dataCode + '" data-id="' + dataCode + '">X</span></span>';
                                code += isDiv ? '&nbsp' : ''
                                destination.innerHTML += code;
                                if (hiddenInput) {
                                    hiddenInput.value += dataCode + ",";
                                } else {
                                    destination.getElementsByTagName('input')[0].value += dataCode + ",";
                                }
                                spans = document.getElementsByClassName('removeItems');
                                for (const span of spans) {
                                    span.addEventListener('click', onclickListener);
                                }
                            }
                            divValue = destination.innerHTML;
                            if (callFun != null)
                                callFun(dataCode, dataValue);
                        } else {
                            inputElem.value = dataValue;
                            if (callFun != null)
                                callFun(dataCode, dataValue);
                        }
                        closeList();
                    });
                    divElem.appendChild(innerDiv);
                }

            });
    });
    inputElem.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            currentFocus++;
            addActive(x);
        } else if (e.keyCode == 38) {
            currentFocus--;
            addActive(x);
        } else if (e.keyCode == 13) {
            e.preventDefault();
            if (currentFocus > -1) {
                if (x) {
                    x[currentFocus].click();
                }
            }
        }
    });

    function addActive(x) {
        if (!x) {
            return false;
        }
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeList() {
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            x[i].parentNode.removeChild(x[i]);
        }
    }
    document.addEventListener("click", function(e) {
        closeList();
    });

}

function removeItem(event, hiddenId) {
    let remove = event.target.getAttribute('data-id') + ",";
    let iElem = hiddenId == undefined ? event.target.parentElement.parentElement.getElementsByTagName('input')[0] : document.getElementById(hiddenId);
    let authors = iElem.value;
    iElem.value = authors.replace(remove, "");
    event.target.parentElement.remove();
}


// function removeItem(event, elem) {
//     let remove = event.target.getAttribute('data-id') + ",";
//     let authors = event.target.parentElement.parentElement.getElementsByTagName('input')[0].value;
//     event.target.parentElement.parentElement.getElementsByTagName('input')[0].value = authors.replace(remove, "");
//     event.target.parentElement.remove();
// }