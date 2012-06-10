<?php
$page = "user_twitter_timeline_user";
include "header.php";
$smarty->assign('_active_tab', 'timeline_user');


if(!$SEP_TwitterUser->account_verify_credentials()) {
	header('Location: user_twitter_settings.php');
	exit;	
}


include "footer.php";
?>