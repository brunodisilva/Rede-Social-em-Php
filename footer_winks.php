<?

switch($page) {

// CODE FOR PROFILE PAGE
  case "profile":
		
		//ENSURE WINKS ARE ENABLED
		$winks_enabled = winks_enabled();
				
		//VARS
		$user_wink = new se_winks();
		$owner_wink = new se_winks();
		
		//HANDLE PENDING WINKS
		if($owner_wink->user_winked($user->user_info[user_id], $owner->user_info[user_id],0)) {
		$wink_pending = 1;
		}
		if($owner_wink->user_winked_incoming($user->user_info[user_id], $owner->user_info[user_id],0)) {
		$wink_pending = 1;
		}
		
		// SHOW LINK IF USER IS ONLINE
		$online_users_array = online_users();
		if(in_array($user->user_info[user_username], $online_users_array[2])) { $is_online_winks = 1; } else { $is_online_winks = 0; }
		
		
		// WINKS PRIVACY
		$privacy_owner = $owner->user_info[user_username];
		$privacy_query = $database->database_query("SELECT user_privacy_winks FROM se_users WHERE user_username = '$privacy_owner'");
		$winks_privacy_array = Array();

		while($item = $database->database_fetch_assoc($privacy_query)) {
		$user_winks_privacy = $item[user_privacy_winks];
		}
		
		//CHECK IF FRIENDS
		$is_friend_winks = $user->user_friended($owner->user_info[user_id]);
		if($is_friend_winks) { $winks_allowed = 1; }
				
		// ASSIGN VARIABLES
		$smarty->assign('winks_enabled', $winks_enabled);
		$smarty->assign('wink_pending', $wink_pending);
		$smarty->assign('wink_pending', $wink_pending);
		$smarty->assign('is_online_winks', $is_online_winks);
		$smarty->assign('user_winks_privacy', $user_winks_privacy);
		$smarty->assign('is_friend_winks', $is_friend_winks);
		$smarty->assign('winks_alowed', $winks_allowed);

// CODE FOR USER_HOME PAGE	
	case "user_home":
		
		//VARS
		$user_wink = new se_winks();
		
		//GET INCOMING WINKS
		$total_wink_requests = $user_wink->wink_total_incoming($user->user_info[user_id]);
		$winks_per_page=5;
		$page_vars=make_page($total_winks,$winks_per_page,$p);
		$winksreq = $user_wink->user_wink_list_incoming($user->user_info[user_id],$page_vars[0], $winks_per_page);
		
		//HANDLE IF MORE OR LESS THAN 0 WINKS
		if ($total_wink_requests == 1){$wink_count = "";};
		if ($total_wink_requests > 1){$wink_count = "s";};
		
		// ASSIGN VARIABLES
		$smarty->assign('wink_count',$wink_count);
		$smarty->assign('winksreq',$winksreq);
		$smarty->assign('total_wink_requests', $total_wink_requests);


}
?>