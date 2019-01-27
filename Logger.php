<?php

class Logger
{

    public static function log($message)
    {
        //TODO: use existing DB instances from other php pages
        $db = DbController::getDbInstance();
        $db->log(
            $message,
            print_r(Cookie::decryptCookie(), true),
            print_r($_POST, true),
            print_r($_GET, true),
            print_r($_FILES, true),
            print_r($_COOKIE, true),
            print_r($_SERVER['HTTP_USER_AGENT'], true),
            print_r($_SERVER['HTTP_ACCEPT'], true),
            print_r($_SERVER['HTTP_ACCEPT_LANGUAGE'], true),
            print_r($_SERVER['HTTP_ACCEPT_ENCODING'], true),
            print_r($_SERVER['HTTP_REFERER'], true),
            print_r($_SERVER['CONTENT_TYPE'], true),
            print_r($_SERVER['CONTENT_LENGTH'], true),
            print_r($_SERVER['HTTP_COOKIE'], true),
            print_r($_SERVER['HTTP_CONNECTION'], true),
            print_r($_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS'], true),
            print_r($_SERVER['SERVER_PORT'], true),
            print_r($_SERVER['REMOTE_ADDR'], true),
            print_r($_SERVER['REQUEST_SCHEME'], true),
            print_r($_SERVER['CONTEXT_PREFIX'], true),
            print_r($_SERVER['CONTEXT_DOCUMENT_ROOT'], true),
            print_r($_SERVER['SCRIPT_FILENAME'], true),
            print_r($_SERVER['REMOTE_PORT'], true),
            print_r($_SERVER['GATEWAY_INTERFACE'], true),
            print_r($_SERVER['SERVER_PROTOCOL'], true),
            print_r($_SERVER['REQUEST_METHOD'], true),
            print_r($_SERVER['QUERY_STRING'], true),
            print_r($_SERVER['REQUEST_URI'], true),
            print_r($_SERVER['SCRIPT_NAME'], true),
            print_r($_SERVER['PHP_SELF'], true),    
            print_r($_SERVER['REQUEST_TIME_FLOAT'], true),
            print_r($_SERVER['SERVER_ADDR'], true),
            print_r($_SERVER['HTTP_ACCEPT_CHARSET'], true),
            print_r($_SERVER['REMOTE_HOST'], true)
            );
    }

    public static function logSensitiveData($message)
    {
        //TODO: open and close DB
        DbController::getDbInstance()->logSensitiveData($message);
    }

}
