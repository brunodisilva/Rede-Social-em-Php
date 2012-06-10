<?php
$page = "recommended_recommenders";
include "header.php";

$task = rc_toolkit::get_request('task','main');
$p = rc_toolkit::get_request('p',1);

if($user->user_exists == 0 & $setting[setting_permission_recommended] == 0) {
  $page = "error";
  $smarty->assign('error_header', 11140711);
  $smarty->assign('error_message', 11140712);
  $smarty->assign('error_submit', 11140713);
  include "footer.php";
}

if ($owner->user_exists == 0) {
  rc_toolkit::redirect('user_home.php');
}

$rc_recommededvote = new rc_recommendedvote();

$owner_user_id = $owner->user_info['user_id']; 

$total_entries = $rc_recommededvote->count_recommenders($owner_user_id);
$rc_pager = new rc_pager($p, 10, $total_entries);
$recommended_recommenders = $rc_recommededvote->get_recommenders($owner_user_id,$rc_pager->offset,$rc_pager->page_size);

$smarty->assign('recommended_recommenders', $recommended_recommenders);

$rc_pager->assign_smarty_vars(count($recommended_recommenders));

include "footer.php";
