<?php
    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';
	require_once '../../assets/php/_database.php';
				
	//getPaginateData($conn, $table_name, $cond='', $limit=1)
	$paginate_data = getPagData($conn, 'users');
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
	$sql = "SELECT id, user_name FROM users order by id LIMIT ? OFFSET ?";

	$stmt = $conn->prepare($sql);

	if ($stmt) {

        /* bind parameters for markers */
		$stmt->bind_param("ii", $el_limit, $el_offset);

        /* set parameters and execute */
		$el_limit = $paginate_data['limit'];
		$el_offset = $paginate_data['offset'];
		$stmt->execute();

		$stmt->store_result();
		$stmt->bind_result($id, $user_name);

		if ($stmt->num_rows > 0) {

$str_html = <<<STR
<table class='table table-hover table-condensed table_autofit'>
	<caption>Users</caption>
	<thead>
		<tr> <th>Username</th> <th>Edit</th> <th>Delete</th> </tr>
	</thead>
	<tbody>
STR;

			while ($row = $stmt->fetch()) {
$str_html .= <<<STR
<tr>
    <td> $user_name </td>
    <td> <a href="edit?id=$id"><span class='glyphicon glyphicon-pencil'>&nbsp;</span></a></td>
    <td> <a href="delete?id=$id" class='delete_confirmation_ajax'><span class='glyphicon glyphicon-remove'>&nbsp;</span></a></td>
</tr>
STR;
			}

			$str_html .= '<tbody></table>';

            /* append pagination */
			$str_html .= paginateAJAX2($paginate_data);

            /* free result set */
            $stmt->free_result();

            /*  close statement */
            $stmt->close();

            /*  close connection */
            $conn->close();

            /* return */
			echo $str_html;
		}
        else {
            /* empty table, couldn't get results or stmt error */

            /* free result set */
            $stmt->free_result();

            /*  close statement */
            $stmt->close();

            /*  close connection */
            $conn->close();

$str_script = <<<STR
<script type='text/javascript'>
    $('#info_sect').html("No users.");
</script>
STR;
            /* return */
            echo $str_script;
		}
	}
    else {
        /* query error */

        /*  close connection */
        $conn->close();

$str_script = <<<STR
<script type='text/javascript'>
    $('#info_sect').html("<span class='error_color'>No users. Query error.</span>");
</script>
STR;
        /* return */
        echo $str_script;
    }