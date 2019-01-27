<?php
require_once "General.php";

$loggedInUsername = Cookie::decryptCookie();

//CSRF token
session_start();
$length = 32;
$_SESSION['token'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length); 


if (!$loggedInUsername) {
    Logger:log("Unauthorized forum access attempt, but was redirected because decryptCookie returned false");
    header("Location: index.php");
    exit;
} else {
    Logger::log($loggedInUsername . " accessed the forum");
}

//register CSS files
$res = "<head>";
$res .= "<link type='text/css' rel='stylesheet' href='assets/css/forum.css' />";
$res .= "<link rel='stylesheet' type='text/css' href='assets/css/mui.css' />";
$res .= "<link href='assets/css/hub.css' rel='stylesheet' type='text/css' />";
$res .= "</head>";
echo $res;

//TODO: wss zijn we onderhevig aan DB connection DoS?? -> toch weinig aan te doen en buiten project scope dus ¯\_(ツ)_/¯
$db = DbController::getDbInstance();

$res = "<header class='mui-appbar'>";
$res .= "<table width='100%'>";
$res .= "<tr style='vertical-align:middle;'>";
$res .= "<td align='left'><a href='forum.php'>Home</a></td>";
$res .= "<td><a href='chat.php'>Go talk to our bot!</a></td>";
$res .= "<td><a href='feedback.php'>Found a vulnerability?</a></td>";
$res .= "<td class='mui--appbar-height' align='right'>";
$userProfilePicture = $db->getProfilePicture($loggedInUsername);
$res .= "<a href='fileupload.php' class='mui-btn mui-btn--fab' id='profile'>";
if (isset($userProfilePicture->image)) {
    $res .= "<img src='" . $userProfilePicture->image . "' height='55px' width='55px'/></a>";
} else {
    $res .= '<img src="assets/media/avatar_placeholder"/></a>';
}
$res .= "</td>";
$res .= "</tr>";
$res .= "</table>";
$res .= "</header>";
$res .= "<button class='mui-btn mui-btn--primary' id='logout'>Logout</button>";
echo $res;

//display add post textarea form
//TODO: sanitize
$res = "<form action='forumpostcontroller.php' method='post'>";
$res .= "<textarea cols='60' rows='8' name='forumpost' placeholder='Post something!'></textarea>";
$res .= "<br>";
$res .= "<input type='text' class='hidden' name='token' value='".$_SESSION['token']."'/>";
$res .= "<input type='submit' value='Submit'>";
$res .= "</form>";
echo $res;

//fetch & display forum contents
$forumPosts = $db->getAllForumPosts();
foreach ($forumPosts as $forumPost) {
    if (!$forumPost->deleted) {
        $profilePicture = $db->getProfilePicture($forumPost->username);
        // echo "<pre>".print_r($profilePicture, true)."</pre>";
        $res = "<div class='forumpost'>";

        if (isset($profilePicture->image)) {
            $res .= "<img src='" . $profilePicture->image . "' height='75px' width='75px'/>";
        }

        $res .= "<h3>" . SanitiseHTMLDisplay::sanitiseInput($forumPost->username) . " wrote:</h3>";
        $res .= "<p>Post ID: " . $forumPost->post_id . "</p>";
        $res .= "<p class='js-forum-post' id='post" . $forumPost->post_id . "'>" . SanitiseHTMLDisplay::sanitiseInput($forumPost->message) . "</p>";

        $res .= "<p>at " . $forumPost->timestamp . "</p>";
        //if comment author matches logged in user -> display edit and remove options
        if ($loggedInUsername == $forumPost->username || $db->canAdministrate($loggedInUsername)) {
            $res .= "<p>";
            $res .= "<a href='#' class='editpost' id=" . "$forumPost->post_id" . ">Edit</a>";
            $res .= " || ";
            $res .= "<a href='#' class='deletepost' id=" . "$forumPost->post_id" . ">Delete</a>";
            $res .= "</p>";
        }
        $res .= "</div>";
        $res .= "<hr/>";
        echo $res;
    }
}

//register JS files
$res = "<script type='text/javascript' src='assets/js/forum.js'></script>";
echo $res;

