<?
$page = "user_kiss_confirm_outgoing";
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
$user_kiss = new se_kiss();
$owner_kiss = new se_kiss();
 
//REMOVE kiss 
if($task == "remove") {  
	$owner_kiss->kiss_remove($owner->user_info[user_id],$user->user_info[user_id]);  
	
		//SUBTRACT ONE FOR TOTAL kiss
		$kiss_user_totals = $database->database_query("SELECT * FROM se_users WHERE user_id='".$owner->user_info[user_id]."'");    
		while($item = $database->database_fetch_assoc($kiss_user_totals)) {
			$total_kiss_before = $item[kiss_total];
			$total_kiss_minus = 1;
			$total_kiss_after = $total_kiss_before - $total_kiss_minus;
		}  
	$database->database_query("UPDATE se_users SET kiss_total='$total_kiss_after' WHERE user_id='".$owner->user_info[user_id]."'");
	
	//REMOVE NOTIFICATION
	$notify_query = $database->database_query("SELECT * FROM se_notifytypes WHERE notifytype_name = 'kiss' ");
	$notify_array = Array();
		while($item = $database->database_fetch_assoc($notify_query)) {
		$id = $item[notifytype_id];
		$database->database_query("DELETE FROM se_notifys WHERE notify_user_id='".$owner->user_info[user_id]."' AND notify_notifytype_id='$id' LIMIT 1");
		}
		
	//REMOVE ACTION
	$action_query = $database->database_query("SELECT * FROM se_actiontypes WHERE actiontype_name = 'sendkiss' ");
	$action_array = Array();
		while($item = $database->database_fetch_assoc($action_query)){
		$action_id = $item[actiontype_id];
		$database->database_query("DELETE FROM se_actions WHERE action_user_id='".$user->user_info[user_id]."' AND action_actiontype_id = '$action_id' LIMIT 1");
	
	header("Location: user_kiss_outgoing.php"); exit();
	}
	}
include "footer.php";
?>