<?php

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