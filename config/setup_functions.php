<?php 

function get_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function check_form($postField, &$error, &$errMsg, $fieldName) {
    if(empty($_POST[$postField])) {
        $error = true;
        $errMsg .= " $fieldName <br>";
        return 0;
    }

    return get_input($_POST[$postField]);
}

function sqlite3_create_table(&$nd_title, &$nd_user, &$nd_pass, &$nd_user_email) {
    try {

        $url = "http";
        if ($_SERVER['HTTPS'] == "on") { $url .= 's'; }
        $url .= '://';
        $uri = str_replace('/config/install.php?step=2', '/', $_SERVER['REQUEST_URI']);
        $url .= $_SERVER['SERVER_NAME'] . $uri;
        $path = $_SERVER['DOCUMENT_ROOT'];


        $sqlite = new PDO('sqlite:../nanodoc.sq3');

        $sql = <<< EndOfSQL
CREATE TABLE IF NOT EXISTS users (
    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_name TEXT NOT NULL DEFAULT '',
    user_login TEXT NOT NULL,
    user_pass TEXT NOT NULL,
    user_email TEXT NOT NULL,
    user_role INTEGER NOT NULL DEFAULT 0
    );
CREATE TABLE IF NOT EXISTS pages (
    page_id INTEGER PRIMARY KEY AUTOINCREMENT,
    page_name TEXT NOT NULL,
    page_author INTEGER,
    page_content longtext NOT NULL,
    page_url TEXT NOT NULL,
    page_date date NOT NULL,
    FOREIGN KEY(page_author) REFERENCES users(user_id)
    ON DELETE SET NULL 
    ON UPDATE CASCADE
    );
CREATE TABLE IF NOT EXISTS options (
    option_id INTEGER PRIMARY KEY AUTOINCREMENT,
    nd_url TEXT NOT NULL,
    nd_title TEXT NOT NULL,
    nd_description TEXT NOT NULL DEFAULT ''
    );
INSERT INTO users (user_login, user_pass, user_email) VALUES ('$nd_user', '$nd_pass', '$nd_user_email');

INSERT INTO options (nd_url, nd_title) VALUES ('$url', '$nd_title');

INSERT INTO pages (page_name, page_author, page_content, page_url, page_date) VALUES ('New Page', '$nd_user', 'This Is Your First Page Edit or Delete It.', '$url?p=1', date('now'));
EndOfSQL;

        $sqlite->exec($sql);

        echo "<p>Nanodoc installed succesfully. You can login now and create your first page</p>";
        echo "<a class=\"btn btn-primary btn-lg\" href=\"../login.php\">Login</a>"; 

        $sqlite= NULL;
    } catch (PDOException $e) {
        echo "Failed to connect to MySQL: " . $e->getMessage();
        echo "<a class=\"btn btn-primary btn-lg\" href=\"install.php?step=1\">Try again</a>";
    }
    
}

function install_nanodoc() {
    $errMsg = '';
    $error = false;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $nd_title = check_form('nd_title', $error, $errMsg, $fieldName = 'Title for your Nanodoc');
        $nd_user = check_form('nd_user', $error, $errMsg, $fieldName = 'Username for your Nanodoc');
        $nd_pass = check_form('nd_pass', $error, $errMsg, $fieldName = 'Password for your Nanodoc');
        $nd_pass = crypt($nd_pass);
        $nd_user_email = check_form('nd_user_email', $error, $errMsg, $fieldName = 'Your email address');

        if ($error) {
            echo "<p>Please provide <br>$errMsg</p>";
            echo "<a class=\"btn btn-primary btn-lg\" href=\"install.php?step=1\">Try again</a>";
        } else {
            sqlite3_create_table($nd_title, $nd_user, $nd_pass, $nd_user_email);
        }
    }
}


 ?>