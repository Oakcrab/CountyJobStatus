<?php
// This script retrieves all the records from the jobs table.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'View all Jobs';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title">Jobs <a href="add_job.php"><button type="button" class="btn btn-danger">Add</button></a></h3></div>
<div class="panel-body">';

require ('../mysqli_connect.php');

// Number of records to display per page:
$display = 20;

// Determine how many pages there are:
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already determined.
	$pages = $_GET['p'];
} else { // need to determine.
	// Count the number of records:
	$q = "SELECT COUNT(job_id) FROM jobs";
		$r = @mysqli_query ($dbc, $q);
	$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
	$records = $row[0];
	// Calculate the number of pages...
	if ($records > $display) { // More than 1 page.
		$pages = ceil ($records/$display);
	} else {
		$pages = 1;
	}
} // End of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Determine the sort...
// Default is by registration date.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// Determine the sorting order:
switch ($sort) {
	case 'no':
		$order_by = 'j.job_num ASC';
		break;
	case 'name':
		$order_by = 'j.job_name ASC';
		break;
	case 'sec':
		$order_by = 'j.section ASC';
		break;
	case 'twp':
		$order_by = 'j.township ASC';
		break;
	case 'range':
		$order_by = 'j.sur_range ASC';
		break;
	case 'type':
		$order_by = 't.type_name ASC';
		break;
	default:
		$order_by = 'j.job_num ASC';
		$sort = 'rd';
		break;
}

// Determine the sort...
// Default is by registration date.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// Define the query:
$q = "SELECT j.job_id, j.job_num, j.job_name, j.job_desc, j.section, j.township, j.sur_range, t.type_name
	FROM jobs AS j
	INNER JOIN job_type AS t
	USING (type_id)
	ORDER BY $order_by 
	LIMIT $start, $display";		
$r = @mysqli_query ($dbc, $q);

// Count the number of returned rows:
$num = mysqli_num_rows($r);

if ($num > 0) { // If it ran OK, display the records.

	// Print how many jobs there are:
	echo "$records results, showing $display per page";

	// Table header:
	echo '<div class="table-responsive">
	<table class="table">
	<tr>
		<th><a href="view_jobs.php?sort=no">Job No</a></th>
		<th><a href="view_jobs.php?sort=name">Job Name</a></th>
		<th>Description</th>
		<th><a href="view_jobs.php?sort=sec">Sec</a></th>
		<th><a href="view_jobs.php?sort=twp">Twp</a></th>
		<th><a href="view_jobs.php?sort=range">Rng</a></th>
		<th><a href="view_jobs.php?sort=type">Type</a></th>
	</tr>';
	
	// Fetch and print all the records:
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo '<tr>
			<td><a href="job_progress.php?id=' . $row['job_id'] . '"><button type="button" class="btn btn-default">' . $row['job_num'] . '</button></a></td>
			<td>' . $row['job_name'] . '</td>
			<td>' . $row['job_desc'] . '</td>
			<td>' . $row['section'] . '</td>
			<td>' . $row['township'] . '</td>
			<td>' . $row['sur_range'] . '</td>
			<td>' . $row['type_name'] . '</td>
		</tr>';
	}

	echo '</table></div>';
	mysqli_free_result ($r);	

} else { // If no records were returned.
	echo '<p class="error">There are currently no jobs in the database.</p>';
}

mysqli_close($dbc);

// Make the links to other pages, if necessary.
if ($pages > 1) {
	
	echo '<nav>
  <ul class="pagination">';
  
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a Previous button:
	if ($current_page != 1) {
		echo '<li><a href="view_jobs.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
	}
	
	// Make all the numbered pages:
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<li><a href="view_jobs.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a></li> ';
		}
	} // End of FOR loop.
	
	// If it's not the last page, make a Next button:
	if ($current_page != $pages) {
		echo '<li><a href="view_jobs.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '"aria-label="Next">
        <span aria-hidden="true">&raquo;</span></a></li>';
	}
	
	echo '  </ul>
</nav>'; // Close the pagination
	
	
} // End of links section.

// Echo panel end tag
echo '</div></div>';

include ('includes/footer.html');
?>