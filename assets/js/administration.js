document.addEventListener('DOMContentLoaded', init);
let activeRow;

function init() {
    document.querySelector("#deleteUser").addEventListener("click", deleteUser);
    document.querySelector("#addUser").addEventListener("click", addUser);
    addRowHandlers();
}

function addRowHandlers() {
    let table = document.getElementById("tableId");
    let rows = table.getElementsByTagName("tr");
    for (let i = 0; i < rows.length; i++) {
        rows[i].addEventListener('click', setActive);
    }
}

function setActive(e) {
    e.target.parentNode.classList.add("activeRow");
    if (activeRow !== undefined) {
        activeRow.classList.remove("activeRow");
    }
    activeRow = e.target.parentNode;

}

function deleteUser() {
    let userId = parseInt(activeRow.childNodes[0].id);
    console.log(userId);
    fetch("deleteuser.php", {
        headers: {
            'Accept': 'application/json',
            'Content-type': 'application/json'
        },
        method: "POST",
        body: JSON.stringify({ "id": userId })
    })
        .then(function (res) {
            window.location.reload();
        })
}

function addUser() {
    let formData = new FormData();
    let email = prompt("Give email please");
    formData.append('email', email);
    let username = prompt("Give username please");
    formData.append('username', username);
    let password = prompt("Give password please");
    formData.append('password', password);
    formData.append('confirmpassword', password);

    fetch("registercontroller.php", {
        method: "POST",
        body: formData
    })
        .then(function (res) {
            window.location.reload();
        })
}

