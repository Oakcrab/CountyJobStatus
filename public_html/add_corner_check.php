<?php
// This script performs an INSERT query to add a record to the task table.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Add a corner check';
include ('includes/header.html');
echo '<h1>Add a corner check</h1>';

require ('../mysqli_connect.php'); 

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = array(); // Initialize an error array.
	
	// Check for a survey:
	if (empty($_POST['survey'])) {
		$errors[] = 'You forgot to enter a survey.';
	} else {
		$survey = mysqli_real_escape_string($dbc, trim($_POST['survey']));
	}
	
	// Check for a surveyor
	if (empty($_POST['surveyor'])) {
		$errors[] = 'You forgot to enter a surveyor.';
	} else {
		$surveyor = mysqli_real_escape_string($dbc, trim($_POST['surveyor']));
	}
	
	// Check for a section:
	if (empty($_POST['section'])) {
		$errors[] = 'You forgot to enter a section.';
	} else {
		$section = mysqli_real_escape_string($dbc, trim($_POST['section']));
	}
	
	// Check for a twp:
	if (empty($_POST['twp'])) {
		$errors[] = 'You forgot to enter a twp.';
	} else {
		$twp = mysqli_real_escape_string($dbc, trim($_POST['twp']));
	}

	// Check for a rng:
	if (empty($_POST['rng'])) {
		$errors[] = 'You forgot to enter a range.';
	} else {
		$rng = mysqli_real_escape_string($dbc, trim($_POST['rng']));
	}
	
	// Check for a corner:
	if (empty($_POST['corner'])) {
		$errors[] = 'You forgot to enter a corner.';
	} else {
		$corner = mysqli_real_escape_string($dbc, trim($_POST['corner']));
	}
	
	$notes = mysqli_real_escape_string($dbc, trim($_POST['notes']));
	
	if (empty($errors)) { // If everything's OK.
	
		// Add the job in the database...
		
		// Make the query:		
		$q = "INSERT INTO corner_checks (survey, surveyor, section, twp, rng, corner, notes, reg_date) VALUES ('$survey', '$surveyor', '$section', '$twp', '$rng', '$corner', '$notes', NOW() )";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<p>You have added a new corner to check!</p><p><br /></p><p><a href="add_task.php">Add another corner?</a></p>';	
		
			// Redirect to view_jobs.php:
			echo "<script>
				setTimeout(function(){
					window.location = \"corner_checks.php\";
				}, 5000);
			</script>";
		
		} else { // If it did not run OK.
			
			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not add a new check due to a system error. We apologize for any inconvenience.</p>'; 
			
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
		// Print the corner example
		echo '<img src="./includes/corner_example.png">';
		// Run the form: (survey, surveyor, section, twp, rng, corner)
		echo '<form action="add_corner_check.php" method="post">
		<p>Survey Record Number: <input type="text" name="survey" size="10" maxlength="10" /></p>
		<p>Surveyor: <input type="text" name="surveyor" size="40" maxlength="100" /></p>
		<p>Section: <input type="text" name="section" size="2" maxlength="2" /> Township: <input type="text" name="twp" size="2" maxlength="2" /> Range: <input type="text" name="rng" size="2" maxlength="2" /> Corner: <input type="text" name="corner" size="1" maxlength="1" /></p>
		<p>Notes: <textarea name="notes" rows="4" cols="50"></textarea></p>
		<p><input type="submit" name="submit" value="Submit" /></p>
		</form>';
	
		mysqli_close($dbc); // Close the database connection.

	}// End of the main Submit conditional.

include ('includes/footer.html'); 
 
?>