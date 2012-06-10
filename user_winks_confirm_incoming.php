<?
$page = "user_winks_confirm_incoming";
include "header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }
if(isset($_POST['return_url'])) { $return_url = $_POST['return_url']; } elseif(isset($_GET['return_url'])) { $return_url = $_GET['return_url']; } else { $return_url = ""; }
$return_url = urldecode($return_url);
$return_url = str_replace("&amp;", "&", $return_url);

if($owner->user_exists == 0) {
  $page = "error";  $smarty->assign('error_header', "An Error Has Occurred");  
  $smarty->assign('error_message', "The person you are looking for has been deleted or does not exist.");  
  $smarty->assign('error_submit', "Return");  
  include "footer.php";
 }

//VARS
$user_wink = new se_winks();
$owner_wink = new se_winks();

//WINK BACK 
 if($task == "wink_back") {  
	
	$wink_date = time();
	$user_wink->wink_back($user->user_info[user_id],$owner->user_info[user_id],$wink_date);
	
	//ADD ONE TO TOTAL WINKS
	$wink_user_totals = $database->database_query("SELECT * FROM se_users WHERE user_id='".$owner->user_info[user_id]."'");    
		while($item = $database->database_fetch_assoc($wink_user_totals)) {
			$total_winks_before = $item[wink_total];$total_winks_plus = 1;
			$total_winks_after = $total_winks_before + $total_winks_plus;
		}  
	$database->database_query("UPDATE se_users SET wink_total='$total_winks_after' WHERE user_id='".$owner->user_info[user_id]."'");	
	
	// INSERT ACTION  
	$actions->actions_add($user, "sendwink", Array($user->user_info[user_username], $user->user_displayname,$owner->user_info[user_username],$owner->user_displayname));    
	
	// INSERT NOTIFY
	$notify->notify_add($owner->user_info[user_id], 'wink', $user->user_info[user_id]);
		
   // SEND WINK EMAIL
    send_wink_email($owner, $user->user_info[user_username]);
	
$owner_wink->wink_remove_incoming($owner->user_info[user_id],$user->user_info[user_id]); 
   header("Location: user_winks_incoming.php"); exit();
 }

//REMOVE WINK
if($task == "remove") {  
	$owner_wink->wink_remove_incoming($owner->user_info[user_id],$user->user_info[user_id]);  
	header("Location: user_winks_incoming.php"); exit();
	}
	
include "footer.php";
?>