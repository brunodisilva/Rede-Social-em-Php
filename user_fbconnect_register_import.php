<?php

/* $Id: user_fbconnect_register_import.php 1 2009-07-04 09:36:11Z SocialEngineAddOns $ */
$page = "user_fbconnect_register_import";
include "header.php";
if (!$_POST) {
	
	global $user, $var_fbconnect_import, $var_fbconnect_field_to_import, $var_site_name;
	$fbuid = fbconnect_get_fbuid();
	$fbuser_site  = fbconnect_is_siteuser($fbuid);
	if ($user->user_info['user_id'] && ($fbuser_site['uid'] == $user->user_info['user_id'])) { // An existing user that is already looged in by facebook connect is associating his account with Facebook
		header("Location: user_fbconnect_settings.php");
  } else if ($user->user_info['user_id'] && (!empty($fbuser_site['uid']))) { // An existing user is associating his account with Facebook
		header("Location: user_fbconnect_settings.php?error_message_facebook_connect=650000104");
	} elseif ($fbuid) {
		$fbname = fbconnect_get_fb_username($fbuid);
		$avatar = $output = fbconnect_output_user_fbavatar($fbuid, 'big_large');
	  if ($var_fbconnect_import) {
	  	$checkboxes_options = fbconnect_available_import_user_fields($fbuid);
	  }	
	}
	$isassociation = $user->user_info['user_id'] ? 1 : 0;

	$smarty->assign('var_site_name', $var_site_name);
	$smarty->assign('isassociation', $isassociation);
	$smarty->assign('fbname', $fbname);
	$smarty->assign('avatar', $avatar);
	$smarty->assign('checkboxes_options', $checkboxes_options);
	$smarty->assign('var_fbconnect_field_to_import', $var_fbconnect_field_to_import);
}
else {
	global $user;
	$post_values = $_POST;
	$isassociation = $post_values['isassociation'];
	$visibility = $post_values['visibility'];
	$fb_avatar = $post_values['fb_avatar'];
	unset($post_values['op']);
	unset($post_values['isassociation']);
	unset($post_values['visibility']);
	unset($post_values['fb_avatar']);
	
	if($isassociation) { // An existing user is associating his account with Facebook
		$fbuid = fbconnect_get_fbuid();
		if (fbconnect_is_siteuser($fbuid)) {
			header("Location: user_fbconnect_settings.php?error_message_facebook_conect=650000104");
		}
		else {
    	fbconnect_register($user->user_info['user_id'], $fbuid, $visibility, $fb_avatar, $user->user_info['user_photo']);
			$fbusersettings_import = fbconnect_set_user_fbinfo($user->user_info['user_id'], $post_values);
			// Todo : Show a SUCCESS message to the user over here
		  if($fbconnect_setting['reg_feed'] && !empty($visibility)) { // If registration feed has been enabled by the Admin and the user chooses to be visible to his FB friends
		   $session_object =& SESession::getInstance();
			 $session_object->set('fbconnect_feed', array(
        'type' => 'registration'
	      ));
		  }
		  if(!empty($visibility)) {
		  	// Redirect the user to the Faceebbok Friends Invite 
		  	header("Location: user_fbconnect_friends_invite.php");
		  }
		  else {
		  	//Redirect the user to the FBConnect tab of his profile
				header("Location: user_fbconnect_settings.php");
		  }
		}
	}
	else { // A new user is registering using FB Connect
		$_SESSION['fbconnect_visibility'] = $visibility;
		$_SESSION['fbconnect_fb_avatar'] = $fb_avatar;
		$_SESSION['fb_reg_import'] = $post_values;
		header("Location: fbconnect_register_create.php");
	}
}

include "footer.php";
?>