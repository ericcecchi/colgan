<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php bloginfo('name'); ?></title>
		<meta name="author" content="Marty Colgan">
		<meta name="description" content="Colgan Commodities is a futures and commodities brokerage firm specializing in providing farmers with the tools to market their products.">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/bootstrap.css">
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.css">
		
	  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
	  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	  <script>window.jQuery || document.write('<script src="<?php bloginfo('template_url'); ?>/js/jquery.js"><\/script>')</script>
	  
		<!-- Typekit -->
		<script src="//use.typekit.com/vpn3lfq.js"></script>
		<script>try{Typekit.load();}catch(e){}</script>
		
		<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<!-- Begin WordPress -->
		<?php wp_head(); ?>
		<!-- End Wordpress -->
	</head>
	<body <?php body_class(); ?>>
		<header role="banner">
			<h1><a href="<? echo bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
		</header>
		<nav role="navigation" class="navbar">
			<div class="navbar-inner container">
				<?php if ( is_user_logged_in() ) { 
					global $user_login;
					get_currentuserinfo();
				?>
					<p class="navbar-text pull-right">Logged in as <a href="/profile/"><?php echo $user_login; ?></a>. <a href="/wordpress/wp-login.php?action=logout">Log out.</a></p>
				<?php } ?>
				<ul class="nav">
					<li><a href="<?php bloginfo('url') ?>">Home</a></li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">About<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/about/">Colgan Commodities</a></li>
							<li><a href="/commodities-trading/">Commodities Trading</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">Audio Commentaries<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/commentaries/">Listen Online</a></li>
							<li><a href="/commentaries/snapshot-tour">Snapshot Tour</a></li>
							<li><a href="/commentaries/colgan-audio-cast/">Colgan AudioCast</a></li>
						</ul>
					</li>
					<li><a href="/open-account/">Open Account</a></li>
					<li><a href="/contact-us/">Contact Us</a></li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">Log in<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="https://members.admis.com/AccountLogin.aspx?ReturnUrl=%2fdefault.aspx">To ADM</a></li>
							<?php if ( !is_user_logged_in() ) { ?><li><a href="/login">To ColganCommodities.com</a></li><?php } ?>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
		<div id="content" class="container-fluid">