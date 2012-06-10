<?php

/* $Id: admin_fbconnect.php 1 2009-07-04 09:36:11Z SocialEngineAddOns $ */
//  THIS FILE CONTAINS THE ADMIN FUNCTIONS FOR THE FACEBOOK CONNECT PLUGIN FROM SOCIALENGINEADDONS

$page = "admin_fbconnect";
include "admin_header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }
$is_error = 0;
$result = 0;
$error_message = '';
$warning_message = '';
$success_message = '';
$invite_message = '';





if($task == "edit") {
	$key_lsetting = $_POST['lsettings'];
	$return_lsettings = fbconnect_lsettings($key_lsetting, 'fbconnect');
	if (!empty($return_lsettings)) {
	  $is_error = true;
	  $smarty->assign('error_message_lsetting', $return_lsettings);
	}
	else if (!facebook_client(1)) {
  	$error_message = 650000019;
  }
  else {
   	$properties = array('callback_url', 'dev_mode');
	  try {
	    $check_app_properties = facebook_client(1)->api_client->admin_getAppProperties((array)$properties);
	  } catch (Exception $e) {
	  	//$warning_message = 'Exception thrown while calling admin_getAppProperties.' . $e->getMessage();
	  	$exception_code = $e->getCode();
	  	switch ($exception_code) {
	  		case 1 :
	  			$error_message = 650000020;
	  		break;
	  		case 2 :
	  			$error_message = 650000021;
	  		break;
	  		case 101 :
	  			$error_message = 650000022;
	  		break;
	  		case 104 :
	  			$error_message = 650000023;
	  		break;
	  	}
	  } 
	  if (!empty($check_app_properties)) {
	    if (!$check_app_properties['callback_url']) {
	  		$warning_message = 650000024;
	    }
	    if ($check_app_properties['dev_mode'] == 1) {
	  		$warning_message = 650000025;
	    }
	  }  	
  }
  if(!empty($error_message)) {
		  $is_error = true;
		  $smarty->assign('error_message', $error_message);
  }
  
  // GET POST VARIABLES
  $apikey = $_POST['api_key'];
  $secret = $_POST['secret'];
  $facebook_button = $_POST['facebook_button'];
  $site_name = $_POST['site_name'];
  $redirect_url = $_POST['redirect_url'];
  $invite_message = $_POST['invite_message'];
  $reg_feed = $_POST['reg_feed'];  
  $reg_feed_bundleid = $_POST['reg_feed_bundleid'];
  
  $signup_setting = $_POST['signup_setting'];
  $siteimage_setting = $_POST['siteimage_setting'];
  $bigimage_setting = $_POST['bigimage_setting'];
  $thumbnail_setting = $_POST['thumbnail_setting'];
  
  $status_feed = $_POST['status_feed'];
  $status_feed_bundleid = $_POST['status_feed_bundleid'];
  
  $editphoto_feed = $_POST['editphoto_feed'];
  $editphoto_feed_bundleid = $_POST['editphoto_feed_bundleid'];
  
  $postblog_feed = $_POST['postblog_feed'];
  $postblog_feed_bundleid = $_POST['postblog_feed_bundleid'];
  
  $newgroup_feed = $_POST['newgroup_feed'];
  $newgroup_feed_bundleid = $_POST['newgroup_feed_bundleid'];
  
  $newevent_feed = $_POST['newevent_feed'];
  $newevent_feed_bundleid = $_POST['newevent_feed_bundleid'];
  
  $newpoll_feed = $_POST['newpoll_feed'];
  $newpoll_feed_bundleid = $_POST['newpoll_feed_bundleid'];
  
  $newalbum_feed = $_POST['newalbum_feed'];
  $newalbum_feed_bundleid = $_POST['newalbum_feed_bundleid'];
  
  $postclassified_feed = $_POST['postclassified_feed'];
  $postclassified_feed_bundleid = $_POST['postclassified_feed_bundleid'];
  
  $postmusic_feed = $_POST['postmusic_feed'];
  $postmusic_feed_bundleid = $_POST['postmusic_feed_bundleid'];
  
  $postvideo_feed = $_POST['postvideo_feed'];
  $postvideo_feed_bundleid = $_POST['postvideo_feed_bundleid'];
  
  $postyoutube_feed = $_POST['postyoutube_feed'];
  $postyoutube_feed_bundleid = $_POST['postyoutube_feed_bundleid'];
  
  if(empty($facebook_button)) {
	  $is_error = true;
	  $smarty->assign('error_message_button', 650000026);
  }
  if(empty($site_name)) {
	  $is_error = true;
	  $smarty->assign('error_message_site_name', 650000027);
  }
  if(empty($redirect_url)) {
	  $is_error = true;
	  $smarty->assign('error_message_redirect_url', 650000028);
  }
  if(empty($invite_message)) {
	  $is_error = true;
	  $smarty->assign('error_message_invite_message', 650000029);
  }
  
  if(!empty($redirect_url)) {
	  $substring = substr($redirect_url, 0, 7);
		if ($substring == 'http://') {
			$redirect_url = $redirect_url;
		} else {
			$redirect_url = 'http://' . $redirect_url;
		}
  } 
  
  // if error message is false then update the facebook connect settings
  if (empty($is_error)) {
  	
	  $database->database_query("UPDATE fbconnect_settings SET api_key='$apikey', secret='$secret', license_key='$key_lsetting', facebook_button='$facebook_button', site_name ='$site_name', redirect_url='$redirect_url', invite_message='$invite_message', signup_setting='$signup_setting', siteimage_setting='$siteimage_setting', bigimage_setting='$bigimage_setting', thumbnail_setting='$thumbnail_setting', reg_feed='$reg_feed', reg_feed_bundleid='$reg_feed_bundleid', status_feed='$status_feed', status_feed_bundleid='$status_feed_bundleid',  editphoto_feed='$editphoto_feed', editphoto_feed_bundleid='$editphoto_feed_bundleid', postblog_feed='$postblog_feed', postblog_feed_bundleid='$postblog_feed_bundleid', newgroup_feed='$newgroup_feed', newgroup_feed_bundleid='$newgroup_feed_bundleid', newevent_feed='$newevent_feed' , newevent_feed_bundleid='$newevent_feed_bundleid', newpoll_feed='$newpoll_feed', newpoll_feed_bundleid='$newpoll_feed_bundleid',  newalbum_feed='$newalbum_feed', newalbum_feed_bundleid='$newalbum_feed_bundleid', postclassified_feed='$postclassified_feed', postclassified_feed_bundleid='$postclassified_feed_bundleid', postmusic_feed='$postmusic_feed', postmusic_feed_bundleid='$postmusic_feed_bundleid', postvideo_feed='$postvideo_feed', postvideo_feed_bundleid='$postvideo_feed_bundleid', postyoutube_feed='$postyoutube_feed', postyoutube_feed_bundleid='$postyoutube_feed_bundleid'");
	  
	  if (empty($siteimage_setting)) { 
	  	// if admin select a option that Show the profile pictures uploaded on the site for the users, and not their Facebook Avatars. then all the facebook connect user avatar settings will be zero  and if user have user_photo='nphoto.gif' then it will update by user_photo=''
	  	$database->database_query("UPDATE `se_users` SET  user_photo='' WHERE user_photo='nphoto.gif'" );
	  } else {
    	$sql = "SELECT fbuid, avatar, user_id FROM fbconnect_users INNER JOIN se_users ON se_users.user_id = fbconnect_users.uid WHERE se_users.user_photo=''";
	    $resource = $database->database_query($sql);		    
	    $friends = array();
	    while( $user_info = $database->database_fetch_assoc($resource) )
	    { 
	      $user_photo_temp = '';
		  	$user_photo_temp = fbconnect_avatar_src($user_info['fbuid']);	
		    if (!empty($user_photo_temp) && !empty($user_info['avatar'])) {
			    $database->database_query("UPDATE se_users SET user_photo='nphoto.gif' WHERE user_id='{$user_info['user_id']}' LIMIT 1");
		    }	  	
	    }
	  }
	  global $api_key, $api_secret;
    $api_key = $apikey;
    $api_secret = $secret;

	  $smarty->assign('success_message', 650000030);
  } else {
  	// AN ERROR OCCURED SEND THE DATA BACK
    $result = array(
    'api_key'          => $apikey,
    'secret'           => $secret,
    'lsettings'           => $key_lsetting,
    'facebook_button'  => $facebook_button,
    'redirect_url'     => $redirect_url,
    'site_name'        => $site_name,
    'invite_message'   => $invite_message,
    'signup_setting'   => $signup_setting,
    'siteimage_setting'   => $siteimage_setting,
    'bigimage_setting'   => $bigimage_setting,
    'thumbnail_setting'   => $thumbnail_setting,
    'reg_feed'         => $reg_feed,
    'reg_feed_bundleid' => $reg_feed_bundleid,
    'editphoto_feed'    => $editphoto_feed,
    'editphoto_feed_bundleid' => $editphoto_feed_bundleid,
    'status_feed'     => $status_feed,
    'status_feed_bundleid'  => $status_feed_bundleid,    
    'postblog_feed'    => $postblog_feed_feed,
    'postblog_feed_bundleid' => $postblog_feed_bundleid,
    'newgroup_feed'    => $newgroup_feed,
    'newgroup_feed_bundleid' => $newgroup_feed_bundleid,
    'newevent_feed'    => $newevent_feed,
    'newevent_feed_bundleid' => $newevent_feed_bundleid,
    'newpoll_feed'    => $newpoll_feed,
    'newpoll_feed_bundleid' => $newpoll_feed_bundleid,
    'newalbum_feed'    => $newalbum_feed,
    'newalbum_feed_bundleid' => $newalbum_feed_bundleid,
    'postclassified_feed'    => $postclassified_feed,
    'postclassified_feed_bundleid' => $postclassified_feed_bundleid,
    'postmusic_feed'    => $postmusic_feed,
    'postmusic_feed_bundleid' => $postmusic_feed_bundleid,
    'postvideo_feed'    => $postvideo_feed,
    'postvideo_feed_bundleid' => $postvideo_feed_bundleid,
    'postyoutube_feed'    => $postyoutube_feed,
    'postyoutube_feed_bundleid' => $postyoutube_feed_bundleid,
    );
  }
}

// GET USER LEVEL ARRAY
if (empty($is_error)) {
	$row = $database->database_query("SELECT * FROM fbconnect_settings");
	$result = $database->database_fetch_assoc($row);
} 
$smarty->assign('is_error', $is_error);
$smarty->assign('result', $result);
include "admin_footer.php";
?>