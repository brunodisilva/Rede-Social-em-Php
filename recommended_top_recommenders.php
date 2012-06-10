<?php
$page = "recommended_top_recommenders";
include "header.php";

$task = rc_toolkit::get_request('task','main');
$p = rc_toolkit::get_request('p',1);

if($user->user_exists == 0 & $setting[setting_permission_recommended] == 0) {
  $page = "error";
  $smarty->assign('error_header', 11140901);
  $smarty->assign('error_message', 11140902);
  $smarty->assign('error_submit', 11140903);
  include "footer.php";
}

$rc_recommededvote = new rc_recommendedvote();
$total_entries = $rc_recommededvote->count_user_recommenders();

$rc_pager = new rc_pager($p, 25, $total_entries);
$recommendedvote_users = $rc_recommededvote->get_user_recommenders($rc_pager->offset,$rc_pager->page_size);

$smarty->assign('recommendedvote_users', $recommendedvote_users);

$rc_pager->assign_smarty_vars(count($recommendedvote_users));
//rc_toolkit::debug($rc_pager);
include "footer.php";
