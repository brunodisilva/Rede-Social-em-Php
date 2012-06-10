<?
/*
Title: Member Moods
Author: ModMySocialEngine
Copyright: 2008
PLEASE READ THE LISCENSE AGREEMENT BEFORE INSTALLING THIS MOD
AND MAKE SURE YOU ARE FULLY AWARE OF THE TERMS BEFORE USING THIS
*/

$page = "user_mood";
include "header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }
if(isset($_POST['return_url'])) { $return_url = $_POST['return_url']; } elseif(isset($_GET['return_url'])) { $return_url = $_GET['return_url']; } else { $return_url = ""; }

// SHOW SUCCESSFUL/FAILED MESSAGE ?
$result = 0;

// SAVE NEW MOOD
if($task == "dosave") {
  $mood_new = $_POST['mood_new'];
  $database->database_query("UPDATE se_users SET mood='$mood_new' WHERE user_id='".$user->user_info[user_id]."' LIMIT 1");
  $user->user_lastupdate();
  $user->user_info[mood] = $mood_new;
  $mood_image = strtolower($mood_new);
  
//ONLY INSERT ACTION IF MOOD IS NOT (NONE)
if($_POST['mood_new'] != "(none)"){
  
// INSERT ACTION
  $actions->actions_add($user, "editmood", Array($user->user_info[user_username],$user->user_displayname,str_replace("&", "&amp;", $mood_new),$mood_image));
  $result = 1;
  $return_url= "user_home.php";
  if($return_url != "") { header("Location: $return_url"); exit; }
}
  //SUCCESSFUL - RETURN TO USER_HOME
  $result = 1;
  $return_url= "user_home.php";
  if($return_url != "") { header("Location: $return_url"); exit; }
}

//GRAB CURRENT MOOD
$user = $user->user_info[user_username];
$my_mood = $database->database_query("SELECT * FROM se_users WHERE user_username = '$user'");
	while($item = $database->database_fetch_assoc($my_mood)){
		$current_mood = $item[mood];
		}	

// ASSIGN SMARTY VARIABLES AND INCLUDE FOOTER
$smarty->assign('result', $result);
$smarty->assign('return_url', $return_url);
$smarty->assign('current_mood', $current_mood);
include "footer.php";
?>