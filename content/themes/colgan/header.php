<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php bloginfo('name'); ?></title>
		<meta rel="author" content="Marty Colgan">
		<meta name="description" content="Colgan Commodities is a futures and commodities brokerage firm specializing in providing farmers with the tools to market their products.">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/bootstrap.min.css?v=51235123">
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.css?v=51235123">

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
			<div class="container">
				<h1><a href="<? echo bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
			</div>
		</header>
		<nav role="navigation" class="navbar">
			<div class="container">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				  <span class="sr-only">Toggle navigation</span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				</button>

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="<?php bloginfo('url') ?>">Home</a></li>
						<li class="dropdown">
							<a class="dropdown-toggle" href="#" data-toggle="dropdown">About</a>
							<ul class="dropdown-menu">
								<li><a href="/about/">Colgan Commodities</a></li>
								<li><a href="/commodities-trading/">Commodities Trading</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a class="dropdown-toggle" href="#" data-toggle="dropdown">Audio Commentaries</a>
							<ul class="dropdown-menu">
								<li><a href="/commentaries/">Listen Online</a></li>
								<li><a href="/commentaries/snapshot-tour">Snapshot Tour</a></li>
								<li><a href="/commentaries/colgan-audio-cast/">Colgan AudioCast</a></li>
							</ul>
						</li>
						<li><a href="/photos/">Photo Blog</a></li>
						<li><a href="/open-account/">Open Account</a></li>
						<li><a href="/contact-us/">Contact Us</a></li>
						<li class="dropdown">
							<a class="dropdown-toggle" href="#" data-toggle="dropdown">Log in</a>
							<ul class="dropdown-menu">
								<?php if ( !is_user_logged_in() ) { ?><li><a href="/login">To ColganCommodities.com</a></li><?php } ?>
								<li><a href="https://members.admis.com/AccountLogin.aspx?ReturnUrl=%2fdefault.aspx">To ADM</a></li>
							</ul>
						</li>
					</ul>
				</div>

			</div>
		</nav>
		<?php if ( is_user_logged_in() ) {
			global $user_identity;
			global $user_login;
			get_currentuserinfo();
		?>
		<p class="container logged-in">Logged in as <a href="/profile/"><?php echo ($user_identity) ? $user_identity : $user_login; ?></a>. <a href="<?php echo colgan_logout_url('/'); ?>">Log out.</a></p>
		<?php } ?>
		<div id="content" class="container">

