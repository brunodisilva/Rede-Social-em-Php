<?
$page = "user_winks_topusers";
include "header.php";


//VARS
$is_wink = 0;

//GET TOP USERS
$winks_query = $database->database_query("SELECT * FROM se_users WHERE wink_total ORDER BY wink_total DESC");
$wink_array = Array();
	while($wink = $database->database_fetch_assoc($winks_query)) {
		$wink_user = new se_user();
		$wink_user->user_info[user_id] = $wink[user_id];
		$wink_user->user_info[user_username] = $wink[user_username];
		$wink_user->user_info[user_photo] = $wink[user_photo];

	$wink_array[] = Array('wink' => $wink_user,
		       'wink_total' => $wink[wink_total]);
}

//DISPLAY ERROR MESSAGE IF NO WINKS YET
if($database->database_num_rows($database->database_query("SELECT * FROM se_users WHERE wink_total")) == 0) {
$is_wink = 1;
}

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
// ASSIGN SMARTY VARIABLES AND INCLUDE FOOTER
$smarty->assign('winks', $wink_array);
$smarty->assign('total_winks', $total_winks);
$smarty->assign('is_wink', $is_wink);

include "footer.php";
?>