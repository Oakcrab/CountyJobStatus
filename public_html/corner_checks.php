<?php // This script retrieves all the records from the tasks table.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Corner Checks';
include ('includes/header.html');


// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title">Corner checks<abbr title="Use this page to track corners that were set by a private surveyor."><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></abbr> <a href="add_corner_check.php"><button type="button" class="btn btn-danger">Add</button></a></h3></div>
<div class="panel-body">';

require ('../mysqli_connect.php');

// Define the query:
$q = "SELECT check_id, survey, surveyor, section, twp, rng, corner, notes, checked, DATE_FORMAT(reg_date, '%m-%d-%y') AS date FROM corner_checks ORDER BY reg_date ASC";		
$r = @mysqli_query ($dbc, $q);

// Count the number of returned rows:
$num = mysqli_num_rows($r);

if ($num > 0) { // If it ran OK, display the records.

	// Print how many tasks there are:
	echo "<p>About $num results</p>\n";

	// Table header:
	echo '<div class="table-responsive">
	<table class="table">
	<tr>
		<th>Edit</th>
		<th>Delete</th>
		<th>Survey</th>
		<th>Surveyor</th>
		<th>S-T-R-C</th>
		<th>Notes</th>
		<th>Reg_date</th>
	</tr>';
	
	// Fetch and print all the records:
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo '<tr>
			<td><a href="edit_corner_check.php?id=' . $row['check_id'] . '"><button type="button" class="btn btn-default">Edit</button></a></td>
			<td><a href="delete_corner_check.php?id=' . $row['check_id'] . '"><button type="button" class="btn btn-default">Delete</button></a></td>
			<td>' . $row['survey'] . '</td>
			<td>' . $row['surveyor'] . '</td>
			<td>' . $row['section'] . '-' . $row['twp'] . '-' . $row['rng'] . '-' . $row['corner'] . '</td>
			<td>' . $row['notes'] . '</td>
			<td>' . $row['date'] . '</td>
		</tr>';
	}

	echo '</table></div>';
	mysqli_free_result ($r);	

} else { // If no records were returned.
	echo '<p class="error">There are currently no corner checks in the database.</p>';
}

	
// Echo panel end tag
echo '</div></div>';

mysqli_close($dbc);

include ('includes/footer.html');
?>