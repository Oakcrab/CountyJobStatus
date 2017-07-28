<?php

error_reporting(0); // setto 0 to hide errors

session_start(); // Start the session.

$page_title = 'Status';
$addToHeader = '<meta http-equiv="refresh" content="240">';
include ('./includes/header.html');

if (isset($_SESSION['user_id'] )) {	
	
	// Echo panel start tag
	echo '<div class="panel panel-default">
	  <div class="panel-heading"><h3 class="panel-title">Active Jobs <a href="add_job.php"><button type="button" class="btn btn-danger">Add</button></a></h3></div>
	<div class="panel-body">';

	require ('../mysqli_connect.php');
	
	// Define the query:
	$q = "SELECT j.job_id, DATE_FORMAT(j.reg_date, '%m-%d-%y') AS rd, j.job_num, j.job_name, j.section, j.township, j.sur_range, j.status, j.notes, t.type_name
	FROM jobs AS j
	INNER JOIN job_type AS t
	USING (type_id)
	WHERE status IN (0, 1, 2, 3, 5, 6) 
	ORDER BY j.job_num ASC";
	
	$r = @mysqli_query ($dbc, $q);

	// Count the number of returned rows:
	$num = mysqli_num_rows($r);
	
	if ($num > 0) { // If it ran OK, display the records.

		// Print how many jobs there are:
		echo "$num results";

		// Table header:
		echo '<div class="table-responsive">
		<table class="table">
		<tr>
			<th>Job No</th>
			<th>Date</th>
			<th>Name</th>
			<th>S-T-R</th>
			<th>Type</th>
			<th>Progress</th>
			<th>Notes</th>
		</tr>';
		
		// Fetch and print all the records:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			echo '<tr>
				<td><a href="job_progress.php?id=' . $row['job_id'] . '"><button type="button" class="btn btn-default">' . $row['job_num'] . '</button></a></td>
				<td>' . $row['rd'] . '</td>
				<td>' . $row['job_name'] . '</td>
				<td>' . $row['section'] . '-' . $row['township'] . '-' . $row['sur_range'] . '</td>
				<td>' . $row['type_name'] . '</td>
				<td><script>status(' . $row['status'] . ')</script></td>
				<td>' . $row['notes'] . '</td>
			</tr>';
		}

		echo '</table></div>';
		
	} else {
		echo '<p>There are no active jobs in the database.</p>';
	}
	
	// Echo panel end tag
	echo '</div></div>';
	
	mysqli_free_result ($r);
	
	// Echo panel start tag
	echo '<div class="panel panel-default">
	 <div class="panel-heading"><h3 class="panel-title">On Hold Jobs</h3></div>
	<div class="panel-body">';
	
	// Define the query:
	$q = "SELECT j.job_id, DATE_FORMAT(j.reg_date, '%m-%d-%y') AS rd, j.job_num, j.job_name, j.section, j.township, j.sur_range, j.status, j.notes, t.type_name
	FROM jobs AS j
	INNER JOIN job_type AS t
	USING (type_id)
	WHERE status = 4  
	ORDER BY j.job_num ASC";
	
	$r = @mysqli_query ($dbc, $q);

	// Count the number of returned rows:
	$num = mysqli_num_rows($r);
	
	if ($num > 0) { // If it ran OK, display the records.
	
		// Print how many jobs there are:
		echo "$num results";

		// Table header:
		echo '<div class="table-responsive">
		<table class="table">
		<tr>
			<th>Job No</th>
			<th>Date</th>
			<th>Name</th>
			<th>S-T-R</th>
			<th>Type</th>
			<th>Progress</th>
			<th>Notes</th>
		</tr>';
		
		// Fetch and print all the records:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			echo '<tr>
				<td><a href="job_progress.php?id=' . $row['job_id'] . '"><button type="button" class="btn btn-default">' . $row['job_num'] . '</button></a></td>
				<td>' . $row['rd'] . '</td>
				<td>' . $row['job_name'] . '</td>
				<td>' . $row['section'] . '-' . $row['township'] . '-' . $row['sur_range'] . '</td>
				<td>' . $row['type_name'] . '</td>
				<td><script>status(' . $row['status'] . ')</script></td>
				<td>' . $row['notes'] . '</td>
			</tr>';
		}

		echo '</table></div>';
		
	}
	
	// Echo panel end tag
	echo '</div></div>';
	
	mysqli_free_result ($r);

	// Echo panel start tag
	echo '<div class="panel panel-default">
	<div class="panel-heading"><h3 class="panel-title">Tasks <a href="add_task.php"><button type="button" class="btn btn-danger">Add</button></a></h3></div>
	<div class="panel-body">';
	
	// Define the query:
	$q = "SELECT t.task_id, DATE_FORMAT(t.due, '%m-%d-%y') AS dd, t.task_desc, u.first_name, u.last_name
		FROM tasks AS t
		INNER JOIN users AS u
		ON t.assigned_id = u.user_id
		WHERE complete IS NULL
		ORDER BY due ASC";		
	$r = @mysqli_query ($dbc, $q);

	// Count the number of returned rows:
	$num = mysqli_num_rows($r);

	if ($num > 0) { // If it ran OK, display the records.

		// Print how many tasks there are:
		echo "<p>There are currently $num active tasks in the database.</p>\n";
		
		// Table header:
		echo '<div class="table-responsive">
		<table class="table">
		<tr>
			<th>Due Date</th>
			<th>Description</th>
			<th>Assigned to</th>
			<th>Mark Completed</th>
		</tr>';
		
		$date = strtotime("+1 day", time());
		
		// Fetch and print all the records:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			echo '<tr>
				<td';
			if ($row['dd'] < date('m-d-y'))	{ // If past due make red and flash the first one
				echo ' id="flashingtext" style="color:red;" ';
			} elseif ($row['dd'] == date('m-d-y')) { // If on the due date make orange and flash the first one
				echo ' style="color:orange;" ';				
			//} elseif ($row['dd'] == date('m-d-y', $date)) { // If the due date is one day away make yellow and flash the first
			//	echo ' style="color:yellow; text-shadow: 2px 2px 8px black;" ';
			} else {
				//continue as normal
			}
				echo '>' . $row['dd'] . '</td>				
				<td>
				<a href="#" onclick="window.open(\'edit_task_pop.php?id=' . $row['task_id'] . '\', \'_blank \', \'location=no, top=250, left=500, width=400, height=400\'); ">' . $row['task_desc'] . '</a>
				</td>
				<td>' . $row['first_name'] . '  ' . $row['last_name'] . '</td>
				<td><a href="complete_task.php?id=' . $row['task_id'] . '">Done</a></td>
			</tr>';
		}

		echo '</table></div>';
	}
	
		// Echo panel end tag
	echo '</div></div>';

	mysqli_free_result ($r);
	
	mysqli_close($dbc);
	
} else {
	
	// Echo panel start tag
	echo '<div class="panel panel-default">
	<div class="panel-body">';

	echo '<form class="navbar-form navbar-right" role="form" action="login.php" method="post">
							<div class="form-group">
							  <input type="text" name="email" placeholder="Email" class="form-control">
							</div>
							<div class="form-group">
							  <input type="password" name="pass" placeholder="Password" class="form-control">
							</div>
							<button type="submit" name="submit" class="btn btn-success">Sign in</button>
						  </form>';
	
	// Echo panel end tag
	echo '</div></div>';
	
}
	
	#echo '<h1>News</h1>';


include ('./includes/footer.html');

?>