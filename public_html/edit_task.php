<?php

// Check for login:
include ('includes/login_check.inc.php');

$page_title = 'Edit a Task';
include ('includes/header.html');

// Echo panel start tag
echo '<div class="panel panel-default">
<div class="panel-body">';

echo '<h1>Edit a Task</h1>';
require('includes/task_functions.php');

$task = new tasks();

$task->editTasks();

// Echo panel end tag
echo '</div></div>';

include ('includes/footer.html');

?>