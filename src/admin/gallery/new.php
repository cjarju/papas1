<?php

require_once '../../assets/php/php_functions.php';
require_once '../includes/_restrict.php';

/* generate a form token valid for 15 mins. the form will expire after this time
   limits the possibility of cross site request forgery (XSRF)
*/
generateFormToken('new-gphoto',900);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>New Gallery Photo</title>
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
			    <!-- html 5 validation with custom message: <input type="text" required data-validation-required-message="Please enter your name."> -->
				<form method="post" name="new-gphoto" id="new-gphoto" class="form-horizontal" action="create" enctype="multipart/form-data" onsubmit="return validateForm(this.id)">
                <fieldset>
                    <legend>New Gallery Photo</legend>
					
					<div class="form-group">
						<label id="img_descr-label" for="img_descr" class="col-sm-2 control-label">Photo Description</label>
						<div class="col-sm-6">
							<input type="text" name="img_descr" id="img_descr" class="form-control obligatory" placeholder="Photo Description *" required maxlength="255" />
						</div>
					</div>
									
					<div class="form-group">
						<label id="file_to_upload-label" for="file_to_upload" class="col-sm-2 control-label">Gallery Photo</label>
						<div class="col-sm-10">
							<input type="file" name="file_to_upload" id="file_to_upload" class="file_required" required />
						</div>
					</div>
				 
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-primary">Upload</button>
						</div>
					</div>
                </fieldset>
				</form>
			</div>
		</div>
	</div> <!-- /container -->

	<?php require_once '../includes/_footer.php'?>
</body>
</html>

 





