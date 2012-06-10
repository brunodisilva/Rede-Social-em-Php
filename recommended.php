<?php
$page = "recommended";
include "header.php";

$task = rc_toolkit::get_request('task','main');
$rc_validator = new rc_validator();

if ($user->user_exists == 0 || $owner->user_exists == 0 || $user->level_info[level_recommended_allow] == 0) {
  rc_toolkit::redirect('user_home.php');
}

$profile_url = $url->url_create('profile', $owner->user_info['user_username']);

$rc_recommededvote = new rc_recommendedvote();

$has_recommended = $rc_recommededvote->has_vote($user->user_info['user_id'], $owner->user_info['user_id']);


if ($task == 'unrecommend') {
  if ($has_recommended) {
    $rc_recommededvote->unregister_vote($owner->user_info['user_id'], $user->user_info['user_id']);
    $actions->actions_add($user, "recommendednovote", Array('[recommender]', '[recommendee]'), array($user->user_info[user_username], $owner->user_info[user_username]));
  }
  rc_toolkit::redirect($profile_url);
}

if ($has_recommended) {
  rc_toolkit::redirect($profile_url);
}

if ($task == 'recommend') {
  
  $comment = censor($_POST['recommended_comment']);
  $rc_validator->is_not_trimmed_blank($comment, 11140509);
  
  if (!$rc_validator->has_errors()) {
    $rc_recommededvote->register_vote($owner->user_info['user_id'], $user->user_info['user_id'], $comment);
    $actions->actions_add($user, "recommendedvote", Array('[recommender]', '[recommendee]'), array($user->user_info[user_username], $owner->user_info[user_username]));
    
    rc_toolkit::redirect($profile_url);
  }
}

$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
$smarty->assign('recommended', $recommended);
include "footer.php";