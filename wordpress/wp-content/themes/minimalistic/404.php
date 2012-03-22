<?php
get_header();
?>
<div id="container">
    <div id="main_content">
    	<div id="content">
			<h2 class="center">Error 404 - Not Found</h2>
			<p>Sorry, but the page you are looking for could not be found. The page may have been deleted or the link you followed may have been outdated.</p>
			<ul class="styledlist">
				<?php
				  $my_pages = wp_list_pages('echo=0&title_li=');
				  $ul_str1 = '<ul>';
				  $ul_str2 = '<ul style="padding:0;">';
				  $my_pages = str_replace($ul_str1, $ul_str2, $my_pages);
				  echo $my_pages;
				?>
			</ul>
		</div>
	</div>
	
	<?php get_sidebar(); ?>
	
</div>	

<?php get_footer(); ?>