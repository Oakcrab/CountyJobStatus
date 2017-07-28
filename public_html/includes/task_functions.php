<?php

	class tasks {

		function editTasks() {

			// Check for a valid Task ID, through GET or POST:
			if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_tasks.php
				$id = $_GET['id'];
			} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
				$id = $_POST['id'];
			} else { // No valid ID, kill the script.
				echo '<p class="error">This page has been accessed in error.</p>';
				include ('includes/footer.html'); 
				exit();
			}

			require ('../mysqli_connect.php'); 

			// Check if the form has been submitted:
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$errors = array();
				
				// Check for a due date:
				if (empty($_POST['due'])) {
					$errors[] = 'You forgot to enter a due date.';
				} else {
					$due = mysqli_real_escape_string($dbc, trim($_POST['due']));
				}
				
					// Check for an assigned to entry:
				if (empty($_POST['assigned_id'])) {
					$errors[] = 'You forgot to enter who this task is assigned to.';
				} else {
					$assigned_id = mysqli_real_escape_string($dbc, trim($_POST['assigned_id']));
				}
				
				// Check for a description:
				if (empty($_POST['task_desc'])) {
					$errors[] = 'You forgot to enter a description.';
				} else {
					$task_desc = mysqli_real_escape_string($dbc, trim($_POST['task_desc']));
				}
				
				// Null sets:
				$notes = mysqli_real_escape_string($dbc, trim($_POST['notes']));
				$user_id = mysqli_real_escape_string($dbc, trim($_POST['user_id']));
				
				if (empty($errors)) { // If everything's OK.

					// Make the query:
					$q = "UPDATE tasks SET due='$due', task_desc='$task_desc', notes='$notes', assigned_id='$assigned_id' WHERE task_id=$id LIMIT 1";
					$r = @mysqli_query ($dbc, $q);
					if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

						// Print a message:
						echo '<p>The task has been edited.</p>';	
						
					} else { // If it did not run OK.
						echo '<p class="error">The task could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
						echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
					}
					
				} else { // Report the errors.

					echo '<p class="error">The following error(s) occurred:<br />';
					foreach ($errors as $msg) { // Print each error.
						echo " - $msg<br />\n";
					}
					echo '</p><p>Please try again.</p>';
				
				} // End of if (empty($errors)) IF.

			} // End of submit conditional.

			// Always show the form...

			// Retrieve the task information:
			$q_form = "SELECT t.task_id, DATE_FORMAT(t.due, '%Y-%m-%d') AS dd, t.task_desc, t.notes, t.assigned_id, u.first_name, u.last_name, a.first_name, a.last_name
				FROM tasks AS t
				LEFT JOIN users AS u
				ON u.user_id = t.user_id
				LEFT JOIN users AS a
				ON a.user_id = t.assigned_id
				WHERE t.task_id=$id";			
			$r_form = @mysqli_query ($dbc, $q_form);

			if (mysqli_num_rows($r_form) == 1) { // Valid user ID, show the form.

				// Get the Job information:
				$row_form = mysqli_fetch_array ($r_form, MYSQLI_BOTH);
					
					echo '<br />The original entry was submitted by ' . $row_form[5] . ' ' . $row_form[6] . '.';
					
					// Run the form:
					echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">
					<p>*Due Date: <input type="text" id="datepicker" name="due" value="' . $row_form['dd'] . '" size="10" maxlength="10" />
					<p>*Description: <input type="text" name="task_desc" value="' . $row_form['task_desc'] . '" size="60" maxlength="250" /></p>
					<p>Notes: <textarea name="notes" rows="4" cols="50">' . $row_form['notes'] . '</textarea></p>';

					echo '</select></p>
					<p>Assign Task to: <select name="assigned_id"><option value="' . $row_form['assigned_id'] . '">' . $row_form[7] . ' ' . $row_form[8] . '</option>';
					// SELECT Drop down list values from users table:
					// Define the query:
					$q = "SELECT user_id, first_name, last_name FROM users ORDER BY last_name ASC";		
					$r = @mysqli_query ($dbc, $q);

					// Count the number of returned rows:
					$num = mysqli_num_rows($r);

					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
						echo '<option value="' . $row['user_id'] . '">' . $row['last_name'] . ', ' . $row['first_name'] . '</option>';
					}

					mysqli_free_result ($r);
					
					echo '</select></p>
					<input type="hidden" name="id" value="' . $id . '">
					<p><input type="submit" name="submit" value="Update" /></p>
					</form>
					<p>(*) Marks the items that must be completed to submit.</p>';
					
					mysqli_free_result ($r_form);

			} else { // Not a valid user ID.
				echo '<p class="error">This page has been accessed in error.</p>';
			}

			mysqli_close($dbc);
		} // end of editTasks function.
		
	} // end of tasks class.

?>