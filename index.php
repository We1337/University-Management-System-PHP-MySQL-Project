<?php
	ob_start();
	session_start();

	require "includes/config.php";
	require_once "includes/functions.php";

	$user = new login_registration_class();

	if($user->get_admin_session())
	{
		header("Location: admin/admin.php");
		exit();
	}
?>

<?php

   	$pageTitle = "Admin Login";
	include("header.php"); 

?>

<?php

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$username = $_POST["username"];
		$password = $_POST["password"];

		if(empty($username) or empty($password))
		{
			echo "<p>Field must not be empty</p>";
		}
		else
		{
			$password = md5($password);
			$login = $user->admin_userlogin($username, $password);

			if($login)
			{
				header('Location: admin/admin.php');
			}
			else
			{
				echo "<p>Incorrect username or password</p>";
			}
		}
	}

?>

      	<div class="p-4 sm:ml-64">
         	<div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
            	<div class="items-center justify-center h-48 mb-4 rounded bg-gray-50 dark:bg-gray-800">
               		<section class="bg-gray-50 dark:bg-gray-900">
  						<div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      						<a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
          						<img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo">
          						UMS - Admin   
      						</a>
      						<div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
          						<div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              						<h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                  						Sign in to your admin account
              						</h1>
									<form class="space-y-4 md:space-y-6" action="#" method="POST">
										<div>
											<label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your username</label>
											<input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="admin" required="">
										</div>
										<div>
											<label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
											<input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
										</div>
										<p class="text-sm font-light text-gray-500 dark:text-gray-400">
											<input type="submit" value="Login" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
										</p>
									</form>
          						</div>
      						</div>
  						</div>
					</section> 
            	</div>
         	</div>
      	</div>


<?php
	include('footer.php');
?>