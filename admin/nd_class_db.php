<?php 
require_once dirname(__FILE__) . '/lib/Parsedown.php';

class nd_db {


    private $htmlErrorPage = '<html><head><meta charset="UTF-8"><title>Document</title></head><body><h1>Error whyle trying to send informations to database. Plasese Try again later.</h1></body></html>';
    
    public function __construct() {
        $this->parsedown = new Parsedown();
    }

    private function sanitizeInput($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;      
    }

    public function checkDatabase() {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if($mysql->connect_error) {
            die($this->htmlErrorPage);
        }
    }

    public function checkLogin($login, $passwd) {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        if(!empty($login) && !empty($passwd)) {

            $sql = "SELECT `user_login`, `user_pass` FROM `users` WHERE `user_login` = '$login';";

            $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            $mysql->query("SET NAMES 'utf8'");

            if(!$result = $mysql->query($sql)) {
                die($this->htmlErrorPage);
            }

            $row = $result->fetch_assoc();

            if(!$row['user_login'] == $login || !(crypt($passwd, $row['user_pass']) == $row['user_pass'])) {
                return 'Username or Password Not valid.';
            }

            $result->close();
            $mysql->close();

            start_login_session();
            $_SESSION['login'] = $row['user_login'];

            return header("Location: admin/");

        }
        
        return 'Please Provide a Username and Password.';
        
    }

    public function getPagesInfo() {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        $sql = "SELECT `page_id`, `user_login`, `page_content`, `page_name`, `page_url`, `page_date` FROM `pages`, `users` 
                WHERE `page_author` = `user_id`;";

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $mysql->query("SET NAMES 'utf8'");

        if(!$result = $mysql->query($sql)) {
            die($this->htmlErrorPage);
        }
        if($result->num_rows == 0) {
            return false;
        }

        while ($row = $result->fetch_assoc()) {
            $row['page_content'] = $this->parsedown->parse($row['page_content']);
            $pages[] = $row;
        }

        $result->close();
        $mysql->close();

        return $pages;
    }

    public function getPage($id) {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        $sql = "SELECT `page_name`, `page_content`, `page_url`, `page_date` FROM `pages` WHERE `page_id` = $id;";

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $mysql->query("SET NAMES 'utf8'");

        if(!$result = $mysql->query($sql)) {
            die($this->htmlErrorPage);
        }

        $page = $result->fetch_assoc();

        $result->close();
        $mysql->close();

        return $page;
    }

    public function editPage($id, $pageName, $pageContent) {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $mysql->query("SET NAMES 'utf8'");

        $sql = "UPDATE `pages` SET `page_name` = '$pageName', `page_content` = '" . $mysql->real_escape_string($pageContent) . "' WHERE `page_id` = $id;";

        if(!$mysql->query($sql)) {
            die($this->htmlErrorPage);
        }

        $mysql->close();

        return true;
    }

    public function deletePage($id) {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        $sql = "DELETE FROM `pages` WHERE `page_id` = $id";

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if(!$mysql->query($sql)) {
            die($this->htmlErrorPage);
        }

        $mysql->close();

        return true;
    }

    public function addPage($pageName, $pageContent, $author) {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $mysql->query("SET NAMES 'utf8'");

        /* Get the url */
        if(!$result = $mysql->query("SELECT `nd_url` FROM `options`;")) {
            die($this->htmlErrorPage);
        }
        $pageUrl = $result->fetch_assoc();

        /* Get next auto_increment value */
        if(!$result = $mysql->query("SHOW TABLE STATUS LIKE 'pages'")) {
            die($this->htmlErrorPage);
        }
        $pageId = $result->fetch_assoc();

        /* Get author's user_id */
        if(!$result = $mysql->query("SELECT `user_id` FROM `users` WHERE `user_login`='$author'")) {
            die($this->htmlErrorPage);
        }
        $userId = $result->fetch_assoc();


        $sql = "INSERT INTO `pages` (`page_name`, `page_author`, `page_content`, `page_url`, `page_date`) VALUES ('$pageName', '{$userId['user_id']}', '" . $mysql->real_escape_string($pageContent) . "', " . "'{$pageUrl['nd_url']}/?p={$pageId['Auto_increment']}'" . ", CURDATE());";

        if(!$mysql->query($sql)) {
            die($this->htmlErrorPage);
        }

        $mysql->close();
        $result->close();

        return true;
    }

    public function getUsersInfo($userLogin=false) {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $mysql->query("SET NAMES 'utf8'");

        $sql = "SELECT `user_login`, `user_name`, `user_email`, `user_role` FROM `users` WHERE `user_login` = '$userLogin';";

        if(!$result = $mysql->query($sql)) {
            die($this->htmlErrorPage);
        }

        $usersInfo = $result->fetch_assoc();

        switch ($usersInfo['user_role']) {
            case 1:
                $usersInfo['user_role'] = 'Contibutor';
                break;
            
            default:
                $usersInfo['user_role'] = 'Administrator';
                break;
        }


        $mysql->close();
        $result->close();

        return $usersInfo;
    }

    public function updateUser($userLogin, $email, $passwd, $userName) {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        if (empty($passwd)) {
            return false;
        }

        $email = $this->sanitizeInput($email);
        $passwd = crypt($this->sanitizeInput($passwd));
        $userName = $this->sanitizeInput($userName);

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $mysql->query("SET NAMES 'utf8'");

        $sql = "UPDATE `users` SET `user_name` = '" . $mysql->real_escape_string($userName) . "', `user_pass` = '" . $mysql->real_escape_string($passwd) . "', `user_email` = '" . $mysql->real_escape_string($email) . "' WHERE `user_login` = '$userLogin';";

        if(!$result = $mysql->query($sql)) {
            die($this->htmlErrorPage);
        }


        return true;
    }

    public function getOptions() {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $mysql->query("SET NAMES 'utf8'");

        $sql = "SELECT `nd_url`, `nd_title`, `nd_description` FROM `options`;";

        if(!$result = $mysql->query($sql)) {
            die($this->htmlErrorPage);
        }

        $options = $result->fetch_assoc();

        $mysql->close();
        $result->close();

        return $options;
    }

    public function getOption($param) {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        $sql = "SELECT `$param` FROM `options`;";

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $mysql->query("SET NAMES 'utf8'");

        if(!$result = $mysql->query($sql)) {
            die($this->htmlErrorPage);
        }

        $row = $result->fetch_assoc();

        $result->close();
        $mysql->close();

        return $row[$param];
    }

    public function updateOptions($title, $description) {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/nanodoc/config.php";

        if(empty($title)) {
            return false;
        }

        $title = $this->sanitizeInput($title);
        $description = $this->sanitizeInput($description);

        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $mysql->query("SET NAMES 'utf8'");

        $sql = "UPDATE `options` SET `nd_title` = '" . $mysql->real_escape_string($title) . "', `nd_description` = '" . $mysql->real_escape_string($description) . "';";

        if(!$result = $mysql->query($sql)) {
            die($this->htmlErrorPage);
        }


        return true;
    }



}





 ?>