<?php
include_once "General.php";

Banhammer::checkNumberOfRequests();

$loggedInUsername = Cookie::decryptCookie();

if(!$loggedInUsername){
    Logger::log("Unauthorized connection attempt to editpost.php");
    header("Location: index.php");
    exit;
}

$db = DbController::getDbInstance();

$postId = $_POST["postId"];
$message = $_POST["editpost"];

//if id is equal to postId, delete post
$post = $db->getForumPost($postId);


if ($post->username == $loggedInUsername || $db->canAdministrate($loggedInUsername)) {
    $db->editForumPost($postId, $message);
    Logger::log($post->username . " edited post with ID " . $postId);
    header("Location: forum.php");
    exit;
} else {
    Logger::log(Cookie::decryptCookie() . " tried to edit post with ID " . $postId." but failed");
    header("Location: forum.php");
    exit;
}
