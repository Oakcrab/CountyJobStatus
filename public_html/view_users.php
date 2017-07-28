<?php # Script 10.1 - view_users.php #3
// This script retrieves all the records from the users table.
// This new version links to edit and delete pages.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'View the Current Users';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

echo '<h1>Registered Users</h1>';

require ('../mysqli_connect.php');
		
// Define the query:
$q = "SELECT last_name, first_name, DATE_FORMAT(reg_date, '%M %d, %Y') AS dr, user_id FROM users ORDER BY reg_date ASC";		
$r = @mysqli_query ($dbc, $q);

// Count the number of returned rows:
$num = mysqli_num_rows($r);

if ($num > 0) { // If it ran OK, display the records.

	// Print how many users there are:
	echo "$num results";

	// Table header:
	echo '<div class="table-responsive">
	<table class="table">
	<tr>
		<th>Edit</th>
		<th>Delete</th>
		<th>Last Name</th>
		<th>First Name</th>
		<th>Date Registered</th>
	</tr>
';
	
	// Fetch and print all the records:
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo '<tr>
			<td><a href="edit_user.php?id=' . $row['user_id'] . '"><button type="button" class="btn btn-default">Edit</button></a></td>
			<td><a href="delete_user.php?id=' . $row['user_id'] . '"><button type="button" class="btn btn-default">Delete</button></a></td>
			<td>' . $row['last_name'] . '</td>
			<td>' . $row['first_name'] . '</td>
			<td>' . $row['dr'] . '</td>
		</tr>
		';
	}

	echo '</table></div>';
	mysqli_free_result ($r);	

} else { // If no records were returned.
	echo '<p class="error">There are currently no registered users.</p>';
}

// Echo panel end tag
echo '</div></div>';

mysqli_close($dbc);

include ('includes/footer.html');
?>