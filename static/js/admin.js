function deleteItem(delUrl, title) {
    AskConfirm("Are sure to delete..?", () => fetch(delUrl, {
            method: 'DELETE',
            headers: {
                response: "application/json"
            }
        })
        .then(response => { return response.json() })
        .then(data => {
            if (data.result == 1) {
                parts = delUrl.split("/");
                // document.getElementById(parts[parts.length - 1]).remove();
                title = (title == undefined) ? "item" : title;
                loadTable();
                toast("The " + title + " was deleted..!", 'success');
            } else {
                msg = data.msg != undefined ? data.msg : "Unable to delete the item..!";
                toast(msg, 'danger', 'Failed');
            }
        }));
}


function MarkasReturn(id) {
    data = {
        "action": "markReturned"
    }
    fetch('/issued-book-management/issued-books/' + id, {
            method: 'PUT',
            headers: {
                response: "application/json",
            },
            body: JSON.stringify(data)
        })
        .then(response => { return response.json() })
        .then(data => {
            if (data.result == 1) {
                // document.getElementById(id).remove();
                toast("Success..!", 'success');
                loadTable();
            } else {
                toast("Failed..!", 'danger', 'Failed');
            }
        });
}

function editItem(editUrl, element = "editRecord") {
    fetch(editUrl, { headers: { response: "application/json" } })
        .then(response => { return response.json() })
        .then(data => {
            openModal(element);
            for (const key in data.data) {
                document.getElementById("edit-" + key).value = data.data[key];
            }
        });
}

function changeStatus(event, statusChangeUrl) {
    status = event.target.checked ? 1 : 0;

    data = {
        "status": status
    }
    fetch(statusChangeUrl, {
            method: 'PUT',
            headers: {
                response: "application/json",
            },
            body: JSON.stringify(data)
        })
        .then(response => { return response.json() })
        .then(data => {
            if (data.result == 1) {
                toast("The status was updated..!", 'success');
            } else {
                event.target.checked = !(event.target.checked);
                toast("Unable to upate the status..!", 'danger', 'Failed');
            }
        });
}
var loadTable;

function loadTableData(id, url, columns) {
    loadTable = function() {
        studentTable = jQuery('#' + id).dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 10,
            'destroy': true,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 10,
            "bProcessing": true,
            "bServerSide": true,
            "rowId": "id",
            "sAjaxSource": url,
            "columns": columns,
            "ordering": false
        });
    }
    loadTable();
}

function changeReport(url) {
    let list = document.getElementById('list').value;
    let sDate = document.getElementById('sDate').value;
    let eDate = document.getElementById('eDate').value;
    if (new Date(sDate) > new Date(eDate)) {
        alert("Invalid date range end date should be greater than start date");
        return;
    } else if (new Date(eDate) > new Date) {
        alert("Future dates are not allowed to generate report");
        return;
    }
    sDate = sDate == '' ? '0000-00-00' : sDate;
    url = url + "/" + list + '?sDate=' + sDate + '&eDate=' + eDate;
    window.location.replace(url);
}