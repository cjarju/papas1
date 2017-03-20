<?php

require_once '../../assets/php/php_functions.php';
require_once '../includes/_restrict.php';

/* generate a form token valid for 15 mins. the form will expire after this time
   limits the possibility of cross site request forgery (XSRF)
*/
generateFormToken('new-service',900);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>New Service</title>
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
				<form method="post" name="new-service" id="new-service" class="form-horizontal" action="create" enctype="multipart/form-data" onsubmit="return validateForm(this.id)">
                <fieldset>
                    <legend>New Service</legend>
					
					<div class="form-group">
						<label id="svc_name-label" for="svc_name" class="col-sm-2 control-label">Service Name</label>
						<div class="col-sm-5">
							<input type="text" name="svc_name" id="svc_name" class="form-control obligatory" placeholder="Service name *" required maxlength="255" />
						</div>
					</div>
					
					<div class="form-group">
						<label id="svc_descr-label" for="svc_descr" class="col-sm-2 control-label">Service Description</label>
						<div class="col-sm-10">
							<textarea rows="5" name="svc_descr" id="svc_descr" class="form-control obligatory" placeholder="Service description *" required maxlength="5000"></textarea>
						</div>
					</div>
					
					<div class="form-group">
						<label id="file_to_upload-label" for="file_to_upload" class="col-sm-2 control-label">Service Photo</label>
						<div class="col-sm-10">
							<input type="file" name="file_to_upload" id="file_to_upload" class="file_required" required />
						</div>
					</div>
				 
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-primary">Create</button>
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

 





