<?php

/* $Id: functions_fbconnect.php 1 2009-07-04 09:36:11Z SocialEngineAddOns $ */

//  THIS FILE CONTAINS FACEBOOK CONNECT RELATED FUNCTIONS
//  FUNCTIONS IN THIS FILE:
//	fbconnect_get_site_settings()
//	fbconnect_output_js()
//	fbconnect_get_appconfig()
//	fbconnect_initialize_check()
//	fbconnect_get_fbuid()
//	facebook_client()
//	fbconnect_is_siteuser()
//	fbconnect_get_user_fbuid()
//	fbconnect_filter_importdata()		
//	fbconnect_user_import_setting()
//	fbconnect_get_userinfo_fb()
//	fbconnect_user_load()
//	fbconnect_user_delete()
//	fbconnect_get_user_avatar_setting()
//	fbconnect_output_user_fbavatar()
//	fbconnect_avatar_src()
//	fbconnect_available_import_user_fields()
//	fbconnect_get_fb_username()
//	fbconnect_render_login_button()
//	fbconnect_render_connect_button()
//	fbconnect_render_button_association()
//	remove_special_chars()
//	text_validate_utf8()
//	generate_random_user_password()
//	fbconnect_register()
//	fbconnect_update_user_visibility()
//	fbconnect_set_user_fbinfo()
//	fbconnect_suggest_username()
//	fbconnect_output_friends_invite_form()
//	fbconnect_user_profile_view()
//	fbconnect_get_fbconnected_friends_site()
//	fbconnect_get_app_settings()
//	fbconnect_admin_settings_validate()
//	fbconnect_register_feed_forms()
//	fbconnect_get_first_name()
//	fbconnect_get_last_name()
//  fbconnect_get_config_admin()	
//  fbconnect_get_config_admin()	
//  deleteuser_fbconnect()	
//  site_statistics_fbconnect()

// Define the standard variables for this FBConnect implementation
define('FBCONNECT_RENEW_CACHE', 12);
define('FBCONNECT_REGISTRATION_FEED_BUNDLEID', 138149323920); 
define('FBCONNECT_POSTBLOG_FEED_BUNDLEID', 140375186520); 
define('FBCONNECT_NEWGROUP_FEED_BUNDLEID', 140376046520); 
define('FBCONNECT_NEWEVENT_FEED_BUNDLEID', 140379316520); 
define('FBCONNECT_NEWPOLL_FEED_BUNDLEID', 140412431520); 
define('FBCONNECT_NEWALBUM_FEED_BUNDLEID', 140412751520); 
define('FBCONNECT_POSTCLASSIFIED_FEED_BUNDLEID', 140413161520); 
define('FBCONNECT_MUSIC_FEED_BUNDLEID', 155085776520); 
define('FBCONNECT_VIDEO_FEED_BUNDLEID', 155086121520); 
define('FBCONNECT_YOUTUBE_FEED_BUNDLEID', 155086311520); 

/**
 * This function gets all the site settings used by the plugin.
 */
function fbconnect_get_site_settings() {
	global $api_key, $api_secret, $var_fbconnect_button_type, $var_site_name, $var_fbconnect_invitef_redirect, $var_fbconnect_invitef_content, $var_fbconnect_import, $var_fbconnect_field_to_import, $var_facebook_user_fields;
	
	global $database;
  $data_query = $database->database_query('SELECT * FROM fbconnect_settings');
  $data = $database->database_fetch_assoc($data_query);
  
  $api_key = $data['api_key'];
  $api_secret = $data['secret'];
  $var_fbconnect_button_type = $data['facebook_button'];
  $var_site_name = $data['site_name'];
  $var_fbconnect_invitef_redirect = $data['redirect_url'];
  $var_fbconnect_invitef_content = $data['invite_message'];
  $var_fbconnect_import = 1;
  $var_fbconnect_field_to_import = array("name"=>"name", "affiliations"=>"affiliations", "religion"=>"religion", "birthday"=>"birthday", "sex"=>"sex", "about_me"=>"about_me", "hometown_location"=>"hometown_location", "current_location"=>"current_location", "meeting_sex"=>"meeting_sex", "meeting_for"=>"meeting_for", "relationship_status"=>"relationship_status", "political"=>"political", "activities"=>"activities", "interests"=>"interests", "music"=>"music", "tv"=>"tv", "movies"=>"movies", "books"=>"books", "quotes"=>"quotes");
  $var_facebook_user_fields = array("name"=>"Facebook name", "affiliations"=>"Affiliations", "religion"=>"Religious Views", "birthday"=>"Birthday", "sex"=>"Sex", "about_me"=>"About me", "hometown_location"=>"Hometown location", "current_location"=>"Current location", "meeting_sex"=>"Meeting sex", "meeting_for"=>"Meeting for", "relationship_status"=>"Relationship status", "political"=>"Political", "activities"=>"Activities", "interests"=>"Interests", "music"=>"Favourite Music", "tv"=>"Favourite TV", "movies"=>"Favourite Films", "books"=>"Favourite Books", "quotes"=>"Favourite Quotations");
  
  return $data;
}


/**
 * This function manages and renders all the javascripts used by this plugin.
 * 
 * @return string
 * Javascript code to render
 */
function fbconnect_output_js() {
	global $user, $url, $var_site_name, $fbconnect_setting, $database;
  $js_to_render = '';
  $js_to_render .= '<script type="text/javascript" src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"></script>
										<script type="text/javascript" src="./include/js/fbconnect.js"></script>';
	
  $conf = fbconnect_get_appconfig();
  if (!$conf) {
    return;
  }
  
  $js_to_render .= '<script type="text/javascript">
       								FB.init("' . $conf['api_key'] . '", "' .  $url->url_base . 'xd_receiver.htm");
     								</script>';
	
  $fbconnect_feed = '';
	$session_object =& SESession::getInstance();
	$fbconnect_feed    = $session_object->get('fbconnect_feed');
	
	if (empty($fbconnect_feed['type'])) {
		$duration = 20*60;
		$time = time();
		$sql = "SELECT * FROM `fbconnect_actions` WHERE `user_id` = " . $user->user_info['user_id'] . " AND `action_name` = 'status' AND $time - created <= $duration LIMIT 0, 1";
	  $res = $database->database_query($sql);
    if($database->database_num_rows($res)) {
    	$result  = $database->database_fetch_assoc($res);
    	$fbconnect_feed['type'] = 'status';
    	$fbconnect_feed['status_message'] =  $result['message1'];
      $database->database_query("DELETE FROM fbconnect_actions WHERE user_id = " . $user->user_info['user_id'] . " AND action_name = 'status'");
    }
	}
	
  // Check if there are any feeds to be published to Facebook
  if (!empty($fbconnect_feed['type'])) {
  	$feed = $fbconnect_feed;    
    switch ($feed['type']) {
      case 'editphoto' :
				$bundle_id = $fbconnect_setting['editphoto_feed_bundleid'];
	      $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '", "username" : "' . $user->user_info['user_username'] . '"};'; 
	      $feed_js  .= 'var body = null;';
      break;
     
     
      case 'status' :
		    $bundle_id = $fbconnect_setting['status_feed_bundleid'];
        $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '", "status" : "' . $feed['status_message'] . '"};'; 
        $feed_js  .= 'var body = null;';
      break;
     
      case 'registration' :
				$bundle_id = $fbconnect_setting['reg_feed_bundleid'];
        $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '"};'; 
        $feed_js  .= 'var body = null;';
      break;
      
      case 'postblog' :
				$bundle_id = $fbconnect_setting['postblog_feed_bundleid'];
        $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '", "id" : "' . $feed['id'] . '", "title" : "' . $feed['title'] . '", "username" : "' .  $user->user_info['user_username'] . '"};'; 
        $feed_js  .= 'var body = null;';
      break;
      
      case 'newgroup' :
				$bundle_id = $fbconnect_setting['newgroup_feed_bundleid'];
        $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '", "id" : "' . $feed['id'] . '", "title" : "' . $feed['title'] . '", "username" : "' .  $user->user_info['user_username'] . '"};'; 
        $feed_js  .= 'var body = null;';
      break;
      
      case 'newevent' :
				$bundle_id = $fbconnect_setting['newevent_feed_bundleid'];
        $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '", "id" : "' . $feed['id'] . '", "title" : "' . $feed['title'] . '", "username" : "' .  $user->user_info['user_username'] . '"};'; 
        $feed_js  .= 'var body = null;';
      break;
      
      case 'newpoll' :
				$bundle_id = $fbconnect_setting['newpoll_feed_bundleid'];
        $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '", "id" : "' . $feed['id'] . '", "title" : "' . $feed['title'] . '", "username" : "' .  $user->user_info['user_username'] . '"};'; 
        $feed_js  .= 'var body = null;';
      break;
      
     case 'newalbum' :
				$bundle_id = $fbconnect_setting['newalbum_feed_bundleid'];
        $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '", "id" : "' . $feed['id'] . '", "title" : "' . $feed['title'] . '", "username" : "' .  $user->user_info['user_username'] . '"};'; 
        $feed_js  .= 'var body = null;';
      break;
      
    case 'postclassified' :
				$bundle_id = $fbconnect_setting['postclassified_feed_bundleid'];
        $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '", "id" : "' . $feed['id'] . '", "title" : "' . $feed['title'] . '", "username" : "' .  $user->user_info['user_username'] . '"};'; 
        $feed_js  .= 'var body = null;';
      break;
      
    case 'postmusic' :
				$bundle_id = $fbconnect_setting['postmusic_feed_bundleid'];
        $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '"};'; 
        $feed_js  .= 'var body = null;';
      break;
      
    case 'postvideo' :
				$bundle_id = $fbconnect_setting['postvideo_feed_bundleid'];
        $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '", "id" : "' . $feed['id'] . '", "title" : "' . $feed['title'] . '", "username" : "' .  $user->user_info['user_username'] . '"};'; 
        $feed_js  .= 'var body = null;';
      break;
      
    case 'postyoutube' :
				$bundle_id = $fbconnect_setting['postyoutube_feed_bundleid'];
        $feed_js   = 'var template_data = {"sitename" : "' . $var_site_name . '", "siteurl" : "' . $url->url_base . '", "id" : "' . $feed['id'] . '", "title" : "' . $feed['title'] . '", "username" : "' .  $user->user_info['user_username'] . '"};'; 
        $feed_js  .= 'var body = null;';
      break;
    }
    

    
    $js_to_render .= '<script type="text/javascript">
    										window.addEvent("domready", function() {
      										FB.Bootstrap.requireFeatures(["Connect"], function() { ' .$feed_js. '
          									FB.Connect.showFeedDialog('. $bundle_id .', template_data, null, body);
      										});
    										});
    									</script>';
    $session_object->clear('fbconnect_feed');
  }

	return $js_to_render;
}


/**
 * Get fbconnect application configuration parameters (API key and Secret key)
 *
 * @return array
 */
function fbconnect_get_appconfig() {
	global $api_key, $api_secret;
  $config = array();
  
  $config['api_key'] = $api_key;
  $config['secret_api_key'] = $api_secret;  
  
  if ($config['api_key'] && $config['secret_api_key']) {
    return $config;
  }
}



/**
 * This function detects the presence of facebook session.
 */
function fbconnect_initialize_check() {
  
  global $user, $url, $database, $var_fbconnect_field_to_import;

  // USER IS LOGGED IN
  if ($user->user_exists != 0) {
    return;
  }
  
  $fbuid = fbconnect_get_fbuid();  
  $current_url = $url->url_current();
  $current_url = urldecode($current_url);
  $baseurl_length = strlen($url->url_base);
  $action_file = substr($current_url, $baseurl_length);
  if (!empty($action_file)) {
		$temp_array = explode('?', $action_file);
		$action_file = $temp_array[0];
 }

  // If the user is not logged into Facebook, or the file under action is the logout file, then exit this function
  if (!$fbuid || $action_file == 'user_logout.php') {
    return;
  }
  
  // If the action file is one of the registration files through FBConnect, then exit this function
  if ($action_file == 'fbconnect_register_import.php' || $action_file == 'fbconnect_register_create.php' || $action_file == 'user_fbconnect_register_import.php') {
    return;
  }
  
  // Verify if the user is associated with a uid in the database  
  $uid = fbconnect_is_siteuser($fbuid);
  
 // user is either logged in by Facebook.Com or trying to make a new signup on the site using FBConnect. Then check if the return file name is user_home.php, if yes then it means that user should redirect to facebook import file. 
	if (!empty($_GET['return_url'])) {
	  $return_url_file_name = substr($_GET['return_url'], $baseurl_length);
	}
  if (empty($uid['uid']) && ($return_url_file_name == 'user_home.php')) {
    $fields = array_filter($var_fbconnect_field_to_import, 'fbconnect_filter_importdata');
    $path = ($fields) ? 'fbconnect_register_import.php' : 'fbconnect_register_create.php';    
    header("Location: $path");
  } else if (!empty($uid['uid']) ) { 
  	$user_temp = new SEUser(Array($uid['uid']));
  	if($user_temp->user_exists == 0) {
      $is_error = 676;
       return;       
    } elseif($user_temp->user_info[user_enabled] == 0) {     // CHECK IF USER IS ENABLED OR  DISABLED BY ADMIN
      $is_error = 677;
      return ;
    } else {     // INITIATE COOKIES FOR LOGIN
     $user_temp->user_setcookies(true);    
     $current_time = time();
     $email = $user_temp->user_info['user_email'];
     // Change the last active time for the user
     $user_active_query = sprintf("UPDATE se_users SET user_lastlogindate='%s', user_lastactive = '%s', user_ip_lastactive = '%s', user_logins=user_logins+1 WHERE user_id = '%s' LIMIT 1", time(), time(), $_SERVER['REMOTE_ADDR'], $uid['uid']);
    $database->database_query($user_active_query);  
    
    $visitor_ip = ip2long($_SERVER['REMOTE_ADDR']);
    $visitor_browser = addslashes(substr($_SERVER['HTTP_USER_AGENT'], 0, 255));
    $sql = "DELETE FROM se_visitors WHERE visitor_ip='{$visitor_ip}' && visitor_browser='{$visitor_browser}' && visitor_user_id='0'";
    $database->database_query($sql);
    
    setcookie("se_user_lastactive", time() - 3600, 0, "/");
    
    // UPDATE LOGIN STATS
    update_stats("logins");
      
    // BUMP LOG
	  $database->database_query("INSERT INTO se_logins (login_email, login_date, login_ip, login_result) VALUES ('$email', '$current_time', '".$_SERVER['REMOTE_ADDR']."', '$login_result')");
	  bumplog();
	  // REDIRECT TO USER_HOME PAGE AFTER SUCCESSFULLY LOGIN
	  header("Location: user_home.php");
    }
  }
}


/**
 * Check if a Facebook session exists for the user.
 *
 * OUTPUT : integer : Facebook user id
 */
function fbconnect_get_fbuid() {
  if (facebook_client()) {
    return facebook_client()->get_loggedin_user();
  }
}


/**
 * Get the Facebook client object.
 * 
 * OUTPUT : object : Facebook API Object
 */
function facebook_client($is_admin = 0) {
  static $fb = NULL;
  if (!$fb instanceof Facebook) {
  	$conf = empty($is_admin) ? fbconnect_get_appconfig() : fbconnect_get_config_admin();
    if ($conf) {
      // Facebook PHP Client API, stored locally in the facebook-client directory
      $lib_path = ($is_admin == 1) ? '../facebook-client/' : 'facebook-client/';
      $lib_files = array(
        'facebook.php',
        'facebook_desktop.php',
        'jsonwrapper/jsonwrapper_inner.php',    
        'jsonwrapper/jsonwrapper.php',
        'jsonwrapper/JSON/JSON.php'
      );
      foreach ($lib_files as $file_path) {
        if (!file_exists($lib_path.$file_path)) {
          return;
        }
      }
      // Include facebook.php
      include_once($lib_path.$lib_files[0]);
      if (class_exists('Facebook')) {
        $fb = new Facebook($conf['api_key'], $conf['secret_api_key']);
      }
    }
  }
  return $fb;
}


/**
 * Check if the FB user is already registered in the fbconnect_users table
 *
 * INPUT : integer : Facebook user id
 *   
 * OUTPUT : array
 */
function fbconnect_is_siteuser($fbuid) {
	global $database;
  $data_query = sprintf("SELECT uid FROM fbconnect_users WHERE fbuid = '%s'", $fbuid);
  $user_query = $database->database_query($data_query);
  $data = $database->database_fetch_assoc($user_query);
  return ($data) ? $data : FALSE;
}


/**
 * Check if the site user has a FB Connect account, i.e., already registered in the fbconnect_users table
 *
 * INPUT : integer : $uid, Site user id
 *   
 * OUTPUT : array
 */
function fbconnect_get_user_fbuid($uid) {
	global $database;
  $data_query = sprintf("SELECT fbuid FROM fbconnect_users WHERE uid = '%s'", $uid);
  $user_query = $database->database_query($data_query);
  $data = $database->database_fetch_assoc($user_query);
  return ($data) ? $data : FALSE;
}


/**
 * Used as a filter function for the array used to import user Facebook data
 */
function fbconnect_filter_importdata($var) {
  return (is_string($var)) ? $var : NULL;
}


/**
 * Get the Profile fields import setting chosen by user
 * 
 * OUTPUT : array : Setting chosen by user
 */
function fbconnect_user_import_setting($uid) {
	global $database;
  $data_query = sprintf("SELECT import_setting FROM fbconnect_users WHERE uid = '%s'", $uid);
  $user_query = $database->database_query($data_query);
  $data = $database->database_fetch_assoc($user_query);

  if ($data['import_setting'] != NULL) {
     return unserialize($data['import_setting']);
  }
}


/**
 * Query information from facebook user table for importing FB profile values.
 *
 * OUTPUT : array : User profile information
 */
function fbconnect_get_userinfo_fb($fbuid, $fields = NULL) {
	global $var_fbconnect_field_to_import;
  if (!$fields) {
    $fields = implode(', ', array_filter($var_fbconnect_field_to_import, 'fbconnect_filter_importdata'));
  }
  if (facebook_client() && $fields) {
    try {
      $result = facebook_client()->api_client->fql_query("SELECT $fields FROM user WHERE uid = $fbuid");
      if (!empty($result[0])) {
      	return $result[0];
      }
      return;      
    } catch (Exception $e) {
    }
  }
}


/**
 * Returns the Social Engine and FB Connect tables' data for the user
 * 
 * INPUT : integer : User id
 * 
 * OUTPUT : array : User data from se_users & fbconnect_users tables
 */
function fbconnect_user_load($uid) {
	global $database;
	$user_maindetails_query = sprintf("SELECT * FROM se_users WHERE user_id = '%s'", $uid);
  $user_maindetails = $database->database_query($user_maindetails_query);
  $datamain = $database->database_fetch_assoc($user_maindetails);
	
	$user_details_query = sprintf("SELECT fbuid, timestamp, avatar, visibility FROM fbconnect_users WHERE uid = '%s'", $uid);
  $user_details = $database->database_query($user_details_query);
  $data = $database->database_fetch_assoc($user_details);
  
  $a = array_merge($datamain, $data);
  return $a;
}


/**
 * Called when a SE user is being deleted
 */
function fbconnect_user_delete($uid) {
	global $database;
	$fbuid = fbconnect_get_user_fbuid($uid);
	if(!empty($fbuid['fbuid'])) {
		$user_fbconnect_entry = sprintf("DELETE FROM fbconnect_users WHERE uid = '%s'", $uid);
	  $user_query1 = $database->database_query($user_fbconnect_entry);
	}
}


/**
 * Return the user's avatar settings.
 * 
 * INPUT : integer : Iser id
 * 
 * OUTPUT : integer : 0 OR 1
 */
function fbconnect_get_user_avatar_setting($uid) {
	global $database;
	$user_fb_avatar_query = sprintf("SELECT avatar FROM fbconnect_users WHERE uid = '%s'", $uid);
  $user_fb_avatar_run = $database->database_query($user_fb_avatar_query);
  $user_fb_avatar = $database->database_fetch_assoc($user_fb_avatar_run);
 	return $user_fb_avatar['avatar'];
}


/**
 * Render Facebook user's avatar picture
 *
 * INPUT : (integer, string) : (Facebook User id, Size of avatar picture one of ('thumb', 'medium', 'large')) 
 */
function fbconnect_output_user_fbavatar($fbuid, $size='normal') {
	if ($size == 'big_large') {
    return '<fb:profile-pic  facebook-logo="true" size="normal"  uid="'. $fbuid .'"></fb:profile-pic>';
	} else {
		return '<fb:profile-pic  facebook-logo="true" size="'. $size .'" uid="'. $fbuid .'"></fb:profile-pic>';
	}
}


/**
 * Render Facebook user's avatar picture
 *
 * INPUT : (integer, string) : (Facebook User id, Size of avatar picture one of ('thumb => 50X50', 'small => 50X150', 'medium => 100X300', 'large => 200X600'))
 * Note : The thumb and small pics are being shown without logo, append "_with_logo" to their fields to return pics with logo
	# pic_with_logo - URL of user profile picture with a Facebook logo overlaid, with max width 100px and max height 300px. May be blank.
	# pic_big_with_logo - URL of user profile picture with a Facebook logo overlaid, with max width 200px and max height 600px. May be blank.
	# pic_small_with_logo - URL of user profile picture with a Facebook logo overlaid, with max width 50px and max height 150px. May be blank.
	# pic_square_with_logo - URL of a square section of the user profile picture with a Facebook logo overlaid, with width 50px and height 50px. May be blank. 
	//http://wiki.developers.facebook.com/index.php/Users.getInfo
 */
function fbconnect_avatar_src($fbuid, $type='big', $size='1') {
  $field = 'pic_with_logo'; // default
  
  if ($type == 'thumbnail') {
	  switch ($size) {
	    case '0':
	    	$field = 'pic_square';
	    break;
	    
	    case '1':
	     	$field = 'pic_small';
	    break;
	    
	    case '2':
				$field = 'pic'; 
	    break;
	    
	   case '3':
	    	$field = 'pic_square_with_logo';
	    break;
	    
	    case '4':
	    	$field = 'pic_small_logo';
	    break;
	    
	    case '5':
	    	$field = 'pic_with_logo';
	    break;
	  }
 }
  
  if ($type == 'big') {
	  switch ($size) {
	    case '0':
	    	$field = 'pic_big_with_logo';
	    break;
	    
	    case '1':
	     	$field = 'pic_with_logo';
	    break;
	    
	    case '2':
				$field = 'pic_big'; 
	    break;
	    
	   case '3':
	    	$field = 'pic';
	    break;
    }
  }
  if (facebook_client()) {
    try {
      $result = facebook_client()->api_client->fql_query("SELECT $field FROM user WHERE uid = $fbuid");
      if (!empty($result[0][$field])) {
        return $result[0][$field];
      } 
      return;
    } catch (Exception $e) {
    }
  }
}


/**
 * Check which all user profile information is available for import from Facebook. This can be made Site Admin configurable
 *
 * INPUT : integer : Facebook User id
 * 
 * OUTPUT : array : Profile fields that can be imported
 */
function fbconnect_available_import_user_fields($fbuid) {
	global $var_facebook_user_fields;
 
  //Get user's Facebook Profile data
	$data1 = array();
		$data = fbconnect_get_userinfo_fb($fbuid);
		if (!empty($data)) {
	    $label = $var_facebook_user_fields;
	    foreach ($data as $key => $value) { 
	      if (is_array($value)) {
	        switch ($key) {
	          case 'affiliations':
	          		$data1[$key] =  $label[$key] . ' : ' . $value[0]['name'];
	          break;                  
	          case 'hometown_location':
	            	$data1[$key] =  $label[$key] . ' : ' . (!empty($value['city']) ? ($value['city'] .', ') : '') . (!empty($value['state']) ? ($value['state'] .', ') : '') . (!empty($value['country']) ? $value['country'] : '') . (!empty($value['zip']) ? (' - ' . $value['zip']) : '');
	          break;
	          case 'current_location':
	            	$data1[$key] =  $label[$key] . ' : ' . (!empty($value['city']) ? ($value['city'] .', ') : '') . (!empty($value['state']) ? ($value['state'] .', ') : '') . (!empty($value['country']) ? $value['country'] : '') . (!empty($value['zip']) ? (' - ' . $value['zip']) : '');
	          break;
	          case 'meeting_sex':
	          		$data1[$key] =  $label[$key] . ' : ' . '';
	          		foreach ($value as $value_m_sex) {
	          			$data1[$key] .= $value_m_sex . ', ';
	          		}
	          		$data1[$key] = rtrim($data1[$key], ', ');
	          break;
	          case 'meeting_for':
	          		$data1[$key] =  $label[$key] . ' : ' . '';
	          		foreach ($value as $value_m_for) {
	          			$data1[$key] .= $value_m_for . ', ';
	          		}
	          		$data1[$key] = rtrim($data1[$key], ', ');
	          break;
	        }
	      }  else {
	      	  if (!empty($value)) {
	         	  $data1[$key] =  $label[$key] . ' : ' . $value;
	      	  }
	      }
	    }
	  }
   return $data1;
  
}


/**
 * Get user's Facebook username
 * 
 * INPUT : integer : User's Facebook User id
 * 
 * OUTPUT : string : Facebook Username
 */
function fbconnect_get_fb_username($fbuid) {
  if (facebook_client()) {
    try {
      $result = facebook_client()->api_client->users_getStandardInfo($fbuid, array('name'));
      if (!empty($result[0]['name'])) {
        return remove_special_chars($result[0]['name']);
      }
      return;
    } catch (Exception $e) {
    }
  }
}


/**
 * Adds Facebook Connect login button to the login forms. This same button is used for associating exising accounts with Facebook Connect
 */
function fbconnect_render_login_button($is_association = 0) {

	$button = '';
	if (fbconnect_get_appconfig()) {
		if ($is_association == 1) {
			$button .= fbconnect_render_button_association();
		} else if ($is_association == 2) {
			$button .= fbconnect_render_button_association_on_settingpage();
		}
		else {
			$button .= fbconnect_render_connect_button();
		}
	}	

	return $button;
}


/**
 * Render the custom Facebook Connect button to log in via Facebook. The button size would be the one chosen by the Site Admin
 */
function fbconnect_render_connect_button() {
  global $var_fbconnect_button_type, $url;
  list($size, $length) = explode('_', $var_fbconnect_button_type);
	// Note : fb:login-button gives issues with IE7 (not visible) and so, it was not used
	return '<a href="#" onclick="FB.Connect.requireSession(function() { window.location=\'' . $url->url_base . 'user_home.php\'; }); return false;"><img alt="Connect" src="http://static.ak.fbcdn.net/images/fbconnect/login-buttons/connect_white_' . $size . '_' . $length . '.gif" id="fb_login_image" border="none" /></a>';
}


/**
 * Render the custom button to log in via Facebook for an already registered user who chooses to associate his account with Facebook.
 */
function fbconnect_render_button_association() {
  global $var_fbconnect_button_type, $url;
  list($size, $length) = explode('_', $var_fbconnect_button_type);
	// Note : fb:login-button gives issues with IE7 (not visible) and so, it was not used
	return '<a href="#" onclick="FB.Connect.requireSession(function() { window.location=\'' . $url->url_base . 'fbconnect_register_import.php\'; }); return false;"><img alt="Connect" src="http://static.ak.fbcdn.net/images/fbconnect/login-buttons/connect_white_' . $size . '_' . $length . '.gif" id="fb_login_image" border="none" /></a>';
}


/**
 * Render the custom button to log in via Facebook for an already registered user who chooses to associate his account with Facebook.
 */
function fbconnect_render_button_association_on_settingpage() {
  global $var_fbconnect_button_type, $url;
  list($size, $length) = explode('_', $var_fbconnect_button_type);
	// Note : fb:login-button gives issues with IE7 (not visible) and so, it was not used
	return '<a href="#" onclick="FB.Connect.requireSession(function() { window.location=\'' . $url->url_base . 'user_fbconnect_register_import.php\'; }); return false;"><img alt="Connect" src="http://static.ak.fbcdn.net/images/fbconnect/login-buttons/connect_white_' . $size . '_' . $length . '.gif" id="fb_login_image" border="none" /></a>';
}

/**
 * Removes special characters and returns text in valid format
 */
function remove_special_chars($text) {
  return text_validate_utf8($text) ? htmlspecialchars($text, ENT_QUOTES) : '';
}

function text_validate_utf8($text) {
  if (strlen($text) == 0) {
    return TRUE;
  }
  return (preg_match('/^./us', $text) == 1);
}


/**
 * Generate a random alphanumeric password. Every user who registers on the site through Facebook Connect gets a random password
 */
function generate_random_user_password($length = 10) {
  // This variable contains the list of allowable characters for the
  // password. Note that the number 0 and the letter 'O' have been
  // removed to avoid confusion between the two. The same is true
  // of 'I', 1, and l.
  $allowable_characters = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';

  // Zero-based count of characters in the allowable list:
  $len = strlen($allowable_characters) - 1;

  // Declare the password as a blank string.
  $pass = '';

  // Loop the number of times specified by $length.
  for ($i = 0; $i < $length; $i++) {

    // Each iteration, pick a random character from the
    // allowable string and append it to the password:
    $pass .= $allowable_characters[mt_rand(0, $len)];
  }

  return $pass;
}


/**
 * Save the user into the fbconnect_users table
 *
 * INPUT : (integer, integer, integer, integer) : (Site User id, Facebook User id, Visibility settings of user, Avatar settings of user)
 */
function fbconnect_register($uid, $fbuid, $visibility = 1, $avatar = 1, $nphoto='') {
	global $database;
	$user_fbregister_query = sprintf("INSERT INTO fbconnect_users (uid, fbuid, timestamp, avatar, visibility) VALUES ('%s', '%s', '%s', '%s', '%s') ON DUPLICATE KEY UPDATE uid = '%s'", $uid, $fbuid, time(), $avatar, $visibility, $uid);
	
  return $database->database_query($user_fbregister_query);
}


function fbconnect_update_user_visibility($uid, $visibility) {
	global $database;
	$user_fbvisibility_query = sprintf("UPDATE fbconnect_users SET visibility = '%s' WHERE uid = '%s'", $visibility, $uid);
  $database->database_query($user_fbvisibility_query);
}


/**
 * Save / Update user's Facebook profile import settings and the profile fields into the Facebook profile cache table.
 *
 * INPUT : (integer, array) : (Site User id, Profile fields that are to be imported)
 */
function fbconnect_set_user_fbinfo($uid, $field_to_import = NULL) {
	global $database;
  if ($field_to_import == NULL) {
    $field_to_import = fbconnect_user_import_setting($uid);
  }
  elseif ($field_to_import) {
    $user_importsetting_query = sprintf("UPDATE fbconnect_users SET import_setting = '%s' WHERE uid = '%s'", serialize($field_to_import), $uid);
    $user_importsetting = $database->database_query($user_importsetting_query);
  }
  if (!$field_to_import) {
    return 'successful';
  }
  
  // Update the timer and save fields settings.
  $user_updatecache_query = sprintf("UPDATE fbconnect_users SET timestamp = '%s', import_setting = '%s' WHERE uid = '%s'", time(), serialize($field_to_import), $uid);
  $user_updatecache = $database->database_query($user_updatecache_query);
  return 'successful';
}


/**
 * Suggests a username to a user who registers via Facebook Connect based on the user's name in Facebook.
 */
function fbconnect_suggest_username() {
	$fbuid = fbconnect_get_fbuid();
	$fbname = fbconnect_get_fb_username($fbuid);
	$suggested_username = str_replace(" ", "", $fbname);
	return $suggested_username;
}


/**
 * Outputs the facebook friends invite form.
 */
function fbconnect_output_friends_invite_form() {
  global $url, $var_site_name, $var_fbconnect_invitef_redirect, $var_fbconnect_invitef_content;
  $base_url = $url->url_base;
  
  $sitename = remove_special_chars($var_site_name);
  
  $actiontxt = 'Please select the Facebook friends you want to tell about ' . $sitename . '.';
  
  $action = remove_special_chars($var_fbconnect_invitef_redirect);
  
  $type = remove_special_chars($var_site_name);
  
  $text = remove_special_chars($var_fbconnect_invitef_content);
  $content = $text .'  <fb:req-choice url=\''. $base_url .'\' label=\'Become a Member!\' />';
  
  $output    = '<fb:serverfbml style="width: 100%;">
                <script type="text/fbml">
                  <fb:fbml>
                    <fb:request-form
                      action="'. $action .'"
                      method="POST"
                      invite="true"
                      type="'. $type .'"
                      content="'. $content .'">	
                      <fb:multi-friend-selector
                      showborder="false"
                      actiontext="'. $actiontxt .'"
                      max="200">
                    </fb:request-form>
                  </fb:fbml>
                </script>
              </fb:serverfbml>';
  return $output;
}

/**
 * Return the data for user's Facebook profile to be shown on the site
 *
 * INPUT : integer : Site User id
 *   
 * OUTPUT : array : User FB profile data
 */
function fbconnect_user_profile_view($uid) {
	global $var_facebook_user_fields;
	
	//Get user's Facebook Profile data
	$owner_fbuid_arr = fbconnect_get_user_fbuid($uid);
	$owner_fbuid = $owner_fbuid_arr['fbuid'];
	$fbprofile_settings = fbconnect_user_import_setting($uid);
	$data1 = array();
	if (!empty($fbprofile_settings)) {
		$data = fbconnect_get_userinfo_fb($owner_fbuid, implode(',', $fbprofile_settings));
		if (!empty($data)) {
	    $label = $var_facebook_user_fields;
	    foreach ($data as $key => $value) { 
	      if (is_array($value)) {
	        switch ($key) {
	          case 'affiliations':
	          		$data1[$label[$key]] = $value[0]['name'];
	          break;                  
	          case 'hometown_location':
	            	$data1[$label[$key]] = (!empty($value['city']) ? ($value['city'] .', ') : '') . (!empty($value['state']) ? ($value['state'] .', ') : '') . (!empty($value['country']) ? $value['country'] : '') . (!empty($value['zip']) ? (' - ' . $value['zip']) : '');
	          break;
	          case 'current_location':
	            	$data1[$label[$key]] = (!empty($value['city']) ? ($value['city'] .', ') : '') . (!empty($value['state']) ? ($value['state'] .', ') : '') . (!empty($value['country']) ? $value['country'] : '') . (!empty($value['zip']) ? (' - ' . $value['zip']) : '');
	          break;
	          case 'meeting_sex':
	          		$data1[$label[$key]] = '';
	          		foreach ($value as $value_m_sex) {
	          			$data1[$label[$key]] .= $value_m_sex . ', ';
	          		}
	          		$data1[$label[$key]] = rtrim($data1[$label[$key]], ', ');
	          break;
	          case 'meeting_for':
	          		$data1[$label[$key]] = '';
	          		foreach ($value as $value_m_for) {
	          			$data1[$label[$key]] .= $value_m_for . ', ';
	          		}
	          		$data1[$label[$key]] = rtrim($data1[$label[$key]], ', ');
	          break;
	        }
	      }  else {
	      	  if (!empty($value)) {
	         	  $data1[$label[$key]] = $value;
	      	  }
	      }
	    }
	  }
	}
	return $data1;
}


/**
 * Get Facebook mutual friends who have added this app : has_added_app.

 * 
 * INPUT : integer : Facebook User id
 * 
 * OUTPUT : array : Friends
 */
function fbconnect_get_fbconnected_friends_site($fbuid_loogedinuser, $fbuid_profile_owner) {
	global $database;
	$friends = array();
	
	//SELECT uid1 FROM friend WHERE uid1 IN (SELECT uid2 FROM friend WHERE uid1=source_uid) AND uid2=target_uid
  if (facebook_client()) { 
          $query = "SELECT uid1 FROM friend WHERE uid1 IN (SELECT uid2 FROM friend WHERE uid1='$fbuid_loogedinuser') AND uid2='$fbuid_profile_owner'";
   try {
    	$rows_temp = facebook_client()->api_client->fql_query($query);
    	$friends_temp = '';
    	foreach ($rows_temp as $row_temp) {
    		$friends_temp[] = $row_temp['uid1'];
    	}
    	$imlode_str = implode(', ', $friends_temp);
      $query_final = "SELECT uid, pic_square, has_added_app FROM user WHERE uid IN ($imlode_str)"; 
      try {
    	  $rows = facebook_client()->api_client->fql_query($query_final);   	
	    } catch (Exception $e) {
	    }
    } catch (Exception $e) {
    }
    if (empty($rows)) {
      return;
    }  
    $friends = array();
    foreach ($rows as $row) {
      if ($row['has_added_app'] == 0) {
        continue;
      }
     $uid_temp = fbconnect_is_siteuser($row['uid']);
      if (!empty($uid_temp['uid'])) {  
 	    	$sql = "SELECT se_users.user_id, se_users.user_username, se_users.user_fname, se_users.user_lname, se_users.user_photo FROM se_users INNER JOIN fbconnect_users ON se_users.user_id = fbconnect_users.uid WHERE se_users.user_verified='1' AND se_users.user_enabled='1' AND se_users.user_search='1' AND fbconnect_users.visibility = '1' AND fbconnect_users.uid = " . $uid_temp['uid'].  " ORDER BY se_users.user_signupdate DESC";
		    $resource = $database->database_query($sql);		    

		    while( $user_info = $database->database_fetch_assoc($resource) )
		    {
		      $friend_user = new se_user();
		      $friend_user->user_info['user_id'] = $user_info['user_id'];
		      $friend_user->user_info['user_username'] = $user_info['user_username'];
		      $friend_user->user_info['user_photo'] = $user_info['user_photo'];
		      $friend_user->user_info['user_fname'] = $user_info['user_fname'];
		      $friend_user->user_info['user_lname'] = $user_info['user_lname'];
		      $friend_user->user_displayname();
		      
		      $friends[] =& $friend_user;
		      unset($friend_user);
		    }
      }
    }  
   return $friends;
  }
}


/*
 * Make the API call to register the feed forms. This is a setup call that only
 * needs to be made once.
 *
 */
function fbconnect_register_feed_forms() {
  $one_line_stories = $short_stories = $full_stories = array();

  // registrations
  $one_line_stories[] = '{*actor*} just joined <a href="{*siteurl*}">{*sitename*}</a>';
  $short_stories[] = array('template_title' => '{*actor*} just joined <a href="{*siteurl*}">{*sitename*}</a>', 'template_body' => '{*actor*} recommends <a href="{*siteurl*}">{*sitename*}</a>. <a href="{*siteurl*}signup.php">Join it</a> too.'); 
  
  //$one_line_stories[] = '{*actor*} commented on {*owner*}\'s Profile.';
  
	
  // edit photo	
//	$one_line_stories[] = '{*actor*} updated their profile photo on <a href="{*siteurl*}">{*sitename*}</a>';
//  $short_stories[] = array('template_title' => '{*actor*} updated their profile photo on <a href="{*siteurl*}">{*sitename*}</a>','template_body' => 'Checkout {*actor*}\'s <a href="{*siteurl*}profile.php?user={*username*}">profile</a> on <a href="{*siteurl*}">{*sitename*}</a>'); 
//
//  $form_id = facebook_client()->api_client->feed_registerTemplateBundle($one_line_stories, $short_stories, $full_story);
//  return $form_id;
  
  // edit status	

//	$one_line_stories[] = '{*actor*} updated their status  on <a href="{*siteurl*}">{*sitename*}</a>';
//  $short_stories[] = array('template_title' => '{*actor*} updated their status  on <a href="{*siteurl*}">{*sitename*}</a>','template_body' => '{*actor*} {*status*}'); 
//
 //blog
//  $one_line_stories[] = '{*actor*} wrote a new Blog Entry on <a href="{*siteurl*}">{*sitename*}</a>';
//  $short_stories[] = array('template_title' => '{*actor*} wrote a new Blog Entry on <a href="{*siteurl*}">{*sitename*}</a>','template_body' => '{*actor*} posted : <a href="{*siteurl*}blog.php?user={*username*}&blogentry_id={*id*}">{*title*}</a>'); 
  
  // Group
//  $one_line_stories[] = '{*actor*} created a new Group on <a href="{*siteurl*}">{*sitename*}</a>';
//  $short_stories[] = array('template_title' => '{*actor*} created a new Group on <a href="{*siteurl*}">{*sitename*}</a>','template_body' => '{*actor*} created : <a href="{*siteurl*}group.php?user={*username*}&group_id={*id*}">{*title*}</a>'); 
  
    // Event
//  $one_line_stories[] = '{*actor*} created a new Event on <a href="{*siteurl*}">{*sitename*}</a>';
//  $short_stories[] = array('template_title' => '{*actor*} created a new Event on <a href="{*siteurl*}">{*sitename*}</a>','template_body' => '{*actor*} created : <a href="{*siteurl*}event.php?user={*username*}&event_id={*id*}">{*title*}</a>'); 
  
    // poll
//  $one_line_stories[] = '{*actor*} created a new Poll on <a href="{*siteurl*}">{*sitename*}</a>';
//  $short_stories[] = array('template_title' => '{*actor*} created a new Poll on <a href="{*siteurl*}">{*sitename*}</a>','template_body' => '{*actor*} created : <a href="{*siteurl*}poll.php?user={*username*}&poll_id={*id*}">{*title*}</a>'); 
  
    // album
//  $one_line_stories[] = '{*actor*} created a new Album on <a href="{*siteurl*}">{*sitename*}</a>';
//  $short_stories[] = array('template_title' => '{*actor*} created a new Album on <a href="{*siteurl*}">{*sitename*}</a>','template_body' => '{*actor*} created : <a href="{*siteurl*}album.php?user={*username*}&album_id={*id*}">{*title*}</a>'); 
  
    // classified
//  $one_line_stories[] = '{*actor*} created a new Classified on <a href="{*siteurl*}">{*sitename*}</a>';
//  $short_stories[] = array('template_title' => '{*actor*} created a new Classified on <a href="{*siteurl*}">{*sitename*}</a>','template_body' => '{*actor*} created : <a href="{*siteurl*}classfied.php?user={*username*}&classified_id={*id*}">{*title*}</a>'); 


   // newmusic
//  $one_line_stories[] = '{*actor*} added a new song on <a href="{*siteurl*}">{*sitename*}</a>';


	// new video	 
//  $one_line_stories[] = '{*actor*} added a new video on <a href="{*siteurl*}">{*sitename*}</a>';
//  $short_stories[] = array('template_title' => '{*actor*} added a new video on <a href="{*siteurl*}">{*sitename*}</a>','template_body' => '{*actor*} added : <a href="{*siteurl*}video.php?user={*username*}&video_id={*id*}">{*title*}</a>'); 
  
  // you tube video  	 
//  $one_line_stories[] = '{*actor*} posted a YouTube video on <a href="{*siteurl*}">{*sitename*}</a>';
//  $short_stories[] = array('template_title' => '{*actor*} posted a YouTube video on <a href="{*siteurl*}">{*sitename*}</a>','template_body' => '{*actor*} posted : <a href="{*siteurl*}video.php?user={*username*}&video_id={*id*}">{*title*}</a>');
  

  $form_id = facebook_client()->api_client->feed_registerTemplateBundle($one_line_stories, $short_stories, $full_story);
  return $form_id;
}


/**
 * Get the user's Facebook first_name
 */
function fbconnect_get_first_name() {
	$fbuid = fbconnect_get_fbuid();
  if (facebook_client()) {
    try {
      $result = facebook_client()->api_client->users_getStandardInfo($fbuid, array('first_name'));
      return remove_special_chars($result[0]['first_name']);
    } catch (Exception $e) {
    }
  }
}


/**
 * Get the user's Facebook last_name
 */
function fbconnect_get_last_name() {
	$fbuid = fbconnect_get_fbuid();
  if (facebook_client()) {
    try {
      $result = facebook_client()->api_client->users_getStandardInfo($fbuid, array('last_name'));
      return remove_special_chars($result[0]['last_name']);
    } catch (Exception $e) {
    }
  }
}


/**
 * Get Facebook Connect config parameter
 * This function is used for validation of admin settings
 * 
 * OUTPUT : array
 */
function fbconnect_get_config_admin() {
  $config['api_key'] = $_POST['api_key'];
  $config['secret_api_key'] = $_POST['secret'];
  
  if ($config['api_key'] && $config['secret_api_key']) {
    return $config;
  }
}


function fbconnect_lsettings($key, $type) {
	return false;
}

// START deleteuser_fbconnect() FUNCTION
// THIS FUNCTION IS RUN WHEN A FCAEBOOK C0NNECT USER IS DELETED
// INPUT: $user_id REPRESENTING THE USER ID OF THE USER BEING DELETED
// OUTPUT: 
function deleteuser_fbconnect($user_id) {
	global $database;

  // DELETE FACEBOOK CONNECT TABLE ROW OF USER
  $database->database_query("DELETE FROM fbconnect_users WHERE uid = '".$user_id."'");
	  
} // END deleteuser_fbconnect() FUNCTION



// THIS FUNCTION IS RUN WHEN GENERATING SITE STATISTICS
// INPUT: 
// OUTPUT: 
function site_statistics_fbconnect(&$args)
{
  global $database;
  
  $statistics =& $args['statistics'];
  
  // NOTE: CACHING WILL BE HANDLED BY THE FUNCTION THAT CALLS THIS
  
  $total = $database->database_fetch_assoc($database->database_query("SELECT COUNT(uid) AS total FROM fbconnect_users"));
  $statistics['fbconnect'] = array(
    'title' => 650000102,
    'stat'  => (int) ( isset($total['total']) ? $total['total'] : 0 )
  );
}

// END site_statistics_fbconnect() FUNCTION

?>
