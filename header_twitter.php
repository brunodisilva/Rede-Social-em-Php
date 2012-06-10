<?

error_reporting(E_USER_ERROR & E_USER_WARNING & E_USER_NOTICE); 
ini_set('display_errors', 0); // set to 1 to debug
ini_set('log_errors', 1);

require_once(getcwd().'/include/class_twitter.php');


// INTERNAL CONFIGURATION
$SEP_Twitter_Config['cache_lifetime_for_ajax_loaded_pages'] = 7; // minutes
$SEP_Twitter_Config['cache_lifetime_twitter_friends'] = 120; // minutes
$SEP_Twitter_Config['cache_lifetime_twitter_randon_members'] = 60; // minutes
$SEP_Twitter_Config['cache_lifetime_statuses_friends_timeline'] = 3; // minutes
$SEP_Twitter_Config['cache_lifetime_statuses_user_timeline'] = 3; // minutes
$SEP_Twitter_Config['cache_lifetime_favorites'] = 10; // minutes
$SEP_Twitter_Config['cache_lifetime_statuses_friends'] = 2; // minutes
$SEP_Twitter_Config['cache_lifetime_statuses_followers'] = 5; // minutes
$SEP_Twitter_Config['cache_lifetime_profile_statuses_owner_timeline'] = $SEP_Twitter_Config['cache_lifetime_statuses_user_timeline']; // minutes
$SEP_Twitter_Config['cache_lifetime_profile_statuses_owner_friends'] = $SEP_Twitter_Config['cache_lifetime_statuses_friends']; // minutes
$SEP_Twitter_Config['cache_lifetime_profile_statuses_owner_followers'] = $SEP_Twitter_Config['cache_lifetime_statuses_followers']; // minutes
$SEP_Twitter_Config['cache_lifetime_profile_statuses_guest_timeline'] = 15; // minutes
$SEP_Twitter_Config['cache_lifetime_profile_statuses_guest_friends'] = 30; // minutes
$SEP_Twitter_Config['cache_lifetime_profile_statuses_guest_followers'] = 30; // minutes
$SEP_Twitter_Config['cache_lifetime_trends'] = 10; // minutes
$SEP_Twitter_Config['cache_lifetime_statuses_public_timeline'] = 1; // minutes
$SEP_Twitter_Config['cache_lifetime_search'] = 10; // minutes


$SEP_Twitter_Config['max_twitter_friends'] = 24; // members
$SEP_Twitter_Config['max_twitter_randon_members'] = 24; // members

$SEP_Twitter_Config['force_wordwrap_after_characters'] = 30; // insert space if a word is longer than xx characters (otherwise layout could break)

// OAuth Config
$SEP_Twitter_Config['TWITTER_CONSUMER_KEY'] = $setting['setting_twitter_oauth_consumer_key'];
$SEP_Twitter_Config['TWITTER_CONSUMER_SECRET'] = $setting['setting_twitter_oauth_consumer_secret'];

if($setting['setting_twitter_authentication'] == 'oauth') {
	// include libs for oauth
	require_once(getcwd().'/include/twitter_libs/EpiCurl.php');
	require_once(getcwd().'/include/twitter_libs/EpiOAuth.php');
}

// update status settings
$SEP_Twitter_Config['twitter2status'] = true;	// update status if twitter tweet is sent
$SEP_Twitter_Config['status2twitter'] = true;	// update twitter if community status is updated (140 character limit) // requires SEP_Twitter_Status2Twitter() call in misc_js.php (task == status_change)

// assign config to smarty
$smarty->assign('SEP_Twitter_Config', $SEP_Twitter_Config);



// Set Object For User (over all pages)
$SEP_TwitterUser = new SEP_Twitter();
$smarty->assign_by_ref('SEP_TwitterUser', $SEP_TwitterUser);
$SEP_TwitterUser->account($user->user_info['user_id']);

$plugin_vars['menu_user'] = Array('file' => 'user_twitter_timeline_friends.php', 'icon' => 'twitter.gif', 'title' => 18910018);
if($setting['setting_twitter_show_in_main_menu']) {
	$plugin_vars['menu_main'] = array('file' => 'twitter_timeline_public.php', 'title' => 18910017);
}


if($page == 'profile' || $page == 'user_twitter_ajax') {
	
	// Set Object For Owner (of profile)
	$SEP_TwitterOwner = new SEP_Twitter();
	$smarty->assign_by_ref('SEP_TwitterOwner', $SEP_TwitterOwner);

	if($SEP_TwitterOwner->account($owner->user_info['user_id']) || $user->user_info['user_id'] == $owner->user_info['user_id']) {
		// show tab/ side tab if: profile owner has got twitter account or user is profile owner	
		$plugin_vars['menu_profile_tab'] = array('file'=> 'profile_twitter_tab.tpl', 'title' => 18910019);
		$plugin_vars['menu_profile_side'] = Array('file'=> 'profile_twitter_side.tpl', 'title' => 18910020);
	}
	
}


// Smarty Include Method To Allow Cached Includes
function SEP_Twitter_Include_Cached($params, &$smarty) {
	$smarty->caching = 2;
	$smarty->cache_lifetime = 60 * (!empty($params['cache_lifetime']) ? $params['cache_lifetime']*1 : 5);
	
	if(!empty($params['user_id']) && !empty($params['search_hash'])) {
		$cache_id = $params['user_id'].'|'.$params['file'].'|'.trim(sha1($params['search_hash'])).'|'.(!empty($params['page']) ? $params['page']*1 : 1);	
	}
	elseif(!empty($params['user_id'])) {
		$cache_id = $params['user_id'].'|'.$params['file'].'|'.(!empty($params['page']) ? $params['page']*1 : 1);
	}
	elseif(!empty($params['search_hash'])) {
		$cache_id = 'search|'.trim(sha1($params['search_hash'])).'|'.$params['file'].'|'.(!empty($params['page']) ? $params['page']*1 : 1);		
	}
	else {
		$cache_id = 'twitter|'.$params['file'].'|'.(!empty($params['page']) ? $params['page']*1 : 1);
	}
	
	if(!$smarty->is_cached($params['file'], $cache_id)) { 
		foreach($params as $index => $value) {
			$smarty->assign($index, $value);	
		}	
	}
	$smarty->display($params['file'], $cache_id);
	$smarty->caching = 0;
}
$smarty->register_function('SEP_Twitter_Include_Cached', 'SEP_Twitter_Include_Cached', true);


// Update Twitter If CommunityStatus Is Updated
function SEP_Twitter_Status2Twitter($text) {
	global $SEP_Twitter_Config, $SEP_TwitterUser;
	
	if($SEP_Twitter_Config['status2twitter'] && $SEP_TwitterUser->account_verify_credentials()) {
		if($SEP_TwitterUser->statuses_update($text, null, false)) {
			$SEP_TwitterUser->clear_cache($user->user_info['user_id']);
		}
	}
}

function SEP_Twitter_convert2html($string) {
	return htmlspecialchars_decode($string);
}
$smarty->register_modifier('SEP_Twitter_convert2html', 'SEP_Twitter_convert2html');



function SEP_Twitter_wordWrap($string) {
	global $SEP_Twitter_Config;
	if($SEP_Twitter_Config['force_wordwrap_after_characters'] > 0) {
		return SEP_Twitter::mb_wordwrap($string, $SEP_Twitter_Config['force_wordwrap_after_characters'], ' ', true);	
	}
	else {
		return $string;	
	}
}
$smarty->register_modifier('SEP_Twitter_wordWrap', 'SEP_Twitter_wordWrap');

?>