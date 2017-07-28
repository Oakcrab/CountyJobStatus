<?php

session_start(); // Start the session.

// If no session value is present, redirect the user:
if (!isset($_SESSION['user_id'] )) {

	// Need the functions:
	require ('includes/login_functions.inc.php');
	redirect_user();	

}

$page_title = 'Delete a Job Type';
include ('includes/header.html');

	// Echo panel start tag
	echo '<div class="panel panel-default">
	  <div class="panel-heading"><h3 class="panel-title">Delete a Job Type</h3></div>
	<div class="panel-body">';

// Check for a valid type ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<div class="alert alert-danger" role="alert"><p class="error">This page has been accessed in error.</p></div>';
	include ('includes/footer.html'); 
	exit();
}

require ('../mysqli_connect.php');

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($_POST['sure'] == 'Yes') { // Delete the record.

		// Make the query:
		$q = "DELETE FROM job_type WHERE type_id=$id LIMIT 1";		
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Print a message:
			echo '<div class="alert alert-success" role="alert"><p>The job type has been deleted.</p></div>';

			echo "<script>setTimeout(function(){
			  window.location = \"view_job_types.php\";
			}, 3000);</script>";

		} else { // If the query did not run OK.
			echo '<div class="alert alert-danger" role="alert"><p class="error">The job type could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p></div>'; // Debugging message.
		}
	
	} else { // No confirmation of deletion.
		echo '<div class="alert alert-info" role="alert"><p>The job type has NOT been deleted.</p></div>';	
	}

} else { // Show the form.

	// Retrieve the type's information:
	$q = "SELECT type_name FROM job_type WHERE type_id=$id";
	$r = @mysqli_query ($dbc, $q);

	if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

		// Get the type's information:
		$row = mysqli_fetch_array ($r, MYSQLI_NUM);
		
		// Display the record being deleted:
		echo "<div class=\"alert alert-warning\" role=\"alert\"><h3>Name: $row[0]</h3>
		Are you sure you want to delete this job type?</div>";
		
		// Create the form:
		echo '<form action="delete_job_type.php" method="post">
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No
	<input type="submit" name="submit" value="Submit" />
	<input type="hidden" name="id" value="' . $id . '" />
	</form>';
	
	} else { // Not a valid type ID.
		echo '<div class="alert alert-danger" role="alert"><p class="error">This page has been accessed in error.</p></div>';
	}

} // End of the main submission conditional.

mysqli_close($dbc);
		
include ('includes/footer.html');
?>