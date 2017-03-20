<?php

    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';
    
    if( isset($_COOKIE['token']) && isset($_SESSION['token']) && $_COOKIE['token'] == $_SESSION['token']  ){
        
        /* request ok (form has valid token)*/
        
        require_once '../../assets/php/_database.php';
        
    
        $upload_dir = '../../assets/images/logos/';
        $file_input_id = "file_to_upload";
        $img_name = "";

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
        $business_name = trim($_POST['business_name']);
        $about_text = trim($_POST['about_text']);
        $intro_lead_in = trim($_POST['intro_lead_in']);
        $intro_heading = trim($_POST['intro_heading']);
        $phone_no = trim($_POST['phone_no']);
        $email = trim($_POST['email']);
        $instagram = trim($_POST['instagram']);
        $facebook = trim($_POST['facebook']);
        $logo_name = $img_name;
        
        /* update changes in database table */
        /* image upload is optional */
    
        if ($logo_name) {

            /* get current filename before update */
            $sql = "SELECT logo_name FROM about WHERE id = '$id'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $_POST = $result->fetch_assoc();
                $old_logo_name = trim($_POST['logo_name']);
            }

            $sql = "UPDATE about SET logo_name = ?, business_name = ?, about_text = ?, intro_lead_in = ?, intro_heading = ?, phone_no = ?, email = ?, instagram = ?, facebook = ? WHERE id = ?";
        }
        else {
            $sql = "UPDATE about SET business_name = ?, about_text = ?, intro_lead_in = ?, intro_heading = ?, phone_no = ?, email = ?, instagram = ?, facebook = ? WHERE id = ?";
        }

        /* create a prepared statement */
        $stmt = $conn->prepare($sql);

        if ($stmt && $logo_name) {

            /* update includes image upload */

            /* bind parameters for markers */
            $stmt->bind_param("sssssssssi", $the_logo_name, $the_business_name, $the_about_text, $the_intro_lead_in, $the_intro_heading, $the_phone_no, $the_email, $the_instagram, $the_facebook, $the_id);

            /*  set parameters and execute */
            /* update record */
            $the_logo_name = $logo_name;
            $the_business_name = $business_name;
            $the_about_text = $about_text;
            $the_intro_lead_in = $intro_lead_in;
            $the_intro_heading = $intro_heading;
            $the_phone_no = $phone_no;
            $the_email = $email;
            $the_instagram = $instagram;
            $the_facebook = $facebook;
            $the_id = $id;
            $stmt->execute();

            /* update should affect row */
            if ($stmt->affected_rows > 0) {
                /* backend update successful */

                /* resize uploaded image */
                $src_file = $upload_dir . $logo_name;
                $img_filename = pathinfo($logo_name, PATHINFO_FILENAME);

                $dst_file = $upload_dir . $img_filename;
                resizeImage($src_file, 50, 50, $dst_file);

                /* delete old files after update: only needed when file is uploaded and db updated successfully */
                /* file must exist before delete */

                $file_path = $upload_dir . $old_logo_name;
                if (file_exists($file_path)) { unlink($file_path); }

                /* set flash message */
                /* upload function pushes an 'upload successful' message
                   to flash.
                */
                array_push($_SESSION['flash'], "Business info edited.");

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                redirect(".");
            }
            else {
                /* backend update unsuccessful or stmt error */

                /* delete uploaded image */

                $file_path = $upload_dir . $logo_name;
                if (file_exists($file_path)) { unlink($file_path); }

                /* set flash message */
                /* image might have been uploaded successfully. upload function pushes an 'upload successful' message
                   to flash. reset flash to have only the below message displayed
                */
                $_SESSION['flash'] = array();
                array_push($_SESSION['flash'], "<span class='error_color'>Business info not edited.</span>");

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
            $stmt->bind_param("ssssssssi", $the_business_name, $the_about_text, $the_intro_lead_in, $the_intro_heading, $the_phone_no, $the_email, $the_instagram, $the_facebook, $the_id);

            /*  set parameters and execute */
            /* update record */
            $the_business_name = $business_name;
            $the_about_text = $about_text;
            $the_intro_lead_in = $intro_lead_in;
            $the_intro_heading = $intro_heading;
            $the_phone_no = $phone_no;
            $the_email = $email;
            $the_instagram = $instagram;
            $the_facebook = $facebook;
            $the_id = $id;
            $stmt->execute();
            /* update may [not] affect record depending on user input for description
               don't use affected_rows condition
            */

            array_push($_SESSION['flash'], "Business info edited.");

            /* close statement */
            $stmt->close();

            /* close connection */
            $conn->close();

            redirect(".");
        }
        else {
            /* query error */
    
            array_push($_SESSION['flash'], "<span class='error_color'>Business info not edited.</span>");

            /* close connection */
            $conn->close();
    
            redirect("edit?id=$id");
        }
    }
    else{
        /* bad request */
        resetFormToken();

        /* redirect */
        redirect(".");
    }



 

