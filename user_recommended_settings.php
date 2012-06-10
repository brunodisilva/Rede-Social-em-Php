<?
$page = "user_recommended_settings";
include "header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }

// ENSURE recommendedS ARE ENABLED FOR THIS USER
if($user->level_info[level_recommended_allow] == 0) { header("Location: user_home.php"); exit(); }

$result = 0;

if($task == "dosave") {
  $usersetting_notify_recommendedcomment = $_POST['usersetting_notify_recommendedcomment'] ? 1 : 0;
  $database->database_query("UPDATE se_usersettings SET usersetting_notify_recommendedcomment='$usersetting_notify_recommendedcomment' WHERE usersetting_user_id='".$user->user_info[user_id]."'");
  $user->user_lastupdate();
  $user = new se_user(Array($user->user_info[user_id]));
  $result = 1;
}

$user->user_settings();

$smarty->assign('result', $result);
include "footer.php";
?>