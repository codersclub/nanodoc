<?php 
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(dirname(__FILE__)));
}

require_once ABSPATH . '/admin/lib/Parsedown.php';

class nd_db {
    
    public function __construct() {
        $this->parsedown = new Parsedown();
        $this->dbFilePath = ABSPATH . '/nanodoc.sq3';
    }

    private function sanitizeInput($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;      
    }

    public function checkDatabase() {

        try {
            $sqlite = new PDO('sqlite:' . $this->dbFilePath);
            $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function checkLogin($login, $passwd) {

        if(!empty($login) && !empty($passwd)) {

            try {
                $sqlite = new PDO('sqlite:' . $this->dbFilePath);
                $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $sqlite->prepare("SELECT user_login, user_pass FROM users WHERE user_login = :login");
                $stmt->bindParam(':login', $login);
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if(!$row['user_login'] == $login || !(crypt($passwd, $row['user_pass']) == $row['user_pass'])) {
                    return 'Username or Password Not valid.';
                }

                $sqlite = NULL;

//vot                start_login_session();
                $_SESSION['login'] = $row['user_login'];

//vot                return header("Location: admin/");
/*vot*/                return '';


            } catch (PDOException $e) {
                die($e->getMessage());
            }
                

        }
        
        return 'Please Provide a Username and Password.';
        
    }

    public function getPages($getContent = true, $numberOfPages) {

        try {

            $sqlite = new PDO('sqlite:' . $this->dbFilePath);
            $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (is_int($numberOfPages)) {
                $result = $sqlite->query("SELECT page_id, user_login, page_content, page_name, page_url, page_date FROM pages, users WHERE page_author = user_id LIMIT {$numberOfPages}");        
            }
            elseif ($numberOfPages = 'all') {
                $result = $sqlite->query("SELECT page_id, user_login, page_content, page_name, page_url, page_date FROM pages, users WHERE page_author = user_id");
            }
            

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if($getContent) {
                    $row['page_content'] = $this->parsedown->parse($row['page_content']);
                }
                $pages[] = $row;
            }

            $sqlite = NULL;

            return isset($pages) ? $pages : false;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getPage($id) {

        try {

            $sqlite = new PDO('sqlite:' . $this->dbFilePath);
            $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $sqlite->prepare("SELECT page_id, page_name, user_login, page_author, page_content, page_url, page_date FROM pages, users WHERE page_id = :id AND page_author = user_id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $page = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $sqlite = NULL;

            return $page;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function editPage($id, $pageName, $pageContent) {

        try {
            $sqlite = new PDO('sqlite:' . $this->dbFilePath);
            $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $sqlite->prepare("UPDATE pages SET page_name = :page_name, page_content = :page_content WHERE page_id = :id");
            $stmt->bindParam(':page_name', $pageName);
            $stmt->bindParam(':page_content', $pageContent);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $sqlite = NULL;

            return header("Location: " . $_SERVER['REQUEST_URI']);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function deletePage($id) {

        try {
            $sqlite = new PDO('sqlite:' . $this->dbFilePath);
            $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $sqlite->prepare("DELETE FROM pages WHERE page_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $sqlite = NULL;

            return true;
        } catch (PDOException $e) {
            die($e->getMessage);
        }
    }

    public function addPage($pageName, $pageContent, $author) {

        try {
            $sqlite = new PDO('sqlite:' . $this->dbFilePath);
            $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            /* Get the url */
            $result = $sqlite->query("SELECT nd_url FROM options");
            $pageUrl = $result->fetch(PDO::FETCH_ASSOC);

            /* Get next auto_increment value */
            $result = $sqlite->query("SELECT seq FROM SQLITE_SEQUENCE where name = 'pages'");
            $pageId = $result->fetch(PDO::FETCH_ASSOC);
            $pageId['seq']++;
            $pageUrl['nd_url'] .= '?p=' . $pageId['seq'];

            /* Get author's user_id */
            $result = $sqlite->query("SELECT user_id FROM users WHERE user_login = '$author'");
            $userId = $result->fetch(PDO::FETCH_ASSOC);


            $stmt = $sqlite->prepare("INSERT INTO pages (page_name, page_author, page_content, page_url, page_date) VALUES (:page_name, :page_author, :page_content, :page_url, date('now'))");
            $stmt->bindParam(':page_name', $pageName);
            $stmt->bindParam(':page_author', $userId['user_id']);
            $stmt->bindParam(':page_content', $pageContent);
            $stmt->bindParam(':page_url', $pageUrl['nd_url']);
            $stmt->execute();

            return header("Location: " . $_SERVER['REQUEST_URI'] . '?p=' . $pageId['seq']);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getUsersInfo($userLogin) {

        try {
            $sqlite = new PDO('sqlite:' . $this->dbFilePath);
            $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $sqlite->prepare("SELECT user_login, user_name, user_email, user_role FROM users WHERE user_login = :user_login");
            $stmt->bindParam(':user_login', $userLogin);
            $stmt->execute();

            $usersInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            switch ($usersInfo['user_role']) {
                case 1:
                    $usersInfo['user_role'] = 'Contibutor';
                    break;
                
                default:
                    $usersInfo['user_role'] = 'Administrator';
                    break;
            }


            $sqlite = NULL;

            return $usersInfo;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function updateUser($userLogin, $email, $passwd, $userName) {

        try {

            $email = $this->sanitizeInput($email);
            $userName = $this->sanitizeInput($userName);

            $sqlite = new PDO('sqlite:' . $this->dbFilePath);
            $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            if (empty($passwd)) {
                $stmt = $sqlite->prepare("UPDATE users SET user_name = :userName, user_email = :email WHERE user_login = :userLogin;");
            } else {
                $stmt = $sqlite->prepare("UPDATE users SET user_name = :userName, user_pass = :passwd, user_email = :email WHERE user_login = :userLogin;");
                $passwd = crypt($this->sanitizeInput($passwd));
                $stmt->bindParam(':passwd', $passwd);
            }
            
            $stmt->bindParam(':userName', $userName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':userLogin', $userLogin);
            $stmt->execute();

            return header("Location: " . $_SERVER['REQUEST_URI']);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getOptions() {

        try {
            $sqlite = new PDO('sqlite:' . $this->dbFilePath);
            $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $result = $sqlite->query("SELECT nd_url, nd_title, nd_description FROM options");

            $options = $result->fetch(PDO::FETCH_ASSOC);

            $sqlite = NULL;

            return $options;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getOption($param) {

        try {

            $sqlite = new PDO('sqlite:' . $this->dbFilePath);
            $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $result = $sqlite->query("SELECT $param FROM options");

            $row = $result->fetch(PDO::FETCH_ASSOC);

            $sqlite = NULL;

            return $row[$param];
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function updateOptions($title, $description) {

        try {
            if(empty($title)) {
                return false;
            }

            $title = $this->sanitizeInput($title);
            $description = $this->sanitizeInput($description);

            $sqlite = new PDO('sqlite:' . $this->dbFilePath);
            $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $sqlite->prepare("UPDATE options SET nd_title = :nd_title, nd_description = :nd_description");
            $stmt->bindParam(':nd_title', $title);
            $stmt->bindParam(':nd_description', $description); 

            $stmt->execute();

            $sqlite = NULL;

            return header("Location: " . $_SERVER['REQUEST_URI']);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

}

