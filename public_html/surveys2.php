<?php

error_reporting(0);

session_start(); // Start the session.

$page_title = 'Surveys';
include ('./includes/header.html');
	
// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

// iframe surveys
echo '<iframe src="http://kc9zxm.com/jackson/surveys" width="100%" height="600px"></iframe>';

// Echo panel end tag
echo '</div></div>';
include ('./includes/footer.html');

?>