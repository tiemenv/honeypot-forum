<?php
require_once "General.php";
//No IP timeout/ban check here because falsely banned users should still be able to appeal their bans, and the feedback form is protected by a captcha anyways
// Banhammer::checkNumberOfRequests();
Logger::log("index.php connection");
?>

<head>
    <link rel="stylesheet" href="assets/css/mui.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css" />
    <link type="text/css" rel="stylesheet" href="assets/css/screen.css" />
    <link type="text/css" rel="stylesheet" href="assets/css/style.css" />
</head>

<main class="mui-container">
<?php
$res = "<a class='mui-btn mui-btn--primary' href='login.php'>Login</a>";
$res .= "<br/>";
$res .= "<a class='mui-btn mui-btn--primary' href='register.php'>Register</a>";
$res .= "<br/>";
$res .= "<a class='mui-btn mui-btn--primary' href='forum.php'>Forum (Only for logged in users)</a> ";
$res .= "<br/>";
$res .= "<a class='mui-btn mui-btn--primary' href='halloffame.php'>Hall of Fame - A thanks to all the pentesters making this website secure</a> ";
echo $res;
?>
</main>
