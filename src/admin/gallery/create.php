<?php

    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';

    if( isset($_COOKIE['token']) && isset($_SESSION['token']) && $_COOKIE['token'] == $_SESSION['token']  ){

        /* request ok (form has valid token)*/

        require_once '../../assets/php/_database.php';

        $upload_dir = '../../assets/images/gallery/';
        $thumbs_dir = '../../assets/images/gallery/thumbs/';
        $admin_dir = '../assets/images/gallery/';
        $file_input_name = "file_to_upload";

        /* upload image to directory. returns a unique filename  */
        $img_name = uploadImage($upload_dir, $file_input_name);
        $img_descr = trim($_POST['img_descr']);

        $file_path = $upload_dir . $img_name;
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
        $sql = "INSERT INTO gphotos (img_name,img_descr) VALUES (?, ?)";

        /* create a prepared statement */
        $stmt = $conn->prepare($sql); /* returns object on success, FALSE on failure */

        if ($stmt && $img_name) {

            /* bind parameters for markers */
            $stmt->bind_param("ss", $the_img_name, $the_img_descr);

            /*  set parameters and execute */
            /* insert record */
            $the_img_name = $img_name;
            $the_img_descr = $img_descr;
            $stmt->execute();

            /* insert should affect row */
            if ($stmt->affected_rows > 0) {

                /* if record insert was successful */

                /* resize the uploaded image */
                /* gallery size: width 640 */
                /* thumbnail size for frontend 65*65 */
                /* thumbnail size for backend 50*50 */

                $src_file = $file_path;
                $img_filename = pathinfo($img_name, PATHINFO_FILENAME);

                $new_width  = 640;
                $dst_file = $upload_dir . $img_filename;
                resizeImageAspectRatio($src_file, $new_width, 0, $dst_file);

                $dst_file = $thumbs_dir . $img_filename;
                resizeImage($src_file, 65, 65, $dst_file);

                $dst_file = $admin_dir . $img_filename;
                resizeImage($src_file, 50, 50, $dst_file);

                /* set flash message */
                /* upload function pushes an 'upload successful' message
                   to flash. comment below as it is redundant.
                */
                /* array_push($_SESSION['flash'], "Gallery photo uploaded."); */

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                /* Redirect to */
                redirect(".");

            } else {
                /* stmt error or record not inserted */

                /* delete uploaded file if it exists since record was not inserted */
                if (file_exists($file_path)) { unlink($file_path); }

                /* set flash message */
                /* array_push($_SESSION['flash'], "<span class='error_color'>Gallery photo not uploaded. stmt error: $stmt->error</span>"); */
                /* image might have been uploaded successfully. upload function pushes an 'upload successful' message
                   to flash. reset flash to have only the below message displayed
                */
                $_SESSION['flash'] = array();
                array_push($_SESSION['flash'], "<span class='error_color'>Gallery photo not uploaded.</span>");

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                redirect("new");
            }
        }
        else {
            /* sql query error: statement not valid and/or no file uploaded */

            /* delete uploaded file if it exists since record was not inserted */
            if (file_exists($file_path)) { unlink($file_path); }

            $_SESSION['flash'] = array();
            array_push($_SESSION['flash'], "<span class='error_color'>Gallery photo not uploaded.</span>");

            /* close connection */
            $conn->close();

            redirect("new");

        }
    }
    else{
        /* bad request */

        resetFormToken();

        /* redirect */
        redirect('.');
    }
