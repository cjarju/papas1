<?php
    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>About</title>
    <?php require_once '../includes/_head_elements.php'?>
</head>

<body>

    <?php
        require_once '../includes/_header.php';
        require_once '../includes/_info_section.php';
    ?>
	
	<div class="container">
		<div class="row">
            <div class="col-lg-12" id="table_div">
            <?php
            require_once '../../assets/php/_database.php';

            $sql = "select * from about";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id = $row['id'];
                $business_name = $row['business_name'];
                $about_text = implode(' ', array_slice(explode(' ', $row['about_text']), 0, 10));
                $intro_lead_in = $row['intro_lead_in'];
                $intro_heading = $row['intro_heading'];
                $phone_no = $row['phone_no'];
                $email = $row['email'];
                $instagram = $row['instagram'];
                $facebook = $row['facebook'];
                $logo_name = $row['logo_name'];

$str_html = <<<STR
<table class='table table-hover table-condensed table_autofit'>
	<caption>About</caption>
	<thead>
		<tr> <th>Name</th> <th>About</th> <th>Edit</th> </tr>
	</thead>
	<tbody>
        <tr>
            <td> $business_name </td>
            <td> $about_text... </td>
            <td> <a href="edit?id=$id"><span class='glyphicon glyphicon-pencil'>&nbsp;</span></a></td>
        </tr>
    <tbody>
</table>
STR;
                echo $str_html;
            }

            /* free result set */
            $result->free_result();

            /* close database connection */
            $conn->close();
            ?>
            </div>
		</div>
	</div>

    <?php require_once '../includes/_footer.php'?>

</body>
</html>
