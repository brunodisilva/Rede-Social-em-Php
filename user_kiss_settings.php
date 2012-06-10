<?
$page = "user_kiss_settings";
include "header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }

// SET VARS
$result = 0;

// SAVE NEW SETTINGS
if($task == "dosave") {
  $privacy_kiss = $_POST['privacy_kiss'];
  $usersetting_notify_kiss = $_POST['usersetting_notify_kiss'];

  // UPDATE DATABASE
  $database->database_query("UPDATE se_users SET user_privacy_kiss='$privacy_kiss' WHERE user_id='".$user->user_info[user_id]."'");
  $database->database_query("UPDATE se_usersettings SET usersetting_notify_kiss='$usersetting_notify_kiss' WHERE usersetting_user_id='".$user->user_info[user_id]."'");
  $user->user_lastupdate();
  $user = new se_user(Array($user->user_info[user_id]));
  $result = 1;
}

// ENSURE kiss ARE ENABLED
//if(kiss_enabled() == 0) { header("Location: ".$url->url_create('profile', $user->user_info[user_username])); exit(); }

// ENSURE kiss ARE ENABLED FOR THIS USER
//if($user->level_info[level_kiss_allow] == 0) { header("Location: ".$url->url_create('profile', $user->user_info[user_username])); exit(); }

// DISPLAY ERROR PAGE IF USER IS NOT ALLOWED TO kiss
if( !$user->level_info['level_kiss_allow'] )
{
  $page = "error";
  $smarty->assign('error_header', $kiss[1]);
  $smarty->assign('error_message', $kiss[4]);
  $smarty->assign('error_submit', $kiss[3]);
  include "footer.php";
}
// GET AVAILABLE PRIVACY OPTIONS
$privacy_count = 0;
$privacy_kiss_options = Array();
for($p=0;$p<strlen($user->level_info[level_kiss_privacy]);$p++) {
  $privacy_level = substr($user->level_info[level_kiss_privacy], $p, 1);
  if(kiss_privacy_levels($privacy_level) != "") {
    $privacy_kiss_options[$privacy_count] = Array('privacy_id' => "privacy_kiss".$privacy_level,
					    	     'privacy_value' => $privacy_level,
					   	     'privacy_option' => kiss_privacy_levels($privacy_level));
    $privacy_count++;
  }
}

// ASSIGN USER SETTINGS
$user->user_settings();

// ASSIGN SMARTY VARIABLES AND INCLUDE FOOTER
$smarty->assign('result', $result);
$smarty->assign('privacy_kiss_options', $privacy_kiss_options);
$smarty->assign('privacy_kiss', true_privacy($user->user_info[user_privacy_kiss], $user->level_info[level_kiss_privacy]));
include "footer.php";
?>