<?php 

if (file_exists('nanodoc.sq3')) {
	require_once 'themes/default/index.php';
} else {
	header('Location: config/install.php');
}
?>