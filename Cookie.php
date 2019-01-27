<?php

class Cookie
{
    public static function decryptCookie()
    {
        if (isset($_COOKIE["token"])) {
            //magic
            $encryptedCookie = $_COOKIE["token"];

            $explodedCookie = explode(";", $encryptedCookie);

            $encryptedString = $explodedCookie[0];
            $iv = $explodedCookie[1];

            $decryptedString = openssl_decrypt($encryptedString, Encrypter::getCipherMethod(), Encrypter::getAesKey(), 0, $iv);

            $explodedCredentials = explode(";", $decryptedString);

            $username = $explodedCredentials[0];
            $expiryDate = $explodedCredentials[1];

            if ($expiryDate >= time()) {
//                echo "cookie OK!, logged in as: " . $username;
                return $username;
            }
            return false;
        }
    }
}


