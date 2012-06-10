<?
$page = "user_kiss_confirm";
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
 
 if($task == "kiss_back") {  
	
	$user_kiss->kiss_back($user->user_info[user_id],$owner->user_info[user_id]);
	
	//ADD ONE TO TOTAL kiss
	$kiss_user_totals = $database->database_query("SELECT * FROM se_users WHERE user_id='".$owner->user_info[user_id]."'");    
		while($item = $database->database_fetch_assoc($kiss_user_totals)) {
			$total_kiss_before = $item[kiss_total];$total_kiss_plus = 1;
			$total_kiss_after = $total_kiss_before + $total_kiss_plus;
		}  
	$database->database_query("UPDATE se_users SET kiss_total='$total_kiss_after' WHERE user_id='".$owner->user_info[user_id]."'");	
	
   // SEND kiss EMAIL
    send_kiss_email($owner, $user->user_info[user_username]);

	
	// INSERT ACTION  
	$actions->actions_add($user, "sendkiss", Array('[kiss_owner]', '[kiss_sender]'), Array($owner->user_info[user_username], $user->user_info[user_username]));    
	
$owner_kiss->kiss_remove_incoming($owner->user_info[user_id],$user->user_info[user_id]); 
   header("Location: user_home.php"); exit();
 }

//REMOVE kiss
if($task == "remove") {  
	$owner_kiss->kiss_remove_incoming($owner->user_info[user_id],$user->user_info[user_id]);  
	header("Location: user_home.php"); exit();
	}
	
include "footer.php";
?>