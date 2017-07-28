<?php

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Complete a Task';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

echo '<h1>Complete a Task</h1><br />';

// Check for a valid Task ID, through GET or POST:
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

	if ($_POST['sure'] == 'Yes') { // Update the record to complete.

		// Make the query:
		$q = "UPDATE tasks SET complete= NOW() WHERE task_id=$id LIMIT 1";	
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Print a message:
			echo '<p>The task has been marked completed.</p>';
			
			// Redirect to index.php:
			echo "<script>
				setTimeout(function(){
					window.location = \"index.php\";
				}, 1500);
			</script>";

		} else { // If the query did not run OK.
			echo '<p class="error">The task could not be updated due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
		}
	
	} else { // No confirmation of deletion.
		echo '<p>The task has NOT been marked complete.</p>';	
	}

} else { // Show the form.

	// Retrieve the task information:
	$q = "SELECT t.task_id, DATE_FORMAT(t.due, '%m-%d-%y') AS dd, t.task_desc, u.first_name, u.last_name
	FROM tasks AS t
	INNER JOIN users AS u
	USING (user_id)
	WHERE task_id=$id";
	$r = @mysqli_query ($dbc, $q);

	if (mysqli_num_rows($r) == 1) { // Valid task ID, show the form.

		// Get the task information:
		$row = mysqli_fetch_array ($r, MYSQLI_NUM);
		
		// Display the record being deleted:
		echo "<h3>Task ID No: $row[0]</h3><h3>Due Date: $row[1]</h3><h3>Description: $row[2]</h3><h3>Posted by: $row[3] $row[4]</h3><br />
		<p>Are you sure you want to complete this task?";
		
		// Create the form:
		echo '<form action="complete_task.php" method="post">
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No
	<input type="submit" name="submit" value="Complete" />
	<input type="hidden" name="id" value="' . $id . '" />
	</form></p>';
	
	} else { // Not a valid ID.
		echo '<p class="error">This page has been accessed in error.</p>';
	}

} // End of the main submission conditional.

mysqli_close($dbc);

// Echo panel end tag
echo '</div></div>';

include ('includes/footer.html');
?>