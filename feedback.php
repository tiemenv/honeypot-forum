<?php
require_once "General.php";

Logger::log("feedback.php access");

$loggedInUsername = Cookie::decryptCookie();
$db = DbController::getDbInstance();
$userProfilePicture = $db->getProfilePicture($loggedInUsername)
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Send us feedback</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"/>
    <link rel="stylesheet" href="assets/css/mui.css"/>
    <link rel="stylesheet" href="assets/css/hub.css"/>
    <link rel="stylesheet" href="assets/css/feedback.css"/>
    <script src='https://www.google.com/recaptcha/api.js?hl=en'></script>

</head>
<body>
<header class="mui-appbar">
    <table width="100%">
        <tr style="vertical-align:middle;">
            <td align="left"><a href="forum.php">Home</a></td>
            <td><a href="chat.php">Go talk to our bot!</a></td>
            
            <td class="mui--appbar-height" align="right">
                <?php
                if (isset($userProfilePicture->image)) {
                    echo "<a href='fileupload.php' class='mui-btn mui-btn--fab' id='profile'><img src='" . $userProfilePicture->image . "' height='55px' width='55px'/></a>";
                } else {
                    $res .= "<a href='fileupload.php' class='mui-btn mui-btn--fab' id='profile'><img src='assets/media/avatar_placeholder'/></a>";
                }?>
            </td>
        </tr>
    </table>
</header>
<?php if($loggedInUsername){
 echo "<button class='mui-btn mui-btn--primary' id='logout'>Logout</button>";
} ?>
<main class="mui-container">
<?php 
//CSRF protection
session_start();
$length = 32;
$_SESSION['token'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length); 
?>
<form action="feedbackpostcontroller.php" class="feedbackform" method="post">
<div id="responsibledisclosure">
<p>
Found a vulnerability and/or bug? We would love to hear! Here at group 10, we care for security and appreciate any help we can get. Your report will get processed as soon as possible and you can earn your very own spot in our Hall of Fame!</p>
</div>
    <div class="feedbackRadio">
        <p class="title" id="feedbackType">Feedback type: </p>
        <input type="radio" name="type" value="comment" id="comment" >
        <label for="comment">Comment</label>

        <input type="radio" name="type" value="question" id="question">
        <label for="question">Question</label>

        <input type="radio" name="type" value="bug" id="bug">
        <label for="bug">Bug report</label>

        <input type="radio" name="type" value="disclosure" id="disclosure" checked>
        <label for="other">Responsible disclosure</label>
    </div>
    <input type="text" name="name" id="name" placeholder="Your name"><br>
    <input type="text" name="email" id="email" placeholder="How can we contact you?"><br>
    <label class="title" for="feedback">Write your feedback here:</label><br>
    <textarea name="feedback" id="feedback" cols="80" rows="8" required></textarea>
    <input type="checkbox" name="contact_me_by_fax_only" value="1" style="display:none !important" tabindex="-1" autocomplete="off">
    <input type="text" class='hidden' name="token" value="<?=$_SESSION['token']?>"/>
    <div class="g-recaptcha" data-sitekey="6LemDHsUAAAAAJ4CPFkB9HmIuL0_rVDVx1-kdHVw"></div>

    <input type="submit" value="Submit">

</form>
</main>
<script src='assets/js/feedback.js'></script>
</body>
</html>
