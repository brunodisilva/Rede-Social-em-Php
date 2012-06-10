<?
$page = "admin_userpoints_viewusers_edit";
include "admin_header.php";

$s = semods::getpost('s', "vcd");   // sort default by vote count desc
$p = semods::getpost('p', 1);
$f_user = semods::getpost('f_user', "");
$f_email = semods::getpost('f_email', "");
$f_level = semods::getpost('f_level', "");
$f_subnet = semods::getpost('f_subnet', "");
$f_enabled = semods::getpost('f_enabled', "");
$task = semods::getpost('task', "main");
$user_id = semods::getpost('user_id', 0);

// VALIDATE USER ID OR RETURN TO VIEW USERS
$user = new se_user(Array($user_id));
if($user->user_exists == 0) { header("Location: admin_userpoints_viewusers.php?s=$s&p=$p&f_user=$f_user&f_email=$f_email&f_level=$f_level&f_enabled=$f_enabled"); exit(); }


// INITIALIZE ERROR VARS
$error_message = "";
$result = "";



// CANCEL
if($task == "cancel") {
  header("Location: admin_userpoints_viewusers.php?s=$s&p=$p&f_user=$f_user&f_email=$f_email&f_level=$f_level&f_enabled=$f_enabled");
  exit();

// EDIT USER
} elseif($task == "edituser") {

  // GET POST VARIABLES
  $user_points = intval( semods::post('user_points', 0) );
  $user_points_enabled = semods::post('user_points_enabled', 0);
  $points_totalearned = semods::post('points_totalearned', 0);
  

  // SAVE CHANGES IF NO ERROR
  // EDIT USER AND RETURN TO VIEW USERS
  $database->database_query("INSERT INTO se_semods_userpoints (userpoints_user_id, userpoints_count, userpoints_totalearned) VALUES ( {$user->user_info[user_id]}, $user_points, $points_totalearned ) ON DUPLICATE KEY UPDATE userpoints_count = $user_points, userpoints_totalearned = $points_totalearned");
  
  // user_points_enabled
  $database->database_query("UPDATE se_users SET user_userpoints_allowed = $user_points_enabled WHERE user_id = {$user->user_info['user_id']} ");
  header("Location: admin_userpoints_viewusers.php?s=$s&p=$p&f_user=$f_user&f_email=$f_email&f_level=$f_level&f_enabled=$f_enabled");
  exit();

}

$userpoints = semods::db_query_assoc( "SELECT userpoints_count, userpoints_totalearned, userpoints_totalspent FROM se_semods_userpoints WHERE userpoints_user_id = $user_id" );
if($userpoints) {
  $points = $userpoints['userpoints_count'];
  $points_totalearned = $userpoints['userpoints_totalearned'];
  $points_totalspent = $userpoints['userpoints_totalspent'];
} else {
  $points = 0;
  $points_totalearned = 0;
  $points_totalspent = 0;
}

// ASSIGN VARIABLES AND SHOW EDIT USERS PAGE
$smarty->assign('error_message', $error_message);
$smarty->assign('result', $result);
$smarty->assign('user_id', $user_id);
$smarty->assign('user_username', $setting['setting_username'] ? $user->user_info['user_username'] : $user->user_displayname );

$smarty->assign('points', $points);
$smarty->assign('points_totalearned', $points_totalearned);
$smarty->assign('points_totalspent', $points_totalspent);

$smarty->assign('user_points_enabled', $user->user_info['user_userpoints_allowed'] );

$smarty->assign('levels', $level_array);
$smarty->assign('s', $s);
$smarty->assign('p', $p);
$smarty->assign('f_user', $f_user);
$smarty->assign('f_email', $f_email);
$smarty->assign('f_level', $f_level);
$smarty->assign('f_enabled', $f_enabled);
include "admin_footer.php";
?>