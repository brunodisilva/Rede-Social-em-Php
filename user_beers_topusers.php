<?
$page = "user_beers_topusers";
include "header.php";


//VARS
$is_beer = 0;

//GET TOP USERS
$beers_query = $database->database_query("SELECT * FROM se_users WHERE beer_total ORDER BY beer_total DESC");
$beer_array = Array();
	while($beer = $database->database_fetch_assoc($beers_query)) {
		$beer_user = new se_user();
		$beer_user->user_info[user_id] = $beer[user_id];
		$beer_user->user_info[user_username] = $beer[user_username];
		$beer_user->user_info[user_photo] = $beer[user_photo];

	$beer_array[] = Array('beer' => $beer_user,
		       'beer_total' => $beer[beer_total]);
}

//DISPLAY ERROR MESSAGE IF NO beerS YET
if($database->database_num_rows($database->database_query("SELECT * FROM se_users WHERE beer_total")) == 0) {
$is_beer = 1;
}

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
// ASSIGN SMARTY VARIABLES AND INCLUDE FOOTER
$smarty->assign('beers', $beer_array);
$smarty->assign('total_beers', $total_beers);
$smarty->assign('is_beer', $is_beer);

include "footer.php";
?>