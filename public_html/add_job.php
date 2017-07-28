<?php # add_job.php
// This script performs an INSERT query to add a record to the jobs table.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Add a Job';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

echo '<h1>Add a Job</h1>';

require ('../mysqli_connect.php'); 

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = array(); // Initialize an error array.
	
	// Check for a job number:
	if (empty($_POST['job_num'])) {
		$errors[] = 'You forgot to enter a job number.';
	} else {
		$jono = mysqli_real_escape_string($dbc, trim($_POST['job_num']));
	}
	
	// Check for a job name:
	if (empty($_POST['job_name'])) {
		$errors[] = 'You forgot to enter a job name.';
	} else {
		$jona = mysqli_real_escape_string($dbc, trim($_POST['job_name']));
	}
	
	// Check for a type:
	if (empty($_POST['type_id'])) {
		$errors[] = 'You forgot to enter a job type.';
	} else {
		$type = mysqli_real_escape_string($dbc, trim($_POST['type_id']));
	}
	
	// Check for a user_id:
	if (empty($_POST['user_id'])) {
		$errors[] = 'You forgot to enter a Primary Tech.';
	} else {
		$pt = mysqli_real_escape_string($dbc, trim($_POST['user_id']));
	}
	
	// List of null items:
	$n = mysqli_real_escape_string($dbc, trim($_POST['notes']));
	$sec = mysqli_real_escape_string($dbc, trim($_POST['section']));
	$twp = mysqli_real_escape_string($dbc, trim($_POST['township']));
	$rng = mysqli_real_escape_string($dbc, trim($_POST['range']));
	$jode = mysqli_real_escape_string($dbc, trim($_POST['job_desc']));
	
	if (empty($errors)) { // If everything's OK.
	
		// Add the job in the database...
		
		// Make the query:
		$q = "INSERT INTO jobs (job_num, job_name, section, township, sur_range, type_id, notes, job_desc, user_id, reg_date) VALUES ('$jono', '$jona', '$sec', '$twp', '$rng', '$type', '$n', '$jode', '$pt', NOW() )";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<p>You have added a new job!</p><p><br /></p><p><a href="add_job.php">Add another job</a></p>';	
		
			// Redirect to view_jobs.php:
			echo "<script>
				setTimeout(function(){
					window.location = \"view_jobs.php\";
				}, 5000);
			</script>";
		
		} else { // If it did not run OK.
			
			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not add a new job due to a system error. We apologize for any inconvenience.</p>'; 
			
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
		
		$q_job = "SELECT job_num FROM jobs ORDER BY job_num DESC LIMIT 1";
		$r_job = @mysqli_query ($dbc, $q_job);
		$last_job = mysqli_fetch_array($r_job, MYSQLI_ASSOC);
		$new_num = $last_job['job_num'] + 1;
		$cur_ser = date('y') * 1000;
		
		echo '<form action="add_job.php" method="post">
		<p>*Job No.: <input type="text" name="job_num" value="' . $new_num . '" size="5" maxlength="5" />';
		
		if ($new_num < $cur_ser) {
			echo ' New Job Number is in previous year series, consider ' . $cur_ser . ' series.';
		}
		
		echo '</p><p>*Job Name: <input type="text" name="job_name" size="40" maxlength="60" /></p>
		<p>Description: <input type="text" name="job_desc" size="100" maxlength="250" /></p>
		<p>Section: <select name="section">';
		
		foreach (range(0,36) as $sec) {
			echo '<option value="' . $sec . '">' . $sec . '</option>';
		}
		
		echo '</select>	Township: <select name="township">
		<option value="0">0</option>'; 
		
		foreach (range(3,7) as $twp) {
			echo '<option value="' . $twp . '">T' . $twp . 'N</option>';
		}
		
		echo '</select> Range: <select name="range">
		<option value="0">0</option>';
		
		foreach (range(2,6) as $rng) {
			echo '<option value="' . $rng . '">R' . $rng . 'E</option>';
		}
		
		echo '</select></p>
		<p>*Primary Tech: <select name="user_id"><option></option>';
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
		<p>*Job Type: <select name="type_id"><option></option>';
		// SELECT Drop down list values from job_type table:
		// Define the query:
		$q = "SELECT type_id, type_name FROM job_type ORDER BY type_name ASC";		
		$r = @mysqli_query ($dbc, $q);

		// Count the number of returned rows:
		$num = mysqli_num_rows($r);

		// Fetch and print all the records:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			echo '<option value="' . $row['type_id'] . '">' . $row['type_name'] . '</option>';
		}

		mysqli_free_result ($r);
		
		echo '</select></p>
		<p>Notes: <textarea name="notes" rows="4" cols="50"></textarea></p>
		<p><input type="submit" name="submit" value="Submit" /></p>
		</form>
		<p>(*) Marks the items that must be completed to submit.</p>';
	
		mysqli_close($dbc); // Close the database connection.

	}// End of the main Submit conditional.

// Echo panel end tag
echo '</div></div>';

 include ('includes/footer.html'); 
 
 ?>