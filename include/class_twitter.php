<?

class SEP_Twitter {
	
	// http://apiwiki.twitter.com/Twitter-API-Documentation
	
	var $screen_name;
	var $password;	
	
	var $oauth_token;
	var $oauth_token_secret;
	
	var $account_obj;
	
	var $_is_testing;
	var $_errors;
	
	function SEP_Twitter() {
		global $smarty, $setting;
		
		$_is_testing = false;
		$_errors = 0;

	}
	

	
	
	// SEARCH API METHODS
	// ******************
	// ******************
	
	// Returns tweets that match a specified query.
	function search($q = null, $lang = null, $rpp = 20, $page = 1, $since_id = null, $geocode = null, $show_users = false) {
		// http://apiwiki.twitter.com/Twitter-Search-API-Method%3A-search
		
		if(!empty($q) && strlen($q) > 140) {
			// Queries are limited 140 URL characters.
			$q = substr($q, 0, 140);	
		}
			
		$result = $this->_send_search('search', $this->_build_array_with_non_empties(array('q' => $q, 'lang' => $lang, 'rpp' => $rpp, 'page' => $page, 'since_id' => $since_id, 'geocode' => $geocode, 'show_users' => $show_users)));
		if($result) {
			$json = $this->_json_read($result);
			$this->_test_api_log('search - Success', false, print_r($json, true));
			return $json;
		}
		else {
			$this->_test_api_log('search - No Result', true);
			return false;
		}
				
	}
	
	// Returns the top ten topics that are currently trending on Twitter.
	// The response includes the time of the request, the name of each trend, and the url to the Twitter Search results page for that topic.
	function trends() {
		$result = $this->_send_search('trends');
		if($result) {
			$json = $this->_json_read($result);
			$this->_test_api_log('trends - Success', false, print_r($json, true));
			return $json;
		}
		else {
			$this->_test_api_log('trends - No Result', true);
			return false;
		}		
	}
	
	// Returns the current top 10 trending topics on Twitter.  
	// The response includes the time of the request, the name of each trending topic, and query used on Twitter Search results page for that topic.
	// exclude: Optional. Setting this equal to hashtags will remove all hashtags from the trends list. => {tag1,tag2,tag3,...}
	function trends_current($exclude = null) {
		$result = $this->_send_search('trends/current', $this->_build_array_with_non_empties(array('exclude' => $exclude)));
		if($result) {
			$json = $this->_json_read($result);
			$this->_test_api_log('trends_current - Success', false, print_r($json, true));
			return $json;
		}
		else {
			$this->_test_api_log('trends_current - No Result', true);
			return false;
		}	
	}
	
	// Returns the top 20 trending topics for each hour in a given day.
	// date: Optional. Permits specifying a start date for the report. The date should be formatted YYYY-MM-DD.
	// exclude: Optional. Setting this equal to hashtags will remove all hashtags from the trends list. => {tag1,tag2,tag3,...}	
	function trends_daily($date = null, $exclude = null) {
		$result = $this->_send_search('trends/daily', $this->_build_array_with_non_empties(array('date' => $date, 'exclude' => $exclude)));
		if($result) {
			$json = $this->_json_read($result);
			$this->_test_api_log('trends_daily - Success', false, print_r($json, true));
			return $json;
		}
		else {
			$this->_test_api_log('trends_daily - No Result', true);
			return false;
		}			
	}
	
	// Returns the top 30 trending topics for each day in a given week.
	// date: Optional. Permits specifying a start date for the report. The date should be formatted YYYY-MM-DD.
	// exclude: Optional. Setting this equal to hashtags will remove all hashtags from the trends list. => {tag1,tag2,tag3,...}		
	function trends_weekly($date = null, $exclude = null) {
		$result = $this->_send_search('trends/weekly', $this->_build_array_with_non_empties(array('date' => $date, 'exclude' => $exclude)));
		if($result) {
			$json = $this->_json_read($result);
			$this->_test_api_log('trends_weekly - Success', false, print_r($json, true));
			return $json;
		}
		else {
			$this->_test_api_log('trends_weekly - No Result', true);
			return false;
		}
	}
	

	
	
	// REST API METHODS
	// ****************
	// ****************
	
	
	// TIMELINE METHODS
	// ****************
	
	// Returns the 20 most recent statuses from non-protected users who have set a custom user icon. 
	// The public timeline is cached for 60 seconds so requesting it more often than that is a waste of resources.
	function statuses_public_timeline() {
		$this->_test_api_log('statuses_public_timeline - Starting test ...');
		$result = $this->_send('statuses/public_timeline', 'get', false);
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('statuses_public_timeline - Success', false, print_r($xml, true));
			return $xml;
		}
		else {
			$this->_test_api_log('statuses_public_timeline - No Result', true);
			return false;
		}			
	}

	// Returns the 20 most recent statuses posted by the authenticating user and that user's friends. 
	// This is the equivalent of /timeline/home on the Web.
	function statuses_friends_timeline($since_id = null, $max_id = null, $count = null, $page = null) {
		$this->_test_api_log('statuses_friends_timeline - Starting test ...');
		$result = $this->_send('statuses/friends_timeline', 'get', true, array('get' => $this->_build_array_with_non_empties(array('since_id' => $since_id, 'max_id' => $max_id, 'count' => $count, 'page' => $page))));
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('statuses_friends_timeline - Success', false, print_r($xml, true));
			return $xml;
		}
		else {
			$this->_test_api_log('statuses_friends_timeline - No Result', true);
			return false;
		}			
	}
	

	// Returns the 20 most recent statuses posted from the authenticating user. 
	// It's also possible to request another user's timeline via the id parameter. 
	// This is the equivalent of the Web /<user> page for your own user, or the profile page for a third party.
	function statuses_user_timeline($screen_name = null, $user_id = null, $since_id = null, $max_id = null, $count = null, $page = null, $authentication = true) {
		$this->_test_api_log('statuses_user_timeline - Starting test ...');
		if($authentication) {
			$result = $this->_send('statuses/user_timeline', 'get', true, array('get' => array_merge($this->_screen_name_or_user_id($screen_name, $user_id, true), $this->_build_array_with_non_empties(array('since_id' => $since_id, 'max_id' => $max_id, 'count' => $count, 'page' => $page)))));
		}
		else {
			$result = $this->_send('statuses/user_timeline', 'get', false, array('get' => array_merge($this->_screen_name_or_user_id($screen_name, $user_id), $this->_build_array_with_non_empties(array('since_id' => $since_id, 'max_id' => $max_id, 'count' => $count, 'page' => $page)))));
		}
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('statuses_user_timeline - Success', false, print_r($xml, true));
			return $xml;
		}
		else {
			$this->_test_api_log('statuses_user_timeline - No Result', true);
			return false;
		}			
	}
	
	// function statuses_mentions()
	
	
	// STATUS METHODS
	// **************
	
	// Returns a single status, specified by the id parameter below.  
	// The status's author will be returned inline.
	function statuses_show($id) {
		$this->_test_api_log('statuses_show - Starting test ...');
		$result = $this->_send('statuses/show/'.$id, 'get', true);
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('statuses_show - Success', false, print_r($xml, true));
			return $xml;
		}
		else {
			$this->_test_api_log('statuses_show - No Result', true);
			return false;
		}			
	}
	
	// Updates the authenticating user's status. Requires the status parameter specified below.  
	// Request must be a POST. A status update with text identical to the authenticating user's current 
	// status will be ignored to prevent duplicates.
	function statuses_update($status_text, $in_reply_to_status_id = null, $update_community_status = false) {
		// @in_reply_to_status_id This parameter will be ignored unless the author of the tweet this parameter references is @replied within the status text. 
		// Therefore, you must start the status with @username, where username is the author of the referenced tweet.
		
		// check 140 characters limit
		if(strlen($status_text) > 140) {
			$status_text = substr($status_text, 0, 140);	
		}
		
		$this->_test_api_log('statuses_update - Starting test ...');
		$result = $this->_send('statuses/update', 'post', true, array('post' => $this->_build_array_with_non_empties(array('status' => $status_text, 'in_reply_to_status_id' => $in_reply_to_status_id))));
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('statuses_update - Success', false, print_r($xml, true));
			
			// update community status?
			if($update_community_status) {
				global $user, $database, $actions;
				if($user->level_info['level_profile_status']) {
					$status_text = trim(chunkHTML_split(substr(censor($status_text), 0, 100), 12, "<wbr>&shy;"));
				    $database->database_query("UPDATE `se_users` SET `user_status`='{$status_text}', user_status_date='".time()."' WHERE user_id='{$user->user_info['user_id']}' LIMIT 1");
				    $user->user_lastupdate();
				    if( !empty($status_text) ) {
						$actions->actions_add($user, "editstatus_twitter", array($user->user_info['user_username'], $user->user_displayname, $status_text), array(), 600, false, "user", $user->user_info['user_id'], $user->user_info['user_privacy']);
				    }					
				}					
			}
			
			return $xml;
		}
		else {
			$this->_test_api_log('statuses_update - No Result', true);
			return false;
		}			
	}
	
	// Destroys the status specified by the required ID parameter. 
	// The authenticating user must be the author of the specified status.
	function statuses_destroy($id) {
		$result = $this->_send('statuses/destroy/'.$id, 'post', true);
		if($result) {
			// @TODO: API doesnt return destroyed status, even if request was successful
			// $xml = $this->_xml_read($result);
			// $this->_test_api_log('statuses_destroy - Success', false, print_r($xml, true));
			// return $xml;
			$this->_test_api_log('statuses_destroy - Success');
			return true;
		}
		else {
			$this->_test_api_log('statuses_destroy - No Result', true);
			return false;
		}			
	}	
	
	// USER METHODS
	// ************
	
	// Returns extended information of a given user, specified by ID or screen name as per the required id parameter. 
	// The author's most recent status will be returned inline.
	function users_show($screen_name = null, $user_id = null) {
		$this->_test_api_log('users_show - Starting test ...');
		$result = $this->_send('users/show', 'get', true, array('get' => $this->_screen_name_or_user_id($screen_name, $user_id)));
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('users_show - Success', false, print_r($xml, true));
			return $xml;
		}
		else {
			$this->_test_api_log('users_show - No Result', true);
			return false;
		}			
	}
	
	// Returns a user's friends, each with current status inline. They are ordered by the order in which they were added as friends. 
	// Defaults to the authenticated user's friends. It's also possible to request another user's friends list via the id parameter.
	function statuses_friends($screen_name = null, $user_id = null, $page = null) {
		$this->_test_api_log('statuses_friends - Starting test ...');
		if(!empty($screen_name) || !empty($user_id)) {
			$result = $this->_send('statuses/friends', 'get', false, array('get' => array_merge($this->_screen_name_or_user_id($screen_name, $user_id), $this->_build_array_with_non_empties(array('page' => $page)))));
		}
		else {
			$result = $this->_send('statuses/friends', 'get', true, array('get' => $this->_build_array_with_non_empties(array('page' => $page))));
		}
		
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('statuses_friends - Success', false, print_r($xml, true));
			return $xml;
		}
		else {
			$this->_test_api_log('statuses_friends - No Result', true);
			return false;
		}				
	}
	
	// Returns the authenticating user's followers, each with current status inline.  
	// They are ordered by the order in which they joined Twitter.
	function statuses_followers($screen_name = null, $user_id = null, $page = null) {
		$this->_test_api_log('statuses_followers - Starting test ...');
		$result = $this->_send('statuses/followers', 'get', true, array('get' => array_merge($this->_screen_name_or_user_id($screen_name, $user_id), $this->_build_array_with_non_empties(array('page' => $page)))));
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('statuses_followers - Success', false, print_r($xml, true));
			return $xml;
		}
		else {
			$this->_test_api_log('statuses_followers - No Result', true);
			return false;
		}			
	}

	
	
	// DIRECT MESSAGE METHODS
	// **********************
	
	// function direct_messages()
	// function direct_messages_sent()
	// function direct_messages_new()
	// function direct_messages_destroy()
	
	
	
	// FRIENDSHIP METHODS
	// ******************
	
	// Allows the authenticating users to follow the user specified in the ID parameter.  
	// Returns the befriended user in the requested format when successful.  Returns a string describing the failure condition when unsuccessful.
	function friendships_create($screen_name = null, $user_id = null, $follow = true) {
		$result = $this->_send('friendships/create', 'post', true, array('get' => array_merge($this->_screen_name_or_user_id($screen_name, $user_id), array('follow' => ($follow ? 'true' : 'false')))));
		if($result) {
			return $this->_xml_read($result);
		}
		else {
			return false;
		}			
	}
	
	
	// Allows the authenticating users to unfollow the user specified in the ID parameter.
	// Returns the unfollowed user in the requested format when successful.  
	// Returns a string describing the failure condition when unsuccessful.
	function friendships_destroy($screen_name = null, $user_id = null) {
		$result = $this->_send('friendships/destroy', 'post', true, array('get' => $this->_screen_name_or_user_id($screen_name, $user_id)));
		if($result) {
			return $this->_xml_read($result);
		}
		else {
			return false;
		}				
	}
	
	
	// Tests for the existance of friendship between two users. 
	// Will return true if user_a follows user_b, otherwise will return false.
	function friendships_exists($user_a, $user_b) {
		$result = $this->_send('friendships/exists', 'get', true, array('get' => array('user_a' => $user_a, 'user_b' => $user_b)));
		if($result) {
			$xml = $this->_xml_read($result);
			if($xml[0] == 'true') {
				return true;	
			}
			else {
				return false;	
			}
		}	
		else {
			return false;
		}			
	}
	
	
	// SOCIAL GRAPH METHODS
	// ********************
	
	// Returns an array of numeric IDs for every user the specified user is following.
	function friends_ids($screen_name = null, $user_id = null) {
		$this->_test_api_log('friend_ids - Starting test ...');
		$result = $this->_send('friends/ids', 'get', false, array('get' => $this->_screen_name_or_user_id($screen_name, $user_id)));
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('friends_ids - Success', false, print_r($xml, true));
			return $xml;
		}
		else {
			$this->_test_api_log('friends_ids - No Result', true);
			return false;
		}		
	}
	
	// Returns an array of numeric IDs for every user following the specified user.
	function followers_ids($screen_name = null, $user_id = null) {
		$this->_test_api_log('followers_ids - Starting test ...');
		$result = $this->_send('followers/ids', 'get', false, array('get' => $this->_screen_name_or_user_id($screen_name, $user_id)));
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('followers_ids - Success', false, print_r($xml, true));
			return $xml;
		}
		else {
			$this->_test_api_log('followers_ids - No Result', true);			
			return false;
		}				
	}
	
	// ACCOUNT METHODS
	// ***************
	
	// Returns an HTTP 200 OK response code and a representation of the requesting user if authentication was successful; 
	// returns a 401 status code and an error message if not.  Use this method to test if supplied user credentials are valid.
	function account_verify_credentials($cache_account_obj = true, $force_reload = false) {
		$this->_test_api_log('account_verify_credentials - Starting test ...');
		$this->_test_api_log('account_verify_credentials - Open connection for user "'.$this->screen_name.'" with password "'.$this->password.'"');
		
		if(!$force_reload && !empty($this->account_obj)) {
			return true;	
		}
		
		if($force_reload) {
			$this->account_obj = null;	
		}
		
		if(!$this->is_oauth() && empty($this->screen_name)) {
			return false;				
		}
		
		$result = $this->_send('account/verify_credentials', 'get', true);
		if($result) {
			if($cache_account_obj) {
				$this->account_obj = $this->_xml_read($result);
			}
			$this->_test_api_log('account_verify_credentials - connected');			
			return true;
		}
		else {
			$this->_test_api_log('account_verify_credentials - could not open connection', true);	
			return false;
		}	
	}
	
	// Returns the remaining number of API requests available to the requesting user before the API limit is reached for the current hour. 
	// Calls to rate_limit_status do not count against the rate limit. If authentication credentials are provided, 
	// the rate limit status for the authenticating user is returned. Otherwise, the rate limit status for the requester's IP address is returned.
	function account_rate_limit_status($status_for) {
		
		if(!in_array($status_for, array('ip', 'user'))) {
			trigger_error('Twitter: Invalid argument for rate limit status request', E_USER_WARNING);
			return false;	
		}
		$this->_test_api_log('account_rate_limit_status - Checking rate limit status for '.$status_for);		
		
		$result = $this->_send('account/rate_limit_status', 'get', ($status_for == 'ip' ? false : true));
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('account_rate_limit_status - STATUS:', false, print_r($xml, true));
			return array('remaining-hits' => $xml->{'remaining-hits'}, 'hourly-limit' => $xml->{'hourly-limit'}, 'reset-time' => $xml->{'reset-time'}, 'reset-time-in-seconds' => $xml->{'reset-time-in-seconds'});
		}
		else {
			$this->_test_api_log('account_rate_limit_status - unable to fetch status', true);
			return false;
		}				
	}
	
	// Ends the session of the authenticating user, returning a null cookie.  
	// Use this method to sign users out of client-facing applications like widgets.
	function account_end_session() {
		$this->_send('account/end_session', 'post', true);	
		return true; 
	}
	
	// Sets which device Twitter delivers updates to for the authenticating user.  
	// Sending none as the device parameter will disable IM or SMS updates.
	function account_update_delivery_device($device) {
		$this->_test_api_log('account_update_delivery_device - Starting test ...');
		if(!in_array($device, array('sms', 'im', 'none'))) {
			trigger_error('Twitter: Invalid device specified', E_USER_WARNING);
			return false;	
		}
		
		$result = $this->_send('account/update_delivery_device', 'post', true, array('get' => array('device' => $device)));
		if($result) {
			$this->_test_api_log('account_update_delivery_device - Success');
			return true;
		}
		else {
			$this->_test_api_log('account_update_delivery_device - unable to set device', true);
			return false;
		}				
	}
	
	// function account_update_profile_colors() 
	// function account_update_profile_image() 
	// function account_update_profile_background_image()
	// function account_update_profile()			
	
	// FAVORITE METHODS
	// ****************
	
	// Returns the 20 most recent favorite statuses for the authenticating user or user specified by the ID parameter in the requested format.
	function favorites($screen_name = null, $user_id = null, $page = null) {
		
		$this->_test_api_log('favorites - Starting test ...');
		
		// @TODO: why is there an id parameter only? why is user_id/ screen_name missing in the api?!
		$id = !empty($screen_name) ? $screen_name : $user_id;

		$result = $this->_send('favorites'.(!empty($id) ? '/'.$id : ''), 'get', true, array('get' => $this->_build_array_with_non_empties(array('page' => $page))));
		if($result) {
			$xml = $this->_xml_read($result);
			$this->_test_api_log('favorites - Success', false, print_r($xml, true));
			return $xml;
		}
		else {
			$this->_test_api_log('favorites - No Result', true);
			return false;
		}			
	}
	
	// Favorites the status specified in the ID parameter as the authenticating user. 
	// Returns the favorite status when successful.
	function favorites_create($id) {
		$this->_test_api_log('favorites_create - Starting test ...');
		$result = $this->_send('favorites/create/'.$id, 'post', true);
		if($result) {
			// @TODO: API does not return favorite status even if successful request
			// $xml = $this->_xml_read($result);
			// $this->_test_api_log('favorites_create - Success', false, print_r($xml, true));
			// return $xml;
			$this->_test_api_log('favorites_create - Success');
			return true;
		}
		else {
			$this->_test_api_log('favorites_create - No Result', true);
			return false;
		}			
	}	
	
	// Un-favorites the status specified in the ID parameter as the authenticating user. 
	// Returns the un-favorited status in the requested format when successful.
	function favorites_destroy($id) {
		$this->_test_api_log('favorites_destroy - Starting test ...');
		$result = $this->_send('favorites/destroy/'.$id, 'post', true);
		if($result) {
			// @TODO: API does not return un-favorited status even if successful request
			// $xml = $this->_xml_read($result);
			// $this->_test_api_log('favorites_destroy - Success', false, print_r($xml, true));
			// return $xml;
			$this->_test_api_log('favorites_destroy - Success');			
			return true;
		}
		else {
			$this->_test_api_log('favorites_destroy - No Result', true);
			return false;
		}			
	}		
		
	
	// NOTIFICATION METHODS
	// ********************
	
	// Enables device notifications for updates from the specified user. 
	// Returns the specified user when successful.
	function notifications_follow($screen_name=null, $user_id=null) {
		$result = $this->_send('notifcations/follow', 'post', true, array('get' => $this->_screen_name_or_user_id($screen_name, $user_id)));
		if($result) {
			return $this->_xml_read($result);
		}
		else {
			return false;
		}			
	}
	
	// Disables notifications for updates from the specified user to the authenticating user. 
	// Returns the specified user when successful.
	function notifications_leave($screen_name = null, $user_id = null) {
		$result = $this->_send('notifcations/leave', 'post', true, array('get' => $this->_screen_name_or_user_id($screen_name, $user_id)));
		if($result) {
			return $this->_xml_read($result);
		}
		else {
			return false;
		}			
	}
	
	
	// BLOCK METHODS
	// *************
	
	// function blocks_create()
	// function blocks_destroy()
	
	
	// HELP METHODS
	// ************
	
	// Returns the string "ok" in the requested format with a 200 OK HTTP status code.
	function help_test() {
		$this->_test_api_log('help_test - Testing connection to twitter');
		$result = $this->_send('help/test', 'get');
		if($result) {
			$result = $this->_xml_read($result);
			if($result[0] == 'true') {
				$this->_test_api_log('help_test - connected to twitter'); 				
				return true;
			}
		}
		$this->_test_api_log('help_test - coult not open connection', true);		
		return false;
	}
	
	
	

	
	
	// -----------------------
	// CLASS METHODS
	
	// Send request and return response
	function _send($url, $method, $auth = false, $data = array('get' => array(), 'post' => array()), $format = 'xml') {
		global $database, $SEP_Twitter_Config;
		
		$_data = array('get' => array(), 'post' => array());
		
		// generate url
		$url = 'http://twitter.com/' . ($url[0] == '/' ? substr($url, 1) : $url) . '.' . $format;
		
		
		if(!in_array($method, array('get', 'post'))) {
			$msg = 'Twitter: Invalid method argument: '.$method;
			$this->_test_api_log(' - '.$msg, true);
			trigger_error($msg, E_USER_WARNING);	
			return false;
		}
		
		if($this->is_oauth()) {
			// Process Request With OAuth
			$epi = new EpiOAuth($SEP_Twitter_Config['TWITTER_CONSUMER_KEY'], $SEP_Twitter_Config['TWITTER_CONSUMER_SECRET']);
			$epi->setToken($this->oauth_token, $this->oauth_token_secret);
						
			$result = $epi->httpRequest(strtoupper($method), $url, $data[$method]);
			$resultMetaInfo['http_code'] = $result->code;
			$result = $result->data;
			
		}
		else {
			// Process Request With Standard CURL 
			$tcurl = curl_init();
			curl_setopt($tcurl, CURLOPT_VERBOSE, 1);
			curl_setopt($tcurl, CURLOPT_TIMEOUT, 30); 
			curl_setopt($tcurl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($tcurl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($tcurl, CURLOPT_HTTPHEADER, array('Expect:')); // fixes the http response code 417
			
			if($auth) {
				if(!empty($this->screen_name) && !empty($this->password)) {
					curl_setopt($tcurl, CURLOPT_USERPWD, $this->screen_name.':'.$this->password);
				}
				else {
					$msg = 'Twitter: Unable to set auth data (data is missing)';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_WARNING);
				}	
			}
	
			// sanitize and prepare GET data
			$_data['get'] = array();
			if(!empty($data['get']) && is_array($data['get']) && count($data['get']) > 0) {
				foreach($data['get'] as $name => $value) {
					$_data['get'][] = urlencode(trim($name)).'='.urlencode(trim($value));
				}
				$_data['get'] = implode('&', $_data['get']);
			}
	
			// set get or post request
			if($method == 'get') {
				curl_setopt($tcurl, CURLOPT_HTTPGET, 1);	
				curl_setopt($tcurl, CURLOPT_URL, $url . (!empty($_data['get']) ? '?'.$_data['get'] : ''));
			}
			elseif($method == 'post') {
				
				// sanitize and prepare POST data
				$_data['post'] = array();
				if(!empty($data['post']) && is_array($data['post']) && count($data['post']) > 0) {
					foreach($data['post'] as $name => $value) {
						$_data['post'][trim($name)] = (trim($value));
					}
				}			
				
				curl_setopt($tcurl, CURLOPT_POST, 1);
				curl_setopt($tcurl, CURLOPT_POSTFIELDS, !empty($_data['post']) ? $_data['post'] : '');
				curl_setopt($tcurl, CURLOPT_URL, $url. (!empty($_data['get']) ? '?'.$_data['get'] : ''));
			}
			
			$result = curl_exec($tcurl);	
			$this->_test_api_log('API SENT: '.$url. (!empty($_data['get']) ? '?'.$_data['get'] : ''), false, "POST_DATA\n".print_r($_data['post'], true));
			
			$resultMetaInfo = curl_getinfo($tcurl);
			curl_close($tcurl);
			
			// $resultMetaInfo['total_time'];
		}
	
		if ($resultMetaInfo['http_code'] == 200) {
			return $result;
		}
		elseif(!empty($resultMetaInfo['http_code'])) {
			$this->process_returned_http_status_code($resultMetaInfo['http_code']);
			return false;	
		}				
		else {
			$msg = 'Unable to process twitter request';
			$this->_test_api_log(' - '.$msg, true);
			trigger_error($msg, E_USER_WARNING);	
			return false;
		}
		
	}
	

	// send request to Twitter for SEARCH API
	function _send_search($url, $get_params = array()) {
		$url = 'http://search.twitter.com/'.$url.'.json';
		
		$tcurl = curl_init();
		curl_setopt($tcurl, CURLOPT_VERBOSE, 1);
		curl_setopt($tcurl, CURLOPT_TIMEOUT, 30); 
		curl_setopt($tcurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($tcurl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($tcurl, CURLOPT_HTTPHEADER, array('Expect:')); // fixes the http response code 417
		

		// sanitize and prepare GET data
		$_data['get'] = array();
		if(!empty($get_params) && is_array($get_params) && count($get_params) > 0) {
			foreach($get_params as $name => $value) {
				$_data['get'][] = urlencode(trim($name)).'='.urlencode(trim($value));
			}
			$_data['get'] = implode('&', $_data['get']);
		}

		curl_setopt($tcurl, CURLOPT_HTTPGET, 1);	
		curl_setopt($tcurl, CURLOPT_URL, $url . (!empty($_data['get']) ? '?'.$_data['get'] : ''));
		
		$result = curl_exec($tcurl);	
		$resultMetaInfo = curl_getinfo($tcurl);
		curl_close($tcurl);
		
		// $resultMetaInfo['total_time'];
	
	
		if ($resultMetaInfo['http_code'] == 200) {
			return $result;
		}
		elseif(!empty($resultMetaInfo['http_code'])) {
			$this->process_returned_http_status_code($resultMetaInfo['http_code']);
			return false;	
		}				
		else {
			$msg = 'Unable to process twitter search request';
			$this->_test_api_log(' - '.$msg, true);
			trigger_error($msg, E_USER_WARNING);	
			return false;
		}		
		
	}
		
	
	// process returned http status code
	function process_returned_http_status_code($code) {
		global $database;

			switch($code) {
				case 302:
					$msg = 'Twitter: 302 Found: API call to nonexistent method';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_NOTICE);
					break;					
				case 304:
					$msg = 'Twitter: 304 Not Modified: There was no new data to return.';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_NOTICE);
					break;	
				case 400:
					// Rate-Limit reached
					// The default rate limit for calls to the REST API is 100 requests per hour. 
					// http://apiwiki.twitter.com/Rate-limiting
					
					// insert into db
					if($auth) {
						$database->database_query('INSERT INTO `se_twitter_rate_limiting` SET `date`=NOW(), `screen_name`="'.$database->database_real_escape_string($this->screen_name).'"');	
					}
					else {
						$database->database_query('INSERT INTO `se_twitter_rate_limiting` SET `date`=NOW()');
					}
									
					$msg = 'Twitter: 400 Bad Request: The request was invalid. (RATE LIMITING)';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_NOTICE);					
					break;	
				case 401:
					$msg = 'Twitter: 401 Not Authorized: Authentication credentials were missing or incorrect.';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_NOTICE);					
					break;
				case 403:
					$msg = 'Twitter: 403 Forbidden: The request is understood, but it has been refused.';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_NOTICE);
					break;
				case 404:
					$msg = 'Twitter: 404 Not Found: The URI requested is invalid or the resource requested, such as a user, does not exists.';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_NOTICE);
					break;
				case 406:
					$msg = 'Twitter: 406 Not Acceptable: Returned by the Search API when an invalid format is specified in the request.';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_NOTICE);
					break;
				case 417:
					$msg = 'Twitter: 417 Expectation Failed: Strange Twitter error?!';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_NOTICE);
					break;					
				case 500:
					$msg = 'Twitter: 500 Internal Server Error: Something is broken.';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_NOTICE);
					break;
				case 502:
					$msg = 'Twitter: 502 Bad Gateway: Twitter is down or being upgraded.';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_NOTICE);
					break;
				case 503:
					$msg = 'Twitter: 503 Service Unavailable: The Twitter servers are up, but overloaded with requests. Try again later.';
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_NOTICE);
					break;																																		
				default:
					$msg = 'Twitter: Unrecognized twitter http resonse code: '.$code;
					$this->_test_api_log(' - '.$msg, true);
					trigger_error($msg, E_USER_WARNING);
					break;
			}
		
			
	}
	
	// Read/ Parse XML String
	function _xml_read($string) {
		if(empty($string)) {
			return false;	
		}
		
		$xml = simplexml_load_string($string);	
		if($xml) {
			return $xml;
		}
		else {
			return false;	
		}
	}
	
	function _json_read($string) {
		if(empty($string)) {
			return false;	
		}
	
		// check if json_decode exists in this php version
		if(!function_exists('json_decode')) {
			function json_decode($content, $assoc = false) {
				require_once getcwd().'/include/twitter_libs/json.php';
				if($assoc) {
					$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);	
				}
				else {
					$json = new Services_JSON;
				}
				return $json->decode($content);
			}	
		}
		
		return json_decode($string);
	}	
	
	// Return user_id or screen_name
	function _screen_name_or_user_id($screen_name=null, $user_id=null, $allow_empty = false) {
		if(!empty($user_id)) {
			return array('user_id' => $user_id);	
		}
		elseif(!empty($screen_name)) {
			return array('screen_name' => $screen_name);	
		}
		else {
			if(!$allow_empty) {
				trigger_error('Twitter: Empty user_id and screen_name', E_USER_WARNING);
				return false;		
			}
			else {
				return array();
			}
		}
	}	
	
	// Returns array with non empty values
	function _build_array_with_non_empties($array) {
		$_array = array();
		foreach($array as $key => $value) {
			if(!empty($value)) {
				$_array[$key] = $value;	
			}	
		}
		unset($array);
		return $_array;
	}
	
	// Log method to output messages during tests
	function _test_api_log($message, $is_error = false, $more = null) {
		if($this->_is_testing) {
						
			if(!empty($more)) {
				$div_id = 'twitter_error_'.sha1($this->_errors.mt_rand());
				$message .= ' [<a href="#" onClick="if(document.getElementById(\''.$div_id.'\').style.display == \'\'){document.getElementById(\''.$div_id.'\').style.display=\'none\';}else{document.getElementById(\''.$div_id.'\').style.display=\'\';}return false;">more</a>]<div id=\''.$div_id.'\' style="display:none;margin-left:10px;font-size:0.9em;border-left:2px solid #0000ff;padding:4px;">'.$more.'</div>';
			}
			
			if($is_error) {
				echo '<span style="color:#ff0000">'.$message."</span>\n";	
				$this->_errors++;
			}	
			else {
				echo $message."\n";	
			}		
		}
		unset($message);
	}
	
	// Unit Testing for the API
	function _test_api($output_php_error = false) {
		$ln = "\n";

		// Activate Error Output
		$this->_is_testing = true;
		$this->_errors = 0;
		if($output_php_error) {
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}
		echo '<hr><pre style="text-align:left;">TWITTER API TEST'.$ln.$ln;
		
		// Testing CURL
		echo 'CURL Information'.$ln;
		print_r(curl_version());
		
		$this->help_test();
		$this->account_rate_limit_status('ip');
		$this->account_rate_limit_status('user');		
		$this->account_verify_credentials();		
		$this->statuses_public_timeline();
		$this->statuses_friends_timeline();
		$this->statuses_user_timeline($this->screen_name);
		
		$status_id = null;
		$result = $this->statuses_update('api test update: '.date('y-m-d H:i:s', time()).' ('.rand(1000, 9999).')');
		if($result) {	
			$status_id = $result->id;
		}
		if(empty($status_id)) {
			$this->_test_api_log('NO STATUS ID RETURNED!', true);
		}
		
		$this->statuses_show($status_id);
		
		$this->favorites_create($status_id);
		$this->favorites($this->screen_name);
		$this->favorites_destroy($status_id);				
		
		
		
		$this->users_show($this->screen_name);
		$this->statuses_friends($this->screen_name);
		$this->statuses_followers($this->screen_name);
		
		// $this->friendships_create
		// $this->friendships_destroy
		// $this->friendships_exists
		
		$this->friends_ids($this->screen_name);
		$this->followers_ids($this->screen_name);
		
		$this->account_update_delivery_device('none');
		
		// $this->notifications_follow
		// $this->notifications_leave
		
		$this->statuses_destroy($status_id);
		
		
		// Output Error Numbers
		if($this->_errors > 0) {
			echo $ln.'!!! '.$this->_errors.' TEST/S FAILED !!!';			
		}
		else {
			echo $ln.'*** ALL TESTS PASSED ***';
		}
		
		echo '</pre><hr>';
		$this->_is_testing = false;
	}
	
		
		
	// returns time ago string for twitter "created_at" timestamp
	function timeAgo($time) {

		if(empty($time)) {
			return SE_Language::_get(18910001);	// some time ago
		}
	
	    $gap = time() - strtotime($time);
	
	    if ($gap < 5) {
	        return SE_Language::_get(18910002); // less than 5 seconds ago
	    } else if ($gap < 10) {
	        return SE_Language::_get(18910003); // less than 10 seconds ago
	    } else if ($gap < 20) {
	        return SE_Language::_get(18910004); // less than 20 seconds ago
	    } else if ($gap < 40) {
	        return SE_Language::_get(18910005); // half a minute ago
	    } else if ($gap < 60) {
	        return SE_Language::_get(18910006); // less than a minute ago
	    }
	
	    $gap = round($gap / 60);
	    if ($gap < 60)  { 
	        return sprintf(SE_Language::_get(18910007), $gap);
	    }
	
	    $gap = round($gap / 60);
	    if ($gap < 24)  { 
	        return sprintf(SE_Language::_get(18910008), $gap);
	    }
	
	    return date(SE_Language::_get(18910009), strtotime($time));
	
	}
		
	
	
	// TWITTER - SOCIALENGINE - METHODS
	// ********************************
	// ********************************
	
	
	// truncate se_twitter_rate_limiting
	function truncate_rate_limiting() {
		global $database;
		$database->database_query('TRUNCATE `se_twitter_rate_limiting`');	
	}
	
	// returns index of rate limiting
	// 0 = best
	// 5 = worse
	function get_rate_limiting_trend() {
		global $database;
		
		$query = $database->database_query('SELECT COUNT(1) counter FROM `se_twitter_rate_limiting` WHERE `date` >= DATE_SUB(NOW(), INTERVAL -90 DAY)');
		$result = $database->database_fetch_assoc($query);
		$counter = $result['counter'];
		
		// cleanup rate limiting
		if(rand(1, 5) == 1) {
			$database->database_query('DELETE FROM `se_twitter_rate_limiting` WHERE `date` < DATE_SUB(NOW(), INTERVAL -90 DAY)');				
		}
		
		if($counter > 1000) {
			return 5;
		} 
		elseif($counter > 500) {
			return 4;			
		}
		elseif($counter > 300) {
			return 3;			
		}
		elseif($counter > 100) {
			return 2;			
		}
		elseif($counter > 50) {
			return 1;			
		}
		else {
			return 0;	
		}	
			
	}
	
	// checks if user has got an accout saved
	// if yes, set the acccount data
	function account($user_id, $verify_account = false) {
		$result = $this->get_account($user_id);
		if($result) {
			$this->set_account($result['screen_name'], $result['password'], $result['oauth_token'], $result['oauth_token_secret'], $user_id);	
			
			if($verify_account) {
				if($this->account_verify_credentials()) {
					return true;
				}
				else {
					return false;	
				}	
			}
			return true;
		}
		return false;
	}
	
	// returns twitter account for user id
	function get_account($user_id) {
		global $database;
		$query = $database->database_query('SELECT * FROM `se_twitter_accounts` WHERE `user_id`="'.$user_id.'" LIMIT 1');
		$result = $database->database_fetch_assoc($query);
		if(!empty($result)) {
			return $result;
		}
		else {
			return false;
		}
	}	
	
	// set login data for twitter for object
	function set_account($screen_name, $password, $oauth_token = null, $oauth_token_secret = null, $user_id = null) {
		global $database;
		
		$this->screen_name = $screen_name;
		$this->password = $password;		
		$this->oauth_token = $oauth_token;
		$this->oauth_token_secret = $oauth_token_secret;	
		
		if($this->is_oauth() && empty($this->screen_name)) {
			if($this->account_verify_credentials() && !empty($user_id)) {
				$this->screen_name = $this->account_obj->screen_name;
				$database->database_query('UPDATE `se_twitter_accounts` SET `screen_name`="'.$database->database_real_escape_string($screen_name).'" WHERE `user_id`="'.$database->database_real_escape_string($user_id).'" LIMIT 1');
			}
			else {
				trigger_error('Twitter: Can not verify OAuth credentials', E_USER_NOTICE);	
			}
		}			
	}
	
		
	function is_oauth() {
		global $setting;
		return $setting['setting_twitter_authentication'] == 'oauth' && !empty($this->oauth_token) && !empty($this->oauth_token_secret);	
	}

	
	// inserts or updates a user twitter account
	function save_account($user_id, $screen_name, $password) {
		global $database;
		if($this->get_account($user_id)) {
			// update
			if($database->database_query('UPDATE `se_twitter_accounts` SET `screen_name`="'.$database->database_real_escape_string($screen_name).'", `password`="'.$database->database_real_escape_string($password).'" WHERE `user_id`="'.$database->database_real_escape_string($user_id).'" LIMIT 1')) {
				return true;	
			}
		}
		else {
			// insert
			if($database->database_query('INSERT INTO `se_twitter_accounts` SET `screen_name`="'.$database->database_real_escape_string($screen_name).'", `password`="'.$database->database_real_escape_string($password).'", `user_id`="'.$database->database_real_escape_string($user_id).'"')) {
				return $database->database_insert_id();	
			}			
		}
		
		return false;
	}
	
	function set_oauth_tokens($token, $token_secret) {
		global $database, $user;
		$database->database_query("UPDATE `se_twitter_accounts` SET `oauth_token`='".$database->database_real_escape_string($token)."', `oauth_token_secret`='".$database->database_real_escape_string($token_secret)."' WHERE `user_id`='".$user->user_info['user_id']."' LIMIT 1");
		$affected = $database->database_affected_rows();
		if(empty($affected)) {
			$database->database_query("INSERT INTO `se_twitter_accounts` SET `oauth_token`='".$database->database_real_escape_string($token)."', `oauth_token_secret`='".$database->database_real_escape_string($token_secret)."', `user_id`='".$user->user_info['user_id']."'");
		}
	}
		
	
	function delete_account($user_id) {
		global $database;
		$database->database_query("DELETE FROM `se_twitter_accounts` WHERE `user_id`='".$database->database_real_escape_string($user_id)."' LIMIT 1");	
		$this->screen_name = null;
		$this->password = null;
		$this->clear_cache($user_id);
	
		return true;
	}
	

	
	function clear_cache($user_id, $template = null, $page = null) {
		global $smarty;
		
		$cache_id = $user_id;
		
		if(!empty($template)) {
			$cache_id .= '|' . $template;	
		}
		
		if(!empty($page)) {
			$cache_id .= '|' . $page;	
		}
		
		$smarty->caching = 2;
		$smarty->clear_cache(null, $cache_id);
		$smarty->caching = 0;	
	}
	
	function clear_cache_all() {
		global $smarty;
		
		$smarty->caching = 2;
		$smarty->clear_cache();
		$smarty->caching = 0;				
	}

	function mb_wordwrap($str, $width = 75, $break = "\n", $cut = false, $charset = null) {
	    if ($charset === null) $charset = mb_internal_encoding();
	
	    $pieces = split($break, $str);
	    $result = array();
	    foreach ($pieces as $piece) {
	      $current = $piece;
	      while ($cut && mb_strlen($current) > $width) {
	        $result[] = mb_substr($current, 0, $width, $charset);
	        $current = mb_substr($current, $width, 2048, $charset);
	      }
	      $result[] = $current;
	    }
	    return implode($break, $result);
	}
	
}