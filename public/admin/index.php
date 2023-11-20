<?php
    
session_start();

$title_name = "Admin login";
$config = include('../../private/config/database.php');

require "../../private/includes/databaseconnection.php";
require "../../private/admin/admin.class.php";

$admin = new Admin($config);

$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'];

if(!$admin->fetchAdminSession()){
	header('Location: ../welcome.php');
	exit();
}

include('includes/header.php');
?>



<?php
include('includes/footer.php');
?>