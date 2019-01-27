<?php
require_once "General.php";
Banhammer::checkNumberOfRequests();
Logger::log("login.php connection");
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login!</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css" />
    <link type="text/css" rel="stylesheet" href="assets/css/screen.css" />
    <link type="text/css" rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

<form action="logincontroller.php" method="post" >
    <ul class="items"></ul>
    <fieldset class="username enable">
        <div class="icon left"><i class="arrow"></i></div>
        <input type="text" name="username" placeholder="Username" />
        <div class="icon right button"><i class="arrow"></i></div>
    </fieldset>

    <fieldset class="password">
        <div class="icon left"><i class="lock"></i></div>
        <input type="password" name="password" placeholder="Password" id="password" />
        <div class="icon right button"><i class="lock"></i></div>
    </fieldset>
</form>
<script src="assets/js/login.js"></script>
</body>
</html>
