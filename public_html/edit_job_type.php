<?php

session_start(); // Start the session.

// If no session value is present, redirect the user:
if (!isset($_SESSION['user_id'] )) {

	// Need the functions:
	require ('includes/login_functions.inc.php');
	redirect_user();	

}

$page_title = 'Edit a Job Type';
include ('includes/header.html');
echo '<h1>Edit a Job Type</h1>';

// Check for a valid type ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { 
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<div class="alert alert-danger" role="alert"><p class="error">This page has been accessed in error.</p><div>';
	include ('includes/footer.html'); 
	exit();
}

require ('../mysqli_connect.php'); 

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = array();
	
	// Check for a name:
	if (empty($_POST['type_name'])) {
		$errors[] = 'You forgot to enter a type name.';
	} else {
		$type_name = mysqli_real_escape_string($dbc, trim($_POST['type_name']));
	}
	
	if (empty($errors)) { // If everything's OK.

		// Make the query:
		$q = "UPDATE job_type SET type_name='$type_name' WHERE type_id=$id LIMIT 1";
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Print a message:
			echo '<div class="alert alert-success" role="alert"><p>The job type has been edited.</p></div>';	
			
			echo "<script>setTimeout(function(){
			  window.location = \"view_job_types.php\";
			}, 3000);</script>";
		
			
		} else { // If it did not run OK.
			echo '<div class="alert alert-danger" role="alert"><p class="error">The job type could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p></div>'; // Debugging message.
		}
		
	} else { // Report the errors.

		echo '<div class="alert alert-warning" role="alert"><p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p></div>';
	
	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form...

// Retrieve the user's information:
$q = "SELECT type_name FROM job_type WHERE type_id=$id";		
$r = @mysqli_query ($dbc, $q);

if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

	// Get the user's information:
	$row = mysqli_fetch_array ($r, MYSQLI_BOTH);
	
	// Create the form:
	echo '<form action="edit_job_type.php" method="post">
<p>Name: <input type="text" name="type_name" size="15" maxlength="20" value="' . $row['type_name'] . '" /></p>
 <input type="hidden" name="id" value="' . $id . '" />
<p><input type="submit" name="submit" value="Update" /></p>
</form>';

} else { // Not a valid user ID.
	echo '<div class="alert alert-danger" role="alert"><p class="error">This page has been accessed in error.</p></div>';
}

mysqli_close($dbc);
		
include ('includes/footer.html');
?>