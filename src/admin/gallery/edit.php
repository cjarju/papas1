<?php

require_once '../../assets/php/php_functions.php';
require_once '../includes/_restrict.php';

/* generate a form token valid for 15 mins. the form will expire after this time
   limits the possibility of cross site request forgery (XSRF)
*/
generateFormToken('edit-gphoto',900);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Gallery Photo</title>
    <?php require_once '../includes/_head_elements.php'?>
</head>

<body>

    <?php
        require_once '../includes/_header.php';
        require_once '../includes/_info_section.php';
    ?>

	<div class="container">
		<div class="row">
			<div class="col-lg-12">
            <?php

				require_once '../../assets/php/_database.php';
				
				$admin_img_dir = '../assets/images/gallery/';

                $id = $conn->real_escape_string($_GET['id']);

				$sql = "SELECT * FROM gphotos WHERE id = '$id'";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {

					$row = $result->fetch_assoc();

					$img_name = trim($row['img_name']);
					$img_descr = trim($row['img_descr']);
					$file_path = $admin_img_dir . $img_name;

$img = <<<STRHTML
<div class="grow center_align">
 <img src="$file_path" alt="">
</div>
STRHTML;
                    echo $img;

/* html 5 validation with custom message: <input type="text" required data-validation-required-message="Please enter your name."> */

$form = <<<STRHTML
<form method="post" name="edit-gphoto" id="edit-gphoto" class="form-horizontal" action="do_edit" enctype="multipart/form-data" onsubmit="return validateForm(this.id)">
<fieldset>
  <legend>Edit Gallery Photo</legend>
    <p>
        <input type="hidden" name="rec_id" id="rec_id" value="$id" />
    </p>

	<div class="form-group">
			<label id="img_descr-label" for="img_descr" class="col-sm-2 control-label">Photo Description</label>
			<div class="col-sm-6">
				<input type="text" name="img_descr" id="img_descr" class="form-control obligatory" value="$img_descr" required maxlength="255" />
			</div>
		</div>

		<div class="form-group">
			<label id="file_to_upload-label" for="file_to_upload" class="col-sm-2 control-label">Gallery Photo</label>
			<div class="col-sm-10">
				<input type="file" name="file_to_upload" id="file_to_upload" />
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</div>
</fieldset>
</form>
STRHTML;

/* <input type="submit" value="Save" class="btn-primary" /> */

                    echo $form;
                }
                else {
                    array_push($_SESSION['flash'], "Could not retrieve record.");
                    redirect(".");
                }

                /* free result set */
                $result->free_result();

                /* close connection */
                $conn->close();
            ?>
			</div> <!-- /col-lg-12 -->
		</div> <!-- /row -->
	</div> <!-- /container -->

	<?php require_once '../includes/_footer.php'?>

</body>
</html>





