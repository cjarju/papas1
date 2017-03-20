<?php

    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';

    if( isset($_COOKIE['token']) && isset($_SESSION['token']) && $_COOKIE['token'] == $_SESSION['token']  ){

        /* request ok (form has valid token)*/

        require_once '../../assets/php/_database.php';

        $id = trim($_POST['rec_id']);
        $username = trim($_POST['user_name']);
        $password = $_POST['password'];
        $password_hash = md5($password);

        /* fallback validation on server side (bulletproof): complement the client side validation */
        if (isEmpty($username) || isEmpty($password) || isSafeAlphaNum1($username) || isSafeAlphaNum2($password)) {
            array_push($_SESSION['flash'], "<span class='error_color'>User not edited.</span>");
            redirect('edit.php?id=$id');
        }
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
        /* update changes in database table. check username uniqueness before updating.  */

        /* create a prepared statement */
        $sql = "select * from users where user_name = ? and id <> ?";

        $stmt = $conn->prepare($sql);

        /*var_dump($stmt);*/

        if ($stmt) {

            /* bind parameters for markers */
            $stmt->bind_param("si", $the_name, $the_id);

            /* set parameters and execute */
            $the_name = $username;
            $the_id = $id;
            $stmt->execute();
            $stmt->store_result();
            /* $stmt->bind_result($id, $user_name); */

            if ($stmt->num_rows > 0) {
                /* username exists already - choose another */
                array_push($_SESSION['flash'], "<span class='error_color'>Username: $username already exists. Choose another one.</span>");

                /* free result */
                $stmt->free_result();

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                redirect("edit?id=$id");
            }
            else {
                /* update record */
                $sql_update = "UPDATE users SET user_name = ?, password_hash = ? WHERE id = ?";

                /* create a prepared statement */
                $stmt_update = $conn->prepare($sql_update);

                if ($stmt_update) {
                    /* bind parameters for markers */
                    $stmt_update->bind_param("ssi", $the_name, $the_hash, $the_id);

                    /* set parameters and execute */

                    /* update a row */
                    $the_id = $id;
                    $the_name = $username;
                    $the_hash = $password_hash;
                    $stmt_update->execute();

                    if ($stmt_update->error) {
                        /* stmt error */

                        array_push($_SESSION['flash'], "<span class='error_color'>User not edited.</span>");

                        /* close statement */
                        $stmt_update->close();

                        /* close statement */
                        $stmt->close();

                        /* close connection */
                        $conn->close();

                        redirect("edit?id=$id");
                    }
                    else {
                         /* update successful */
                        array_push($_SESSION['flash'], "User: $username Edited.");

                        /* close statement */
                        $stmt_update->close();

                        /* close statement */
                        $stmt->close();

                        /* close connection */
                        $conn->close();

                        redirect('.');
                    }
                }
                else {
                    // update query error. statement invalid

                    array_push($_SESSION['flash'], "<span class='error_color'>User not edited.</span>");

                    /* close connection */
                    $conn->close();

                    redirect("edit?id=$id");
                }
            }
        }
        else {

            /* query error. statement is not valid; false returned */

            array_push($_SESSION['flash'], "<span class='error_color'>User not edited.</span>");

            // close connection
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





