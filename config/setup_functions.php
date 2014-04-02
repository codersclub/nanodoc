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

function create_setup() {
    
    if (is_writable(dirname(dirname(__FILE__)) . '/')) {
    	$config_file = fopen('../config.php', 'w');
    	fwrite($config_file, "<?php \n");
    
	    foreach ($_POST as $key => $value) {
	    	fwrite($config_file, "define('" . strtoupper($key) . "', '$value');\r\n\n");
	    }

    	echo "<p>Configuration file created succesfully. Please proceed to installation.</p>";
    	echo "<a class=\"btn btn-primary btn-lg\" href=\"install.php?step=1\">Start Installation</a>";
    	fclose($config_file);
    } else {
    	echo "<p>Configuration file not created. Please create manually a config.php at the root directory of NanoDoc and copy/paste the informations from below.
    		 Then proceed to installation</p>";

    	echo "<textarea class=\"form-control\">
<?php

define('DB_NAME', '{$_POST['db_name']}'');

define('DB_USER', '{$_POST['db_user']}'');

define('DB_PASS', '{$_POST['db_pass']}'');

define('DB_HOST', '{$_POST['db_host']}'');
</textarea>";

    	echo "<a class=\"btn btn-primary btn-lg\" href=\"install.php?step=1\">Start Installation</a>";
    }
}

function check_setup() {
	$errMsg = '';
	$error = false;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

    	$db_name = check_form('db_name', $error, $errMsg, $fieldName = 'Database name');
    	$db_user = check_form('db_user', $error, $errMsg, $fieldName = 'Database username');
    	$db_pass = check_form('db_pass', $error, $errMsg, $fieldName = 'Database password');
    	$db_host = check_form('db_host', $error, $errMsg, $fieldName = 'Database hostname');

        if ($error) {
            echo "<p>Please provide <br>$errMsg</p>";
            echo "<a class=\"btn btn-primary btn-lg\" href=\"setup.php?step=1\">Try again</a>";
        } else {
        	mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        	if (mysqli_connect_errno()) {
				echo "<p>Failed to connect to MySQL: " . mysqli_connect_error() . "</p>";
				echo "<a class=\"btn btn-primary btn-lg\" href=\"setup.php?step=1\">Try again</a>";
			} else {
            	create_setup();
			}
        }

        mysqli_close($con);
	}
}

function create_nd_tables(&$nd_title, &$nd_user, &$nd_pass, &$nd_user_email) {
	require_once '../config.php';

	$uri = dirname(dirname($_SERVER['REQUEST_URI']));

    $sql = <<< EndOfSQL
CREATE TABLE IF NOT EXISTS `users` (
	`user_id` int(10) NOT NULL AUTO_INCREMENT,
	`user_name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
	`user_login` varchar(30) CHARACTER SET utf8 NOT NULL,
	`user_pass` varchar(300) CHARACTER SET utf8 NOT NULL,
	`user_email` varchar(50) CHARACTER SET utf8 NOT NULL,
	`user_role` tinyint(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (`user_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `pages` (
	`page_id` int(10) NOT NULL AUTO_INCREMENT,
	`page_name` varchar(255) CHARACTER SET utf8 NOT NULL,
	`page_author` int(10) NOT NULL,
	`page_content` longtext CHARACTER SET utf8 NOT NULL,
	`page_url` varchar(255) CHARACTER SET utf8 NOT NULL,
	`page_date` date NOT NULL,
	PRIMARY KEY (`page_id`),
	FOREIGN KEY fk_user(`page_author`) REFERENCES users(`user_id`)
	ON DELETE SET NULL 
	ON UPDATE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `options` (
	`option_id` int(10) NOT NULL AUTO_INCREMENT,
	`nd_url` varchar(255) CHARACTER SET utf8 NOT NULL,
	`nd_title` varchar(100) CHARACTER SET utf8 NOT NULL,
	`nd_description` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
	PRIMARY KEY (`option_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `users` (`user_login`, `user_pass`, `user_email`) VALUES ('$nd_user', '$nd_pass', '$nd_user_email');

INSERT INTO `options` (`nd_url`, `nd_title`) VALUES ('http://{$_SERVER['SERVER_NAME']}$uri', '$nd_title');
EndOfSQL;

	$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		echo "<a class=\"btn btn-primary btn-lg\" href=\"install.php?step=1\">Try again</a>";
	} 
	elseif (mysqli_multi_query($con, $sql)) {
		echo "<p>Nanodoc installed succesfully. You can login now and create your first page</p>";
		echo "<a class=\"btn btn-primary btn-lg\" href=\"../login.php\">Login</a>"; 
	} else {
		echo 'error';
	}

	mysqli_close($con);
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
			create_nd_tables($nd_title, $nd_user, $nd_pass, $nd_user_email);
        }
	}
}


 ?>