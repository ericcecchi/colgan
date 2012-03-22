/*
* Run ajax and check background process status
* this function will call itself untill background finish
*/
function pressbackup_chk_status(task) {

	/*
		data: { "action" : "x", "task_now" : "z", "status": "r", response: "s"}
		action > finish | wait
		status (backprocess) >  ok | fail | percent
		task_now > *
		response > *
	*/
		jQuery.post(ajaxurl, {action:"pressbackup_check_backupnow_status", 'task': task, 'cookie': encodeURIComponent(document.cookie)}, function(data)
		{
			info=jQuery.parseJSON(data);

			if (info.action == 'finish')
			{
				if(reload_url){
					setTimeout('pressbackup_reload_page( info.status,  info.response)', 250);
				}else{
					jQuery("#pressbackup_loading_img").hide('fast');
					jQuery("#pressbackup_loading_bar").hide('fast');
				}
			}
			else if (info.action == 'wait')
			{
				switch (info.status)
				{
					case 'percent':
						jQuery("#contact_info").hide('fast');
						jQuery("#pressbackup_loading_bar_status").html(info.task_now);
						jQuery("#progressbar").progressbar( "value" , ( parseInt( info.response.current ) * 100 ) / parseInt( info.response.total ) );
						jQuery("#pressbackup_loading_img").hide('fast');
						jQuery("#pressbackup_loading_bar").show('fast');
						setTimeout('pressbackup_chk_status("'+task+'")', 1000);
					break;
					case 'ok':
						jQuery("#contact_info").hide('fast');
						jQuery("#pressbackup_loading_img_status").html(info.task_now);
						jQuery("#pressbackup_loading_bar").hide('fast');
						jQuery("#pressbackup_loading_img").show('fast');
						setTimeout('pressbackup_chk_status("'+task+'")', 1000);
					break;
					case 'fail':
						process_fail++;
						if(process_fail==10){
							if(reload_url){
								setTimeout('pressbackup_reload_page( "fail")', 250);
							}else{
								jQuery("#pressbackup_loading_img").hide('fast');
								jQuery("#pressbackup_loading_bar").hide('fast');
								jQuery("#contact_info").show('fast');
							}
						}else{
							jQuery("#contact_info").hide('fast');
							jQuery("#pressbackup_loading_img_status").html(' ... ');
							jQuery("#pressbackup_loading_bar").hide('fast');
							jQuery("#pressbackup_loading_img").show('fast');
							setTimeout('pressbackup_chk_status("'+task+'")', 1000);
						}
					break;
				}

			}
		}
	);
}

/*
* Perform a redirect when a background process finish
* @status string: status of background process
* @data mixed: returned data by background process
*/
function pressbackup_reload_page(status, data) {

	jQuery("#pressbackup_loading_img").hide('fast');
	jQuery("#pressbackup_loading_bar").hide('fast');
	jQuery("#contact_info").show('fast');

	var args = "";
	if (data) { args = '&fargs[]='+data.file; }

	if(status == "fail"){
		document.location.href=reload_url_fail + args;
	}
	else if ( status == "ok" ) {
		document.location.href=reload_url + args;
	}
}

/*
* Number of times checked for background process start
* after 4 time of no notice about process start, it is considered dead
*/
var process_fail = 0;

/*
* Begin check background process status
*/
if (task =="backup_download") {
	setTimeout('pressbackup_chk_status("download")', 200);
}else{
	setTimeout('pressbackup_chk_status("save")', 200);
}




