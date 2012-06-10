<?php
$page = "user_recommended_edit";
include "header.php";

$task = rc_toolkit::get_request('task','main');
$rc_validator = new rc_validator();

$result = "";

if ($owner->user_exists == 0 || $user->level_info[level_recommended_allow] == 0 || $user->user_info[user_id] == $owner->user_info[user_id]) {
  rc_toolkit::redirect('user_home.php');
}

$rc_recommendedvote = new rc_recommendedvote();
$recommendedvote = $rc_recommendedvote->get_vote($user->user_info['user_id'], $owner->user_info['user_id']);

if ($recommendedvote) {
  
  $owner->user_displayname();
  $actions->actions_add($user, "recommendednovote", Array($user->user_info[user_username], $user->user_displayname, $owner->user_info[user_username], $owner->user_displayname), Array(), 0, FALSE, "user", $user->user_info[user_id]);
      
  
	$recommendedvote->delete();
}

$redirect_url = "recommended_recommendees.php?user=".$user->user_info['user_username'];
rc_toolkit::redirect($redirect_url);
