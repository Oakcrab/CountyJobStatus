<?php

error_reporting(0);

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Update Job Progression';
include ('includes/header.html');

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
	
	// Check for a status:
	if (empty($_POST['sta'])) {
		$errors[] = 'Error updateing status.';	
	} else {
		$sta = mysqli_real_escape_string($dbc, trim($_POST['sta']));
	}

	// Make notes variable	
	$notes = mysqli_real_escape_string($dbc, trim($_POST['notes']));
	
	if (empty($errors)) { // If everything's OK.		

		// Make the query:		
		$q = "UPDATE jobs SET status='$sta', notes='$notes' WHERE job_id=$id LIMIT 1";
		$r = @mysqli_query ($dbc, $q);

		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.	

			// Print a message:		
			echo '<p class="alert alert-success" role="alert">The job has been updated.</p>';
			
			//  If status is set to 7 (complete) then set complete date and print message
			if ($sta == 7) { // 7 IS THE CURRENT COMPLETE INT
				$q = "UPDATE jobs SET complete=NOW() WHERE job_id=$id LIMIT 1";
				$r = @mysqli_query ($dbc, $q);
				
				if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.		
					// Print a message:	
					echo '<p class="alert alert-success" role="alert">The job complete date has been set.</p>';
				} else {
					echo '<p class="alert alert-danger" role="alert">Job complete date could not be set, please contact administrator.</p>';
				}
			}
		} else { // If it did not run OK.	
		
			echo '<p class="alert alert-danger" role="alert">The job could not be updated due to a system error. We apologize for any inconvenience. Please contact administrator.</p>'; // Public message.
			//echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.		
		}
	
	} else { // Report the errors.		
	
		echo '<p class="alert alert-danger" role="alert">The following error(s) occurred:<br />';
		
		foreach ($errors as $msg) { // Print each error.	
			echo " - $msg<br />\n";		
		}

		echo '</p><p class="alert alert-warning" role="alert">Please try again.</p>';	
	} // End of if 	(empty($errors)) IF.
} // End of submit conditional.

// Always show the form...
// Retrieve the job's information:
$q = "SELECT j.job_num, j.job_name, j.section, j.township, j.sur_range, j.status, j.notes, t.type_name, j.job_desc, j.job_id	FROM jobs AS j	INNER JOIN job_type AS t	USING (type_id)	WHERE job_id=$id";	

$r = @mysqli_query ($dbc, $q);

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title">Job Status <a class="btn btn-default" href="' . $_SERVER['HTTP_REFERER'] . '" role="button">Back</a></h3></div>
<div class="panel-body">';

if (mysqli_num_rows($r) == 1) { // Valid job ID, show the form.	

	// Get the jobs's information:	
	$row = mysqli_fetch_array ($r, MYSQLI_NUM);
	
	// calculate percent done
	$numSta = (int)$row[5];
	$perDone = ($numSta/7) * 100;
	
	// Create the text:	
	echo '	<div class="progress">
		<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: ' . $perDone . '%;">
			<span class="sr-only">60% Complete</span>
		</div>
	</div>
	<p>Job Number: ' . $row[0] . '</p>
	<p>Job Name: ' . $row[1] . '</p>
	<p>Description: ' . $row[8] . '</p>
	<p>Section: ' . $row[2] . ' - ' . $row[3] . ' - ' . $row[4] . '</p>
	<p>Job Type: ' . $row[7] . '</p>';	
	

	
	// Create the form:
	echo '<form action="job_progress.php" method="post">
	<p>Status:
		<select name="sta">
			<option value="' . $row[5] . '"><script>status(' . $row[5] . ')</script></option>
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
	<p>Notes:</p>
	<p><textarea name="notes" rows="8" cols="50">' . $row[6] . '</textarea></p>	<input type="hidden" name="id" value="' . $id . '" />
	<p><input class="btn btn-primary" type="submit" name="submit" value="Update" /> <a href="edit_job.php?id=' . $row[9] . '"><button type="button" class="btn btn-default">Edit</button></a> <a href="delete_job.php?id=' . $row[9] . '"><button type="button" class="btn btn-danger">Delete</button></a></p>
	</form>';

} else { // Not a valid job ID.
	echo '<p class="error">This page has been accessed in error.</p>';
}

// Echo panel end tag
echo '</div></div>';

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title">Job Log <a href="add_report.php?id=' . $id . '"><button type="button" class="btn btn-primary">Add</button></a></h3></div>
<div class="panel-body">';

// Retrive the job's reports
$q = "SELECT r.report_id, r.report_no, r.report_title, r.report_desc, DATE_FORMAT(r.reg_date, '%m-%d-%y') AS rd, u.last_name, u.first_name	FROM reporting AS r	INNER JOIN users AS u	USING (user_id)	WHERE job_id=$id";	

$r = @mysqli_query ($dbc, $q);

// Count the number of returned rows:
$num = mysqli_num_rows($r);	

if ($num > 0) { // If it ran OK, display the records.	

	// Print how many jobs there are:	
	echo "$num results";	

	// Table header:	
	echo '<div class="table-responsive"><table class="table">	<tr>
		<td align="left"><b>Entry No.</b></td>
		<td align="left" width="75px"><b>Date</b></td>
		<td align="left" width="100px"><b>Report by</b></td>
		<td align="left" width="80px"><b>Title</b></td>
		<td align="left"><b>Entry</b></td>
	</tr>';	
	
	// Fetch and print all the records:		
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {			
		echo '<tr>
			<td><a href="view_report.php?id=' . $row['report_id'] . '"><button type="button" class="btn btn-default">' . $row['report_no'] . '</button></a></td>
			<td>' . $row['rd'] . '</td>				
			<td>' . $row['last_name'] . ', ' . $row['first_name'] . '</td>
			<td>' . $row['report_title'] . '</td>
			<td>' . $row['report_desc'] . '</td>
		</tr>';		
	}	
	
	echo '</table></div>';
	
} else {		
	echo '<p>There are no entries in the database for this job.</p>';	
}

// Echo panel end tag
echo '</div></div>';

mysqli_close($dbc);

include ('includes/footer.html');

?>