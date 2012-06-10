<?
$page = "user_beers_settings";
include "header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }

// SET VARS
$result = 0;

// SAVE NEW SETTINGS
if($task == "dosave") {
  $privacy_beers = $_POST['privacy_beers'];
  $usersetting_notify_beers = $_POST['usersetting_notify_beers'];

  // UPDATE DATABASE
  $database->database_query("UPDATE se_users SET user_privacy_beers='$privacy_beers' WHERE user_id='".$user->user_info[user_id]."'");
  $database->database_query("UPDATE se_usersettings SET usersetting_notify_beers='$usersetting_notify_beers' WHERE usersetting_user_id='".$user->user_info[user_id]."'");
  $user->user_lastupdate();
  $user = new se_user(Array($user->user_info[user_id]));
  $result = 1;
}

// ENSURE beerS ARE ENABLED
//if(beers_enabled() == 0) { header("Location: ".$url->url_create('profile', $user->user_info[user_username])); exit(); }

// ENSURE beerS ARE ENABLED FOR THIS USER
//if($user->level_info[level_beers_allow] == 0) { header("Location: ".$url->url_create('profile', $user->user_info[user_username])); exit(); }

// DISPLAY ERROR PAGE IF USER IS NOT ALLOWED TO beer
if( !$user->level_info['level_beers_allow'] )
{
  $page = "error";
  $smarty->assign('error_header', $beers[1]);
  $smarty->assign('error_message', $beers[4]);
  $smarty->assign('error_submit', $beers[3]);
  include "footer.php";
}
// GET AVAILABLE PRIVACY OPTIONS
$privacy_count = 0;
$privacy_beers_options = Array();
for($p=0;$p<strlen($user->level_info[level_beers_privacy]);$p++) {
  $privacy_level = substr($user->level_info[level_beers_privacy], $p, 1);
  if(beers_privacy_levels($privacy_level) != "") {
    $privacy_beers_options[$privacy_count] = Array('privacy_id' => "privacy_beers".$privacy_level,
					    	     'privacy_value' => $privacy_level,
					   	     'privacy_option' => beers_privacy_levels($privacy_level));
    $privacy_count++;
  }
}

// ASSIGN USER SETTINGS
$user->user_settings();

// ASSIGN SMARTY VARIABLES AND INCLUDE FOOTER
$smarty->assign('result', $result);
$smarty->assign('privacy_beers_options', $privacy_beers_options);
$smarty->assign('privacy_beers', true_privacy($user->user_info[user_privacy_beers], $user->level_info[level_beers_privacy]));
include "footer.php";
?>