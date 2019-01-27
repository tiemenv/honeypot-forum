<?php
require_once "General.php";
Banhammer::checkNumberOfRequests();


//TODO: unsafe, data should be cleaned
if (!isset($_POST["username"]) || !isset($_POST["password"])) {
    Logger::log("login attempt with wrong POST data");
    header("Location: login.php");
    exit;
} else {
    //TODO: should check if these fields contain smth valid
    $username = $_POST["username"];
    $password = $_POST["password"];
}

//db instance
$db = DbController::getDbInstance();

//check if hashed pw is correct for the given username
$userObject = $db->getUserByUsername($username);

if (password_verify($password, $userObject->password)) {
    setUserCookie($userObject->username);
} else {
    Logger::log("Wrong password entered for username " . $userObject->username);
    header("Location: login.php");
    exit;
}

function setUserCookie($username)
{
    $cookieExpiryTime = 3600;
    //concatenates username, timestamp into a cleartext string which it encrypts with AES256 and concatenates with the salt ($iv) and sends to the user as login cookie
    $iv = bin2hex(openssl_random_pseudo_bytes(8, $wasItSecure));
    if ($wasItSecure) {
        $clearString = $username . ";" . (time() + $cookieExpiryTime);
        $encryptedString = openssl_encrypt($clearString, Encrypter::getCipherMethod(), Encrypter::getAesKey(), 0, $iv) . ";" . $iv;
        setcookie("token", $encryptedString, time() + $cookieExpiryTime);
        Logger::log($username . " successfully logged in.");
        header("Location: forum.php");
        exit;
    } else {
        Logger::log("Insecure IV when attempting to encrypt cookie");
        header("Location: login.php");
        exit;
    }

}
