<?php
/* $Id: user_fbconnect_friends_invite.php 1 2009-07-04 09:36:11Z SocialEngineAddOns $ */
$page = "user_fbconnect_friends_invite";
include "header.php";

global $fbuid;
// IF USER IS NOT LOOGED IN THEN REDIRECT HIM ON USER SETTINGS PAGE
if(empty($fbuid)) { 
  header("Location: user_fbconnect_settings.php");
  exit();
}

global $var_site_name;

$friends_invite_form = fbconnect_output_friends_invite_form();

$smarty->assign('var_site_name', $var_site_name);
$smarty->assign('friends_invite_form', $friends_invite_form);

include "footer.php";

?>