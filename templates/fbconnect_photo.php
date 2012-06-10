<?php
// THIS FILE OUTPUTS THE PATH TO THE USER'S PHOTO, FACEBOOK PHOTO OR THE GIVEN NOPHOTO IMAGE
// THIS FILE CALLED BY user_photo FUNCTION IN THE CLASS_USERS.PHP AT THE END OF THE FILE.

/* $Id: fbconnect_photo.php 1 2009-07-04 09:36:11Z SocialEngineAddOns $ */

global $url, $global_plugins, $fbconnect_setting;

if (!empty($global_plugins['fbconnect'])) {
  $fbuid = fbconnect_get_user_fbuid($this->user_info['user_id']);
  $facebook_avtar_setting =  fbconnect_get_user_avatar_setting($this->user_info['user_id']);
}

$user_photo = '';

if (!empty($fbconnect_setting['siteimage_setting'])) { // if admin is allowed to Show the Facebook Avatars (profile pictures) of users associated with Facebook Connect.
	if(!empty($fbuid['fbuid']) && !empty($facebook_avtar_setting)) { // Facebook Photo
	  $thumbnail_setting = $fbconnect_setting['thumbnail_setting'];
	  $bigimage_setting = $fbconnect_setting['bigimage_setting'];
	  if($thumb) {
			$user_photo = fbconnect_avatar_src($fbuid['fbuid'], 'thumbnail', $thumbnail_setting);
	  }
	  else {
	  	$user_photo = fbconnect_avatar_src($fbuid['fbuid'], 'big', $bigimage_setting);
	  }
	  if($user_photo == "") { $user_photo = $nophoto_image; }	
	    	
	}
}
if (empty($user_photo)) { // Social Engine Photo
  if( !$this->user_info['user_photo'] ) {
    $user_photo = $nophoto_image;
  } else {
	  $user_photo = $url->url_userdir($this->user_info[user_id]).$this->user_info[user_photo];
	  if($thumb) { 
	    $user_thumb = substr($user_photo, 0, strrpos($user_photo, "."))."_thumb".substr($user_photo, strrpos($user_photo, ".")); 
	    if(file_exists($user_thumb)) { $user_photo = $user_thumb; }
	  }
	  if(!file_exists($user_photo) || $this->user_info[user_photo] == "") { $user_photo = ""; }
	  if($user_photo == "") { $user_photo = $nophoto_image; }
  }
}
// END user_photo() METHOD
?>