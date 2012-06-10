<?

switch($page) {

// CODE FOR PROFILE PAGE
  case "profile":
		
		//ENSURE beerS ARE ENABLED
		$beers_enabled = beers_enabled();
				
		//VARS
		$user_beer = new se_beers();
		$owner_beer = new se_beers();
		
		//HANDLE PENDING beerS
		if($owner_beer->user_beered($user->user_info[user_id], $owner->user_info[user_id],0)) {
		$beer_pending = 1;
		}
		if($owner_beer->user_beered_incoming($user->user_info[user_id], $owner->user_info[user_id],0)) {
		$beer_pending = 1;
		}
		
		// SHOW LINK IF USER IS ONLINE
		$online_users_array = online_users();
		if(in_array($user->user_info[user_username], $online_users_array[2])) { $is_online_beers = 1; } else { $is_online_beers = 0; }
		
		
		// beerS PRIVACY
		$privacy_owner = $owner->user_info[user_username];
		$privacy_query = $database->database_query("SELECT user_privacy_beers FROM se_users WHERE user_username = '$privacy_owner'");
		$beers_privacy_array = Array();

		while($item = $database->database_fetch_assoc($privacy_query)) {
		$user_beers_privacy = $item[user_privacy_beers];
		}
		
		//CHECK IF FRIENDS
		$is_friend_beers = $user->user_friended($owner->user_info[user_id]);
		if($is_friend_beers) { $beers_allowed = 1; }
				
		// ASSIGN VARIABLES
		$smarty->assign('beers_enabled', $beers_enabled);
		$smarty->assign('beer_pending', $beer_pending);
		$smarty->assign('beer_pending', $beer_pending);
		$smarty->assign('is_online_beers', $is_online_beers);
		$smarty->assign('user_beers_privacy', $user_beers_privacy);
		$smarty->assign('is_friend_beers', $is_friend_beers);
		$smarty->assign('beers_alowed', $beers_allowed);

// CODE FOR USER_HOME PAGE	
	case "user_home":
		
		//VARS
		$user_beer = new se_beers();
		
		//GET INCOMING beerS
		$total_beer_requests = $user_beer->beer_total_incoming($user->user_info[user_id]);
		$beers_per_page=5;
		$page_vars=make_page($total_beers,$beers_per_page,$p);
		$beersreq = $user_beer->user_beer_list_incoming($user->user_info[user_id],$page_vars[0], $beers_per_page);
		
		//HANDLE IF MORE OR LESS THAN 0 beerS
		if ($total_beer_requests == 1){$beer_count = "";};
		if ($total_beer_requests > 1){$beer_count = "s";};
		
		// ASSIGN VARIABLES
		$smarty->assign('beer_count',$beer_count);
		$smarty->assign('beersreq',$beersreq);
		$smarty->assign('total_beer_requests', $total_beer_requests);


}
?>