<?php
// This page is for deleting a task record.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Delete a corner check';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

echo '<h1>Delete a corner check</h1><br />';

// Check for a valid task ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From corner_checks.php
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
		$q = "DELETE FROM corner_checks WHERE check_id=$id LIMIT 1";		
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Print a message:
			echo '<p>The corner check has been deleted.</p>';
			
			// Redirect to view_tasks.php:
			echo "<script>
				setTimeout(function(){
					window.location = \"corner_checks.php\";
				}, 1500);
			</script>";

		} else { // If the query did not run OK.
			echo '<p class="error">The corner check could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
		}
	
	} else { // No confirmation of deletion.
		echo '<p>The corner check has NOT been deleted.</p>';	
	}

} else { // Show the form.

	// Retrieve the job's information:
	$q = "SELECT check_id, survey, surveyor, section, twp, rng, corner, notes FROM corner_checks WHERE check_id=$id";
	$r = @mysqli_query ($dbc, $q);

	if (mysqli_num_rows($r) == 1) { // Valid job ID, show the form.

		// Get the job's information:
		$row = mysqli_fetch_array ($r, MYSQLI_NUM);
		
		// Display the record being deleted:
		echo "<h3>Check ID No: $row[0]</h3><h3>Survey: $row[1]</h3><h3>Surveyor: $row[2]</h3><h3>S-T-R-C: $row[3]-$row[4]-$row[5]-$row[6]</h3><h3>Notes: $row[7]</h3><br />
		<p>Are you sure you want to delete this task?";
		
		// Create the form:
		echo '<form action="delete_corner_check.php" method="post">
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