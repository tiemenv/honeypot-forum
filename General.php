<?php
error_reporting(0);
header("X-Content-Security-Policy: script-src 'self'; style-src 'self'");
header("X-WebKit-CSP: script-src 'self'; style-src 'self'");
spl_autoload_register(function ($classname) {
    require_once $classname . ".php";
});
