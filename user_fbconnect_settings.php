<?php
/* $Id: user_fbconnect_settings.php 1 2009-07-04 09:36:11Z SocialEngineAddOns $ */
$page = "user_fbconnect_settings";
include "header.php";
global $user;

$fbuid_check_facebook_loggedin = fbconnect_get_fbuid();

if (!empty($_GET['msg'])) { $msg = $_GET['msg']; }
if (!$_POST) {
	global $var_fbconnect_import, $var_fbconnect_field_to_import;
	$fbuid = fbconnect_get_user_fbuid($user->user_info[user_id]);
	$fbconnect_user_status = 0;
	
	if(!empty($fbuid['fbuid']) && empty($fbuid_check_facebook_loggedin)) { //  CHECK THAT USER IS FBCOONECT USER OR NOT
		$fb_connect_button = fbconnect_render_login_button(2);
		$fbconnect_user_status = 3;
		$smarty->assign('fb_connect_button', $fb_connect_button);
		$smarty->assign('fbconnect_user_status', $fbconnect_user_status);
	} else if (empty($fbuid_check_facebook_loggedin) || empty($fbuid['fbuid'])) { //  CHECK THAT USER IS LOOGED IN AS A FACEBOOK CONNECT
	  $fb_connect_button = fbconnect_render_login_button(2);
		$fbconnect_user_status = 2;
		$smarty->assign('fb_connect_button', $fb_connect_button);
		$smarty->assign('fbconnect_user_status', $fbconnect_user_status);		
	} else {
		global $var_site_name;
		$fbconnect_user_status = 1;
		$fbuser = fbconnect_user_load($user->user_info[user_id]);
		$user_visibility = $fbuser['visibility'];
		$user_fb_avatar = $fbuser['avatar'];
		$fbname = fbconnect_get_fb_username($fbuid['fbuid']);
		$avatar = fbconnect_output_user_fbavatar($fbuid['fbuid'], 'big_large');
		
  	// The import fields are visible only if we have got an active Facebook session.
	  if (fbconnect_get_fbuid()) {
    	// Importation settings
    	$fields = array_filter($var_fbconnect_field_to_import, 'fbconnect_filter_importdata');
    	
    	// If the site administrator enabled profile import, these fields will be display.
    	if($var_fbconnect_import && $fields) {
    		
    		$default_value = fbconnect_user_import_setting($user->user_info[user_id]);
    		$checkboxes_options = fbconnect_available_import_user_fields($fbuid['fbuid']);
    		
    	}
	  	
	  }
		$smarty->assign('fbname', $fbname);
		$smarty->assign('avatar', $avatar);
		$smarty->assign('var_site_name', $var_site_name);
		$smarty->assign('user_visibility', $user_visibility);
		$smarty->assign('user_fb_avatar', $user_fb_avatar);
		$smarty->assign('checkboxes_options', $checkboxes_options);
		$smarty->assign('default_value', $default_value);
		$smarty->assign('fbconnect_user_status', $fbconnect_user_status);
	}
	
}
else {

	global $var_fbconnect_import;
  //$user = user_load(array('uid' => arg(1)));
  $fbuser = fbconnect_user_load($user->user_info[user_id]);
  // Visibility settings
  if ($_POST['visibility_hid'] != $_POST['visibility']) {
    fbconnect_update_user_visibility($user->user_info[user_id], $_POST['visibility']);
  }
  // The user should have an active Facebook session.
  if (!fbconnect_get_fbuid()) {
    return;
  }
  
//  if ($var_user_pictures) {
	if (!$_POST['fb_avatar_hid'] && $_POST['fb_avatar']) {
		$user_id_temp = $user->user_info[user_id];
  	$user_fb_avatar_update_query = "UPDATE fbconnect_users SET avatar = 1 WHERE uid = $user_id_temp";
  	$database->database_query($user_fb_avatar_update_query);
  }
	elseif($_POST['fb_avatar_hid'] && !isset($_POST['fb_avatar'])) {
		$user_id_temp = $user->user_info[user_id];
		$user_fb_avatar_update_query = "UPDATE fbconnect_users SET avatar = 0 WHERE uid = $user_id_temp";
		$database->database_query($user_fb_avatar_update_query);
		if (!empty($user->user_info['user_photo']) && ($user->user_info['user_photo'] == 'nphoto.gif'))
		$database->database_query("UPDATE se_users SET user_photo='' WHERE user_id='{$user_id_temp}' LIMIT 1");
	}
//  }
  
  if ($var_fbconnect_import) {
  	$post_values = $_POST;
  	unset($post_values['visibility_hid']);
  	unset($post_values['visibility']);
  	unset($post_values['fb_avatar_hid']);
  	unset($post_values['fb_avatar']);
  	unset($post_values['op']);
    if ($fields = array_filter((array)$post_values, 'fbconnect_filter_importdata')) {
      fbconnect_set_user_fbinfo($user->user_info[user_id], $fields);
    }
    else {
			global $database;
			$update_importsetting_query = sprintf("UPDATE fbconnect_users SET import_setting = '' WHERE uid = '%s'", $user->user_info[user_id]);
  		$database->database_query($update_importsetting_query);
    }
  }
	//Redirect the user to the FBConnect tab of his profile
  header("Location: user_fbconnect_settings.php?msg=650000103"); exit();
}
$smarty->assign('success_message', $msg);
include "footer.php";

?>