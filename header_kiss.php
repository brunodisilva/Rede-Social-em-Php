<?

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
if(!defined('SE_PAGE')) { exit(); }

// INCLUDE kiss CLASS FILE
include "./include/class_kiss.php";

// INCLUDE kiss FUNCTION FILE
include "./include/functions_kiss.php";

// SET MAIN MENU VARS
$plugin_vars['menu_main'] = "";

// SET USER MENU VARS
if( $user->level_info['level_kiss_allow'] )
{
  SE_Language::_preload(90000024);
  $plugin_vars['menu_user'] = Array('file' => 'user_kiss_incoming.php', 'icon' => 'kiss16.gif', 'title' => 90000024);
}
//ENSURE kiss ARE ENABLED
$kiss_enabled = kiss_enabled();
		
//VARS
$user_kiss = new se_kiss();
$owner_kiss = new se_kiss();

//HANDLE PENDING kiss
if($owner_kiss->user_kissed($user->user_info[user_id], $owner->user_info[user_id],0)) {
$kiss_pending = 1;
}
if($owner_kiss->user_kissed_incoming($user->user_info[user_id], $owner->user_info[user_id],0)) {
$kiss_pending = 1;
}

// SHOW LINK IF USER IS ONLINE
$online_users_array = online_users();
if(in_array($user->user_info[user_username], $online_users_array[2])) { $is_online_kiss = 1; } else { $is_online_kiss = 0; }


// kiss PRIVACY
$privacy_owner = $owner->user_info[user_username];
$privacy_query = $database->database_query("SELECT user_privacy_kiss FROM se_users WHERE user_username = '$privacy_owner'");
$kiss_privacy_array = Array();

while($item = $database->database_fetch_assoc($privacy_query)) {
$user_kiss_privacy = $item[user_privacy_kiss];
}

//CHECK IF FRIENDS
$is_friend_kiss = $user->user_friended($owner->user_info[user_id]);
if($is_friend_kiss) { $kiss_allowed = 1; }
		
// ASSIGN VARIABLES
$smarty->assign('kiss_enabled', $kiss_enabled);
$smarty->assign('kiss_pending', $kiss_pending);
$smarty->assign('kiss_pending', $kiss_pending);
$smarty->assign('is_online_kiss', $is_online_kiss);
$smarty->assign('user_kiss_privacy', $user_kiss_privacy);
$smarty->assign('is_friend_kiss', $is_friend_kiss);
$smarty->assign('kiss_alowed', $kiss_allowed);


?>