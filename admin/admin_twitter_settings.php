<?
$page = "admin_twitter_settings";
include "admin_header.php";



if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(!empty($_GET['task'])) { $task = $_GET['task'];} else { $task = "main"; }

// SET RESULT AND ERROR VARS
$result = 0;
$is_error = 0;
$error_message = "";


// SAVE CHANGES
if($task == "dosave") {
	// SAVE SETTINGS IF NO ERROR

	$_setting = !empty($_POST['setting']) ? $_POST['setting'] : null;

	$_setting['setting_twitter_twitter2status'] = empty($_setting['setting_twitter_twitter2status']) ? '0' : '1';
	$_setting['setting_twitter_status2twitter'] = empty($_setting['setting_twitter_status2twitter']) ? '0' : '1';
	$_setting['setting_twitter_show_in_main_menu'] = empty($_setting['setting_twitter_show_in_main_menu']) ? '0' : '1';	

	// check if oauth consumer key/ secret key changed
	if(
		(!empty($_setting['setting_twitter_oauth_consumer_key']) && $_setting['setting_twitter_oauth_consumer_key'] != $setting['setting_twitter_oauth_consumer_key']) ||
		(!empty($_setting['setting_twitter_oauth_consumer_secret']) && $_setting['setting_twitter_oauth_consumer_secret'] != $setting['setting_twitter_oauth_consumer_secret'])
	  )		
	 {
		// clear saved auth tokens
		$database->database_query("UPDATE `se_twitter_accounts` SET `oauth_token`='', `oauth_token_secret`='' WHERE 1");
	}
	
	$query = array();
	foreach($_setting as $key => $value) {
		$query[] = "`".mysql_real_escape_string($key)."`='".mysql_real_escape_string($value)."'";
	}
	if(count($query) > 0) {
		$database->database_query("UPDATE `se_settings` SET ".implode(',', $query)." WHERE setting_id='1' LIMIT 1");	
	}
	
	$setting = array_merge($setting, $_setting); // make changes visible
	$result = 1;
	
}
elseif($task == 'truncate_rate_limiting') {
	SEP_Twitter::truncate_rate_limiting();	
}
elseif($task == 'clear_cache') {
	SEP_Twitter::clear_cache_all();	
}


// ASSIGN VARIABLES
$smarty->assign('result', $result);
$smarty->assign('error_message', $error_message);
$smarty->assign('setting', $setting);
$smarty->assign('rate_limit_index', SEP_Twitter::get_rate_limiting_trend());


include "admin_footer.php";
?>