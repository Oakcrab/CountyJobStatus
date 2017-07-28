<?php
// This script performs an INSERT query to add a record to the splits table.

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Add a Split';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

echo '<h1>Add a Split</h1>';

$split = new splits();

$split->addSplit();

// Echo panel end tag
echo '</div></div>';

include ('includes/footer.html'); 
 
?>