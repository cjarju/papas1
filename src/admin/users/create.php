<?php

    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';

    if( isset($_COOKIE['token']) && isset($_SESSION['token']) && $_COOKIE['token'] == $_SESSION['token']  ){

        /* request ok (form has valid token) */

        require_once '../../assets/php/_database.php';

        $username = strtolower($_POST['user_name']);
        $password = $_POST['password'];
        $password_hash = md5($password);

        /* fallback validation on server side (bulletproof): complement the client side validation */
        if (isEmpty($username) || isEmpty($password) || isSafeAlphaNum1($username) || isSafeAlphaNum2($password)) {
            array_push($_SESSION['flash'], "<span class='error_color'>User not created.</span>");
            redirect('new');
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
        $sql = "select * from users where user_name = ?";

        $stmt = $conn->prepare($sql);

        /* var_dump($stmt); */

        if ($stmt) {

            /*  bind parameters for markers */
            $stmt->bind_param("s", $the_name);

            /* set parameters and execute */
            $the_name = $username;
            $stmt->execute();
            $stmt->store_result();
            /* $stmt->bind_result($id, $user_name); */

            if ($stmt->num_rows > 0) {
                /* username exists already - choose another */

                array_push($_SESSION['flash'], "<span class='error_color'>Username already exists. Choose another one.</span>");

                //free result
                $stmt->free_result();

                // close statement
                $stmt->close();

                // close connection
                $conn->close();

                redirect("new");

            }
            else {
                /* insert record */

                $sql_insert = "INSERT INTO users (user_name,password_hash) VALUES (?, ?)";

                /* create a prepared statement */
                $stmt_insert = $conn->prepare($sql_insert);

                if ($stmt_insert) {

                    /* bind parameters for markers */
                    $stmt_insert->bind_param("ss", $the_name, $the_hash);

                    /* set parameters and execute */

                    /* insert a row */
                    $the_name = $username;
                    $the_hash = $password_hash;
                    $stmt_insert->execute();

                    if ($stmt_insert->affected_rows > 0) {

                        array_push($_SESSION['flash'], "User Created.");

                        /* close statement */
                        $stmt_insert->close();

                        /* close statement */
                        $stmt->close();

                        /* close connection */
                        $conn->close();

                        redirect('.');

                    }
                    else {
                        /* insert unsuccessful or stmt error */

                        array_push($_SESSION['flash'], "<span class='error_color'>User not created.</span>");

                        /* close statement */
                        $stmt_insert->close();

                        /* close statement */
                        $stmt->close();

                        /* close connection */
                        $conn->close();

                        redirect('new');
                    }
                }
                else {
                    /* insert query error: statement invalid */

                    array_push($_SESSION['flash'], "<span class='error_color'>User not created.</span>");

                    // close statement
                    $stmt->close();

                    // close connection
                    $conn->close();

                    redirect('new');

                }
            }

        }
        else {
            /* sql query error: statement not valid */

            array_push($_SESSION['flash'], "<span class='error_color'>User not created.</span>");

            /* close connection */
            $conn->close();

            /* redirect to */
            redirect("new");
        }

    } else{
        /* bad request */

        resetFormToken();

        /* redirect to */
        redirect('new');
    }

