<?php

require '../vendor/autoload.php';
require '../private/includes/databaseconnection.php';
require '../private/admin/admin.class.php';
require '../tests/admin/admin_class_fetchAllStudent()_test_case.php';

$config = include('../private/config/database.php');

$test = new AdminTest();

$test->testFetchAllStudentsSuccess($config);