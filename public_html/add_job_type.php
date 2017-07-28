<?php 

session_start(); // Start the session.

// If no session value is present, redirect the user:
if (!isset($_SESSION['user_id'] )) {

	// Need the functions:
	require ('includes/login_functions.inc.php');
	redirect_user();	

}

$page_title = 'Add a Job Type';
include ('includes/header.html');

	// Echo panel start tag
	echo '<div class="panel panel-default">
	  <div class="panel-heading"><h3 class="panel-title">Add Job Type</h3></div>
	<div class="panel-body">';

require ('../mysqli_connect.php'); 

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require ('../mysqli_connect.php'); // Connect to the db.
		
	$errors = array(); // Initialize an error array.
	
	// Check for a name:
	if (empty($_POST['type_name'])) {
		$errors[] = 'You forgot to enter a type name.';
	} else {
		$type_name = mysqli_real_escape_string($dbc, trim($_POST['type_name']));
	}
	
	if (empty($errors)) { // If everything's OK.
	
		// Register the user in the database...
		
		// Make the query:
		$q = "INSERT INTO job_type(type_name) VALUES ('$type_name')";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<div class="alert alert-success" role="alert"><h1>Thank you!</h1>
			<p>You have added a new job type!</p><p><br /></p></div>';	
		
			echo "<script>setTimeout(function(){
			  window.location = \"view_job_types.php\";
			}, 3000);</script>";
		
		} else { // If it did not run OK.
			
			// Public message:
			echo '<div class="alert alert-danger" role="alert"><h1>System Error</h1>
			<p class="error">You could not add a new job due to a system error. We apologize for any inconvenience.</p>'; 
			
			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p></div>';
						
		} // End of if ($r) IF.
		
		mysqli_close($dbc); // Close the database connection.

		// Include the footer and quit the script:
		include ('includes/footer.html'); 
		exit();
		
	} else { // Report the errors.
	
		echo '<div class="alert alert-warning" role="alert"><h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p></div>';
		
	} // End of if (empty($errors)) IF.
	
	mysqli_close($dbc); // Close the database connection.

} // Show the selected client:

?>

<form action="add_job_type.php" method="post">

	<p>Name: <input type="text" name="type_name" size="60" maxlength="100" value="<?php if (isset($_POST['type_name'])) echo $_POST['type_name']; ?>" /></p>
	<p><input type="submit" name="submit" value="Submit" /></p>
</form>

<?php 

echo '</div></div>';

include ('includes/footer.html'); 

?>