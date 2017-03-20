
<!-- Navigation -->
<nav class="navbar navbar-inverse" role="navigation">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="../../" id="business_name_hdr">Papa's Media Systems</a>
		</div>
		
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
                <li><a href="../about/">About</a></li>
				<li><a href="../users/">Users</a></li>
				<li><a href="../services/">Services</a></li>
				<li><a href="../gallery/">Gallery</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="" class="not-active"><span class="glyphicon glyphicon-user"></span> <?php if (isset($_SESSION['username'])) { echo $_SESSION['username']; } ?></a></li>
				<li><a href="../sessions/delete" title="Signout"><span class="glyphicon glyphicon-log-out"></span></a></li>
			</ul>
		</div>
	</div>
</nav>

 
 
 