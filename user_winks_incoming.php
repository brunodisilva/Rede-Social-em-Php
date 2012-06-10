<?
$page = "user_winks_incoming";
include "header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }

//VARS
$user_wink = new se_winks();
$owner_wink = new se_winks();

// GET TOTAL WINKS
$total_winks = $user_wink->wink_total_incoming($user->user_info[user_id]);

// MAKE WINKS PAGES
$winks_per_page = 10;
$page_vars = make_page($total_winks, $winks_per_page, $p);

// GET WINKS ARRAY
$winks = $user_wink->user_wink_list_incoming($user->user_info[user_id],$page_vars[0], $winks_per_page);

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

// CONFIRM WINK - REMOVE NOTIFICATION
if($task == "confirm") {

$wink_owner_id = $user->user_info[user_id];
$wink = "action_sendwink.gif";
$wink_query = $database->database_query("SELECT * FROM se_notifytypes WHERE notifytype_name = 'wink' ");
$wink_array = Array();
	while($item = $database->database_fetch_assoc($wink_query)) {
	$id = $item[notifytype_id];

$database->database_query("DELETE FROM se_notifys WHERE notify_user_id='$wink_owner_id' AND notify_notifytype_id='$id'");
	}
}

// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('winks', $winks);
$smarty->assign('total_winks', $total_winks);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($winks));

include "footer.php";
?>