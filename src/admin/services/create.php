<?php

    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';

    if( isset($_COOKIE['token']) && isset($_SESSION['token']) && $_COOKIE['token'] == $_SESSION['token']  ){

        /* request ok (form has valid token) */

        require_once '../../assets/php/_database.php';

        $upload_dir = '../../assets/images/services/';
        $file_input_name = "file_to_upload";

        $image_name = uploadImage($upload_dir, $file_input_name);

        $svc_name = trim($_POST['svc_name']);
        $svc_descr = trim($_POST['svc_descr']);
        $svc_img_name = $image_name;

        $file_path = $upload_dir . $image_name;
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
        $sql = "INSERT INTO services (svc_name,svc_descr,svc_img_name) VALUES (?, ?, ?)";

        /* create a prepared statement */
        $stmt = $conn->prepare($sql);

        if ($stmt && $image_name) {

            /* bind parameters for markers */
            $stmt->bind_param("sss", $the_svc_name, $the_svc_descr, $the_svc_img_name);

            /* set parameters and execute */

            /* insert a row */
            $the_svc_name = $svc_name;
            $the_svc_descr = $svc_descr;
            $the_svc_img_name = $svc_img_name;
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                /* record inserted successfully */

                /* resize the uploaded image */
                $src_file = $file_path;
                $image_filename = pathinfo($image_name, PATHINFO_FILENAME);
                $new_width  = 900;
                $dst_file = $upload_dir . $image_filename;

                resizeImageAspectRatio($src_file, $new_width, 0, $dst_file);

                /* upload function pushes an 'upload successful' message to flash.*/

                array_push($_SESSION['flash'], "Service created.");

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                /* redirect */
                redirect(".");

            }
            else {
                /* record insert unsuccessful or stmt error */

                /* delete uploaded file if it exists since record was not inserted */
                if (file_exists($file_path)) { unlink($file_path); }

                /* image might have been uploaded successfully. upload function pushes an 'upload successful' message
                   to flash. reset flash to have only the below message displayed
                */
                $_SESSION['flash'] = array();
                array_push($_SESSION['flash'], "<span class='error_color'>Service not created.</span>");

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                /* redirect */
                redirect("new");
            }
        }
        else {
            /* sql query error: statement not valid and/or no file uploaded */

            /* delete uploaded file if it exists since record was not inserted */
            if (file_exists($file_path)) { unlink($file_path); }

            /* image might have been uploaded successfully. upload function pushes an 'upload successful' message
               to flash. reset flash to have only the below message displayed
            */
            $_SESSION['flash'] = array();
            array_push($_SESSION['flash'], "<span class='error_color'>Service not created.</span>");

            /* close connection */
            $conn->close();

            /* redirect */
            redirect("new");

        }
    }
    else{
        /* bad request */

        resetFormToken();

        /* redirect to */
        redirect('.');
    }






