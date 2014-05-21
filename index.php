<?php 
require_once "admin/nd_functions.php";

if (file_exists('nanodoc.sq3')) {
	load_theme('default');
} else {
	goto_install();
}
?>