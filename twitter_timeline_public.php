<?php
$page = "twitter_timeline_public";
include "header.php";
$smarty->assign('_active_tab', 'timeline_public');

if(!$SEP_TwitterUser->account_verify_credentials() && empty($setting['setting_twitter_show_in_main_menu'])) {
	header('Location: home.php');
	exit;	
}

// check for search strings
$q = !empty($_GET['q']) ? strip_tags($_GET['q']) : null;
$smarty->assign('twitter_q', htmlspecialchars($q));


// enable caching
$smarty->caching = 2;

// get random members with twitter account
// ***************************************
if(!$smarty->is_cached('twitter__socialengine_users.tpl', $user->user_info['user_id'].'|random_members|1')) {

	// join friends with twitter_accounts
	$max_members = $SEP_Twitter_Config['max_twitter_randon_members'];
	$cache_members = $SEP_Twitter_Config['cache_lifetime_twitter_randon_members']; // minutes
	
	$users = array();
	$users_query = $database->database_query("SELECT u.user_id, u.user_username, u.user_fname, u.user_lname, u.user_photo FROM se_users u INNER JOIN se_twitter_accounts t ON t.user_id=u.user_id WHERE 1=1 ORDER BY RAND() LIMIT $max_members");
	while($users_result = $database->database_fetch_assoc($users_query)) {	
	    $_user = new se_user();
	    $_user->user_info['user_id'] = $users_result['user_id'];
	    $_user->user_info['user_username'] = $users_result['user_username'];
	    $_user->user_info['user_fname'] = $users_result['user_fname'];
	    $_user->user_info['user_lname'] = $users_result['user_lname'];
	    $_user->user_info['user_photo'] = $users_result['user_photo'];
	    $_user->user_displayname();	
		$users[] = $_user;
		unset($_user);	
	}
	$smarty->assign('users', $users);
	$smarty->assign_by_ref('url', $url);
	$smarty->cache_lifetime = 60 * $cache_members;
}
$smarty->assign('random_members', $smarty->fetch('twitter__socialengine_users.tpl', $user->user_info['user_id'].'|random_members|1'));

// disable caching again
$smarty->caching = 0;


include "footer.php";
?>