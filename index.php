<?php
require_once "nd_functions.php";
//vot start_login_session();

if (file_exists('nanodoc.sq3')) {
	load_theme('default');
} else {
	goto_install();
}
