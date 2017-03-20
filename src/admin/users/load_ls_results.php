<?php
    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';
	require_once '../../assets/php/_database.php';

	if ($_POST['keywords']) {
        
		$keywords = $conn->real_escape_string($_POST['keywords']);
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
		$sql = "SELECT id, user_name FROM users WHERE user_name LIKE ?";
		
		$stmt = $conn->prepare($sql);
		
		if ($stmt) {
		
			// bind parameters for markers
			$stmt->bind_param("s", $the_cond);
			
			// set parameters and execute
			$the_cond = "%$keywords%";
			$stmt->execute();

			$stmt->store_result();
			$stmt->bind_result($id, $user_name);
			
			if ($stmt->num_rows > 0) {
                $str_html = "";
				while ($row = $stmt->fetch()) {
					$str_html .= "<li><a href='' id='$id'>$user_name</a></li>";
				}

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
                /* could not get results */

                /* free result set */
                $stmt->free_result();

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                /* return */
                echo "<li class='center_align'>No results for $keywords</li>";
            }
		}
        else {

            /* close connection */
            $conn->close();

            /* return */
            echo "<li class='center_align'>No results. stmt error.</li>";
		}
	}
    else {
        /* $_POST['keywords'] is empty */
        echo "<li class='center_align'>No results. Something went wrong.</li>";
    }
 