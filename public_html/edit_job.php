<?php
// This page is for editing a job record.
// This page is accessed through view_jobs.php.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Edit a Job';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

echo '<h1>Edit a Job</h1>';

// Check for a valid Job ID, through GET or POST:
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

	$errors = array();
	
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
	$sta = mysqli_real_escape_string($dbc, trim($_POST['sta']));
	
	
	if (empty($errors)) { // If everything's OK.

		// Make the query:
		$q = "UPDATE jobs SET job_num='$jono', job_name='$jona', section='$sec', township='$twp', sur_range='$rng', type_id='$type', notes='$n', job_desc='$jode', user_id='$pt', status='$sta' WHERE job_id=$id LIMIT 1";
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Print a message:
			echo '<p>The job has been edited.</p>';	
			
		} else { // If it did not run OK.
			echo '<p class="error">The job could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
		}
		
	} else { // Report the errors.

		echo '<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p>';
	
	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form...

// Retrieve the user's information:
$q_form = "SELECT j.job_num, j.job_name, j.job_desc, j.section, j.township, j.sur_range, j.user_id, u.last_name, u.first_name, j.type_id, t.type_name, j.notes, j.status
FROM jobs AS j 
INNER JOIN users AS u 
USING (user_id) 
INNER JOIN job_type AS t 
USING (type_id) 
WHERE j.job_id=$id";		
$r_form = @mysqli_query ($dbc, $q_form);

if (mysqli_num_rows($r_form) == 1) { // Valid user ID, show the form.

	// Get the Job information:
	$row_form = mysqli_fetch_array ($r_form, MYSQLI_ASSOC);
		
		echo '<form action="edit_job.php" method="post">
		<p>*Job No.: <input type="text" name="job_num" value="' . $row_form['job_num'] . '" size="5" maxlength="5" />
		</p><p>*Job Name: <input type="text" name="job_name" size="40" maxlength="60" value="' . $row_form['job_name'] . '" /></p>
		<p>Description: <input type="text" name="job_desc" size="100" maxlength="250" value="' . $row_form['job_desc'] . '" /></p>
		<p>Section: <select name="section"><option value="' . $row_form['section'] . '">' . $row_form['section'] . '</option>';
		
		foreach (range(0,36) as $sec) {
			echo '<option value="' . $sec . '">' . $sec . '</option>';
		}
		
		echo '</select>	Township: <select name="township">
		<option value="' . $row_form['township'] . '">T' . $row_form['township'] . 'N</option>'; 
		
		foreach (range(3,7) as $twp) {
			echo '<option value="' . $twp . '">T' . $twp . 'N</option>';
		}
		
		echo '</select> Range: <select name="range">
		<option value="' . $row_form['sur_range'] . '">R' . $row_form['sur_range'] . 'E</option>';
		
		foreach (range(2,6) as $rng) {
			echo '<option value="' . $rng . '">R' . $rng . 'E</option>';
		}
		
		echo '</select></p>
		<p>*Primary Tech: <select name="user_id"><option value="' . $row_form['user_id'] . '">' . $row_form['last_name'] . ', ' . $row_form['first_name'] . '</option>';
		// SELECT Drop down list values from users table:
		// Define the query:
		$q_user = "SELECT user_id, first_name, last_name FROM users ORDER BY last_name ASC";		
		$r_user = @mysqli_query ($dbc, $q_user);

		// Fetch and print all the records:
		while ($row_user = mysqli_fetch_array($r_user, MYSQLI_ASSOC)) {
			echo '<option value="' . $row_user['user_id'] . '">' . $row_user['last_name'] . ', ' . $row_user['first_name'] . '</option>';
		}

		mysqli_free_result ($r_user);
		
		echo '</select></p>
		<p>*Job Type: <select name="type_id"><option value="' . $row_form['type_id'] . '">' . $row_form['type_name'] . '</option>';
		// SELECT Drop down list values from job_type table:
		// Define the query:
		$q_type = "SELECT type_id, type_name FROM job_type ORDER BY type_name ASC";		
		$r_type = @mysqli_query ($dbc, $q_type);

		// Fetch and print all the records:
		while ($row_type = mysqli_fetch_array($r_type, MYSQLI_ASSOC)) {
			echo '<option value="' . $row_type['type_id'] . '">' . $row_type['type_name'] . '</option>';
		}

		mysqli_free_result ($r_type);
		
		echo '</select></p>
		<p>Status: 
			<select name="sta">
				<option value="' . $row_form['status'] . '"><script>status(' . $row_form['status'] . ')</script></option>
				<option value="0">Start</option>
				<option value="1">Research</option>
				<option value="2">Locate</option>
				<option value="3">In Progress</option>
				<option value="4">On Hold</option>
				<option value="5">Set Corners</option>
				<option value="6">Finalizing</option>
				<option value="7">Complete</option>
			</select>
		</p>
		<p>Notes: <textarea name="notes" rows="8" cols="50">' . $row_form['notes'] . '</textarea></p>
		<input type="hidden" name="id" value="' . $id . '" />
		<p><input type="submit" name="submit" value="Submit" /></p>
		</form>
		<p>(*) Marks the items that must be completed to submit.</p>';
		
		mysqli_free_result ($r_form);

} else { // Not a valid user ID.
	echo '<p class="error">This page has been accessed in error.</p>';
}

// Echo panel end tag
echo '</div></div>';

mysqli_close($dbc);

include ('includes/footer.html');

?>