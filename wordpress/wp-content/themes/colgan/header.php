<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php bloginfo('name'); ?></title>
		<meta name="author" content="Marty Colgan">
		<meta name="description" content="Colgan Commodities is a futures and commodities brokerage firm specializing in providing farmers with the tools to market their products.">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/bootstrap.css">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
		
	  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
	  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	  <script>window.jQuery || document.write('<script src="<?php bloginfo('template_url'); ?>/js/libs/jquery-1.7.1.min.js"><\/script>')</script>
	  
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
				<ul class="nav">
					<li><a href="<?php bloginfo('url') ?>">Home</a></li>
					<li><a href="/about/">About</a></li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">Commodities Trading<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/commodities-trading/">About Commodities Trading</a></li>
							<li><a href="/commodities-trading/colgan-audio-cast/">Colgan AudioCast</a></li>
						</ul>
					</li>
					<li><a href="/open-account/">Open Account</a></li>
					<li><a href="/contact-us/">Contact Us</a></li>
				</ul>
				<ul class="nav pull-right"><li><a href="https://members.admis.com/AccountLogin.aspx?ReturnUrl=%2fdefault.aspx">Login</a></li></ul>
			</div>
		</nav>
		<div id="content" class="container-fluid">