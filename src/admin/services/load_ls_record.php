<?php

    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';
	require_once '../../assets/php/_database.php';
	
	if (!empty($_POST['id'])) {
		
		$rec_id = $_POST['id'];
/*
  prepared statement:
   - string is escaped (quoted) implicitly; real_escape_string() not required
   - prevents 1st order injection (injection from external source; e.g. user input)

   $stmt->bind_param(), $stmt->execute(), $stmt->store_result(), $stmt->bind_result(), $stmt->fetch()
   Return TRUE on success or FALSE on failure. No records affected or returned does not mean failure.

   $stmt->error
   Returns a string that describes the last statement error (~ TRUE in condition). An empty string if no error
   occurred (~ FALSE in condition).

   If the statement is UPDATE, DELETE, or INSERT, the total number of affected rows can be determined by using
   $stmt->affected_rows

   If the statement is SELECT, the number of rows in statements result set can be determined by using
   $stmt->num_rows. The entire result should be buffered first using $stmt->store_result()
*/
		 
		$sql = "SELECT id, svc_name FROM services WHERE id = ?";
		
		$stmt = $conn->prepare($sql);
		
		if ($stmt) {
		
			// bind parameters for markers
			$stmt->bind_param("i", $the_id);
			
			// set parameters and execute
			$the_id = $rec_id;
			$stmt->execute();

			$stmt->store_result();
			$stmt->bind_result($id, $svc_name);
			
			if ($stmt->num_rows > 0) {
				$row = $stmt->fetch();
$str_html = <<<STR
<table class='table table-hover table-condensed table_autofit'>
	<caption>Record</caption>
	<thead>
		<tr> <th>ID</th> <th>Name</th> <th>Edit</th> <th>Delete</th> </tr>
	</thead>
	<tbody>
		<tr>
			<td> $id </td>
			<td> $svc_name </td>
			<td> <a href="edit?id=$id"><span class='glyphicon glyphicon-pencil'>&nbsp;</span></a></td>
			<td> <a href="delete?id=$id" class='delete_confirmation_ajax'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a></td>
		</tr>
	<tbody>
</table>
STR;
                /* free result set */
                $stmt->free_result();

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                /* return */
                echo $str_html;
			}
            else {
                /* could not get record */

                /* free result set */
                $stmt->free_result();

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                /* return */
                echo 'Could not retrieve record. Try again.';
            }
		}
        else {

            /* close connection */
            $conn->close();

            /* return */
            echo 'Could not retrieve record. stmt error.';
		}
	}
    else {
        /* $_POST['id'] is empty */
        echo 'Something went wrong, record could not be fetched.';
    }