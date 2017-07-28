<?php
// This script retrieves all the inventory records.
session_start(); // Start the session.
error_reporting(0);
// If no session value is present, redirect the user:
if (!isset($_SESSION['user_id'] )) {
	// Need the functions:
	require ('includes/login_functions.inc.php');
	redirect_user();	
}
$page_title = 'View Job Types';
include ('includes/header.html');

	// Echo panel start tag
	echo '<div class="panel panel-default">
	  <div class="panel-heading"><h3 class="panel-title">View Job Types <a href="add_job_type.php"><button type="button" class="btn btn-danger">Add</button></a></h3></div>
	<div class="panel-body">';

require ('../mysqli_connect.php');
// Define the query: 
$q = "SELECT type_id, type_name
FROM job_type
ORDER BY type_name ASC";

$r = @mysqli_query ($dbc, $q);
// Count the number of returned rows:
$num = mysqli_num_rows($r);
if ($num > 0) { // If it ran OK, display the records.
	// Print how many jobs there are:
	echo "<p>There are currently $num type of jobs in the database.</p>\n";
	// Table header:
	echo '<table class="table" align="center" cellspacing="3" cellpadding="3" width="100%">
	<tr>
		<td align="left"><b>Edit</b></td>
		<td align="left"><b>Delete</b></td>
		<td align="left"><b>Type Name</b></td>
	</tr>';
	// Fetch and print all the records:
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo '<tr>
			<td align="left"><a href="edit_job_type.php?id=' . $row['type_id'] . '" class="btn btn-default btn-xs" role="button">Edit</a></td>
			<td align="left"><a href="delete_job_type.php?id=' . $row['type_id'] . '" class="btn btn-danger btn-xs" role="button">Delete</a></td>
			<td align="left">' . $row['type_name'] . '</td>
		</tr>';
	}
	echo '</table>';
	mysqli_free_result ($r);	
} else { // If no records were returned.
	echo '<p class="error">There are currently no job types in the database.</p>';
}
echo '</div></div>';
mysqli_close($dbc);
include ('includes/footer.html');
?>