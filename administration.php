<?php
require_once "General.php";

Banhammer::checkNumberOfRequests();
$username = Cookie::decryptCookie();

//TODO: Is started a DbController instance before checking if user is authorized, is this safe?
$db = DbController::getDbInstance();

if ($username && $db->canAdministrate($username)) {
    Logger::log($username . " accessed admin page");

    $allUsers = $db->getAllUsers();

    class TableRows extends RecursiveIteratorIterator
    {
        public function __construct($it)
        {
            parent::__construct($it, self::LEAVES_ONLY);
        }

        public function current()
        {
            return "<td style='width: 150px; border: 1px solid black;'>" . parent::current() . "</td>";
        }

        public function beginChildren()
        {
            echo "<tr>";
        }

        public function endChildren()
        {
            echo "</tr>" . "\n";
        }
    }
    $loggedInUsername = Cookie::decryptCookie();
    $userProfilePicture = $db->getProfilePicture($loggedInUsername)

    ?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/css/mui.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/hub.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/administration.css" rel="stylesheet" type="text/css" />
</head>
<body>
<header class="mui-appbar">
    <table width="100%">
        <tr style="vertical-align:middle;">
            <td align="left"><a href="forum.php">Home</a></td>
            <td><a href="chat.php">Go talk to our bot!</a></td>
            <td><a href="feedback.php">Give feedback</a></td>
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
<main class="mui-container">
    <div class="mui-panel" id="usermanagement">
        <div>
        <button class="mui-btn mui-btn--primary" id="deleteUser">Delete user</button>
        <button class="mui-btn mui-btn--primary" id="addUser">Add user</button>
        <!-- Users here -->
            <table class="mui-table mui-table--bordered" id="tableId">
            <thead>
            <tr>
                <th>UserID</th>
                <th>Username</th>
                <th>e-mail</th>
            </tr>
            </thead>
                <?php
foreach ($allUsers as $user) {
        if (!$user->deleted) {
            echo "<tr>";
            echo "<td id='" . $user->user_id . "'>" . $user->user_id . "</td>";
            echo "<td>" . SanitiseHTMLDisplay::sanitiseInput($user->username) . "</td>";
            echo "<td>" . SanitiseHTMLDisplay::sanitiseInput($user->email) . "</td>";
            echo "</tr>";
        }
    }
    ?>
            </table>
        </div>
    </div>
    <div class="mui-panel" id="feedbackdisplay">
        <table class="mui-table mui-table--bordered" id="feedbackTable">
            <thead>
            <tr>
                <th>FeedbackID</th>
                <th>By</th>
                <th>Type</th>
                <th>Message</th>
                <th>Time</th>
            </tr>
            </thead>
            <tbody>
        <?php
$allFeedback = $db->getAllFeedback();
    foreach ($allFeedback as $feedback) {
        echo "<tr>";
        echo "<td id='" . $feedback->idfeedback . "'>" . $feedback->idfeedback . "</td>";
        echo "<td>" . SanitiseHTMLDisplay::sanitiseInput($feedback->name) . "</td>";
        echo "<td>" . SanitiseHTMLDisplay::sanitiseInput($feedback->feedbackType) . "</td>";
        echo "<td>" . SanitiseHTMLDisplay::sanitiseInput($feedback->feedbackMessage) . "</td>";
        echo "<td>" . $feedback->timestamp . "</td>";
    }
    ?>
            </tbody>
        </table>
    </div>
</main>
<script src="assets/js/administration.js" type="text/javascript"></script>
</body>
</html>
<?php

} else {

    echo "<img src='assets/media/easy.gif' height='500px' width='500px'></img>";
    Logger::log(Cookie::decryptCookie() . " tried to access admin page");
    exit;
}?>