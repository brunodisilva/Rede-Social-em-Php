<?php
/* $Id: user_fbconnect_friends.php 1 2009-07-04 09:36:11Z SocialEngineAddOns $ */

$page = "user_fbconnect_friends";
include "header.php";

// IF USER IS NOT LOOGED IN THEN REDIRECT HIM ON USER SETTINGS PAGE
global $fbuid;
if(empty($fbuid)) { 
  header("Location: user_fbconnect_settings.php");
  exit();
}

if(!empty($fbuid)) { // If the profile owner has connected his account with FB Connect
	// find facebook mutual freind
	$user_fbconnected_friends_final = fbconnect_get_fbconnected_friends_site($fbuid, $fbuid);
	$total_facebook_friends = count($user_fbconnected_friends_final);
	$smarty->assign('user_fbconnected_friends', $user_fbconnected_friends_final);
	$smarty->assign('total_facebook_friends', $total_facebook_friends);
}
include "footer.php";
?>