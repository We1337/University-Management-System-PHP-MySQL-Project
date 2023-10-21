<?php
	session_start();
	
	require "../includes/config.php";
	
	require_once "../includes/functions.php";
	
	$user = new login_registration_class();
	
	$admin_id = $_SESSION['admin_id'];
	
	$admin_name = $_SESSION['admin_name'];
	
	if(!$user->get_admin_session())
	{
		header('Location: index.php');
		exit();
	}
?>

<?php 
	$pageTitle = "Admin";
	include "../includes/headertop_admin.php";
?>

<div class="relative my-1 p-10" style="padding-top: 100px; padding-right: 50px; padding-bottom: 50px; padding-left: 50px;">
	<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
		<table class="w-full text-sm">
			<thead class=" text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
				<tr>
					<th scope="col" class="px-6 py-3">
						Student
					</th>
					<th scope="col" class="px-6 py-3">
						Faculty
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
					<th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
						<a href="admin_all_student.php">View all students</a>
					</th>
					<th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
						Faculty details
					</th>
				</tr>
				<tr class="border-b bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
					<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
						Student result
					</th>
					<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
						Information
					</th>
				</tr>
				<tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
					<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
						Attendance
					</th>
					<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
						Search faculty
					</th>
				</tr>
				<tr class="border-b bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
					<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
						Download student list
					</th>
					<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
						Download faculty list
					</th>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<?php 
	include "../includes/footerbottom.php";
?>