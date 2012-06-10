<?php
$page = "user_twitter_people";
include "header.php";
$smarty->assign('_active_tab', 'people');

if(!$SEP_TwitterUser->account_verify_credentials()) {
	header('Location: user_twitter_settings.php');
	exit;	
}

include "footer.php";
?>