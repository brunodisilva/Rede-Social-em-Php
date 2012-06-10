<?

switch($page) {

// CODE FOR PROFILE PAGE
  case "profile":
		
		//ENSURE kiss ARE ENABLED
		$kiss_enabled = kiss_enabled();
				
		//VARS
		$user_kiss = new se_kiss();
		$owner_kiss = new se_kiss();
		
		//HANDLE PENDING kiss
		if($owner_kiss->user_kissed($user->user_info[user_id], $owner->user_info[user_id],0)) {
		$kiss_pending = 1;
		}
		if($owner_kiss->user_kissed_incoming($user->user_info[user_id], $owner->user_info[user_id],0)) {
		$kiss_pending = 1;
		}
		
		// SHOW LINK IF USER IS ONLINE
		$online_users_array = online_users();
		if(in_array($user->user_info[user_username], $online_users_array[2])) { $is_online_kiss = 1; } else { $is_online_kiss = 0; }
		
		
		// kiss PRIVACY
		$privacy_owner = $owner->user_info[user_username];
		$privacy_query = $database->database_query("SELECT user_privacy_kiss FROM se_users WHERE user_username = '$privacy_owner'");
		$kiss_privacy_array = Array();

		while($item = $database->database_fetch_assoc($privacy_query)) {
		$user_kiss_privacy = $item[user_privacy_kiss];
		}
		
		//CHECK IF FRIENDS
		$is_friend_kiss = $user->user_friended($owner->user_info[user_id]);
		if($is_friend_kiss) { $kiss_allowed = 1; }
				
		// ASSIGN VARIABLES
		$smarty->assign('kiss_enabled', $kiss_enabled);
		$smarty->assign('kiss_pending', $kiss_pending);
		$smarty->assign('kiss_pending', $kiss_pending);
		$smarty->assign('is_online_kiss', $is_online_kiss);
		$smarty->assign('user_kiss_privacy', $user_kiss_privacy);
		$smarty->assign('is_friend_kiss', $is_friend_kiss);
		$smarty->assign('kiss_alowed', $kiss_allowed);

// CODE FOR USER_HOME PAGE	
	case "user_home":
		
		//VARS
		$user_kiss = new se_kiss();
		
		//GET INCOMING kiss
		$total_kiss_requests = $user_kiss->kiss_total_incoming($user->user_info[user_id]);
		$kiss_per_page=5;
		$page_vars=make_page($total_kiss,$kiss_per_page,$p);
		$kissreq = $user_kiss->user_kiss_list_incoming($user->user_info[user_id],$page_vars[0], $kiss_per_page);
		
		//HANDLE IF MORE OR LESS THAN 0 kiss
		if ($total_kiss_requests == 1){$kiss_count = "";};
		if ($total_kiss_requests > 1){$kiss_count = "s";};
		
		// ASSIGN VARIABLES
		$smarty->assign('kiss_count',$kiss_count);
		$smarty->assign('kissreq',$kissreq);
		$smarty->assign('total_kiss_requests', $total_kiss_requests);


}
?>