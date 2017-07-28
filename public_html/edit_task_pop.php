<?php
echo '<html><head><title>Edit Task</title></head>';
require('includes/task_functions.php');

$task = new tasks();

$task->editTasks();

echo '<button name=close onclick="window.close();">Close</button></html>';
?>