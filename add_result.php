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

	
	<div class="" style="padding-top: 100px;">

		<?php
			if($_SERVER['REQUEST_METHOD'] == 'POST'){

				$subject = $_POST['subject'];
				$semester = $_POST['semester'];
				$marks = $_POST['marks'];

				$res = $admin->addOrUpdateMarks($stid, $subject, $semester, $marks);

				if($res)
				{
					echo "<h3 style='color:green;margin:0;padding:0;text-align:center'>Marks successfully inserted!</h3>";
				}
				else
				{
					echo "<p style='color:red;text-align:center'>Failed to insert data</p>";
				}
			}	
			//SELECT avg(marks) as sgpa from result where st_id=10 and semester="1sr"
		?>

		<div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" style="text-align:center;" role="alert">
			Name: <?php echo $name; ?><br>
			Student ID: <?php echo $stid; ?>
		</div>

		<div style="width:40%;margin:50px auto">
			<table class="tab_one" style="text-align:center;">
				<form action="" method="post">
					<tr>
						<td>Select Subject: </td>
						<td>
							<select name="subject">
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
						<td>Select Semester: </td>
						<td>
							<select name="semester" id="">
								<option value="1st">1st semester</option>
								<option value="2nd">2nd semester</option>
								<option value="3rd">3rd semester</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Input marks: </td>
						<td><input type="text" name="marks" placeholder="enter marks" required /></td>
					</tr>
					<tr>
						<td><input type="submit" name="sub" value="Add marks" /></td>
						<td><input type="reset" /></td>
					</tr>	
				</form>
			</table>	
		</div>
		<div class="back fix">
			<p style="text-align:center"><a href="st_result.php"><button class="editbtn">Back to list</button></a></p>
		</div>
	</div>

<?php
	include "includes/footerbottom.php";
	ob_end_flush() ; 
?>