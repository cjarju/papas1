<?php

/* prevent display of error messages in browser: production mode */
/*ini_set('display_errors', '0');*/

$servername = "localhost";
$username = "root";
$password = "";
$database = "webapp01";

/* Create connection */

$conn = new mysqli($servername, $username, $password, $database);

/* Check connection
 null in boolean is false; string with value is true
 when connection is successful, connect_error will be null
*/

if ($conn->connect_error) {
    /* die("Connection failed: " . $conn->connect_error); */
    die("Database connection failed.");
}

$conn->set_charset("utf8");

 