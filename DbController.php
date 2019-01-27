<?php

class DbController
{
    private static $dbInstance = null;
    private $db;

    private function __construct()
    {
        try {
            $config = Config::getConfigInstance();
            $server = $config->getServer();
            $database = $config->getDatabase();
            $username = $config->getUsername();
            $password = $config->getPassword();

            $this->db = new PDO("mysql:host=$server; dbname=$database; charset=utf8mb4",
                $username,
                $password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getDbInstance()
    {
        if (is_null(self::$dbInstance)) {
            self::$dbInstance = new DbController();
        }
        return self::$dbInstance;
    }

    public function closeDB()
    {
        self::$dbInstance = null;
    }

    public function getLastInsertId()
    {
        return intval($this->db->lastInsertId());
    }

    public function getUserByUsername($username)
    {
        try {
            $sql = "SELECT * FROM users
                        WHERE username = :username AND deleted = 0";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        if (empty($user)) {
            die("User not found");
        }
        return $user;
    }

    public function addUser($username, $email, $password)
    {
        try {
            $sql = "INSERT INTO users(username, email, password)
						VALUES(:username, :email, :password)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getAllUsernamesAndEmails()
    {
        try {
            $sql = "SELECT username, email FROM users";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $users;
    }

    public function addForumPost($username, $forumPost)
    {
        try {
            $sql = "INSERT INTO forum(username, message)
						VALUES(:username, :message)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":message", $forumPost);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getAllForumPosts()
    {
        try {
            $sql = "SELECT * FROM forum ORDER BY post_id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $forumPosts = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $forumPosts;
    }

    public function getForumPost($postId)
    {
        try {
            $sql = "SELECT * FROM forum WHERE post_id = :post_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":post_id", $postId);
            $stmt->execute();
            $forumPost = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $forumPost;
    }

    public function deleteForumPost($forumPostId)
    {
        try {
            $sql = "UPDATE forum SET deleted = 1 WHERE post_id = :post_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":post_id", $forumPostId);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function editForumPost($forumPostId, $message)
    {
        try {
            $sql = "UPDATE forum SET message = :message WHERE post_id = :post_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":post_id", $forumPostId);
            $stmt->bindParam(":message", $message);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getAllUsers()
    {
        try {
            $sql = "SELECT user_id, username, password, email, deleted FROM users";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $users;
    }

    public function addFeedback($name, $email, $feedbackType, $feedbackMessage)
    {
        try {
            $sql = "INSERT INTO feedback(name, email, feedbackType, feedbackMessage)
						VALUES(:name, :email, :feedbackType, :feedbackMessage)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":feedbackType", $feedbackType);
            $stmt->bindParam(":feedbackMessage", $feedbackMessage);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function addFailedLogin($ipAddress, $numAttempts)
    {
        try {
            $sql = "INSERT INTO failedLogin (ipAddress,numAttempts) VALUES (:ipAddress,:numAttempts)
                    ON DUPLICATE KEY UPDATE numAttempts = numAttempts + 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ipAddress", $ipAddress);
            $stmt->bindParam(":numAttempts", $numAttempts);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function resetLoginAttempts($ipAddress)
    {
        try {
            $sql = "UPDATE failedLogin SET numAttempts = 0 WHERE ipAddress = :ipAddress";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ipAddress", $ipAddress);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getLoginAttempt($ipAddress)
    {
        try {
            $sql = "SELECT numAttempts FROM failedLogin WHERE ipAddress = :ipAddress";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ipAddress", $ipAddress);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getAllFeedback()
    {
        try {
            $sql = "SELECT * FROM feedback";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getUserById($id)
    {
        try {
            $sql = "SELECT * FROM users
                        WHERE user_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        if (empty($user)) {
            die("User not found");
        }
        return $user;
    }

    public function deleteUser($id)
    {
        try {
            $sql = "UPDATE users SET deleted = 1 WHERE user_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function addProfilePicture($image, $username)
    {
        try {
            $sql = "INSERT INTO images(image, username)
			VALUES(:image, :username)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":image", $image);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function updateProfilePicture($image, $username)
    {
        try {
            $sql = "UPDATE images SET image = :image WHERE username = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":image", $image);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getProfilePicture($username)
    {
        try {
            $sql = "SELECT image from images WHERE username = :username ORDER BY created DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $image = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $image;
    }

    //check if user is admin
    public function canAdministrate($username)
    {
        try {
            $sql = "SELECT can_administrate FROM users WHERE username = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $canAdministrate = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $canAdministrate->can_administrate;
    }

    public function log(
        $message,
        $decryptedCookie,
        $post,
        $get,
        $files,
        $cookie,
        $userAgent,
        $accept,
        $acceptLanguage,
        $acceptEncoding,
        $referer,
        $contentType,
        $contentLength,
        $httpCookie,
        $connection,
        $upgradeInsecureRequests,
        $serverPort,
        $remoteAddr,
        $requestScheme,
        $contextPrefix,
        $contextDocumentRoot,
        $scriptFilename,
        $remotePort,
        $gatewayInterface,
        $serverProtocol,
        $requestMethod,
        $queryString,
        $requestUri,
        $scriptName,
        $phpSelf,
        $requestTime,
        $serverAddr,
        $acceptCharset,
        $remoteHost
        ) 
        {
        try {
            $sql = "INSERT INTO logs (message, decrypted_cookie, post, get, files, cookie, user_agent, accept, accept_language, accept_encoding, referer, content_type, content_length, http_cookie, connection, upgrade_insecure_requests, server_port, remote_addr, request_scheme, context_prefix, context_document_root, script_filename, remote_port, gateway_interface, server_protocol, request_method, query_string, request_uri, script_name, php_self, request_time, server_addr, http_accept_charset, remote_host) VALUES (:message, :decrypted_cookie, :post, :get, :files, :cookie, :user_agent, :accept, :accept_language, :accept_encoding, :referer, :content_type, :content_length, :http_cookie, :connection, :upgrade_insecure_requests, :server_port, :remote_addr, :request_scheme, :context_prefix, :context_document_root, :script_filename, :remote_port, :gateway_interface, :server_protocol, :request_method, :query_string, :request_uri, :script_name, :php_self, :request_time_float,:server_addr, :http_accept_charset, :remote_host)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":message", $message);
            $stmt->bindParam(":decrypted_cookie", $decryptedCookie);
            $stmt->bindParam(":post", $post);
            $stmt->bindParam(":get", $get);
            $stmt->bindParam(":files", $files);
            $stmt->bindParam(":cookie", $cookie);
            $stmt->bindParam(":user_agent", $userAgent);
            $stmt->bindParam(":accept", $accept);
            $stmt->bindParam(":accept_language", $acceptLanguage);
            $stmt->bindParam(":accept_encoding", $acceptEncoding);
            $stmt->bindParam(":referer", $referer);
            $stmt->bindParam(":content_type", $contentType);
            $stmt->bindParam(":content_length", $contentLength);
            $stmt->bindParam(":http_cookie", $httpCookie);
            $stmt->bindParam(":connection", $connection);
            $stmt->bindParam(":upgrade_insecure_requests", $upgradeInsecureRequests);
            $stmt->bindParam(":server_port", $serverPort);
            $stmt->bindParam(":remote_addr", $remoteAddr);
            $stmt->bindParam(":request_scheme", $requestScheme);
            $stmt->bindParam(":context_prefix", $contextPrefix);
            $stmt->bindParam(":context_document_root", $contextDocumentRoot);
            $stmt->bindParam(":script_filename", $scriptFilename);
            $stmt->bindParam(":remote_port", $remotePort);
            $stmt->bindParam(":gateway_interface", $gatewayInterface);
            $stmt->bindParam(":server_protocol", $serverProtocol);
            $stmt->bindParam(":request_method", $requestMethod);
            $stmt->bindParam(":query_string", $queryString);
            $stmt->bindParam(":request_uri", $requestUri);
            $stmt->bindParam(":script_name", $scriptName);
            $stmt->bindParam(":php_self", $phpSelf);
            $stmt->bindParam(":request_time_float", $requestTimeFloat);
            $stmt->bindParam(":server_addr", $serverAddr);
            $stmt->bindParam(":http_accept_charset", $acceptCharset);
            $stmt->bindParam(":remote_host", $remoteHost);

            
            $stmt->execute();
        } catch (PDOException $e) {

            die($e->getMessage());
        }
    }

    public function logSensitiveData($message)
    {
        try {
            $sql = "INSERT INTO logs (message) VALUES (:message)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":message", $message);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getHallOfFame(){
        try {
            $sql = "SELECT * FROM hall_of_fame";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $hallOfFame = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $hallOfFame;
    }

    public function getLastLoginAttempts($remoteAddr){
        try {
            $sql = "SELECT UNIX_TIMESTAMP(logtimestamp) AS timestamp FROM logs WHERE remote_addr LIKE :remote_addr ORDER BY log_id DESC LIMIT 30";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("remote_addr", $remoteAddr);
            $stmt->execute();
            $lastLogins = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $lastLogins;
    }

    public function tempBanIp($remoteAddr, $expiryTime){
        try {
            $sql = "INSERT INTO bans (remote_addr, expiry_time) VALUES (:remote_addr, :expiry_time)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":remote_addr", $remoteAddr);
            $stmt->bindParam(":expiry_time", $expiryTime);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getBanExpiryTime($remoteAddr){
        try {
            $sql = "SELECT expiry_time FROM bans WHERE remote_addr LIKE :remote_addr ORDER BY id DESC LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":remote_addr", $remoteAddr);
            $stmt->execute();
            $banExpiryTime = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $banExpiryTime;
    }
}
