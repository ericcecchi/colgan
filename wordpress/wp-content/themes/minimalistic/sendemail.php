<?php
		require("../../../wp-load.php"); //required to include worpress file
		GLOBAL $shortname;
		$mailTo = get_option($shortname.'_contact_admin_email');
		$name = $_POST['name'];
		$mailFrom = $_POST['email'];
		$subject = $_POST['subject'];
		$message = $_POST['message'];
		
		$headers = "From: $mailFrom";
		$subject = $subject." - A message from ".get_option('siteurl');
		$body = "Name: $name\n\n"
			. "Email: $mailFrom\n\n"
			. "Subject: $subject\n\n"
			. "Message: \n$message"
			;
		$Send_Email = (mail($mailTo, $subject, $body, $headers));
?>