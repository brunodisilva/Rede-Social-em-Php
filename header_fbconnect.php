<?php

/* $Id: header_fbconnect.php 1 2009-07-04 09:36:11Z SocialEngineAddOns $ */

if(!defined('SE_PAGE')) { exit(); }

include "include/functions_fbconnect.php";

$fb_connect_button = '';
global $fbconnect_setting, $fbuser, $fbuid;
$fbconnect_setting = fbconnect_get_site_settings();

// Check if the user is logged in on FB
//  IN THE AJAX CALL DO NOT CALL FBCONNECT INTIIALIZE FUNCTION
if( !defined('SE_PAGE_AJAX') )
{
fbconnect_initialize_check();
}

$fbuid = fbconnect_get_fbuid();
if(!empty($fbuid)) {
	$smarty->assign('fbuid', $fbuid);
	if ($user->user_exists) {
	$fbuser_query = $database->database_query('SELECT * FROM fbconnect_users WHERE uid = ' . $user->user_info['user_id']);
  $fbuser = $database->database_fetch_assoc($fbuser_query);
	}
}

// FOR PROFILE PAGE ONLY
if($page == "profile") {
	// Show User's Facebook profile if it is cached with the site
	$fbprofile_view_status = 0;
	$fbuid1 = fbconnect_get_user_fbuid($owner->user_info[user_id]);
	if(!empty($fbuid1['fbuid']) && !empty($fbuid)) { // If the profile owner has connected his account with FB Connect
		$fbprofile_view_status = 1;
		$user_fb_profile = fbconnect_user_profile_view($owner->user_info['user_id']);
		
		// find facebook mutual freind
		$user_fbconnected_friends_final = fbconnect_get_fbconnected_friends_site($fbuid, $fbuid1['fbuid']);
		
		$total_facebook_friends = count($user_fbconnected_friends_final);
		
		$smarty->assign('fbprofile_view_status', $fbprofile_view_status);
		$smarty->assign('user_fb_profile', $user_fb_profile);
		$smarty->assign('user_fbconnected_friends', $user_fbconnected_friends_final);
		$smarty->assign('total_facebook_friends', $total_facebook_friends);
	} elseif(!empty($user->user_info[user_id]) && ($user->user_info[user_id] == $owner->user_info[user_id])) {
		if (!empty($fbuid1['fbuid'])) {
			$fbprofile_view_status = 3;
		} else {
			$fbprofile_view_status = 2;
		}

		// Put the FB Connect button
		$fb_connect_button = fbconnect_render_login_button(1);	
		$smarty->assign('fbprofile_view_status', $fbprofile_view_status);
		$smarty->assign('fb_connect_button', $fb_connect_button);
	}
	
	if($fbprofile_view_status == 0) {
		$smarty->assign('fbprofile_view_status', $fbprofile_view_status);
	}
}
// End  : FOR PROFILE PAGE ONLY

// INCLUDE THE JS FILES NECESSARY FOR FACEBOOK CONNECT
//  IN THE AJAX CALL DO NOT LOAD FACEBOOK JAVA SCRIPT
if( !defined('SE_PAGE_AJAX') )
{
$fbconnect_footer_js = fbconnect_output_js();
}

$smarty->assign('fbconnect_footer_js', $fbconnect_footer_js);

if(($user->user_info[user_id] != $owner->user_info[user_id] && empty($fbuser['visibility'])) ) {
	$plugin_vars['menu_profile_tab'] = '';
	$plugin_vars['menu_profile_side'] = "";
} else {
	$plugin_vars['menu_profile_tab'] = Array('file'=> 'profile_fbconnect.tpl', 'title' => 650000011);
	$plugin_vars['menu_profile_side'] = "";
}

// Main Header link
if( (!empty($fbuser['fbuid'])) )
{
  $plugin_vars['menu_main'] = Array('file' => 'user_fbconnect_friends_invite.php', 'title' => 650000051);
}

// My App link
if( (!empty($fbuser['fbuid'])) ) {
  $plugin_vars['menu_user'] = Array('file' => 'user_fbconnect_friends_invite.php', 'icon' => 'fbconnect_fbconnect16.gif', 'title' => 650001020);
} else {
	$plugin_vars['menu_user'] = Array('file' => 'user_fbconnect_settings.php', 'icon' => 'fbconnect_fbconnect16.gif', 'title' => 650001020);
}

// Link for various location
if( is_a($smarty, 'SESmarty') )
{
  if( !empty($plugin_vars['menu_main']) )
    $smarty->assign_hook('menu_main', $plugin_vars['menu_main']);
  
  if( !empty($plugin_vars['menu_user']) )
    $smarty->assign_hook('menu_user_apps', $plugin_vars['menu_user']);
}

//Facebook Javascript files for FBConnect. Note : These should be referenced in the BODY and not in the HEAD 
// register hook for 
SE_Hook::register("se_footer", "fbconnect_hook_footer", -9999);

// for login and signup button
// Put the FB Connect button
if (empty($fb_connect_button)) {
	$fb_connect_button = fbconnect_render_login_button();
	$smarty->assign('fb_connect_button', $fb_connect_button);
}

SE_Hook::register("se_user_delete", 'deleteuser_fbconnect');
SE_Hook::register("se_site_statistics", 'site_statistics_fbconnect');

// set some error messages on profile page for different actions.
if (!empty($_GET['error_message_facebook_connect'])) {
  $smarty->assign('error_message_facebook_connect', $_GET['error_message_facebook_connect']);
}

// For showing the facebook users on newest members, popular members, random members, user_photo field must be non empty.
//

if (!empty($fbuser['fbuid'])) {
	$user_photo_temp = fbconnect_avatar_src($fbuser['fbuid']);	
	if (!empty($user_photo_temp) && !empty($fbuser['avatar']) && empty($user->user_info['user_photo']) && !empty($fbconnect_setting['siteimage_setting'])) {
		  $database->database_query("UPDATE se_users SET user_photo='nphoto.gif' WHERE user_id='{$user->user_info['user_id']}' LIMIT 1");
	} else if (empty($user_photo_temp) && !empty($user->user_info['user_photo']) && ($user->user_info['user_photo'] == 'nphoto.gif')) {
		  $database->database_query("UPDATE se_users SET user_photo='' WHERE user_id='{$user->user_info['user_id']}' LIMIT 1");
	} 
}

//
if($page == "user_editprofile_photo") {
	// After edited a photo by user, it still show facebook photo. if user has checkedin fbconnect settings page use my Facebook picture on site. 
	// Show message :-  "Note: You have activated your Facebook Photo. Thus, the photo that you upload here will not show on your profile. To show the photo that you upload, go to the FBConnect Settings page and deactivate your Facebook photo."
	if (!empty($fbuser['avatar']) && !empty($fbconnect_setting['siteimage_setting'])) {
		$smarty->assign('fbconnect_alert_editphoto_msg', 650001024);
	}

}

// send facebook connect setting to smarty templates
$smarty->assign('fbconnect_setting', $fbconnect_setting);
?>