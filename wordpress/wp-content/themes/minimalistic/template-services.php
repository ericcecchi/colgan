<?php
/*
Template Name: Services Page
*/
get_header();
?>
	<!--BEGIN: main_content -->
    <div id="main_content">
    	<div id="content">
			<?
				$page = get_post($pageID);			
				$page_ID = $page->ID;
			?>
        	<h2 class="shadow"><?php echo $page->post_title; ?></h2>
			<p><?php echo str_replace('<img','<img class="border"',$page->post_content); ?></p>
			<div class="column2 services subcontent">	
			<?
				$column_number = 1;
				$row_number = 1;
				$pages = get_pages('child_of='.$page_ID.'&sort_column=ID&sort_order=asc&depth=1&parent='.$page_ID);
				$count = 0;
				foreach($pages as $page)
				{		
					if ($column_number == 1) { 
						echo '<!--begin: row'.$column_number.' -->';
						echo '<div class="first">';
					} else {
						echo '<div>';
					}						

					$content = $page->post_content;
					if(!$content)
						continue;
					if($count >= 10)
						break;
					$count++;	
					$content = apply_filters('the_content', $content);
					$content = str_replace('<p>','',$content);
					$content = str_replace('</p>','',$content);
			?>
			
					<h3><a href="<?php echo get_page_link($page->ID) ?>"><?php echo $page->post_title ?></a></h3>
					<p><?php echo $content; ?><br><a href="<?php echo get_page_link($page->ID) ?>">Learn more about this service &rarr;</a></p>
			<?
						$project_thumb = get_post_meta($page->ID, $shortname.'_small_image_257x57',true);
							
						if($project_thumb){
							echo '<img src="'.$project_thumb.'" />';
						}
			?>					
			
					</div>
			<?php
					$column_number++;
					if ($column_number == 3) {$column_number = 1; $row_number++; }
				}	
			?>

			</div>

			</div>

	<?php get_sidebar(); ?>
        
    </div>
    <!--END: main_content -->    
	
<?php get_footer(); ?>