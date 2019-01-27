<?php
require_once "General.php";

Banhammer::checkNumberOfRequests();

$userData = file_get_contents('php://input');
$postDataAsJson = json_decode(stripslashes($userData));

if (!isset($postDataAsJson->id)) {
    Logger::log("Unauthorized access attempt to deleteuser.php");
    header("Location: index.php");
    exit;
}

$db = DbController::getDbInstance();

$userId = $postDataAsJson->id;

//if id is equal to postId, delete post
$user = $db->getUserById($userId);



if ($db->canAdministrate(Cookie::decryptCookie())) {
    echo "deleting user";
    $db->deleteUser($userId);
    Logger::log(Cookie::decryptCookie() . " removed user with ID " . $userId);
    exit;
} else {
    Logger::log(Cookie::decryptCookie() . " tried to remove user with ID " . $userId." but failed");
}
