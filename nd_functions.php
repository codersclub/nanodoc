<?php
/*vot*/ session_start();

/*vot*/define('DOC_ROOT',str_replace('\\','/',dirname(__FILE__)));
//DEBUG
//echo DOC_ROOT;

$config = json_decode(file_get_contents(DOC_ROOT."/nd_config.json"), true);

function start_login_session() {
//vot	session_name('nanodoc_login');
	
//vot    return session_start();
}

function check_login_session() {
//vot	session_name('nanodoc_login');
//vot	session_start();

    if(empty($_SESSION['login'])) {
    	session_destroy();
        header('Location: ../login.php');
    }
}

function end_login_session() {
	unset($_SESSION);
	session_destroy();
	header('Location: ../login.php');
}

function text_for_id($text) {
	$idText = preg_replace('/[^A-Za-z0-9]/', '-', $text);
	return $idText;
}

function load_theme($themeName) {
    global $config;
    require_once $config['themespath'] . "/{$themeName}/index.php";
}

function goto_install() {
    header('Location: config/install.php');
}

function get_database() {
    global $config;
    require_once $config['adminpath'] . '/nd_class_db.php';
    $nd_sqlite = new nd_db;
    return $nd_sqlite;
}

function get_template_part($part) {
    global $config;
    $nd_sqlite = get_database();
    require_once $config['themespath'] . "/default/{$part}.php";
}