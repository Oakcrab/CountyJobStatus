<?php

	class splits {

		function viewSplits() {

			// This script retrieves records from the splits table.
			require ('../mysqli_connect.php');

			// Number of records to display per page:
			$display = 20;

			// Determine how many pages there are:
			if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already determined.
				$pages = $_GET['p'];
			} else { // need to determine.
				// Count the number of records:
				$q = "SELECT COUNT(record_id) FROM splits";
					$r = @mysqli_query ($dbc, $q);
				$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
				$records = $row[0];
				// Calculate the number of pages...
				if ($records > $display) { // More than 1 page.
					$pages = ceil ($records/$display);
				} else {
					$pages = 1;
				}
			} // End of p IF.

			// Determine where in the database to start returning results...
			if (isset($_GET['s']) && is_numeric($_GET['s'])) {
				$start = $_GET['s'];
			} else {
				$start = 0;
			}

			// Determine the sort...
			// Default is by registration date.
			$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

			// Determine the sorting order:
			switch ($sort) {
				case 'no':
					$order_by = 'j.job_num ASC';
					break;
				case 'name':
					$order_by = 'j.job_name ASC';
					break;
				case 'sec':
					$order_by = 'j.section ASC';
					break;
				case 'twp':
					$order_by = 'j.township ASC';
					break;
				case 'range':
					$order_by = 'j.sur_range ASC';
					break;
				case 'type':
					$order_by = 't.type_name ASC';
					break;
				default:
					$order_by = 'j.job_num ASC';
					$sort = 'rd';
					break;
			}

			// Determine the sort...
			// Default is by registration date.
			$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

			// Define the query:
			$q = "SELECT *
				FROM splits
				ORDER BY $order_by 
				LIMIT $start, $display";		
			$r = @mysqli_query ($dbc, $q);

			// Count the number of returned rows:
			$num = mysqli_num_rows($r);

			if ($num > 0) { // If it ran OK, display the records.

				// Print how many jobs there are:
				echo "<p>There are currently $records jobs in the database.</p>\n";

				// Table header:
				echo '<table align="center" cellspacing="3" cellpadding="3" width="90%">
				<tr>
					<td align="left"><b>Edit</b></td>
					<td align="left"><b>Delete</b></td>
					<td align="left"><b><a href="view_jobs.php?sort=no">Job No</a></b></td>
					<td align="left"><b><a href="view_jobs.php?sort=name">Job Name</a></b></td>
					<td align="left"><b>Description</b></td>
					<td align="left"><b><a href="view_jobs.php?sort=sec">Sec</a></b></td>
					<td align="left"><b><a href="view_jobs.php?sort=twp">Twp</a></b></td>
					<td align="left"><b><a href="view_jobs.php?sort=range">Rng</a></b></td>
					<td align="left"><b><a href="view_jobs.php?sort=type">Type</a></b></td>
				</tr>';
				
				// Fetch and print all the records:
				$bg = '#eeeeee'; // First background color
				while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
					$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Alternate between grey and white background
					echo '<tr bgcolor="' . $bg . '">
						<td align="left"><a href="edit_job.php?id=' . $row['job_id'] . '">Edit</a></td>
						<td align="left"><a href="delete_job.php?id=' . $row['job_id'] . '">Delete</a></td>
						<td align="left"><a href="job_progress.php?id=' . $row['job_id'] . '">' . $row['job_num'] . '</a></td>
						<td align="left">' . $row['job_name'] . '</td>
						<td align="left">' . $row['job_desc'] . '</td>
						<td align="left">' . $row['section'] . '</td>
						<td align="left">' . $row['township'] . '</td>
						<td align="left">' . $row['sur_range'] . '</td>
						<td align="left">' . $row['type_name'] . '</td>
					</tr>';
				}

				echo '</table>';
				mysqli_free_result ($r);	

			} else { // If no records were returned.
				echo '<p class="error">There are currently no jobs in the database.</p>';
			}

			mysqli_close($dbc);

			// Make the links to other pages, if necessary.
			if ($pages > 1) {
				
				echo '<br /><p>';
				$current_page = ($start/$display) + 1;
				
				// If it's not the first page, make a Previous button:
				if ($current_page != 1) {
					echo '<a href="view_jobs.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
				}
				
				// Make all the numbered pages:
				for ($i = 1; $i <= $pages; $i++) {
					if ($i != $current_page) {
						echo '<a href="view_jobs.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
					} else {
						echo $i . ' ';
					}
				} // End of FOR loop.
				
				// If it's not the last page, make a Next button:
				if ($current_page != $pages) {
					echo '<a href="view_jobs.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
				}
				
				echo '</p>'; // Close the paragraph.
				
			} // End of links section.

			include ('includes/footer.html');

		} // end of viewSplits function.
		
		function addSplit() {
		
			require ('../mysqli_connect.php'); 

			// Check for form submission:
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$errors = array(); // Initialize an error array.
				
				$status = "ACTIVE"; // Always make new numbers ACTIVE.
				
				$township = mysqli_real_escape_string($dbc, trim($_POST['township']));
				
				// Check for a map_num:
				if (empty($_POST['map_num'])) {
					$errors[] = 'You forgot to enter a Map Number.';
				} else {
					$map_num = mysqli_real_escape_string($dbc, trim($_POST['map_num']));
				}
				
				// Check for a owner:
				if (empty($_POST['owner'])) {
					$errors[] = 'You forgot to enter an owner.';
				} else {
					$owner = mysqli_real_escape_string($dbc, trim($_POST['owner']));
				}
				
				// Check for a area:
				if (empty($_POST['area'])) {
					$errors[] = 'You forgot to enter an area.';
				} else {
					$area = mysqli_real_escape_string($dbc, trim($_POST['area']));
				}
				
				// Check for a section:
				if (empty($_POST['section'])) {
					$errors[] = 'You forgot to enter a section.';
				} else {
					$section = mysqli_real_escape_string($dbc, trim($_POST['section']));
				}
				
				// Check for a block:
				if (empty($_POST['block'])) {
					$errors[] = 'You forgot to enter a block.';
				} else {
					$block = mysqli_real_escape_string($dbc, trim($_POST['block']));
				}
				
				// Check for a parcel:
				if (empty($_POST['parcel'])) {
					$errors[] = 'You forgot to enter a parcel.';
				} else {
					$parcel = mysqli_real_escape_string($dbc, trim($_POST['parcel']));
				}
				
				// Check for a split:
				if (empty($_POST['split'])) {
					$errors[] = 'You forgot to enter a split.';
				} else {
					$split = mysqli_real_escape_string($dbc, trim($_POST['split']));
				}
				
				// Check for a acres:
				if (empty($_POST['acres'])) {
					$errors[] = 'You forgot to enter an acreage.';
				} else {
					$acres = mysqli_real_escape_string($dbc, trim($_POST['acres']));
				}
				
				// Check for a description:
				if (empty($_POST['description'])) {
					$errors[] = 'You forgot to enter a description.';
				} else {
					$description = mysqli_real_escape_string($dbc, trim($_POST['description']));
				}
				
				// Check for a deed:
				if (empty($_POST['deed'])) {
					$errors[] = 'You forgot to enter a deed instrument number.';
				} else {
					$deed = mysqli_real_escape_string($dbc, trim($_POST['deed']));
				}
				
				// Check for a survey:
				if (empty($_POST['survey'])) {
					$errors[] = 'You forgot to enter a survey instrument number.';
				} else {
					$survey = mysqli_real_escape_string($dbc, trim($_POST['survey']));
				}
				
				
				if (empty($errors)) { // If everything's OK.
					
					// TODO
					// Need to make the quary:
					
					// Make the query:
					$q = "INSERT INTO tasks (due, task_desc, notes, user_id, reg_date) VALUES ('$due', '$task_desc', '$notes', '$user_id', NOW() )";		
					$r = @mysqli_query ($dbc, $q); // Run the query.
					if ($r) { // If it ran OK.
					
						// Print a message:
						echo '<p>You have added a new task!</p><p><br /></p><p><a href="add_task.php">Add another task?</a></p>';	
					
						// Redirect to view_jobs.php:
						echo "<script>
							setTimeout(function(){
								window.location = \"view_tasks.php\";
							}, 5000);
						</script>";
					
					} else { // If it did not run OK.
						
						// Public message:
						echo '<h1>System Error</h1>
						<p class="error">You could not add a new task due to a system error. We apologize for any inconvenience.</p>'; 
						
						// Debugging message:
						echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
									
					} // End of if ($r) IF.
					
					mysqli_close($dbc); // Close the database connection.

					// Include the footer and quit the script:
					include ('includes/footer.html'); 
					exit();
					
				} else { // Report the errors.
				
					echo '<h1>Error!</h1>
					<p class="error">The following error(s) occurred:<br />';
					foreach ($errors as $msg) { // Print each error.
						echo " - $msg<br />\n";
					}
					echo '</p><p>Please try again.</p><p><br /></p>';
					
				} // End of if (empty($errors)) IF.
				
				mysqli_close($dbc); // Close the database connection.

			} else {
				
				// Run the form:
				echo '<form action="add_split.php" method="post">
				<p>*Due Date: <input type="text" id="datepicker" name="due" size="10" maxlength="10" /></p>
				<p>*Owner: <input type="text" name="owner" size="100" maxlength="100" /></p>
				<p>*Map Number: <input type="text" name="township" size="2" maxlength="2" />-<input type="text" name="map_num" size="8" maxlength="8" /> Acres: <input type="text" name="acres" size="8" maxlength="8" /></p>
				<p>Description: <input type="text" name="description" size="100" maxlength="100" /></p>
				<p>*Parcel Number: <input type="text" name="county" size="2" maxlength="2" value="36" />-<input type="text" name="area" size="2" maxlength="2" />-<input type="text" name="section" size="2" maxlength="2" />-<input type="text" name="block" size="3" maxlength="3" />-<input type="text" name="parcel" size="3" maxlength="3" />.<input type="text" name="split" size="3" maxlength="3" />-<input type="text" name="tax_id" size="3" maxlength="3" /></p>
				<p>*Deed: <input type="text" name="deed" size="9" maxlength="9" /> *Survey: <input type="text" name="survey" size="9" maxlength="9" /></p>
				<p>Notes: <textarea name="notes" rows="4" cols="50"></textarea></p>
				<p><input type="submit" name="submit" value="Submit" /></p>
				<input type="hidden" name="user_id" value="' . $_COOKIE['user_id'] . '" />
				</form>
				<p>(*) Marks the items that must be completed to submit.</p>';

				mysqli_close($dbc); // Close the database connection.
				
			}// End of the main Submit conditional.
		
		} // end of addSplit function.
		
	} // end of split class.

?>