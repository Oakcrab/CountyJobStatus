<?php

// Check for login:
include ('includes/login_check.inc.php');

// Set the page title and include the HTML header:
$page_title = 'Logged In!';
include ('includes/header.html');
require ('includes/login_functions.inc.php');

mark_login();

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

// Print a customized message:
echo "<h1>Logged In!</h1>
<p>You are now logged in, {$_SESSION['first_name']}!</p>
<script>
	setTimeout(function(){window.location = \"index.php\";}, 1500);
</script>";

// Echo panel end tag
echo '</div></div>';

include ('includes/footer.html');

?>