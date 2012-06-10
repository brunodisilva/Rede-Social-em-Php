<?php
// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
if(!defined('SE_PAGE')) { exit(); }

//include_once "./lang/lang_".$global_lang."_commented.php";
include_once "./include/class_radcodes.php";
include_once "./include/class_commented.php";
include_once "./include/functions_commented.php";


SE_Language::_preload_multi(11130101);
SE_Language::load();

/*
// SET MAIN MENU VARS
//$plugin_vars[menu_main] = Array('file' => 'search_commented.php', 'title' => 11020106);

// SET USER MENU VARS
if($user->level_info[level_commented_allow] == 1) {
  $plugin_vars[menu_user] = Array('file' => 'user_commented.php', 'icon' => 'commented16.gif', 'title' => 11040102);
}
*/

// SET PROFILE MENU VARS
if($owner->level_info[level_commented_allow] == 1 && $page == "profile" && $setting['setting_permission_commented'] == 1) {

  $rc_commented = new rc_commented;
  $commented_total_users = $rc_commented->count_users_commented($owner->user_info['user_id']);
  $smarty->assign('commented_total_users', $commented_total_users);
  
  // SET PROFILE MENU VARS
  if($commented_total_users > 0) {

    // DETERMINE WHERE TO SHOW ALBUMS
    $level_commented_profile = explode(",", $owner->level_info[level_commented_profile]);
    if(!in_array($owner->user_info[user_profile_commented], $level_commented_profile)) { $user_profile_commented = $level_commented_profile[0]; } else { $user_profile_commented = $owner->user_info[user_profile_commented]; }

    $user_profile_commented = "side";
    
    // SHOW ALBUM IN APPROPRIATE LOCATION
    if($user_profile_commented == "tab") {
      $plugin_vars[menu_profile_tab] = Array('file'=> 'profile_commented_tab.tpl', 'title' => 11130101);
    } else {
      $plugin_vars[menu_profile_side] = Array('file'=> 'profile_commented_side.tpl', 'title' => 11130101);
    }
  }

}
