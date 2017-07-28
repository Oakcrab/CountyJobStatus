<?php
// Check for login:
include ('includes/login_check.inc.php');
$page_title = 'View Splits';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';
echo '<h1>View Splits</h1>';
require('includes/split_functions.php');

$split = new splits();

$split->viewSplits();

// Echo panel end tag
echo '</div></div>';
include ('includes/footer.html');
?>