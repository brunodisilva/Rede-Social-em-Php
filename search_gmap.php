<?php
$page = "search_gmap";
include "header.php";

// DISPLAY ERROR PAGE IF USER IS NOT LOGGED IN AND ADMIN SETTING REQUIRES REGISTRATION
if($user->user_exists == 0 && $setting[setting_permission_gmap] == 0) {
  $page = "error";
  $smarty->assign('error_header', 11080420);
  $smarty->assign('error_message', 11080419);
  $smarty->assign('error_submit', 11080421);
  include "footer.php";
}

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }


// SET VARS
$showfields = 1;
$linked_field_title = "";
$linked_field_value = "";
$sort = "user_dateupdated DESC";
$users_per_page = 20;


// START FIELD OBJECT
$field = new se_field("profile");
$rc_gmap = new rc_gmap();
// GET CATS TO DISPLAY ACROSS TOP
$field->cat_list(0, 0, 0, "(SELECT TRUE FROM se_profilecats AS t2 LEFT JOIN se_profilefields ON t2.profilecat_id=se_profilefields.profilefield_profilecat_id WHERE t2.profilecat_dependency=se_profilecats.profilecat_id AND profilefield_search<>0 LIMIT 1)", "cat_id=0");
$cat_menu_array = $field->cats;

if(isset($_POST['cat_selected'])) { $cat_selected = $_POST['cat_selected']; } elseif(isset($_GET['cat_selected'])) { $cat_selected = $_GET['cat_selected']; } else { $cat_selected = $cat_menu_array[0][cat_id]; }

// GET LIST OF FIELDS
$field->cat_list(0, 0, 1, "profilecat_id=$cat_selected", "", "profilefield_search<>'0'");
$cat_array = $field->cats;
$url_string = $field->url_string;

// PERFORM SEARCH
if(isset($_POST['sort'])) { $sort = $_POST['sort']; } elseif(isset($_GET['sort'])) { $sort = $_GET['sort']; } else { $sort = "user_dateupdated DESC"; }
if(isset($_POST['user_online'])) { $user_online = $_POST['user_online']; } elseif(isset($_GET['user_online'])) { $user_online = $_GET['user_online']; } else { $user_online = 0; }
if(isset($_POST['user_withphoto'])) { $user_withphoto = $_POST['user_withphoto']; } elseif(isset($_GET['user_withphoto'])) { $user_withphoto = $_GET['user_withphoto']; } else { $user_withphoto = 0; }

// BEGIN CONSTRUCTING SEARCH QUERY    
$search_query = "SELECT se_users.*, se_profilevalues.*, se_usersettings.* FROM se_profilevalues LEFT JOIN se_users ON se_profilevalues.profilevalue_user_id = se_users.user_id LEFT JOIN se_levels ON se_levels.level_id=se_users.user_level_id JOIN se_usersettings ON se_usersettings.usersetting_user_id=se_users.user_id WHERE se_users.user_profilecat_id='$cat_selected' AND se_users.user_verified='1' AND se_users.user_enabled='1' AND (se_users.user_search='1' OR se_levels.level_profile_search='0') 
  
  AND se_usersettings.usersetting_permission_gmap='1'
  AND se_levels.level_gmap_allow='1'
  ";

$mapfields = $rc_gmap->get_setting_mapfields_for_profilecat($cat_selected);  
if (count($mapfields)) {
  $gmap_filter_criteria = $rc_gmap->get_profilefields_criteria($mapfields);
  $search_query .= " AND ( $gmap_filter_criteria )";
}

if($user_online == 1) { $search_query .= " AND user_lastactive>'".(time()-10*60)."' AND user_invisible=0"; }
if($user_withphoto == 1) { $search_query .= " AND user_photo <> ''"; }
if($field->field_query != "") { $search_query .= " AND ".$field->field_query; }

// GET TOTAL USERS
$total_users = $database->database_num_rows($database->database_query($search_query));

// MAKE SEARCH PAGES
$page_vars = make_page($total_users, $users_per_page, $p);

// ADD LIMIT TO QUERY
$search_query .= " ORDER BY $sort LIMIT $page_vars[0], $users_per_page";

// GET USERS
$online_users_array = online_users();
$users = $database->database_query($search_query);
while($user_info = $database->database_fetch_assoc($users)) {
  $search_user = new se_user();
  $search_user->user_info = array_merge((array)$search_user->user_info, $user_info);
  $search_user->user_displayname();

  // DETERMINE IF USER IS ONLINE
  if(in_array($search_user->user_info[user_username], $online_users_array[2])) { $search_user->is_online = 1; } else { $search_user->is_online = 0; }

  $user_location = $rc_gmap->parse_user_location($search_user);
  $user_address = join(', ',$user_location);
  
    if ($rc_gmap->add_user_location($search_user,$user_address,array('user_photo'=>true)) === false) {
      $unknown_locations[$user_info['user_username']] = $search_user;
    }
  
  $user_array[] = $search_user;
}

// SET GLOBAL PAGE TITLE
$global_page_title[0] = 926;
$global_page_description[0] = 1088;

//rc_toolkit::debug($user_array,'$user_array');

// do this .. and let CSS take care of it
$rc_gmap->setWidth('100%');
$rc_gmap->setHeight('100%');
$smarty->assign('rc_gmap', $rc_gmap);
$smarty->assign('unknown_locations',$unknown_locations);

// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('users', $user_array);
$smarty->assign('total_users', $total_users);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($user_array));
$smarty->assign('showfields', $showfields);
$smarty->assign('url_string', $url_string);
$smarty->assign('linked_field_title', $linked_field_title);
$smarty->assign('linked_field_value', $linked_field_value);
$smarty->assign('cats_menu', $cat_menu_array);
$smarty->assign('cat_selected', $cat_selected);
$smarty->assign('cats', $cat_array);
$smarty->assign('sort', $sort);
$smarty->assign('task', $task);
$smarty->assign('user_online', $user_online);
$smarty->assign('user_withphoto', $user_withphoto);
include "footer.php";
?>