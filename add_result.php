<?php
	session_start();

	require "includes/config.php";
	require_once "admin/includes/admin.class.php";

	$admin = new Admin();

	if(!$admin->isAdminLoggedIn())
	{
		header('Location: index.php');
		exit();
	}

	if(isset($_REQUEST['ar']))
	{
		$stid = $_REQUEST['ar'];
		$name = $_REQUEST['vn'];
	}

	$pageTitle = "Student Result";
	include "admin/headertop_admin.php";
?>
	
	<style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
    </style>

	<div style="">

		<?php
			if($_SERVER['REQUEST_METHOD'] == 'POST'){

				$subject = $_POST['subject'];
				$semester = $_POST['semester'];
				$marks = $_POST['marks'];
				$student_id = $_POST['student_id'];

				$res = $admin->addOrUpdateMarks($student_id, $subject, $semester, $marks);

				if($res)
				{
					echo "<h3 style='color:green;margin:0;padding:0;text-align:center'>Marks successfully inserted!</h3>";
				}
				else
				{
					echo "<p style='color:red;text-align:center'>Failed to insert data</p>";
				}
			}	
		?>

		<div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" style="text-align:center;" role="alert">
			Name: <?php echo $name; ?><br>
			Student ID: <?php echo $stid; ?>
		</div>

		<div class="">
			<table class="tab_one">
				<form action="" method="POST">
					<input name="student_id" type="hidden" value="<?php echo $stid ?>">
					<tr>
						<td>
							<label for="subject" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select subject:</label>
							<select name="subject" id="subject" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
								<option value="DBMS">Database management</option>
								<option value="DBMS Lab">DBMS Lab</option>
								<option value="Mathematics">Mathematics</option>
								<option value="Programming">Programming</option>
								<option value="Programming Lab">Programming Lab</option>
								<option value="English">English</option>
								<option value="Physics">Physics</option>
								<option value="Chemistry">Chemistry</option>
								<option value="Psychology">Psychology</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="semester" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select semester:</label>
							<select name="semester" id="semester" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
								<option value="1st">1st semester</option>
								<option value="2nd">2nd semester</option>
								<option value="3rd">3rd semester</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Input marks: </label>
					    	<input name="marks" type="text" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
						</td>
					</tr>
					<tr>
						<td>
							<br>
							<input type="submit" name="sub" value="Add marks" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
							<input type="reset" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
						</td>
					</tr>	
				</form>
			</table>	
		</div>

		<div class="back fix">
			<br>
			<p style="text-align:center"><a href="st_result.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Back to list</a></p>
		</div>
	</div>

<?php
	include "includes/footerbottom.php";
	ob_end_flush() ; 
?>