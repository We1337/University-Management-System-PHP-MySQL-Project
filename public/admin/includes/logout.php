<?php
	session_start();

	$config = include("../../../private/config/database.php");

    require __DIR__ . "../../../../private/includes/databaseconnection.php";
	require __DIR__ . "../../../../private/admin/admin.class.php";
	
	$user = new Admin($config);

	$user->adminLogout();

	header('Location: ../../../../index.php');
	exit();
?>