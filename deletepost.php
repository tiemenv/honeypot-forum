<?php
require_once "General.php";

Banhammer::checkNumberOfRequests();
$loggedInUsername = Cookie::decryptCookie();



$postData = file_get_contents('php://input');
$postDataAsJson = json_decode(stripslashes($postData));

if (!isset($postDataAsJson->id)) {
    Logger::log("Unauthorized access attempt to deletepost.php");
    header("Location: index.php");
    exit;
}

$db = DbController::getDbInstance();
//TODO: CSRF vulnerable!

//TODO: check if integer, else redirect and exit
$postId = $postDataAsJson->id;

//if id is equal to postId, delete post
$post = $db->getForumPost($postDataAsJson->id);


if ($post->username == $loggedInUsername || $db->canAdministrate($loggedInUsername)) {  
    $db->deleteForumPost($postId);
    Logger::log($post->username . " successfully removed post with ID " . $postId);
    header("Refresh:0");
    exit;
} else {
    Logger::log($loggedInUsername . " tried to remove post with ID " . $postId. " but failed");
    header("Location: index.php");
    exit;
}
