<?php
$page = "user_recommended_add";
include "header.php";

$task = rc_toolkit::get_request('task','main');
$rc_validator = new rc_validator();

if ($owner->user_exists == 0 || $user->level_info[level_recommended_allow] == 0 || $user->user_info[user_id] == $owner->user_info[user_id]) {
  rc_toolkit::redirect('user_home.php');
}

$profile_url = $url->url_create('profile', $owner->user_info['user_username']);

$rc_recommendedvote = new rc_recommendedvote();
$has_recommended = $rc_recommendedvote->has_vote($user->user_info['user_id'], $owner->user_info['user_id']);


if ($has_recommended) {
  rc_toolkit::redirect($profile_url);
}

if ($task == 'dosave') {
  
  $comment = censor($_POST['recommended_comment']);
  $rc_validator->is_not_trimmed_blank($comment, 11141009);
  
  if (!$rc_validator->has_errors()) {
    $rc_recommendedvote->register_vote($owner->user_info['user_id'], $user->user_info['user_id'], $comment);
    
    $comment_short = $comment;
    if(strlen($comment_short) > 100) { $comment_short = substr($comment_short, 0, 97); $comment_short .= "..."; }
    $comment_short = nl2br($comment_short);
    $owner->user_displayname();
    $actions->actions_add($user, "recommendedvote", Array($user->user_info[user_username], $user->user_displayname, $owner->user_info[user_username], $owner->user_displayname, $comment_short), Array(), 0, FALSE, "user", $user->user_info[user_id]);
    
    $owner->user_settings();
    if($owner->usersetting_info[usersetting_notify_recommendedcomment] == 1) { 
      send_generic($owner->user_info[user_email], "$setting[setting_email_fromname] <$setting[setting_email_fromemail]>", $setting[setting_email_recommendedcomment_subject], $setting[setting_email_recommendedcomment_message], Array('[username]', '[commenter]', '[link]'), Array($owner->user_displayname, $user->user_displayname, "<a href=\"".$url->url_create("profile", $owner->user_info[user_username])."\">".$url->url_create("profile", $owner->user_info[user_username])."</a>")); 
    }    
    
    $redirect_url = "user_recommended_edit.php?user={$owner->user_info['user_username']}&added=1";
    rc_toolkit::redirect($redirect_url);
  }
}

$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
include "footer.php";