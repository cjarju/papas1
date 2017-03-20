<?php

require_once '../../assets/php/php_functions.php';

/* generate a form token valid for 5 mins. the form will expire after this time
   limits the possibility of cross site request forgery (XSRF)
*/
generateFormToken('signin',300);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign in</title>
    <?php require_once '../includes/_head_elements.php'?>
</head>

<body>

    <!-- requires special style: require_once '../includes/_info_section.php'; -->

    <div class="container notification_sect signin_not_sect" id="info_sect">
        <?php
        /* show flash message(s) and reset flash session variable */
        showNotifications();
        ?>
    </div>


	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<form class="form-inline center_align" name="signin" id="signin" method="post" action="create" onsubmit="return validateForm(this.id)">
					<h2 class="form-signin-heading h2">Sign in</h2>  
					<div class="form-group">
						<label id="username-label" for="username" class="sr-only">Username</label>
						<input type="text" name="username" id="username" class="form-control obligatory safe-alphanum1" placeholder="Username" required maxlength="25" />
					</div>
					<div class="form-group">
						<label id="password-label" for="password" class="sr-only">Password</label>
						<input type="password" name="password" id="password" class="form-control obligatory safe-alphanum2" placeholder="Password" required maxlength="25"/>
					</div>
					<button type="submit" class="btn btn-primary">Sign in</button>
				</form>
			</div>
		</div>
	</div>

    <?php require_once '../includes/_footer.php'?>

</body>
</html>