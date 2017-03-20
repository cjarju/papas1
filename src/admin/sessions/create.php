<?php

    require_once '../../assets/php/php_functions.php';

    /* echo $_COOKIE['token'] . '<br/>'; echo $_SESSION['token'] . '<br/>';
       echo $_COOKIE['token'] == $_SESSION['token'];
    */

    if( isset($_COOKIE['token']) && isset($_SESSION['token']) && $_COOKIE['token'] == $_SESSION['token']  ){

        /* request ok (form has valid token) */

        require_once '../../assets/php/_database.php';

        $username = strtolower($_POST['username']);
        $password = $_POST['password'];
        $password_hash = md5($password);

        /* fallback validation on server side (bulletproof): complement the client side validation */
        if (isEmpty($username) || isEmpty($password) || isSafeAlphaNum1($username) || isSafeAlphaNum2($password)) {
            array_push($_SESSION['flash'], "<span class='error_color'>Login fail.</span>");
            redirect('new');
        }

        $sql = "select * from users where user_name = ?";
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
        $stmt = $conn->prepare($sql);

        if ($stmt) {

            /*  bind parameters for markers */
            $stmt->bind_param("s", $the_name);

            /* set parameters and execute */
            $the_name = $username;
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $name, $hash, $admin);

            if ($stmt->num_rows > 0) {
                /* username exists already: check if password is correct */

                $stmt->fetch();

                if ($username == $name && $password_hash == $hash) {
                    /* signin successful */

                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $name;
                    $_SESSION['signed_in'] = true;
                    $_SESSION['is_admin'] = $admin;

                    array_push($_SESSION['flash'], "Login successful.");

                    /* free result */
                    $stmt->free_result();

                    /* close statement */
                    $stmt->close();

                    /* close connection */
                    $conn->close();

                    if (isset($_SESSION['request_url'])) {
                        $request_url = $_SESSION['request_url'];
                        redirect($request_url);
                    }
                    else {
                        redirect('../');
                    }


                }
                else {
                    /* signin unsuccessful: password incorrect */

                    array_push($_SESSION['flash'], "<span class='error_color'>Login unsuccessful. Username and/or password incorrect.</span>");

                    /* free result */
                    $stmt->free_result();

                    /* close statement */
                    $stmt->close();

                    /* close connection */
                    $conn->close();

                    redirect('new');
                }


            }
            else {
                /* username does not exists, stmt error or couldn't fetch user info */

                array_push($_SESSION['flash'], "<span class='error_color'>Login unsuccessful. Username and/or password incorrect.</span>");

                /* close statement */
                $stmt->close();

                /* close connection */
                $conn->close();

                redirect('new');
            }
        }
        else {
            /* sql query error: statement not valid */

            array_push($_SESSION['flash'], "<span class='error_color'>Login unsuccessful. Username and/or password incorrect.</span>");

            /* close connection */
            $conn->close();

            redirect('new');

        }
    }
    else {
        /*  bad request */

        resetFormToken();

        /* redirect to  */
        redirect('new');
    }


