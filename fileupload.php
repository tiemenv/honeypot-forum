<?php
include_once "General.php";

Banhammer::checkNumberOfRequests();

$username = Cookie::decryptCookie();

if (!$username) {
    Logger::log("Unauthorized connection attempt to fileupload.php");
    header("Location: index.php");
    exit;
}

//TODO: wss zijn we onderhevig aan DB connection DoS??
$db = DbController::getDbInstance();

$res = "<html>";
$res .= "<head>";
$res .= "<link type='text/css' rel='stylesheet' href='assets/css/forum.css' />";
$res .= "<link type='text/css' rel='stylesheet' href='assets/css/mui.css' />";
$res .= "<link href='assets/css/hub.css' rel='stylesheet' type='text/css' />";
$res .= "<body>";
$res .= "<header class='mui-appbar'>";
$res .= "<table width='100%'>";
$res .= "<tr style='vertical-align:middle;'>";
$res .= "<td align='left'><a href='forum.php'>Home</a></td>";
$res .= "<td><a href='chat.php'>Go talk to our bot!</a></td>";
$res .= "<td><a href='feedback.php'>Found a vulnerability?</a></td>";
$res .= "<td class='mui--appbar-height' align='right'>";

$userProfilePicture = $db->getProfilePicture($username);
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
$res .= "<h1>Upload a profile picture!</h1>";
$res .= "<form action='' method='POST' enctype='multipart/form-data'>";
$res .= "<input type='file' name='image' />";
$res .= "<input type='submit'/>";
$res .= "</form>";
$res .= "</body>";
$res .= "</html>";

$errorString = "";
if (isset($_FILES['image'])) {
    $errors = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    if (isset(explode("/", $file_type)[1])) {
        $file_ext = explode("/", $file_type)[1];
    } else {
        $file_ext = "";
    }

    $extensions = array("jpeg", "jpg", "png");

    if (!in_array($file_ext, $extensions)) {
        $errors[] = "Please choose a JPEG or PNG file under 1MB.";
    }

    if (empty($errors) == true) {
        $imageContentBase64 = base64_encode(file_get_contents($file_tmp));
        $imageContent = 'data:image/' . $file_ext . ';base64,' . $imageContentBase64;
        $currentProfilePicture = $db->getProfilePicture($username);

        //let's NOT overwrite the previously set image, attacker could hide his tracks this way, but DB could easily get spammed full. Maybe implement a timer?

        // if (!isset($currentProfilePicture->image)) {
            $db->addProfilePicture($imageContent, $username);
        // } else {
        //     $db->updateProfilePicture($imageContent, $username);
        // }
        Logger::log($username . " uploaded a file");
        header("Location: forum.php");
        exit;
    } else {
        Logger::log($username." caused an error on fileupload.php");
        $errorString = "<span style='color:red'>" . $error . "</span><br>";
    }

}
echo $res;
echo $errorString;
//register JS files
$res = "<script type='text/javascript' src='assets/js/fileupload.js'></script>";
echo $res;
