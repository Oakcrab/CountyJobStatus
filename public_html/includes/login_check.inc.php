<?php
/* 
# New code for cookies and sessions:

session_start(); // Start the session.

if (!isset($_SESSION['user_id'])) {

	if (isset($_COOKIE['user_id'])) {
	
		$_SESSION['user_id'] = $_COOKIE['user_id'];
		$_SESSION['first_name'] = $_COOKIE['first_name'];
		
	} else {
	
		// Need the functions:
		require ('includes/login_functions.inc.php');
		redirect_user();
		
	}

}
 */
# 0 for no error reporting:
error_reporting(0);

#Sessions:

session_start(); // Start the session.

# If no session value is present, redirect the user:
if (!isset($_SESSION['user_id'] )) {

	// Need the functions:
	require ('includes/login_functions.inc.php');
	redirect_user();	

}
#
#	See link below for extending a session.
#	http://stackoverflow.com/questions/6360093/how-to-set-lifetime-of-session
#
#
# Cookies:
# If no cookie is present, redirect the user:
#if (!isset($_COOKIE['user_id'])) {
#
#	// Need the functions:
#	require ('includes/login_functions.inc.php');
#	redirect_user();
#	
#}
#
?>