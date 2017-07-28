<?php
// This script performs an INSERT query to add a record to the reporting table.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Add an Entry';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

echo '<h1>Add an Entry</h1>';

// Check for a valid Job ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From *.php link
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">This page has been accessed in error.</p>';
	include ('includes/footer.html'); 
	exit();
}

require ('../mysqli_connect.php'); 

// Check for form submission:
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
	
	$user_id = $_SESSION['user_id'];
	
	if (empty($errors)) { // If everything's OK.
		
		// Run query to count existing reports:
		$q = "SELECT * FROM reporting WHERE job_id=$id";
		$r = @mysqli_query ($dbc, $q);

		// Count the number of returned rows:
		$rep_num = mysqli_num_rows($r);
		
		// Add 1 to get Report #:
		$rep_num++;
		
		// Add the report in the database...
		
		// Make the query:
		$q = "INSERT INTO reporting (job_id, report_no, report_desc, report_title, user_id, reg_date) VALUES ('$id', '$rep_num', '$rd', '$rt', '$user_id', NOW() )";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<p>You have added a new report!</p><p><br />';	
		
			// Redirect to Progress Page:
			echo '<script>
				setTimeout(function(){
					window.location = "job_progress.php?id=' . $id . '";
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
		
		echo '<form action="add_report.php" method="post">
		<p>Title: <input type="text" name="report_title" size="100" maxlength="50" />
		<p>Entry:</p>
		<p><textarea name="report_desc" rows="4" cols="50"></textarea></p>
		<input type="hidden" name="id" value="' . $id . '" />
		<p><input type="submit" name="submit" value="Submit" /></p>
		</form>';
	
		mysqli_close($dbc); // Close the database connection.

	}// End of the main Submit conditional.

// Echo panel end tag
echo '</div></div>';

 include ('includes/footer.html'); 
 
 ?>