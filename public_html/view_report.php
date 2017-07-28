<?php
// This script retrieves the selected record in a list of reports from reporting table.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'View Entry';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

echo '<h1>View Entry</h1><p>Changes can only be saved if you started this Entry.</p>';

// Check for a valid report ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_jobs.php
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

	$errors = array(); // Initialize an error array.
	
	// Check for a report_title:
	if (empty($_POST['report_title'])) {
		$errors[] = 'You forgot to enter a Title.';
	} else {
		$rt = mysqli_real_escape_string($dbc, trim($_POST['report_title']));
	}
	
	// Check for a report_desc:
	if (empty($_POST['report_desc'])) {
		$errors[] = 'You forgot to enter Text.';
	} else {
		$rd = mysqli_real_escape_string($dbc, trim($_POST['report_desc']));
	}
	
	$job_id = mysqli_real_escape_string($dbc, trim($_POST['job_id']));
	$user_id = mysqli_real_escape_string($dbc, trim($_POST['user_id']));
	
	if (empty($errors)) { // If everything's OK.
	
		if ($user_id == $_COOKIE['user_id']) {	
			
			// Add the report in the database...
			
			// Make the query:
			$q = "UPDATE reporting SET report_desc='$rd', report_title='$rt' WHERE report_id=$id LIMIT 1";		
			$r = @mysqli_query ($dbc, $q); // Run the query.
			if ($r) { // If it ran OK.
			
				// Print a message:
				echo '<p>You have edited a report!</p><p><br />';	
			
				// Redirect to Progress Page:
				echo '<script>
					setTimeout(function(){
						window.location = "job_progress.php?id=' . $job_id . '";
					}, 1500);
				</script>';
			
			} else { // If it did not run OK.
				
				// Public message:
				echo '<h1>System Error</h1>
				<p class="error">You could not add a new report due to a system error. We apologize for any inconvenience.</p>'; 
				
				// Debugging message:
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
							
			} // End of if ($r) IF.
			
			mysqli_close($dbc); // Close the database connection.

			// Include the footer and quit the script:
			include ('includes/footer.html'); 
			exit();
		
		} else {
			
			echo '<p>You cannot edit a report you did not make, please consider making another report about this report.</p>';
			
			mysqli_close($dbc); // Close the database connection.

			// Include the footer and quit the script:
			include ('includes/footer.html'); 
			exit();
			
		}
		
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

	// Retrieve the user's information:
	$q = "SELECT user_id, report_desc, report_title, job_id FROM reporting WHERE report_id=$id";		
	$r = @mysqli_query ($dbc, $q);

	if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

		// Get the user's information:
		$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
	
		// Create the form:
		echo '<form action="view_report.php" method="post">
		<p>Title: <input type="text" name="report_title" size="100" maxlength="50" value="' . $row['report_title'] . '" />
		<p>Entry:</p>
		<p><textarea name="report_desc" rows="8" cols="75">' . $row['report_desc'] . '</textarea></p>
		<input type="hidden" name="id" value="' . $id . '" />
		<input type="hidden" name="job_id" value="' . $row['job_id'] . '" />
		<input type="hidden" name="user_id" value="' . $row['user_id'] . '" />
		<p><input type="submit" name="submit" value="Update" /></p>
		</form>';

	} else { // Not a valid user ID.
	
		echo '<p class="error">This page has been accessed in error.</p>';
		
	}
	
	mysqli_close($dbc); // Close the database connection.

}// End of the main Submit conditional.

// Echo panel end tag
echo '</div></div>';
include ('includes/footer.html');
?>