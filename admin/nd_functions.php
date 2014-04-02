<?php

function start_login_session() {
	session_start();
	$_SESSION['timeout'] = time() + 3600;
}

function check_session_expire() {
	if(time() > $_SESSION['timeout']) {
		session_destroy();
		header('Location: ../login.php');
	}
}

