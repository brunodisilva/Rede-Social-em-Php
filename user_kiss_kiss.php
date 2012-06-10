<?
$page = "user_kiss_kiss";
include "header.php";

//VARS
$user_kiss = new se_kiss();
$owner_kiss = new se_kiss();
$result = "";
$is_error = 0;

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }

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
if($owner->user_exists == 0) {
  $page = "error";
  $smarty->assign('error_header', "An Error Has Occurred");
  $smarty->assign('error_message', "The person you are looking for has been deleted or does not exist.");
  $smarty->assign('error_submit', "Return");
  include "footer.php";
}

//CANCEL kiss
if($task == "cancel") {
  header("Location: ".$url->url_create("profile", $owner->user_info[user_username]));
  exit();
}

//SEND kiss
if($task == "kiss") {

	//DUPLICATE kiss CHECK
	if($user_kiss->user_kissed($user->user_info[user_id], $owner->user_info[user_id],0)) {
		$is_error = 1;
		$result = 1;
	}
	
	//NO DUPLICATES - PROCEED TO ADD  kiss
	if ($is_error == 0 ){
	
	//IN CASE OF WINBACK - AVOID DUPLICATE kiss
	//if($user_kiss->user_kissed($owner->user_info[user_id], $user->user_info[user_id],0)) {
	//$owner_kiss->kiss_remove_incoming($owner->user_info[user_id],$user->user_info[user_id]);
	//}

	$kiss_date = time();
	
	//ADD kiss TO DATABASE
	$user_kiss->kiss($user->user_info[user_id],$owner->user_info[user_id], $kiss_date);

	// INSERT ACTION  
	$actions->actions_add($user, "sendkiss", Array($user->user_info[user_username],$user->user_displayname,$owner->user_info[user_username],$owner->user_displayname));    
	
	// INSERT NOTIFY
	$notify->notify_add($owner->user_info[user_id], 'kiss', $user->user_info[user_id]);
		
   // SEND kiss EMAIL
    send_kiss_email($owner, $user->user_info[user_username]);
  
	//ADD ONE TO TOTAL kiss
	$kiss_user_totals = $database->database_query("SELECT * FROM se_users WHERE user_id='".$owner->user_info[user_id]."'");
		while($item = $database->database_fetch_assoc($kiss_user_totals)) {
			$total_kiss_before = $item[kiss_total];
			$total_kiss_plus = 1;
			$total_kiss_after = $total_kiss_before + $total_kiss_plus;
		}  
	$database->database_query("UPDATE se_users SET kiss_total='$total_kiss_after' WHERE user_id='".$owner->user_info[user_id]."'");
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