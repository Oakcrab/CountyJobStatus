<?php

error_reporting(0);

$page_title = 'Surveys';
include ('./includes/header.html');
	
// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

// Set the time zone:
date_default_timezone_set('America/New_York');

// Set the directory name and scan it:
$search_dir = "http://kc9zxm.com//jackson/surveys";
$contents = scandir($search_dir);

// Create a table header:
print '<hr /><h2>Surveys</h2>
<div class="table-responsive">
<table class="table">
<tr>
<td>Name</td>
<td>Size</td>
<td>Last Modified</td>
</tr>';

// List the files:
foreach ($contents as $item) {
	if ( (is_file($search_dir . '/' . $item)) AND (substr($item, 0, 1) != '.') ) {
	
		// Get the file size:
		$fs = filesize($search_dir . '/' . $item);

		// Get the file's modification date:
		$lm = date('F j, Y', filemtime($search_dir . '/' . $item));

		// Print the information:
		print "<tr>
		<td><a href='http://kc9zxm.com/jackson/surveys/$item'>$item</a></td>
		<td>$fs bytes</td>
		<td>$lm</td>
		</tr>\n";
	
	} else {
		print 'Not a directory!?';
	}// Close the IF.

} // Close the FOREACH.

print '</table></div>'; // Close the HTML table.

// Echo panel end tag
echo '</div></div>';
include ('./includes/footer.html');

?>