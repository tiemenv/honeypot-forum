<?php
require_once 'General.php';
Banhammer::checkNumberOfRequests();


//TODO: unsafe, data should be cleaned
if (!isset($_POST["username"]) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["confirmpassword"])) {
    Logger::log("registercontroller.php post with wrong post data");
    header("Location: register.php");
    exit;
} else {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordConfirm = $_POST["confirmpassword"];
}

//db instance
$db = DbController::getDbInstance();

//check if user or email exists already
$allUsers = $db->getAllUsernamesAndEmails();
foreach ($allUsers as $user) {
    if ($username === $user->username) {
        echo "user already exists!";
        Logger::log("Someone tried to make another user with name " . $username);
        exit;
    } else if ($email === $user->email) {
        echo "email already registered!";
        Logger::log("Someone tried to make another user with email " . $email);
        exit;
    }
}

//are the two passwords equal?
if ($password !== $passwordConfirm) {
    echo "passwords not equal!";
    header("Location: register.php");
    exit;
} else {
    //hash password and send info to db
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $db->addUser($username, $email, $hashedPassword);
    Logger::log("User registered: " . $username);
    //redirect to login
    header("Location: index.php");
    exit;
}
