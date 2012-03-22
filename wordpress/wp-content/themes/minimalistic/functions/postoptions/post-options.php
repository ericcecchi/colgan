<?php
/*	Define the prefix. This prefix will be added before all of our custom fields. */
$prefix = 'mini_';

$meta_box = array(
    'id' => 'my-meta-box',
    'title' => 'Site preview images',
    'page' => 'post',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
		array(
            'name' => 'Blog/News/Gallery Thumb',
            'desc' => 'Image URL. Image size: 160px * 130px.<br>Enter the full URL of the image you would like to use in your Blog, News Gallery posts.',
            'id' => $prefix . 'post_thumb_160x130',
            'type' => 'text',
            'std' => ''
        ),		
		array(
            'name' => 'HomePage/Portfolio/Clients/Services Thumb',
            'desc' => 'Image URL. Image size: 257px * 57px<br>Enter the full URL of the image you would like to use in your HomePage Columns, Portfolio, Clients, Services posts.',
            'id' => $prefix . 'small_image_257x57',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Portfolio/Gallery Image, Video, Flash Preview',
            'desc' => 'Image, Video, Flash content URL. <br>Enter the full URL of the full image, video or flash content you would like to use in your Portfolio and Gallery posts.
					<br><br><strong>Examples:</strong>
					<br>Image: http://www.yoursite.com/images/preview.jpg
					<br>Video: Youtube  - http://www.youtube.com/watch?v=qqXi8WmQ_WM
					<br>Video: Vimeo - http://vimeo.com/8245346
					<br>Flash: http://www.adobe.com/products/flashplayer/include/marquee/design.swf?width=792&height=294
					<br>Quicktime: http://movies.apple.com/movies/wb/terminatorsalvation/terminatorsalvation-tlr3_h.480.mov?width=480&height=204',
            'id' => $prefix . 'big_image_500x500',
            'type' => 'text',
            'std' => ''
        )	
    )
);

add_action('admin_menu', 'mytheme_add_box');

// Add meta box
function mytheme_add_box() {
    global $meta_box;
    
    add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}


// Callback function to show fields in meta box
function mytheme_show_box() {
    global $meta_box, $post;
    
    // Use nonce for verification
    echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
    echo '<table class="form-table">';

    foreach ($meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        
        echo '<tr>',
                '<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong></label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '
', $field['desc'];
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '
', $field['desc'];
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                break;
        }
        echo     '<td>',
            '</tr>';
    }
    
    echo '</table>';
}

add_action('save_post', 'mytheme_save_data');

// Save data from meta box
function mytheme_save_data($post_id) {
    global $meta_box;
    
    // verify nonce
    if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    
    foreach ($meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}
?>