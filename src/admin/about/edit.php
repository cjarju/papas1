<?php

require_once '../../assets/php/php_functions.php';
require_once '../includes/_restrict.php';

/* generate a form token valid for 15 mins. the form will expire after this time
   limits the possibility of cross site request forgery (XSRF)
*/
generateFormToken('edit-about',900);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit About</title>
    <?php require_once '../includes/_head_elements.php'?>
</head>

<body>

    <?php
        require_once '../includes/_header.php';
        require_once '../includes/_info_section.php';

    //nl2br($row['about_text'], true);
    ?>

	<div class="container">
		<div class="row">
			<div class="col-lg-12">
            <?php

				require_once '../../assets/php/_database.php';

				$id = $conn->real_escape_string($_GET['id']);

				$sql = "SELECT * FROM about WHERE id = '$id'";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
                    
                    $business_name = trim($row['business_name']);
                    $about_text = trim($row['about_text']);
                    $intro_lead_in = trim($row['intro_lead_in']);
                    $intro_heading = trim($row['intro_heading']);
                    $phone_no = trim($row['phone_no']);
                    $email = trim($row['email']);
                    $instagram = trim($row['instagram']);
                    $facebook = trim($row['facebook']);
                    $logo_name = trim($row['logo_name']);

/* html 5 validation with custom message: <input type="text" required data-validation-required-message="Please enter your name."> */

$form = <<<STRHTML
<form method="post" name="edit-service" id="edit-about" class="form-horizontal" action="do_edit" enctype="multipart/form-data" onsubmit="return validateForm(this.id)">
<fieldset>
  <legend>Edit about</legend>

    <p>
        <input type="hidden" name="rec_id" id="rec_id" value="$id" />
    </p>

	<div class="form-group">
		<label id="business_name-label" for="business_name" class="col-sm-2 control-label">Business Name</label>
		<div class="col-sm-5">
			<input type="text" name="business_name" id="business_name" class="form-control obligatory" value="$business_name" required maxlength="100" />
		</div>
	</div>

	<div class="form-group">
		<label id="about_text-label" for="about_text" class="col-sm-2 control-label">About Business</label>
		<div class="col-sm-10">
			<textarea rows="5" name="about_text" id="about_text" class="form-control obligatory" required maxlength="10000"> $about_text </textarea>
		</div>
	</div>

    <div class="form-group">
		<label id="intro_lead_in-label" for="intro_lead_in" class="col-sm-2 control-label">Intro Lead</label>
		<div class="col-sm-5">
			<input type="text" name="intro_lead_in" id="intro_lead_in" class="form-control obligatory" value="$intro_lead_in" required maxlength="50" />
		</div>
	</div>
	
    <div class="form-group">
		<label id="intro_heading-label" for="intro_heading" class="col-sm-2 control-label">Intro Heading</label>
		<div class="col-sm-5">
			<input type="text" name="intro_heading" id="intro_heading" class="form-control obligatory" value="$intro_heading" required maxlength="50" />
		</div>
	</div>
	
    <div class="form-group">
		<label id="phone_no-label" for="phone_no" class="col-sm-2 control-label">Phone no.</label>
		<div class="col-sm-5">
			<input type="tel" name="phone_no" id="phone_no" class="form-control obligatory" value="$phone_no" required maxlength="50" />
		</div>
	</div>	
	
    <div class="form-group">
		<label id="email-label" for="email" class="col-sm-2 control-label">Contact Email</label>
		<div class="col-sm-5">
			<input type="email" name="email" id="email" class="form-control obligatory" value="$email" required maxlength="50" />
		</div>
	</div>	
	
    <div class="form-group">
		<label id="instagram-label" for="instagram" class="col-sm-2 control-label">Instagram</label>
		<div class="col-sm-6">
			<input type="url" name="instagram" id="instagram" class="form-control obligatory" value="$instagram" required maxlength="100" />
		</div>
	</div>	
	
    <div class="form-group">
		<label id="facebook-label" for="facebook" class="col-sm-2 control-label">Facebook Page</label>
		<div class="col-sm-6">
			<input type="url" name="facebook" id="facebook" class="form-control obligatory" value="$facebook" required maxlength="100" />
		</div>
	</div>	
	
	<div class="form-group">
		<label id="file_to_upload-label" for="file_to_upload" class="col-sm-2 control-label">Logo</label>
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





