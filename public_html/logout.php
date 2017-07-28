<?php
// This page lets the user logout.

#----------------------- SESSIONS ------------------------------------------------
session_start(); // Access the existing session.

# If no session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {

	// Need the functions:
	require ('includes/login_functions.inc.php');
	redirect_user();	
	
} else { // Cancel the session:

	$_SESSION = array(); // Clear the variables.
	session_destroy(); // Destroy the session itself.
	setcookie ('PHPSESSID', '', time()-3600, '/', '', 0, 0); // Destroy the cookie.

}


#--------------------- COOKIES ----------------------------------------------------
# If no cookie is present, redirect the user:
/* if (!isset($_COOKIE['user_id'])) {

	// Need the functions:
	require ('includes/login_functions.inc.php');
	redirect_user();

} else { // Delete the cookies:

	setcookie ('user_id', '', 0);
	setcookie ('first_name', '', 0);

}
 */
// Set the page title and include the HTML header:
$page_title = 'Logged Out!';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

// Print a customized message:
echo "<h1>Logged Out!</h1>
<script>
setTimeout(function(){window.location = \"index.php\";}, 1500);
</script>";

// Echo panel end tag
echo '</div></div>';

include ('includes/footer.html');

?>