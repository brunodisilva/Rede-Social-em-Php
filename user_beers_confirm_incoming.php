<?
$page = "user_beers_confirm_incoming";
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
$user_beer = new se_beers();
$owner_beer = new se_beers();

//beer BACK 
 if($task == "beer_back") {  
	
	$beer_date = time();
	$user_beer->beer_back($user->user_info[user_id],$owner->user_info[user_id],$beer_date);
	
	//ADD ONE TO TOTAL beerS
	$beer_user_totals = $database->database_query("SELECT * FROM se_users WHERE user_id='".$owner->user_info[user_id]."'");    
		while($item = $database->database_fetch_assoc($beer_user_totals)) {
			$total_beers_before = $item[beer_total];$total_beers_plus = 1;
			$total_beers_after = $total_beers_before + $total_beers_plus;
		}  
	$database->database_query("UPDATE se_users SET beer_total='$total_beers_after' WHERE user_id='".$owner->user_info[user_id]."'");	
	
	// INSERT ACTION  
	$actions->actions_add($user, "sendbeer", Array($user->user_info[user_username], $user->user_displayname,$owner->user_info[user_username],$owner->user_displayname));    
	
	// INSERT NOTIFY
	$notify->notify_add($owner->user_info[user_id], 'beer', $user->user_info[user_id]);
		
   // SEND beer EMAIL
    send_beer_email($owner, $user->user_info[user_username]);
	
$owner_beer->beer_remove_incoming($owner->user_info[user_id],$user->user_info[user_id]); 
   header("Location: user_beers_incoming.php"); exit();
 }

//REMOVE beer
if($task == "remove") {  
	$owner_beer->beer_remove_incoming($owner->user_info[user_id],$user->user_info[user_id]);  
	header("Location: user_beers_incoming.php"); exit();
	}
	
include "footer.php";
?>