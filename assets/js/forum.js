"use strict";

document.addEventListener("DOMContentLoaded", init);

function init() {
  let editLinks = document.querySelectorAll(".editpost");
  let deleteLinks = document.querySelectorAll(".deletepost");
  for (let i = 0; i < editLinks.length; i++) {
    editLinks[i].addEventListener("click", editPost);
  }
  for (let i = 0; i < deleteLinks.length; i++) {
    deleteLinks[i].addEventListener("click", deletePost);
  }
  document.querySelector("#logout").addEventListener('click', logout);
}

function editPost(e) {
  e.preventDefault();
  let postId = e.currentTarget.getAttribute("id");
  let originalMessageContainer = document.querySelector("#post" + postId + ".js-forum-post");
  let originalMessage = originalMessageContainer.innerHTML;
  console.log("original Message: ", originalMessage);
  originalMessageContainer.innerHTML = "<form action='editpost.php' method='post'><input type='text' name='postId' value='" + postId + "' class='hidden'><textarea cols='40' rows='6' name='editpost'>" + originalMessage + "</textarea><br><input type='submit' value='Edit'></form>";
}

function deletePost(e) {
  e.preventDefault();
  let postId = e.currentTarget.getAttribute("id");
  fetch("deletepost.php", {
    headers: {
      'Accept': 'application/json',
      'Content-type': 'application/json'
    },
    method: "POST",
    body: JSON.stringify({ "id": postId })
  })
    .then(function (res) {

      window.location.reload();
    })
}

function logout() {
    let cookies = document.cookie.split(";");

    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i];
        let eqPos = cookie.indexOf("=");
        let name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }

    window.location.reload();

}