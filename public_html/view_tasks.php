<?php
// This script retrieves all the records from the tasks table.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'View Tasks';
include ('includes/header.html');

	// Echo panel start tag
	echo '<div class="panel panel-default">
	  <div class="panel-heading">
	  <h3 class="panel-title">Tasks <abbr title="This is the tasks page. Use this page to track anything that is not a project with a job number."><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></abbr>
	  <a href="add_task.php">
	  <button type="button" class="btn btn-danger">Add</button>
	  </a>
	  </h3>
	  </div>
	<div class="panel-body">';

require ('../mysqli_connect.php');

// TODO call for users as in creator and assigned
// Define the query:
$q = "SELECT t.task_id, DATE_FORMAT(t.due, '%m-%d-%y') AS dd, t.task_desc, u.first_name, u.last_name
	FROM tasks AS t
	INNER JOIN users AS u
	USING (user_id)
	ORDER BY due ASC";		
$r = @mysqli_query ($dbc, $q);

// Count the number of returned rows:
$num = mysqli_num_rows($r);

if ($num > 0) { // If it ran OK, display the records.

	// Print how many tasks there are:
	echo "$num results";

	// Table header:
	echo '<div class="table-responsive">
	<table class="table">
	<tr>
		<th>Edit</th>
		<th>Delete</th>
		<th>Due Date</th>
		<th>Description</th>
	</tr>';
	
	// Fetch and print all the records:
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo '<tr>
			<td><a href="edit_task.php?id=' . $row['task_id'] . '"><button type="button" class="btn btn-default">Edit</button></a></td>
			<td><a href="delete_task.php?id=' . $row['task_id'] . '"><button type="button" class="btn btn-default">Delete</button></a></td>
			<td>' . $row['dd'] . '</td>
			<td>' . $row['task_desc'] . '</td>
		</tr>';
	}

	echo '</table></div>';
	mysqli_free_result ($r);	

} else { // If no records were returned.
	echo '<p class="error">There are currently no tasks in the database.</p>';
}

	
// Echo panel end tag
echo '</div></div>';

mysqli_close($dbc);

include ('includes/footer.html');
?>
