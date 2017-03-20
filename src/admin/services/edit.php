<?php

require_once '../../assets/php/php_functions.php';
require_once '../includes/_restrict.php';

/* generate a form token valid for 15 mins. the form will expire after this time
   limits the possibility of cross site request forgery (XSRF)
*/
generateFormToken('edit-service',900);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Service</title>
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

				$id = $conn->real_escape_string($_GET['id']);

				$sql = "SELECT * FROM services WHERE id = '$id'";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();

					$svc_name = trim($row['svc_name']);
					$svc_descr = trim($row['svc_descr']);

/* html 5 validation with custom message: <input type="text" required data-validation-required-message="Please enter your name."> */

$form = <<<STRHTML
<form method="post" name="edit-service" id="edit-service" class="form-horizontal" action="do_edit" enctype="multipart/form-data" onsubmit="return validateForm(this.id)">
<fieldset>
  <legend>Edit service</legend>

    <p>
        <input type="hidden" name="rec_id" id="rec_id" value="$id" />
    </p>

	<div class="form-group">
		<label id="svc_name-label" for="svc_name" class="col-sm-2 control-label">Service Name</label>
		<div class="col-sm-5">
			<input type="text" name="svc_name" id="svc_name" class="form-control obligatory" value="$svc_name" required maxlength="255" />
		</div>
	</div>

	<div class="form-group">
		<label id="svc_descr-label" for="svc_descr" class="col-sm-2 control-label">Service Description</label>
		<div class="col-sm-10">
			<textarea rows="5" name="svc_descr" id="svc_descr" class="form-control obligatory" required maxlength="5000"> $svc_descr </textarea>
		</div>
	</div>

	<div class="form-group">
		<label id="file_to_upload-label" for="file_to_upload" class="col-sm-2 control-label">Service Photo</label>
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





