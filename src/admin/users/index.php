<?php
    require_once '../../assets/php/php_functions.php';
    require_once '../includes/_restrict.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Users</title>
    <?php require_once '../includes/_head_elements.php'?>
</head>

<body>

    <?php
        require_once '../includes/_header.php';
        require_once '../includes/_info_section.php';
		require_once '../includes/_body.php';
    ?>

	<div class="container">
		<div class="row">
			<div class="col-lg-12 new_item_div center_align">
				<a href='new.php'>
					<span class='glyphicon glyphicon-plus block_as_inline'> </span>
					<span>New User</span>
				</a>
			</div>
		</div>
	</div>

    <?php require_once '../includes/_footer.php'?>
    <?php require_once '../includes/_footer2.php'?>
	
</body>
</html>
