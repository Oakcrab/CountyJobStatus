<?php // This page is used to administer notifications.

// Check for login:
include ('includes/login_check.inc.php');

// if session is available:
$page_title = 'Notifications';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

// Notification options:
echo '<h1>Notifications</h1>';
?>
<p>
<form name="txtNoti" action="notifications.php" method="post">
<u>Text Message Notifications</u><br>
<br>
<input type="radio" name="txt_mode" value="enable">Enable
<input type="radio" name="txt_mode" value="disable">Disable<br>
<br>
Cell Phone Number: <input type="text" name="txt_number"><br>
<br>
<input type="checkbox" name="txt_type" value="jobs">All Jobs
<input type="checkbox" name="txt_type" value="myJobs">My Jobs<br>
<input type="checkbox" name="txt_type" value="tasks">All Tasks
<input type="checkbox" name="txt_type" value="myTasks">My Tasks<br>
<br>
<input type="submit" value="Submit">
</form>
</p><p>
<form name="emailNoti" action="notifications.php" method="post">
<u>Email Notifications</u><br>
<br>
<input type="radio" name="email_mode" value="enable">Enable
<input type="radio" name="email_mode" value="disable">Disable<br>
<br>
Email Address: <input type="text" name="email_add"><br>
<br>
<input type="checkbox" name="email_type" value="jobs">All Jobs
<input type="checkbox" name="email_type" value="myJobs">My Jobs<br>
<input type="checkbox" name="email_type" value="tasks">All Tasks
<input type="checkbox" name="email_type" value="myTasks">My Tasks<br>
<br>
<input type="submit" value="Submit">
</form>
</p>
<?php
// Echo panel end tag
echo '</div></div>';
include ('includes/footer.html');
?>