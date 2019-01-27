<?php
require_once 'General.php';
Banhammer::checkNumberOfRequests();
$loggedInUsername = Cookie::decryptCookie();

if (!$loggedInUsername) {
    Logger::log("Unauthorized attempt to connect to chat.php");
    header("Location: login.php");
    exit;
} else {
    Logger::log($loggedInUsername." is connecting to chat.php");
}

$loggedInUsername = Cookie::decryptCookie();
$db = DbController::getDbInstance();
$userProfilePicture = $db->getProfilePicture($loggedInUsername)
?>
<html>
<head>
    <title>WebSocket</title>

    <style type="text/css">
        #log {
            width:600px;
            height:300px;
            border:1px solid #7F9DB9;
            overflow:auto;
            padding:10px;
        }
        #msg {
            width:300px;
        }
    </style>
    <link href="assets/css/mui.css" type="text/css" rel="stylesheet" />
    <link href="assets/css/hub.css" type="text/css" rel="stylesheet" />
    <link href="assets/css/chat.css" type="text/css" rel="stylesheet" />
</head>

<body>

<header class="mui-appbar">
    <table width="100%">
        <tr style="vertical-align:middle;">
            <td align="left"><a href="forum.php">Home</a></td>
            <td><a href="chat.php">Go talk to our bot!</a></td>
            <td><a href="feedback.php">Found a vulnerability?</a></td>
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
<button id="logout" class="mui-btn mui-btn--primary" onclick="logout()">Logout</button>
<main>
<h3>Chat to our bot!</h3>

<div id="log"></div>

Enter Message <input id="msg" type="textbox" onkeypress="onkey(event)"/>

<button onclick="send()">Send</button>
<button onclick="quit()">Quit</button>
<button onclick="reconnect()">Reconnect</button>
</main>
<script type="text/javascript" src="assets/js/chat.js"></script>

</body>
</html>
