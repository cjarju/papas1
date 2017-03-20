<?php

    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';
    require_once '../../assets/php/_database.php';

    $id = $_GET['id'];
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
    $sql = "DELETE FROM users WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {


        /* bind parameters for markers */
        $stmt->bind_param("i", $the_id);

        /* set parameters and execute */

        /* delete */
        $the_id = $id;
        $stmt->execute();

        /* delete should affect row */
        if ($stmt->affected_rows > 0) {
            array_push($_SESSION['flash'], "User Deleted.");
        }
        else {
            /* stmt error or record couldn't be deleted */
            array_push($_SESSION['flash'], "<span class='error_color'>User not deleted.</span>");
        }
    }
    else {
        /*sql query error */
        array_push($_SESSION['flash'], "<span class='error_color'>User not deleted.</span>");
    }

    /* close statement */
    $stmt->close();

    /* close connection */
    $conn->close();

    redirect(".");
 


