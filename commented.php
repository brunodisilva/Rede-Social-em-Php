<?php
$page = "commented";
include "header.php";

$task = rc_toolkit::get_request('task','main');
$p = rc_toolkit::get_request('p', 1);

// DISPLAY ERROR PAGE IF USER IS NOT LOGGED IN AND ADMIN SETTING REQUIRES REGISTRATION
if($user->user_exists == 0 & $setting[setting_permission_profile] == 0) {
  $page = "error";
  $smarty->assign('error_header', 11130420);
  $smarty->assign('error_message', 11130422);
  $smarty->assign('error_submit', 11130423);
  include "footer.php";
}

// DISPLAY ERROR PAGE IF NO OWNER
if($owner->user_exists == 0) {
  $page = "error";
  $smarty->assign('error_header', 11130420);
  $smarty->assign('error_message', 11130421);
  $smarty->assign('error_submit', 11130423);
  include "footer.php";
}

$rc_commented = new rc_commented();

$total_commented_users = $rc_commented->count_users_commented($owner->user_info['user_id']);

$users_per_page = 10;
$page_vars = make_page($total_commented_users, $users_per_page, $p);


$commented_users = $rc_commented->get_commented_users($owner->user_info['user_id'], $page_vars[0], $users_per_page);



// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('commented_users', $commented_users);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('total_commented_users', $total_commented_users);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($commented_users));

include "footer.php";