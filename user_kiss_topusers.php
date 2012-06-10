<?
$page = "user_kiss_topusers";
include "header.php";


//VARS
$is_kiss = 0;

//GET TOP USERS
$kiss_query = $database->database_query("SELECT * FROM se_users WHERE kiss_total ORDER BY kiss_total DESC");
$kiss_array = Array();
	while($kiss = $database->database_fetch_assoc($kiss_query)) {
		$kiss_user = new se_user();
		$kiss_user->user_info[user_id] = $kiss[user_id];
		$kiss_user->user_info[user_username] = $kiss[user_username];
		$kiss_user->user_info[user_photo] = $kiss[user_photo];

	$kiss_array[] = Array('kiss' => $kiss_user,
		       'kiss_total' => $kiss[kiss_total]);
}

//DISPLAY ERROR MESSAGE IF NO kiss YET
if($database->database_num_rows($database->database_query("SELECT * FROM se_users WHERE kiss_total")) == 0) {
$is_kiss = 1;
}

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
// ASSIGN SMARTY VARIABLES AND INCLUDE FOOTER
$smarty->assign('kiss', $kiss_array);
$smarty->assign('total_kiss', $total_kiss);
$smarty->assign('is_kiss', $is_kiss);

include "footer.php";
?>