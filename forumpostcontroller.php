<?php
require_once "General.php";

Banhammer::checkNumberOfRequests();
$username = Cookie::decryptCookie();

session_start();
if ($_SESSION['token']!=$_POST['token']) {
  Logger::log("CSRF token didn't match on forumpostcontroller!");
  echo "CSRF detected, logging your ip: ". $_SERVER['REMOTE_ADDR'];
  echo "<br>False positive? Please file a bug report with <a href='feedback.php'>the feedback form.</a>";
  exit;
} 

if (!$username) {
    Logger::log("Unauthorized attempt to connect to forumpostcontroller.php");
    header("Location: index.php");
    exit;
}

//db instance
$db = DbController::getDbInstance();

//TODO: unsafe, data should be cleaned
if (!isset($_POST['forumpost'])) {
    //redirect and exit thread
    Logger::log(Cookie::decryptCookie() . " tried to submit empty post");
    header("Location: forum.php");
    exit;
} else {
    //TODO: should check if these fields contain smth valid
    $forumPostText = $_POST["forumpost"];
    $db->addForumPost($username, $forumPostText);
    Logger::log($username . " posted on forum: " . $forumPostText);
    header("Location: forum.php");
    exit;
}
