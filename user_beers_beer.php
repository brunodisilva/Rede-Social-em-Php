<?
$page = "user_beers_beer";
include "header.php";

//VARS
$user_beer = new se_beers();
$owner_beer = new se_beers();
$result = "";
$is_error = 0;

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }

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
if($owner->user_exists == 0) {
  $page = "error";
  $smarty->assign('error_header', "An Error Has Occurred");
  $smarty->assign('error_message', "The person you are looking for has been deleted or does not exist.");
  $smarty->assign('error_submit', "Return");
  include "footer.php";
}

//CANCEL beer
if($task == "cancel") {
  header("Location: ".$url->url_create("profile", $owner->user_info[user_username]));
  exit();
}

//SEND beer
if($task == "beer") {

	//DUPLICATE beer CHECK
	if($user_beer->user_beered($user->user_info[user_id], $owner->user_info[user_id],0)) {
		$is_error = 1;
		$result = 1;
	}
	
	//NO DUPLICATES - PROCEED TO ADD  beer
	if ($is_error == 0 ){
	
	//IN CASE OF WINBACK - AVOID DUPLICATE beerS
	//if($user_beer->user_beered($owner->user_info[user_id], $user->user_info[user_id],0)) {
	//$owner_beer->beer_remove_incoming($owner->user_info[user_id],$user->user_info[user_id]);
	//}

	$beer_date = time();
	
	//ADD beer TO DATABASE
	$user_beer->beer($user->user_info[user_id],$owner->user_info[user_id], $beer_date);

	// INSERT ACTION  
	$actions->actions_add($user, "sendbeer", Array($user->user_info[user_username],$user->user_displayname,$owner->user_info[user_username],$owner->user_displayname));    
	
	// INSERT NOTIFY
	$notify->notify_add($owner->user_info[user_id], 'beer', $user->user_info[user_id]);
		
   // SEND beer EMAIL
    send_beer_email($owner, $user->user_info[user_username]);
  
	//ADD ONE TO TOTAL beerS
	$beer_user_totals = $database->database_query("SELECT * FROM se_users WHERE user_id='".$owner->user_info[user_id]."'");
		while($item = $database->database_fetch_assoc($beer_user_totals)) {
			$total_beers_before = $item[beer_total];
			$total_beers_plus = 1;
			$total_beers_after = $total_beers_before + $total_beers_plus;
		}  
	$database->database_query("UPDATE se_users SET beer_total='$total_beers_after' WHERE user_id='".$owner->user_info[user_id]."'");
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