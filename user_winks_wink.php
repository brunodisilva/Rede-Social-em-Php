<?
$page = "user_winks_wink";
include "header.php";

//VARS
$user_wink = new se_winks();
$owner_wink = new se_winks();
$result = "";
$is_error = 0;

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }

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
if($owner->user_exists == 0) {
  $page = "error";
  $smarty->assign('error_header', "An Error Has Occurred");
  $smarty->assign('error_message', "The person you are looking for has been deleted or does not exist.");
  $smarty->assign('error_submit', "Return");
  include "footer.php";
}

//CANCEL WINK
if($task == "cancel") {
  header("Location: ".$url->url_create("profile", $owner->user_info[user_username]));
  exit();
}

//SEND WINK
if($task == "wink") {

	//DUPLICATE WINK CHECK
	if($user_wink->user_winked($user->user_info[user_id], $owner->user_info[user_id],0)) {
		$is_error = 1;
		$result = 1;
	}
	
	//NO DUPLICATES - PROCEED TO ADD  WINK
	if ($is_error == 0 ){
	
	//IN CASE OF WINBACK - AVOID DUPLICATE WINKS
	//if($user_wink->user_winked($owner->user_info[user_id], $user->user_info[user_id],0)) {
	//$owner_wink->wink_remove_incoming($owner->user_info[user_id],$user->user_info[user_id]);
	//}

	$wink_date = time();
	
	//ADD WINK TO DATABASE
	$user_wink->wink($user->user_info[user_id],$owner->user_info[user_id], $wink_date);

	// INSERT ACTION  
	$actions->actions_add($user, "sendwink", Array($user->user_info[user_username],$user->user_displayname,$owner->user_info[user_username],$owner->user_displayname));    
	
	// INSERT NOTIFY
	$notify->notify_add($owner->user_info[user_id], 'wink', $user->user_info[user_id]);
		
   // SEND WINK EMAIL
    send_wink_email($owner, $user->user_info[user_username]);
  
	//ADD ONE TO TOTAL WINKS
	$wink_user_totals = $database->database_query("SELECT * FROM se_users WHERE user_id='".$owner->user_info[user_id]."'");
		while($item = $database->database_fetch_assoc($wink_user_totals)) {
			$total_winks_before = $item[wink_total];
			$total_winks_plus = 1;
			$total_winks_after = $total_winks_before + $total_winks_plus;
		}  
	$database->database_query("UPDATE se_users SET wink_total='$total_winks_after' WHERE user_id='".$owner->user_info[user_id]."'");
	$result = 2;
}
}

// DECIDE WHICH PAGE TO SHOW
$subpage = "add";
 

// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('result', $result);
$smarty->assign('is_error', $is_error);
$smarty->assign('subpage', $subpage);

include "footer.php";
?>