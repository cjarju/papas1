<?php

    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';

    if( isset($_COOKIE['token']) && isset($_SESSION['token']) && $_COOKIE['token'] == $_SESSION['token']  ){

        /* request ok (form has valid token)*/

        require_once '../../assets/php/_database.php';

        $upload_dir = '../../assets/images/gallery/';
        $thumbs_dir = '../../assets/images/gallery/thumbs/';
        $admin_dir = '../assets/images/gallery/';
        $file_input_id = "file_to_upload";
        $img_name = "";
        $old_img_name = "";
        
        $upload_included = $_FILES["$file_input_id"]["name"];

        /* upload image */
        if ($upload_included) { $img_name = uploadImage($upload_dir, $file_input_id); }
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
        $id = trim($_POST['rec_id']);
        $img_descr = trim($_POST['img_descr']);

        /* update changes in database table */
        /* image upload is optional */

        if ($img_name) {

            /* get current filename before update. */
            $sql = "SELECT img_name FROM gphotos WHERE id = '$id'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $old_img_name = trim($row['img_name']);
            }

            $sql = "UPDATE gphotos SET img_descr = ?, img_name = ? WHERE id = ?";
        }
        else {
            $sql = "UPDATE gphotos SET img_descr = ? WHERE id = ?";
        }

        /* create a prepared statement */
        $stmt = $conn->prepare($sql);

        if ($stmt && $img_name) {

            /* update includes image upload */

            /* bind parameters for markers */
            $stmt->bind_param("ssi", $the_img_descr, $the_img_name, $the_id);

            /*  set parameters and execute */
            /* update record */
            $the_img_descr = $img_descr;
            $the_img_name = $img_name;
            $the_id = $id;
            $stmt->execute();

            /* update should affect row */
            if ($stmt->affected_rows > 0) {

                /* backend update successful */

                /* resize uploaded image */
                /* thumbnail size for frontend 65*65 */
                /* thumbnail size for backend 50*50 */

                $src_file = $upload_dir . $img_name;
                $img_filename = pathinfo($img_name, PATHINFO_FILENAME);

                $dst_file = $thumbs_dir . $img_filename;
                resizeImage($src_file, 65, 65, $dst_file);

                $dst_file = $admin_dir . $img_filename;
                resizeImage($src_file, 50, 50, $dst_file);

                /* delete old files after update: only needed when new file is uploaded and db updated successfully
               file must exist before delete */

                $file_path = $upload_dir . $old_img_name;
                if (file_exists($file_path)) { unlink($file_path); }

                $file_path = $thumbs_dir . $old_img_name;
                if (file_exists($file_path)) { unlink($file_path); }

                $file_path = $admin_dir . $old_img_name;
                if (file_exists($file_path)) { unlink($file_path); }

                /* set flash message */
                /* upload function pushes an 'upload successful' message
                   to flash. comment below as it is redundant.
                */
                /*array_push($_SESSION['flash'], "Gallery photo edited.");*/

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                redirect(".");
            }
            else {

                /* backend update unsuccessful or stmt error */

                /* delete uploaded image */

                $file_path = $upload_dir . $img_name;
                if (file_exists($file_path)) { unlink($file_path); }

                /* set flash message */
                /* image might have been uploaded successfully. upload function pushes an 'upload successful' message
                   to flash. reset flash to have only the below message displayed
                */
                $_SESSION['flash'] = array();
                array_push($_SESSION['flash'], "<span class='error_color'>Gallery photo not edited.</span>");

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                redirect("edit?id=$id");
            }
        }
        elseif ($stmt) {

            /* update doesn't include image upload */

            /* bind parameters for markers */
            $stmt->bind_param("si", $the_img_descr, $the_id);

            /*  set parameters and execute */
            /* update record */
            $the_img_descr = $img_descr;
            $the_id = $id;
            $stmt->execute();

            /* update may [not] affect record depending on user input for description
               don't use affected_rows condition
            */
            array_push($_SESSION['flash'], "Gallery photo edited.");

            /* close statement */
            $stmt->close();

            /* close connection */
            $conn->close();

            redirect(".");


        }
        else {

            /* query error */

            array_push($_SESSION['flash'], "<span class='error_color'>Gallery photo not edited. Query error.</span>");

            /* close connection */
            $conn->close();

            redirect("edit?id=$id");
        }
    }
    else {

        /* bad request */
        resetFormToken();

        /* redirect */
        redirect(".");
    }




