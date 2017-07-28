<?php
// This page is for deleting a task record.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Delete a task';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

echo '<h1>Delete a task</h1><br />';

// Check for a valid task ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_tasks.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">This page has been accessed in error.</p>';
	include ('includes/footer.html'); 
	exit();
}

require ('../mysqli_connect.php');

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($_POST['sure'] == 'Yes') { // Delete the record.

		// Make the query:
		$q = "DELETE FROM tasks WHERE task_id=$id LIMIT 1";		
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Print a message:
			echo '<p>The task has been deleted.</p>';
			
			// Redirect to view_tasks.php:
			echo "<script>
				setTimeout(function(){
					window.location = \"view_tasks.php\";
				}, 1500);
			</script>";

		} else { // If the query did not run OK.
			echo '<p class="error">The task could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
		}
	
	} else { // No confirmation of deletion.
		echo '<p>The task has NOT been deleted.</p>';	
	}

} else { // Show the form.

	// Retrieve the job's information:
	$q = "SELECT t.task_id, DATE_FORMAT(t.due, '%m-%d-%y') AS dd, t.task_desc, u.first_name, u.last_name
	FROM tasks AS t
	INNER JOIN users AS u
	USING (user_id)
	WHERE task_id=$id";
	$r = @mysqli_query ($dbc, $q);

	if (mysqli_num_rows($r) == 1) { // Valid job ID, show the form.

		// Get the job's information:
		$row = mysqli_fetch_array ($r, MYSQLI_NUM);
		
		// Display the record being deleted:
		echo "<h3>Task ID No: $row[0]</h3><h3>Due Date: $row[1]</h3><h3>Description: $row[2]</h3><h3>Posted by: $row[3] $row[4]</h3><br />
		<p>Are you sure you want to delete this task?";
		
		// Create the form:
		echo '<form action="delete_task.php" method="post">
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No
	<input type="submit" name="submit" value="Delete" />
	<input type="hidden" name="id" value="' . $id . '" />
	</form></p>';
	
	} else { // Not a valid user ID.
		echo '<p class="error">This page has been accessed in error.</p>';
	}

} // End of the main submission conditional.

// Echo panel end tag
echo '</div></div>';

mysqli_close($dbc);

include ('includes/footer.html');

?>