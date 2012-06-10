<?
$page = "user_winks_settings";
include "header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }

// SET VARS
$result = 0;

// SAVE NEW SETTINGS
if($task == "dosave") {
  $privacy_winks = $_POST['privacy_winks'];
  $usersetting_notify_winks = $_POST['usersetting_notify_winks'];

  // UPDATE DATABASE
  $database->database_query("UPDATE se_users SET user_privacy_winks='$privacy_winks' WHERE user_id='".$user->user_info[user_id]."'");
  $database->database_query("UPDATE se_usersettings SET usersetting_notify_winks='$usersetting_notify_winks' WHERE usersetting_user_id='".$user->user_info[user_id]."'");
  $user->user_lastupdate();
  $user = new se_user(Array($user->user_info[user_id]));
  $result = 1;
}

// ENSURE WINKS ARE ENABLED
//if(winks_enabled() == 0) { header("Location: ".$url->url_create('profile', $user->user_info[user_username])); exit(); }

// ENSURE WINKS ARE ENABLED FOR THIS USER
//if($user->level_info[level_winks_allow] == 0) { header("Location: ".$url->url_create('profile', $user->user_info[user_username])); exit(); }

// DISPLAY ERROR PAGE IF USER IS NOT ALLOWED TO WINK
if( !$user->level_info['level_winks_allow'] )
{
  $page = "error";
  $smarty->assign('error_header', $winks[1]);
  $smarty->assign('error_message', $winks[4]);
  $smarty->assign('error_submit', $winks[3]);
  include "footer.php";
}
// GET AVAILABLE PRIVACY OPTIONS
$privacy_count = 0;
$privacy_winks_options = Array();
for($p=0;$p<strlen($user->level_info[level_winks_privacy]);$p++) {
  $privacy_level = substr($user->level_info[level_winks_privacy], $p, 1);
  if(winks_privacy_levels($privacy_level) != "") {
    $privacy_winks_options[$privacy_count] = Array('privacy_id' => "privacy_winks".$privacy_level,
					    	     'privacy_value' => $privacy_level,
					   	     'privacy_option' => winks_privacy_levels($privacy_level));
    $privacy_count++;
  }
}

// ASSIGN USER SETTINGS
$user->user_settings();

// ASSIGN SMARTY VARIABLES AND INCLUDE FOOTER
$smarty->assign('result', $result);
$smarty->assign('privacy_winks_options', $privacy_winks_options);
$smarty->assign('privacy_winks', true_privacy($user->user_info[user_privacy_winks], $user->level_info[level_winks_privacy]));
include "footer.php";
?>