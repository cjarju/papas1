<?php

require_once '../../assets/php/php_functions.php';
require_once '../includes/_restrict.php';

/* generate a form token valid for 15 mins. the form will expire after this time
   limits the possibility of cross site request forgery (XSRF)
*/
generateFormToken('new-user',900);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>New User</title>
    <?php require_once '../includes/_head_elements.php'?>
</head>

<body id="login">

    <?php
        require_once '../includes/_header.php';
        require_once '../includes/_info_section.php';
    ?>

	<div class="container">
		<div class="row">
			<div class="col-lg-12">
			    <!-- html 5 validation with custom message: <input type="text" required data-validation-required-message="Please enter your name."> -->
				<form method="post" name="new-user" id="new-user" class="form-horizontal" action="create" enctype="multipart/form-data" onsubmit="return validateForm(this.id)">
                <fieldset>
                    <legend>New User</legend>
					<div class="form-group">
						<label id="user_name-label" for="user_name" class="col-sm-2 control-label">Username</label>
						<div class="col-sm-3">
							<input type="text" name="user_name" id="user_name" class="form-control obligatory safe-alphanum1" placeholder="Username *" required maxlength="25" />
						</div>
						<span id="username-span"></span>
					</div>
					
					<div class="form-group">
						<label id="password-label" for="password" class="col-sm-2 control-label">Password</label>
						<div class="col-sm-3">
							<input type="password" name="password" id="password" class="form-control obligatory compare safe-alphanum2" placeholder="Password *" required maxlength="25" />
						</div>
					</div>
 
					<div class="form-group">
						<label id="password_confirm-label" for="password_confirm" class="col-sm-2 control-label">Confirm Password</label>
						<div class="col-sm-3">
							<input type="password" name="password_confirm" id="password_confirm" class="form-control obligatory compare safe-alphanum2" placeholder="Confirm Password *" required maxlength="25" />
						</div>
						<span id="pwd-confirm-span"></span>
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

 





