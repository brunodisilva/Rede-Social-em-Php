<?php
$page = "user_recommended_edit";
include "header.php";

$task = rc_toolkit::get_request('task','main');
$rc_validator = new rc_validator();

$result = "";

if ($owner->user_exists == 0 || $user->level_info[level_recommended_allow] == 0 || $user->user_info[user_id] == $owner->user_info[user_id]) {
  rc_toolkit::redirect('user_home.php');
}

$profile_url = $url->url_create('profile', $owner->user_info['user_username']);

$rc_recommendedvote = new rc_recommendedvote();
$recommendedvote = $rc_recommendedvote->get_vote($user->user_info['user_id'], $owner->user_info['user_id']);


if (!$recommendedvote) {
  rc_toolkit::redirect($profile_url);
}

if (rc_toolkit::get_request('added')) {
  $result = 11141113;
}

if ($task == 'dosave') {
  
  $recommendedvote->vote_comment = censor($_POST['recommended_comment']);
  $rc_validator->is_not_trimmed_blank($recommendedvote->vote_comment, 11141109);
  
  if (!$rc_validator->has_errors()) {
    $recommendedvote->save();
    $result = 11141110;
  }
}

//rc_toolkit::debug($recommendedvote);
$recommendedvote->vote_comment = html_entity_decode($recommendedvote->vote_comment);
$smarty->assign('recommendedvote', $recommendedvote);

$smarty->assign('result', $result);
$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
include "footer.php";