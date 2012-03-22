<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php bloginfo('name'); ?></title>
		<meta name="author" content="Marty Colgan">
		<meta name="description" content="Colgan Commodities is a futures and commodities brokerage firm specializing in providing farmers with the tools to market their products.">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
		<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
<!-- Begin WordPress -->
<?php wp_head(); ?>
<!-- End Wordpress -->
	</head>
	<body <?php body_class(); ?>>
		<header role="banner">
			<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
		</header>
		
		<nav role="navigation">
			<?php wp_nav_menu('menu=nav'); ?>
		</nav>
