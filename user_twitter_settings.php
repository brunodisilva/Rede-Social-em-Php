<?php
$page = "user_twitter_settings";
include "header.php";
$smarty->assign('_active_tab', 'settings');

$task = !empty($_POST['task']) ? $_POST['task'] : null;
$error = false;


if($task == 'connect') {	
	$screen_name = !empty($_POST['screen_name']) ? $_POST['screen_name'] : null;
	$password = !empty($_POST['password']) ? $_POST['password'] : null;
	
	if(!empty($screen_name) && !empty($password)) {	
		$SEP_TwitterUser->set_account($screen_name, $password);
		if(!$SEP_TwitterUser->account_verify_credentials()) {
			$error = true;
		}
		else {
			// save information
			$SEP_TwitterUser->save_account($user->user_info['user_id'], $screen_name, $password);
		}
	}
	else {
		$error = true;	
	}
}
elseif($task == 'disconnect') {	
	$SEP_TwitterUser->delete_account($user->user_info['user_id']);
	header('Location: user_twitter_settings.php');
	exit;
}


// OAuth Support ...
if($setting['setting_twitter_authentication'] == 'oauth') {
	$epiAuth = new EpiOAuth($SEP_Twitter_Config['TWITTER_CONSUMER_KEY'], $SEP_Twitter_Config['TWITTER_CONSUMER_SECRET']);
	$epiAuth->setToken();
	$smarty->assign('twitter_auth_url', $epiAuth->getAuthorizationUrl());
}
    



$smarty->assign('error', $error);
$smarty->assign('screen_name', !empty($screen_name) ? $screen_name : null);
$smarty->assign('password', !empty($password) ? $password : null);

include "footer.php";
?>