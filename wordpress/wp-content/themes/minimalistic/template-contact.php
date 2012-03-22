<?php
/*
Template Name: Contact Template
*/
get_header();
?>
<script type="text/javascript">
$(document).ready(function(){
	$("#submit").click(function(){					   				   
		$(".error").hide();
		var hasError = false;
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		var msg;
		msg = '';
		var msg_success;
		msg_success = '';
		
		var nameToVal = $("#name").val();
		if(nameToVal == '') {
			hasError = true;
			msg = msg+'<li>Name is a required field.</li>';
		}
		
		var emailFromVal = $("#email").val();
		if(emailFromVal == '') {
			hasError = true;
			msg = msg+'<li>Email Address is a required field.</li>';
		} else if(!emailReg.test(emailFromVal)) {	
			hasError = true;
			msg = msg+'<li>Invalid Email Address</li>';
		}
		
		var subjectVal = $("#subject").val();
		if(subjectVal == '') {
			hasError = true;
			msg = msg+'<li>Subject is a required field.</li>';
		}
		
		var messageVal = $("#message").val();
		if(messageVal == '') {
			hasError = true;
			msg = msg+'<li>Message is a required field.</li>';
		}
		
		if(hasError == true) {
			document.getElementById('msg_error').innerHTML = '<div class="errorMsg"><h3>Message Field!</h3><ul>' + msg + '</ul></div>';	
		}
		
		if(hasError == false) {
			$(this).hide();
			$("#contact-form li.buttons").append('Sending');
			
			$.post("<?php bloginfo('template_url'); ?>/sendemail.php",
   				{ name: nameToVal, email: emailFromVal, subject: subjectVal, message: messageVal },
   					function(data){
						$("#contact-form").slideUp("normal", function() {				   
							msg_success = '<div class="infoMsg"><h3>Message Sent!</h3><p>Thank you for contacting us. We will get back to you shortly!</p></div>';
							document.getElementById('msg_error').innerHTML = '';	
							document.getElementById('msg_success').innerHTML = msg_success;
						});
   					}
				 );
		}		
		return false;	
	});						   
});
</script>
<div id="container">
    <!--BEGIN: main_content -->
    <div id="main_content">
    	<div id="content">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Contact Template Form") ) : ?>				
				<!-- Contact Template replace form with other WIDGET-->
				<h2>Contact</h2>
				<? 
					if(have_posts()) : while(have_posts()) : the_post();
					the_content('');
					endwhile; 
					endif;
				?>
				
				<?php echo $msg;?>	
				<div id="msg_error"></div>
				<div id="msg_success"></div>
				  <form action="/" method="post" id="contact-form">
				<p>
				  <label for="name">Name:</label><input type="text" name="name" id="name" value="<?php echo $name;?>" />
				</p>
				<p>
				  <label for="email">Email:</label><input type="text" name="email" id="email" value="<?= $_POST['emailFrom']; ?>"  />
				</p>
				<p>
				  <label for="subject">Subject:</label><input type="text" name="subject" id="subject" value="<?= $_POST['subject']; ?>"  />
				</p>
				<p>
				  <label for="message">Message:</label><textarea name="message" id="message" rows="5" cols="20"><?= $_POST['message']; ?></textarea>
				</p>
				<p>		
				  <label>&nbsp;</label>
				  <button class="send_btn" type="submit" id="submit">Submit Form</button><input type="hidden" name="submitted" id="submitted" value="true"/>
				</p>
			    </form>
			<?php endif; ?>			
       </div>
        
        <!--BEGIN: sidebar -->
        <div id="sidebar">
				<h3>Contact Info</h3>
				<div class="block">
				<?
					$contact_info_image = get_option($shortname.'_contact_info_image');		
					if ($contact_info_image) {
						echo '<img src="'.$contact_info_image.'" alt="company info" />';
					}
					
					$contact_address_info = get_option($shortname.'_contact_address_info');		
					if ($contact_address_info) {
						echo '<address>'.$contact_address_info.'</address>';
					}
					
				?>
				
				
				<p><span>
				<?
					$contact_telephone_number = get_option($shortname.'_contact_telephone_number');		
					if ($contact_telephone_number) {
						echo 'Tel: '.$contact_telephone_number.'<br />';
					}
					$contact_fax_number = get_option($shortname.'_contact_fax_number');		
					if ($contact_fax_number) {
						echo 'Fax: '.$contact_fax_number.'<br />';
					}		
					$contact_email_info = get_option($shortname.'_contact_email_info');		
					if ($contact_email_info) {
						echo '<a href="mailto:'.$contact_email_info.'">'.$contact_email_info.'</a>';
					}					
				?>
					 </span>
				 </p>
				 </div>
				<?
					$contact_location_info = get_option($shortname.'_contact_location_info');		
					if ($contact_location_info) {
				?>
				 
				 <h3 class="margin_bottom">Our Locations</h3>
				 <div class="block">
				 <dl>
					   <?= $contact_location_info; ?>
					  </dl>
				</div>
				<?
				}
				
				/* Get Follow Us Options */
				$show_facebook_account = get_option($shortname.'_show_facebook_account');
				$facebook_account = get_option($shortname.'_facebook_account');

				$show_twitter_account = get_option($shortname.'_show_twitter_page');
				$twitter_account = get_option($shortname.'_twitter_page');

				$show_linkedin_account = get_option($shortname.'_show_linkedin_account');
				$linkedin_account = get_option($shortname.'_linkedin_account');

				$show_delicious_account = get_option($shortname.'_show_delicious_account');
				$delicious_account = get_option($shortname.'_delicious_account');

				$show_stumbleupon_account = get_option($shortname.'_show_stumbleupon_account');
				$stumbleupon_account = get_option($shortname.'_stumbleupon_account');

				$show_vimeo_account = get_option($shortname.'_show_vimeo_account');
				$vimeo_account = get_option($shortname.'_vimeo_account');

				$show_deviantart_account = get_option($shortname.'_show_deviantart_account');
				$deviantart_account = get_option($shortname.'_deviantart_account');

				if ( ($show_facebook_account == 'Yes') or ($show_twitter_account == 'Yes') or ($show_linkedin_account == 'Yes') or 
					($show_delicious_account == 'Yes') or ($show_stumbleupon_account == 'Yes') or ($show_vimeo_account == 'Yes') or ($show_deviantart_account == 'Yes') ) {
					$show_follow_us_section = True;
				} else {
					$show_follow_us_section = False;
				}
				/* END: Get Follow Us Options */		
				
				if ($show_follow_us_section == True) {
				?>
					<h3>You can find us here?</h3>
					<div class="block">
						<ul class="social_network">
						<?
							// Get social network urls and images
							if ($show_facebook_account == 'Yes') { 
						?>					
							<li><a href="<?= $facebook_account; ?>"><img src="<? bloginfo('template_url'); ?>/<? echo $images_path;?>/facebook_32.png" alt="Facebook" /></a></li>
						<?}
							if ($show_twitter_account == 'Yes') { 
						?>
							<li><a href="<?= $twitter_account; ?>"><img src="<? bloginfo('template_url'); ?>/<? echo $images_path;?>/twitter_32.png" alt="Twitter" /></a></li>
						<?}
							if ($show_linkedin_account == 'Yes') { 
						?>					
							<li><a href="<?= $linkedin_account; ?>"><img src="<? bloginfo('template_url'); ?>/<? echo $images_path;?>/linkedin_32.png" alt="LinkedIn" /></a></li>
						<?}	
							if ($show_delicious_account == 'Yes') { 
						?>	
							<li><a href="<?= $delicious_account; ?>"><img src="<? bloginfo('template_url'); ?>/<? echo $images_path;?>/delicious_32.png" alt="Delicious" /></a></li>
						<?}	
							if ($show_stumbleupon_account == 'Yes') { 
						?>	
						<li><a href="<?= $stumbleupon_account; ?>"><img src="<? bloginfo('template_url'); ?>/<? echo $images_path;?>/stumbleupon_32.png" alt="StumbleUpon" /></a></li>
						<?}	
							if ($show_vimeo_account == 'Yes') { 
						?>	
							<li><a href="<?= $vimeo_account; ?>"><img src="<? bloginfo('template_url'); ?>/<? echo $images_path;?>/vimeo_32.png" alt="Vimeo" /></a></li>
						<?}						
							if ($show_deviantart_account == 'Yes') {
						?>
						<li><a href="<?= $deviantart_account; ?>"><img src="<? bloginfo('template_url'); ?>/<? echo $images_path;?>/deviantart_32.png" alt="DeviantArt" /></a></li>
						<?}					
						?>
						</ul>
					</div>
				<? } // END: follow us section ?>       
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("'Contact Template Sidebar") ) : ?>				
				<!-- Contact Template right sidebar WIDGET-->				
			<?php endif; ?>			
        </div>
        <!--END: sidebar -->
        
    </div>
    <!--END: main_content -->
    
<?php get_footer(); ?>