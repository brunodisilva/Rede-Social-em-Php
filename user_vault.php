<?
$page = "user_vault";
include "header.php";

// ENSURE POINTS ARE ENABLED FOR THIS USER
if(($user->level_info['level_userpoints_allow'] == 0) || ($user->user_info['user_userpoints_allowed'] == 0)){ header("Location: user_home.php"); exit(); }

$task = semods::getpost('task', "main");

$is_error = 0;
$result = 0;

if($task == "sendpoints") {

  $points_recipient_id = semods::getpost('points_recipient_id');
  $points_amount = intval(semods::getpost('points_amount'));
  
  $result = userpoints_transfer_points( $user, $points_recipient_id, $points_amount );

  $response = array();

  if($result['is_error'] == 1) {
    $response['status'] = 1;
    $response['msg'] = $result['message'];
  }
  else {
    $response['status'] = 0;
    $response['msg'] = $result['message'];
    $response['balance'] = userpoints_get_points( $user->user_info['user_id'] );
  }
    
  $response['msg'] = semods::get_language_text( $result['message'] );
    
  echo json_encode( $response );
  exit;
  
}

$points_all = userpoints_get_all($user->user_info['user_id']);
if($points_all) {
  $user_points = $points_all['userpoints_count'];
  $user_points_totalearned = $points_all['userpoints_totalearned'];
} else {
  $user_points = 0;
  $user_points_totalearned = 0;
}

$user_rank = userpoints_get_rank($user->user_info['user_id']);

$userpoints_enable_topusers = semods::get_setting('userpoints_enable_topusers');
$smarty->assign('userpoints_enable_topusers', $userpoints_enable_topusers);

// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('result', $result);
$smarty->assign('error_message', $error_message);
$smarty->assign('user_rank', $user_rank);
$smarty->assign('user_points', $user_points);
$smarty->assign('user_points_totalearned', $user_points_totalearned);
$smarty->assign('semods_settings', semods::get_settings());
include "footer.php";
?>