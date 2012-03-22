<?php
// Get posts Custom Fields
function get_wp_options($option_name, $info, $default = false){
	GLOBAL $shortname;
	$value = get_option($shortname.$option_name);
	if (!$value) { $value = $info; }
	($value == false) ? $default : $value;
	return $value;
}

// Get posts for homepage 3 columns
function get_columns_data($post_id,$selected_type,$max_char,$column_number){
	GLOBAL $shortname;
	$query_post = get_post($post_id);	
	$title = $query_post->post_title;
	$content = $query_post->post_content;
	$permalink = get_permalink($post_id);
	$columnimage = get_post_meta($post_id, $shortname.'_small_image_257x57', true);
	if($columnimage != ''){ 
		$alt_text = 'no image';
		$img_src = '<img src="'.$columnimage.'" alt="'.$alt_text.'" />';
	} else {
		$alt_text = $title;
		$img_src = '';		
	}							
	
	//$column_readmore_text = get_post_meta($post_id, $shortname.'_column_readmore_text', true);		
	$column_readmore_text = get_option($shortname.'_column'.$column_number.'_readmore_text');	
	if ($column_readmore_text == false) $column_readmore_text = 'Read more' ;
	
	$content=str_replace(']]>', ']]&gt;', $content);
	$content = strip_tags($content);
	if ($max_char != 0)	$content = substr($content,0,$max_char);		

	echo '<h3>'.$title.'</h3>'; 
	echo '<p>'.$content.' <a href="'.$permalink.'">'.$column_readmore_text.' &rarr;</a></p>'.$img_src;
}

// Get excerpt content
function excerpt_content($text, $excerpt_length,$removep) {
	$text = get_the_content('');
	$text = apply_filters('the_content', $text);
	$text = str_replace(']]>', ']]&gt;', $text);
	$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
	if ($removep) { $text = strip_tags($text, '<p>'); $text = strip_tags($text, '</p>'); }
	$text = str_replace('<p>-', '<p>&#151;',$text);
	$words = explode(' ', $text, $excerpt_length + 1);
	if (count($words)> $excerpt_length) {
		array_pop($words);
		array_push($words, '.');
		$text = implode(' ', $words);
	}
	return $text;
}
?>