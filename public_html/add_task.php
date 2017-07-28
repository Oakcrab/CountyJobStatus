<?php
// This script performs an INSERT query to add a record to the task table.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Add a Task';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

echo '<h1>Add a Task</h1>';

require ('../mysqli_connect.php'); 

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = array(); // Initialize an error array.
	
	// Check for a due date:
	if (empty($_POST['due'])) {
		$errors[] = 'You forgot to enter a due date.';
	} else {
		$due = mysqli_real_escape_string($dbc, trim($_POST['due']));
	}
	
	// Check for an assigned to entry:
	if (empty($_POST['assigned_id'])) {
		$errors[] = 'You forgot to enter who this task is assigned to.';
	} else {
		$assigned_id = mysqli_real_escape_string($dbc, trim($_POST['assigned_id']));
	}
	
	// Check for a description:
	if (empty($_POST['task_desc'])) {
		$errors[] = 'You forgot to enter a description.';
	} else {
		$task_desc = mysqli_real_escape_string($dbc, trim($_POST['task_desc']));
	}
	
	// Null sets:
	$notes = mysqli_real_escape_string($dbc, trim($_POST['notes']));
	$user_id = mysqli_real_escape_string($dbc, trim($_POST['user_id']));	
	
	if (empty($errors)) { // If everything's OK.
	
		// Add the job in the database...
		
		// Make the query:
		$q = "INSERT INTO tasks (due, task_desc, notes, user_id, assigned_id, reg_date) VALUES ('$due', '$task_desc', '$notes', '$user_id', $assigned_id, NOW() )";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<p>You have added a new task!</p><p><br /></p><p><a href="add_task.php">Add another task?</a></p>';	
		
			// Redirect to view_jobs.php:
			echo "<script>
				setTimeout(function(){
					window.location = \"view_tasks.php\";
				}, 5000);
			</script>";
		
		} else { // If it did not run OK.
			
			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not add a new task due to a system error. We apologize for any inconvenience.</p>'; 
			
			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
						
		} // End of if ($r) IF.
		
		mysqli_close($dbc); // Close the database connection.

		// Include the footer and quit the script:
		include ('includes/footer.html'); 
		exit();
		
	} else { // Report the errors.
	
		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.
	
	mysqli_close($dbc); // Close the database connection.

} else {
		
		// Run the form:
		echo '<form action="add_task.php" method="post">
		<p>*Due Date: <input type="text" id="datepicker" name="due" size="10" maxlength="10" />';

		echo '</select></p>
		<p>*Assign Task to: <select name="assigned_id"><option></option>';
		// SELECT Drop down list values from users table:
		// Define the query:
		$q = "SELECT user_id, first_name, last_name FROM users ORDER BY last_name ASC";		
		$r = @mysqli_query ($dbc, $q);

		// Count the number of returned rows:
		$num = mysqli_num_rows($r);

		// Fetch and print all the records:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			echo '<option value="' . $row['user_id'] . '">' . $row['last_name'] . ', ' . $row['first_name'] . '</option>';
		}

		mysqli_free_result ($r);
		
		echo '</select></p>
		<p>*Description: <input type="text" name="task_desc" size="100" maxlength="250" /></p>
		<p>Notes: <textarea name="notes" rows="4" cols="50"></textarea></p>
		<input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '" />
		<p><input type="submit" name="submit" value="Submit" /></p>
		</form>
		<p>(*) Marks the items that must be completed to submit.</p>';
	
		mysqli_close($dbc); // Close the database connection.

	}// End of the main Submit conditional.

// Echo panel end tag
echo '</div></div>';

include ('includes/footer.html'); 
 
?>