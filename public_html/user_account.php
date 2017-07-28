<?php // This page is used to administer a users account.

// Check for login:
include ('includes/login_check.inc.php');

// if session is available:
$page_title = 'Account';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

// Create links to admin pages:
echo '
<h1>Account</h1>
<p><a href="notifications.php">Notifications</a></p>
<p><a href="password.php">Change Password</a></p>
<p><a href="view_jobs.php">View All Jobs</a></p>
<p><a href="view_tasks.php">View All Tasks</a></p>
<p><a href="view_users.php">View Users</a></p>
<p><a href="register.php">Register a New User</a></p>
<p><a href="logout.php">Logout</a></p>

';

// Echo panel end tag
echo '</div></div>';
include ('includes/footer.html');
?>