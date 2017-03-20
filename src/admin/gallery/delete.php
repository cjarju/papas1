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

    /* get the img name associated with record */

    $fetch_success = false;
    $sql = "SELECT img_name FROM gphotos WHERE id = ?";

    /* create a prepared statement */
    $stmt = $conn->prepare($sql); /* returns object on success, FALSE on failure */

    if ($stmt) {

        /* bind parameters for markers */
        $stmt->bind_param("i", $the_id);

        /*  set parameters and execute */
        $the_id = $id;
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($img_name);

        if ($stmt->num_rows > 0) {
            $fetch_success = $stmt->fetch();
            $file_path = "../../assets/images/gallery/".$img_name;
            $thumbs_file_path = '../../assets/images/gallery/thumbs/'.$img_name;
            $admin_file_path = '../assets/images/gallery/'.$img_name;
        }

        /* close statement */
        $stmt->close();
    }

    /* delete if img info for record is fetched successfully, don't leave files trailing on server */

    $sql = "DELETE FROM gphotos  WHERE id = ?";

    /* create a prepared statement */
    $stmt = $conn->prepare($sql);

    if ($stmt && $fetch_success) {

        /* bind parameters for markers */
        $stmt->bind_param("i", $the_id);

        /* set parameters and execute */
        $the_id = $id;
        $stmt->execute();

        /* delete should affect row */
        if ($stmt->affected_rows > 0) {

            /* delete the files (images) associated with record */

            if (file_exists($file_path)) { unlink($file_path); }

            if (file_exists($thumbs_file_path)) { unlink($thumbs_file_path); }

            if (file_exists($admin_file_path)) { unlink($admin_file_path); }

            array_push($_SESSION['flash'], "Gallery photo deleted.");
        }
        else {
            /* record couldn't be deleted or stmt error */
            array_push($_SESSION['flash'], "<span class='error_color'>Gallery photo not deleted.</span>");
        }

        /* close statement */
        $stmt->close();

    }
    else {
        /* record image info could not be fetched or sql query error */
        array_push($_SESSION['flash'], "<span class='error_color'>Gallery photo not deleted.</span>");
    }

    /* close connection */
    $conn->close();

    redirect(".");


