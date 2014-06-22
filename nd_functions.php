<?php
/*vot*/ session_start();

/*vot*/define('ROOT_DIR',str_replace('\\','/',dirname(__FILE__)));
/*vot*/$url = dirname($_SERVER['PHP_SELF']);
/*vot*/$url = preg_replace("/\/admin$/",'/',$url);
/*vot*/define('ROOT_URL',$url);
//DEBUG
//echo '<pre>';
//echo 'ROOT_DIR=', ROOT_DIR, "\n";
//echo 'ROOT_URL=', ROOT_URL, "\n";
//echo 'Check for file=', ROOT_DIR . '/nanodoc.sq3', "\n";
//echo 'Check for file=', ROOT_DIR . '/config/nd_config.json', "\n";
//echo 'SERVER=';
//print_r($_SERVER);
//echo '</pre>';

/*vot*/ if (file_exists(ROOT_DIR . '/nanodoc.sq3') && file_exists(ROOT_DIR . '/config/nd_config.json')) {
//DEBUG
//echo 'Reading config...', "\n";
            $config = json_decode(file_get_contents(ROOT_DIR . "/config/nd_config.json"), true);
/*vot*/     $nd_sqlite = get_database();
/*vot*/     $nd_sqlite->checkDatabase();
/*vot*/ } else {
//DEBUG
//echo 'Redirect to config/install.php...', "\n";
/*vot*/     header('Location: ' . ROOT_URL . '/config/install.php');
/*vot*/     exit;
/*vot*/ }

//DEBUG
//echo '<pre>';
//echo 'config=';
//print_r($config);
//echo '</pre>';


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