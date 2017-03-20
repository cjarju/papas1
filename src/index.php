<?php
require_once "assets/php/php_functions.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Papa's Media Production, Gambia</title>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Papa's Media Production, Gambia" />
    <meta name="author" content="Charles Jarju" />

    <!-- You can use Open Graph tags to customize link previews -->
    <meta property="og:url"           content="https://papasmedia.com" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="Papa's Media Production, Gambia" />
    <meta property="og:description"   content="Papa's Media Production, Gambia" />
    <meta property="og:image"         content="https://papasmedia.com/assets/images/logos/papasmedia.png" />

	<link href="assets/images/icons/favicon.ico" rel="icon" />

    <!-- Bootstrap Core CSS -->
    <!-- Bootstrap IE10 viewport hack for Surface/desktop Windows 8 bug -->

    <!-- build:css assets/css/feui-main.min.css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/ie10-viewport-bug.css" rel="stylesheet" />
    <link href="assets/css/agency.css" rel="stylesheet" />
	<link href="assets/css/elastislide.css" rel="stylesheet" />
	<link href="assets/css/gallery.css" rel="stylesheet" />
    <link href="assets/css/share-buttons.css" rel="stylesheet" />
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/recaptcha.css" rel="stylesheet" />
    <!-- endbuild -->

    <!-- <link href="assets/css/feui-main.min.css" rel="stylesheet" /> -->

    <link href="assets/css/google-fonts.css" rel="stylesheet" />
    <!--
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700|Kaushan+Script|Droid+Serif:400,700,400italic,700italic|Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css">
    -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn"t work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<noscript>
		<style>
			.es-carousel ul{
				display:block;
			}
		</style>
	</noscript>
</head>

<body id="page-top" class="index">

    <!-- Social -->
    <div id="contact-buttons-bar">
        <button class="contact-button-link show-hide-contact-bar">
            <span class="fa fa-angle-left"></span>
        </button>
        <a href="https://www.facebook.com/sharer/sharer.php?u=https%3A//papasmedia.com" class="contact-button-link cb-ancor facebook"  title="Share on Facebook"><span class="fa fa-facebook"></span></a>
        <a href="https://plus.google.com/share?url=https%3A//papasmedia.com" class="contact-button-link cb-ancor gplus" title="Share on Google Plus"><span class="fa fa-google-plus"></span></a>
    </div>

    <?php
        require_once "assets/php/_database.php";

        $sql = "select * from about";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id = $row["id"];
            $business_name = $row["business_name"];
            /* $about_text = nl2br($row["about_text"],true); */
            $about_text = nl2p($row["about_text"]);
            $intro_lead_in = $row["intro_lead_in"];
            $intro_heading = $row["intro_heading"];
            $phone_no = $row["phone_no"];
            $email = $row["email"];
            $instagram = $row["instagram"];
            $facebook = $row["facebook"];
        }

        /* free result set */
        $result->free_result();

    ?>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top"><?php echo $business_name ?></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about">About</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#services">Services</a>
                    </li>
					<li>
                        <a class="page-scroll" href="#gallery">Gallery</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>

        </div> <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in"><?php echo $intro_lead_in ?></div>
                <div class="intro-heading"><?php echo $intro_heading ?></div>
                <a href="#services" class="page-scroll btn btn-xl">Tell Me More</a>
            </div>
        </div>
    </header>
	
	 <!-- About Section -->
    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">About Us</h2>
                    <h3 class="section-subheading text-muted">Who We Are</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $about_text ?>
					<p>For more information, please do not hesitate to <a class="page-scroll" href="#contact">contact us</a>.</p>
					<!-- <br /><br /><br /><br /><br /><br /> -->
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Services</h2>
                    <h3 class="section-subheading text-muted">What We Offer</h3>
                </div>
            </div>
			
			<?php

			$sql = "select * from services";
            $result = $conn->query($sql);
			
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$id = $row["id"];
					$svc_name = $row["svc_name"];
					$svc_descr = nl2br($row["svc_descr"],true);
					$svc_img_name = $row["svc_img_name"];
                    $img_file_path = "assets/images/services/$svc_img_name";
                    list($width, $height) = getimagesize($img_file_path);

$html = <<<HTML
<div class="row content_section">
	<div class="col-lg-12">
		<hr class="section_heading_spacer">
        <div class="clearfix"></div>
		<h3 class="section-heading">$svc_name</h3>
		<p class="lead">$svc_descr</p>
	</div>
	<div class="col-lg-12 service_img_div">
		<img class="img-responsive" src="assets/images/services/$svc_img_name" alt="" width="$width" height="$height">
	</div>
</div>
HTML;
					echo $html;
				}
			}
            
			/* free result set */
            $result->free_result();
        ?>
        </div> <!-- container -->
    </section>
	
	<!-- Gallery Section -->
    <section id="gallery">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
					<h2 class="section-heading text-center">Gallery</h2>
						<div class="content">
							<div id="rg-gallery" class="rg-gallery">

                                <!-- thumbnail viewer parent div -->
								<div class="rg-thumbs">
									<div class="es-carousel-wrapper">
										<div class="es-nav">
											<span class="es-nav-prev">Previous</span>
											<span class="es-nav-next">Next</span>
										</div>
										<div class="es-carousel">
											<ul>
												<?php								
													$sql = "select * from gphotos";
													$result = $conn->query($sql);
													
													if ($result->num_rows > 0) {
														while($row = $result->fetch_assoc()) {
															$id = $row["id"];
															$img_name = $row["img_name"];
															$img_descr = $row["img_descr"];
                                                            $img_file_path = "assets/images/gallery/$img_name";
                                                            list($width, $height) = getimagesize($img_file_path);
                                                            echo "<li><a href='#'><img src='assets/images/gallery/thumbs/$img_name' width='65' height='65' data-large='assets/images/gallery/$img_name' alt='image$id' data-description='$img_descr' data-width='$width' data-height='$height'/></a></li>";
														}
													}
													/* free result set */
													$result->free_result();
												?>
											</ul>
										</div> <!-- carousel-wrapper -->
									</div> <!-- es-carousel-wrapper -->
								</div><!-- rg-thumbs -->

                                <!-- rg-image-wrapper -->
                                <!--
                                     x-tmpl has no real meaning, it simply stops the browser from interpreting the script as javascript.
                                     It"s mostly used with jquery templates. At some point, a javascript data object will be used in conjunction with the
                                     template to render some html.
                                 -->
                                <script id="img-wrapper-tmpl" type="text/x-jquery-tmpl">
                                    <div class="rg-image-wrapper">
                                        {{if itemsCount > 1}}
                                            <div class="rg-image-nav">
                                                <a href="#" class="rg-image-nav-prev">Previous Image</a>
                                                <a href="#" class="rg-image-nav-next">Next Image</a>
                                                </div>
                                        {{/if}}
                                        <div class="rg-image"></div>
                                        <div class="rg-loading"></div>
                                        <div class="rg-caption-wrapper">
                                            <div class="rg-caption display_none">
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                </script>

							</div><!-- rg-gallery -->
						</div><!-- content -->
					</div><!-- container -->
                </div>
            </div>
    </section>

     <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Contact Us</h2>
                    <!-- <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3> -->
                </div>
            </div>

			<div class="row contact_icons_sect">
				<div class="col-lg-4 col-lg-offset-2 text-center">
                    <i class="fa fa-phone fa-3x wow bounceIn"></i>
                    <p><?php echo $phone_no ?></p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fa fa-envelope-o fa-3x wow bounceIn" data-wow-delay=".1s"></i>
                    <p><a href="mailto:<?php echo $email ?>">Email Us</a></p>
                </div>
			</div>

			<div class="row">
				<div class="col-lg-12 text-center">
                    <h4 class="section-heading">Or leave a message below:</h4>
                </div>
			</div>

            <div class="row">
                <div class="col-lg-12">
                    <form name="sentMessage" id="contactForm" novalidate>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Your Name *" id="name" required data-validation-required-message="Please enter your name.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Your Email *" id="email" required data-validation-required-message="Please enter your email address.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Your Phone *" id="phone" required data-validation-required-message="Please enter your phone number.">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Your Message *" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                            <div id="success"></div>
                        </div>
                        <div class="col-lg-12 text-center">
                            <button type="submit" class="btn btn-xl">Send Message</button>
                        </div>
                        <div class="col-lg-12">
                            <div  class="form-group" id="recaptcha_widget">
                                <div class="g-recaptcha" data-sitekey="6LdiexoTAAAAAK0d13mM2u3nEAY1okXkuEO0cZwp"></div>
                            </div>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">&copy; <a class="page-scroll" href="#page-top"><?php echo $business_name ?></a> <?php echo date("Y"); ?>.</span>
                </div>
                <div class="col-md-4">
				    <span class="copyright"> Follow us: </span>
                    <ul class="list-inline social-buttons">
                        <li><a href="<?php echo $facebook ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="<?php echo $instagram ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                        <!-- <li><a href="#"><i class="fa fa-linkedin"></i></a></li>-->
                    </ul>
                </div>
                <div class="col-md-4">
                    <span class="copyright">Developed by Charles Jarju.</span>
                </div>
            </div>
        </div>
    </footer>

    <?php
		/* close database connection */
		$conn->close();
    ?>

<!-- JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

    <!-- build:js assets/js/feui-core.min.js -->
        <!-- jQuery -->
    <script src="assets/js/jquery.min.js"></script>
        <!-- plugins -->
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/jquery.tmpl.min.js"></script>
        <!-- Bootstrap Core JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>
	    <!-- Bootstrap IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="assets/js/ie10-viewport-bug.js"></script>
    <!-- endbuild -->


    <!-- build:js assets/js/feui-cust.min.js -->
        <!-- gallery -->
	<script src="assets/js/elastislide.js"></script>
	<script src="assets/js/gallery.js"></script>
	    <!-- startboostrap theme -->
    <script src="assets/js/classie.js"></script>
    <script src="assets/js/cbpAnimatedHeader.min.js"></script>
    <script src="assets/js/jqBootstrapValidation.js"></script>
    <script src="assets/js/contact_me.js"></script>
    <script src="assets/js/agency.js"></script>
        <!-- social share -->
    <script src="assets/js/share-buttons.js"></script>
    <!-- endbuild -->

    <!-- <script src="assets/js/feui-core.min.js"></script> -->
    <!-- <script src="assets/js/feui-cust.min.js"></script> -->

	<!-- google recaptcha -->
    <script src="https://www.google.com/recaptcha/api.js"></script>

</body>

</html>