<?php
// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
if(!defined('SE_PAGE')) { exit(); }

//include_once "./lang/lang_".$global_lang."_recommended.php";
include_once "./include/class_radcodes.php";
include_once "./include/class_recommended.php";
include_once "./include/functions_recommended.php";

SE_Language::_preload_multi(11140101,11140102,11140103);

// SET MAIN MENU VARS
if($user->user_exists != 0 || $setting[setting_permission_recommended] != 0) {
  $plugin_vars[menu_main] = Array('file' => 'recommended_top_recommendees.php', 'title' => 11140101);
}
// SET USER MENU VARS
if($user->level_info[level_recommended_allow] == 1) {
  $plugin_vars[menu_user] = Array('file' => 'user_recommended_settings.php', 'icon' => 'recommended16.gif', 'title' => 11140101);
}

// SET PROFILE MENU VARS
if($owner->level_info[level_recommended_allow] == 1 && $page == "profile") {
 
    $recommended_total_recommenders = 0;
    $recommended_total_recommendees = 0;
    $rc_recommededvote = new rc_recommendedvote();
    
      $owner_user_id = $owner->user_info['user_id'];  
      $recommended_total_recommenders = $rc_recommededvote->count_recommenders($owner_user_id);
      $recommended_recommenders = $rc_recommededvote->get_recommenders($owner_user_id,0,5);
      $recommended_total_recommendees = $rc_recommededvote->count_recommendees($owner_user_id);
      $recommended_recommendees = $rc_recommededvote->get_recommendees($owner_user_id,0,5);
      $recommended_has_vote = $rc_recommededvote->has_vote($user->user_info['user_id'], $owner_user_id);
      $smarty->assign('recommended_recommenders', $recommended_recommenders);
      $smarty->assign('recommended_recommendees', $recommended_recommendees);
      $smarty->assign('recommended_has_vote', $recommended_has_vote);
    
     // rc_toolkit::debug($recommended_recommenders);
      
    $smarty->assign('recommended_total_recommenders', $recommended_total_recommenders);
    $smarty->assign('recommended_total_recommendees', $recommended_total_recommendees);
  
  // SET PROFILE MENU VARS
  //if($recommended_total_recommenders > 0 || $recommended_total_recommendees > 0) {

    // DETERMINE WHERE TO SHOW ALBUMS
    $level_recommended_profile = explode(",", $owner->level_info[level_recommended_profile]);
    if(!in_array($owner->user_info[user_profile_recommended], $level_recommended_profile)) { $user_profile_recommended = $level_recommended_profile[0]; } else { $user_profile_recommended = $owner->user_info[user_profile_recommended]; }

    $user_profile_recommended = "tab";
    
    
    
    // SHOW ALBUM IN APPROPRIATE LOCATION
    //if($user_profile_recommended == "tab") {
      $plugin_vars[menu_profile_tab] = Array('file'=> 'profile_recommended_tab.tpl', 'title' => 11140101);
    //} else {
     // $plugin_vars[menu_profile_side] = Array('file'=> 'profile_recommended_side.tpl', 'title' => 11140101);
    //}
  //}

      
//      <tr><td class='profile_menu1' nowrap='nowrap'><a href='{$plugin_v.menu_profile_menu.file}'><img src='./images/icons/{$plugin_v.menu_profile_menu.icon}' class='icon' border='0'>{lang_sprintf id=$plugin_v.menu_profile_menu.title 1=$plugin_v.menu_profile_menu.title_1 2=$plugin_v.menu_profile_menu.title_2}</a></td></tr>

  if ($user->user_info[user_id] != $owner->user_info[user_id] && $user->level_info[level_recommended_allow] != 0) {
    if ($recommended_has_vote) {
		  $plugin_vars[menu_profile_menu] = array(
		    'file' => "user_recommended_edit.php?user=".$owner->user_info['user_username'],
		    'icon' => "recommended_vote16.gif",
		    'title' => 11140102
		  );
    }
    else {
      $plugin_vars[menu_profile_menu] = array(
        'file' => "user_recommended_add.php?user=".$owner->user_info['user_username'],
        'icon' => "recommended_vote16.gif",
        'title' => 11140103
      );
    }
  }
  //rc_toolkit::debug($plugin_vars[menu_profile_menu],'$plugin_vars[menu_profile_menu]' . $user->level_info[level_recommended_allow]);
      
}



