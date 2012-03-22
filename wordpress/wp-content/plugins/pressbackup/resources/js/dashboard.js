jQuery(".pb_loading_img").click(function(){ 
	jQuery('#contact_info').hide("fast");
	jQuery('#pressbackup_loading_img').show("fast");
});

jQuery(".pb_delete").click(function(){
	if (confirm("Do you really want to delete this backup?")){
		jQuery('#contact_info').hide("fast"); 
		jQuery('#pressbackup_loading_img_status').html("Deleting backup");
		jQuery('#pressbackup_loading_img').show("fast");
		return true;
	}
	return false;
});

jQuery(".pb_restore").click(function(){
	if (confirm("Do you really want to apply this backup?")){
		jQuery('#contact_info').hide("fast");
		jQuery('#pressbackup_loading_img_status').html("Restoring from  backup");
		jQuery('#pressbackup_loading_img').show("fast");
		return true;
	}
	return false;
});
