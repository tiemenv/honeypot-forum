<?php
class Banhammer
{
    public static function checkNumberOfRequests()
    {
        $debuggingBanhammer = false;

        $banTime = 15 * 60;
        $intervalTime = 30;

        $db = DbController::getDbInstance();

        //check if the user was banned already
        $banExpiryTime = $db->getBanExpiryTime($_SERVER['REMOTE_ADDR'])->expiry_time;
        if ($banExpiryTime > time()) {
            echo "You have been banned for 15 minutes due to making too much requests. Convinced you're a human? Please file a bug report on our <a href='feedback.php'>feedback page!</a>";
            Logger::log("Banned account tried to request");
            exit;
        }

        if ($debuggingBanhammer) {
            $lastLogins = $db->getLastLoginAttempts("IP that didnt exist before");
        } else {
            $lastLogins = $db->getLastLoginAttempts($_SERVER['REMOTE_ADDR']);
        }

        $lastLogin = end($lastLogins);

        if (isset($lastLogin->timestamp)) {
            $lastLoginTime = $lastLogin->timestamp;
        } else {
            $lastLoginTime = 0;
        }
        $timeBetween = time() - $lastLoginTime;

        if ($debuggingBanhammer) {
            echo "<pre>" . print_r($lastLogins, true) . "</pre>";
            echo "<pre>" . print_r($lastLogin, true) . "</pre>";
            echo "<pre>" . print_r($lastLogin->timestamp, true) . "</pre>";
            echo "<pre>" . print_r($timeBetween, true) . "</pre>";
            echo "<pre>" . print_r($intervalTime, true) . "</pre>";
        }
        //check if the user has done too many requests lately, if so, tempban him
        if ($timeBetween < $intervalTime && sizeof($lastLogins) == 30) {
            Logger::log("banhammer activated!");
            echo "bad bot! Banned for 15 minutes!";
            //insert ban entry into db
            $db->tempBanIp($_SERVER['REMOTE_ADDR'], time() + $banTime);
            exit;
        }
    }
}
