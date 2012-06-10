<?php
$page = "recommended_recommendees";
include "header.php";

$task = rc_toolkit::get_request('task','main');
$p = rc_toolkit::get_request('p',1);

if($user->user_exists == 0 & $setting[setting_permission_recommended] == 0) {
  $page = "error";
  $smarty->assign('error_header', 11140611);
  $smarty->assign('error_message', 11140612);
  $smarty->assign('error_submit', 11140613);
  include "footer.php";
}

if ($owner->user_exists == 0) {
  rc_toolkit::redirect('user_home.php');
}

$rc_recommededvote = new rc_recommendedvote();

$owner_user_id = $owner->user_info['user_id']; 

$total_entries = $rc_recommededvote->count_recommendees($owner_user_id);
$rc_pager = new rc_pager($p, 10, $total_entries);
$recommended_recommendees = $rc_recommededvote->get_recommendees($owner_user_id,$rc_pager->offset,$rc_pager->page_size);

$smarty->assign('recommended_recommendees', $recommended_recommendees);

$rc_pager->assign_smarty_vars(count($recommended_recommendees));

include "footer.php";
