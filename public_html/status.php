<?php

error_reporting(0);

#Sessions:

session_start(); // Start the session.
/*
# If session value is present:
if (isset($_SESSION['user_id'] )) {	
	#Run code
}
*/
	
	$page_title = 'Status';
	include ('./includes/header.html');

	if (isset($_SESSION['user_id'] )) {	
		echo '<h1 id="mainhead">Job Status</h1>
			<p><a href="add_job.php">Add a Job</a> or click on a Job Status to access Progress Screen.</p>';
	}
	
	# ECHO THE CODE BELOW FOR 1 MINUTE UPDATES:
	/* 	<p>Changes may take up to a minute to appear.</p>
		
		<script>
			var time = new Date().getTime();

			function refresh() {
				if(new Date().getTime() - time >= 60000) 
					window.location.reload(true);
				else 
					setTimeout(refresh, 60000);
			}

			setTimeout(refresh, 60000);
		</script>
	'; */
	
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
		echo "<p>There are currently $num active jobs in the database.</p>\n";

		// Table header:
		echo '<table align="center" cellspacing="3" cellpadding="3" width="95%">
		<tr>
			<td align="left"><b>Job No</b></td>
			<td align="left"><b>Date</b></td>
			<td align="left"><b>Name</b></td>
			<td align="left"><b>Sec</b></td>
			<td align="left"><b>Twp</b></td>
			<td align="left"><b>Rng</b></td>
			<td align="left"><b>Type</b></td>
			<td align="left"><b>Job Status</b></td>
			<td align="left" width="45%"><b>Notes</b></td>
		</tr>';
		
		// Fetch and print all the records:
		$bg = '#eeeeee'; // First background color
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Alternate between grey and white background
			echo '<tr bgcolor="' . $bg . '">
				<td align="left">' . $row['job_num'] . '</td>
				<td align="left">' . $row['rd'] . '</td>
				<td align="left">' . $row['job_name'] . '</td>
				<td align="left">' . $row['section'] . '</td>
				<td align="left">' . $row['township'] . '</td>
				<td align="left">' . $row['sur_range'] . '</td>
				<td align="left">' . $row['type_name'] . '</td>';
				
				if (isset($_SESSION['user_id'] )) {	
					echo '<td align="left"><a href="job_progress.php?id=' . $row['job_id'] . '"><script>status(' . $row['status'] . ')</script></a></td><td align="left">' . $row['notes'] . '</td>';
				} else {
					echo '<td align="left"><script>status(' . $row['status'] . ')</script></td><td align="left"></td>';
				}
				
			echo '</tr>';
		}

		echo '</table>';
		
	} else {
		echo '<p>There are no active jobs in the database.</p>';
	}
	
	mysqli_free_result ($r);
	
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
		echo "<p>There are currently $num jobs on hold in the database.</p>\n";

		// Table header:
		echo '<table align="center" cellspacing="3" cellpadding="3" width="95%">
		<tr>
			<td align="left"><b>Job No</b></td>
			<td align="left"><b>Date</b></td>
			<td align="left"><b>Name</b></td>
			<td align="left"><b>Sec</b></td>
			<td align="left"><b>Twp</b></td>
			<td align="left"><b>Rng</b></td>
			<td align="left"><b>Type</b></td>
			<td align="left"><b>Job Status</b></td>
			<td align="left" width="45%"><b>Notes</b></td>
		</tr>';
		
		// Fetch and print all the records:
		$bg = '#eeeeee'; // First background color
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Alternate between grey and white background
			echo '<tr bgcolor="' . $bg . '">
				<td align="left">' . $row['job_num'] . '</td>
				<td align="left">' . $row['rd'] . '</td>
				<td align="left">' . $row['job_name'] . '</td>
				<td align="left">' . $row['section'] . '</td>
				<td align="left">' . $row['township'] . '</td>
				<td align="left">' . $row['sur_range'] . '</td>
				<td align="left">' . $row['type_name'] . '</td>';
				
				if (isset($_SESSION['user_id'] )) {	
					echo '<td align="left"><a href="job_progress.php?id=' . $row['job_id'] . '"><script>status(' . $row['status'] . ')</script></a></td><td align="left">' . $row['notes'] . '</td>';
				} else {
					echo '<td align="left"><script>status(' . $row['status'] . ')</script></td><td align="left"></td>';
				}
				
			echo '</tr>';
		}

		echo '</table>';
		
	}
	
	mysqli_free_result ($r);
	
if (isset($_SESSION['user_id'] )) {	

	echo '<br /><h1>Task Status</h1>
	<p><a href="add_task.php">Add a Task</a>.</p>';
	
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
		echo '<table align="center" cellspacing="3" cellpadding="3" width="90%">
		<tr>
			<td align="left"><b>Due Date</b></td>
			<td align="left"><b>Description</b></td>
			<td align="left"><b>Assigned to</b></td>
			<td align="left"><b>Mark Completed</b></td>
		</tr>';
		
		// Fetch and print all the records:
		$bg = '#eeeeee'; // First background color
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Alternate between grey and white background
			echo '<tr bgcolor="' . $bg . '">
				<td align="left">' . $row['dd'] . '</td>				
				<td align="left">
				<a href="#" onclick="window.open(\'edit_task_pop.php?id=' . $row['task_id'] . '\', \'_blank \', \'location=no, top=250, left=500, width=400, height=400\'); ">' . $row['task_desc'] . '</a>
				</td>
				<td align="left">' . $row['first_name'] . '  ' . $row['last_name'] . '</td>
				<td align="left"><a href="complete_task.php?id=' . $row['task_id'] . '">Done</a></td>
			</tr>';
		}

		echo '</table>';
	}
	
	mysqli_free_result ($r);
	
	mysqli_close($dbc);
	
}
	
	#echo '<h1>News</h1>';


include ('./includes/footer.html');

?>