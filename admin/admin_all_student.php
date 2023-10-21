<?php
	ob_start ();
	session_start();

	require "../includes/config.php";
	require_once "../includes/functions.php";

	$user = new login_registration_class();

	$admin_id = $_SESSION['admin_id'];
	$admin_name = $_SESSION['admin_name'];

	if(!$user->get_admin_session())
	{
		header('Location: ../index.php');
		exit();
	}
	
?>

<?php 
	$pageTitle = "All student details";
	include "../includes/headertop_admin.php";
?>



<div class="relative overflow-x-auto shadow-md sm:rounded-lg" style="padding-top: 100px; padding-left: 50px; padding-right: 50px; padding-bottom: 100px;">
    <div class="flex items-center justify-between pb-4 bg-white dark:bg-gray-900">
        <label for="table-search" class="sr-only">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input type="text" id="table-search-users" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for users">
        </div>
    </div>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
				<th	scope="col" class="px-6 py-3">
					SL
				</th> 
                <th scope="col" class="px-6 py-3">
                    Name
                </th>
                <th scope="col" class="px-6 py-3">
                    View profile
                </th>
                <th scope="col" class="px-6 py-3">
                    Delete
                </th>
                <th scope="col" class="px-6 py-3">
                    Edit
                </th>
            </tr>
        </thead>
        <tbody>
			<?php
				$i = 0;
				$alluser = $user->get_all_student();

				while($rows = $alluser->fetch_assoc())
				{
					$i++;
			?>
				<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
					<td class="w-4 p-4">
						<?php echo $i; ?>
					</td>
					<th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
						<img class="w-10 h-10 rounded-full" src="/img/student/<?php echo $rows['img']; ?>" alt="Jese image">
						<div class="pl-3">
							<div class="text-base font-semibold"><?php echo $rows['name']; ?></div>
							<div class="font-normal text-gray-500">ID: <?php echo $rows['st_id']; ?></div>
						</div>  
					</th>
					<td class="px-6 py-4">
						<a href="admin_single_student.php?id=<?php echo $rows['st_id'];?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View Details</a>
					</td>
					<td class="px-6 py-4">
						<a href="admin_delete_student.php?id=<?php echo $rows['st_id'];?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a>
					</td>
					<td class="px-6 py-4">
						<a href="admin_single_student_update.php?id=<?php echo $rows['st_id']; ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
					</td>
				</tr>
			<?php
				}
			?>
        </tbody>
    </table>
</div>

<?php 
	include "../includes/footerbottom.php";
?>

<?php 
	ob_end_flush(); 
?>