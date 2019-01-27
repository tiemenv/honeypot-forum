<?php
require_once "General.php";
Banhammer::checkNumberOfRequests();
Logger::log("register.php connection");
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register!</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<form action="registercontroller.php" method="post" >
    <ul class="items"></ul>

    <fieldset class="email enable">
        <div class="icon left"><i class="arrow"></i></div>
        <input type="email" name="email" placeholder="Email" id="email" />
        <div class="icon right button"><i class="arrow"></i></div>
    </fieldset>

    <fieldset class="username">
        <div class="icon left"><i class="user"></i></div>
        <input type="text" name="username" placeholder="Username" id="username" />
        <div class="icon right button"><i class="arrow"></i></div>
    </fieldset>

    <fieldset class="password">
        <div class="icon left"><i class="lock"></i></div>
        <input type="password" name="password" placeholder="Password" id="password" />
        <div class="icon right button"><i class="arrow"></i></div>
    </fieldset>

    <fieldset class="password">
        <div class="icon left"><i class="lock"></i></div>
        <input type="password" name="confirmpassword" placeholder="Confirm password" id="confirm" />
        <div class="icon right button"><i class="arrow"></i></div>
    </fieldset>
</form>

<script src="assets/js/register.js"></script>
</body>
</html>
