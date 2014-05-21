<?php
define('ABSPATH', dirname(dirname(__FILE__)));

function start_login_session() {
	session_name('nanodoc_login');
	
    return session_start();
}

function check_login_session() {
	session_name('nanodoc_login');
	session_start();

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
    
    require_once ABSPATH . "/themes/{$themeName}/index.php";
}

function goto_install() {
    header('Location: config/install.php');
}

