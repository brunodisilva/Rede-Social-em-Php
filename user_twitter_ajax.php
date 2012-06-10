<?php
$page = "user_twitter_ajax";
include "header.php";

// set caching lifetime for ajax loaded pages
$cache_lifetime_for_pages = $SEP_Twitter_Config['cache_lifetime_for_ajax_loaded_pages']; // minutes
$cache_start_from_page = 2; // page (page 1 is cached, too) -> you should not change this!


// ************************************************************************


$_ajaxReq = !empty($_POST['_ajaxReq']) ? $_POST['_ajaxReq'] : null;
$task = !empty($_POST['task']) ? $_POST['task'] : null;

if(!empty($_ajaxReq)) {
	// manage ajax request

	if($task == 'friendships_create' && !empty($_POST['screen_name'])) {
		if($SEP_TwitterUser->friendships_create(htmlspecialchars($_POST['screen_name']))) {
			$SEP_TwitterUser->clear_cache($user->user_info['user_id']);
			echo '<img src="./images/icons/twitter_okay.gif" border="0" class="icon"> '.SE_Language::_get(18910030);
			exit;
		}
	}
	elseif($task == 'friendships_destroy' && !empty($_POST['screen_name'])) {
		if($SEP_TwitterUser->friendships_destroy(htmlspecialchars($_POST['screen_name']))) {
			$SEP_TwitterUser->clear_cache($user->user_info['user_id']);
			echo '<img src="./images/icons/twitter_okay.gif" border="0" class="icon"> '.SE_Language::_get(18910031);
			exit;
		}
	}
	elseif($task == 'favorites_create' && !empty($_POST['id'])) {
		if($SEP_TwitterUser->favorites_create(htmlspecialchars($_POST['id']))) {
			$SEP_TwitterUser->clear_cache($user->user_info['user_id']);
			echo 'true';
			exit;
		}
	}
	elseif($task == 'favorites_destroy' && !empty($_POST['id'])) {
		if($SEP_TwitterUser->favorites_destroy(htmlspecialchars($_POST['id']))) {
			$SEP_TwitterUser->clear_cache($user->user_info['user_id']);
			echo 'true';
			exit;
		}
	}
	elseif($task == 'statuses_destroy' && !empty($_POST['id'])) {
		if($SEP_TwitterUser->statuses_destroy(htmlspecialchars($_POST['id']))) {
			$SEP_TwitterUser->clear_cache($user->user_info['user_id']);
			echo 'true';
			exit;
		}
	}
	elseif($task == 'statuses_update' && !empty($_POST['text'])) {
		if($SEP_TwitterUser->statuses_update(htmlspecialchars($_POST['text']), !empty($_POST['in_reply_to_status_id']) ? $_POST['in_reply_to_status_id']*1 : null, $SEP_Twitter_Config['twitter2status'])) {
			$SEP_TwitterUser->clear_cache($user->user_info['user_id']);
			echo 'true';
			exit;
		}
	}	
	elseif($task == 'next_page') {

		$tpl = !empty($_POST['tpl']) ? strip_tags(htmlspecialchars($_POST['tpl'])) : null;
		$page = !empty($_POST['page']) ? $_POST['page']*1 : 1;
		$instance_name = !empty($_POST['instance_name']) ? strip_tags(htmlspecialchars($_POST['instance_name'])) : null;
		$twitter_q = !empty($_POST['twitter_q']) ? strip_tags(htmlspecialchars($_POST['twitter_q'])) : null;		
		
		if(in_array($tpl, array('statuses_user_timeline', 'statuses_friends_timeline', 'favorites', 'statuses_friends', 'statuses_followers',
								'profile_statuses_user_timeline', 'profile_statuses_friends', 'profile_statuses_followers', 'search'))) {

			$smarty->assign($tpl.'_page', $page);
			$smarty->assign('instance_name', $instance_name);
			$smarty->assign('twitter_q', $twitter_q);
			
			
			$smarty->assign_by_ref('owner', $owner);
			
			if(!empty($_POST['user_id'])) {
				// owner (from profile)
				$cache_id = (strip_tags($_POST['user_id'])*1).'|'.$tpl.'|'.$page;
			}
			else {
				// user
				$cache_id = $user->user_info['user_id'].'|'.$tpl.'|'.$page;
			}
			
			
			if($page >= $cache_start_from_page) {
				$smarty->caching = 2;
				$smarty->cache_lifetime = $cache_lifetime_for_pages * 60; // convert minutes to seconds
				if(!$smarty->is_cached('twitter__'.$tpl.'.tpl', $cache_id)) { /* dummy */ }
				$smarty->display('twitter__'.$tpl.'.tpl', $cache_id);
			}
			else {
				$smarty->display('twitter__'.$tpl.'.tpl');
			}
			
			if($page >= $cache_start_from_page) {
				$smarty->caching = 0;
			}
			
			exit;
		}
	}	
		
}

header("HTTP/1.1 500 Internal Server Error");
exit;
?>