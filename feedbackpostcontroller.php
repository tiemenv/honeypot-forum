<?php
require_once "General.php";

//no IP check required since there's a captcha implemented
// Banhammer::checkNumberOfRequests();
//db instance
$db = DbController::getDbInstance();
$ipAddress = $_SERVER['REMOTE_ADDR']; //CAN EASILY BE FAKED

//CSRF validation
session_start();
if ($_SESSION['token']!=$_POST['token']) {
  Logger::log("CSRF token didn't match on feedbackpostcontroller!");
  header("Location: index.php");
  exit;
} 

$honeypot = false;
if (!empty($_REQUEST['contact_me_by_fax_only']) && (bool) $_REQUEST['contact_me_by_fax_only'] == true) {
    $honeypot = true;
    //THIS IS A BOT OR SOMEONE MESSING AROUND
    echo "<p>Don't try to break things!</p>";
    Logger::log("In feedbackpostcontroller, honeypot=true got triggered");
} else {
    //process as normal
    if (!isset($_POST)) {
        echo "empty POST";
        //THE FORM WAS EMPTY
        echo "<p>No data was found</p>";
        Logger::log(Cookie::decryptCookie() . " tried to submit empty feedback form");

        exit;
    } else {
        //CHECK IF CAPTCHA
        $post_data = http_build_query(
            array(
                'secret' => "6LemDHsUAAAAAA4d5NzZcPSuq3ucXh0VGIJDKr9b",
                'response' => $_POST['g-recaptcha-response'],
                'remoteip' => $_SERVER['REMOTE_ADDR'],
            )
        );
        $opts = array('http' => array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $post_data,
        ),
        );
        $context = stream_context_create($opts);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $result = json_decode($response);
        if (!$result->success) {
            //CAPTCHA FAILED OR EMPTY
            echo "<p>Failed, don't forget to verify you're not a robot</p>";
            echo "<a href='feedback.php'>Go back</a>";
            Logger::log(Cookie::decryptCookie() . " tried to submit feedback form without completing Captcha");
        } else {
            $feedbackType = $_POST["type"];
            $feedbackMessage = $_POST["feedback"];
            $name = $_POST["name"];
            $email = $_POST["email"];
            $db->addFeedback($name, $email, $feedbackType, $feedbackMessage);
            echo "<h2>Your feedback has been sent, thank you!</h2>";
            echo "<a href='forum.php'>Go back to the forum</a>";
            Logger::log(Cookie::decryptCookie() . " submitted feedback");
        }
    }
}

