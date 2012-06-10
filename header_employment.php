<?php
// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
if(!defined('SE_PAGE')) { exit(); }

//include_once "./lang/lang_".$global_lang."_employment.php";
include_once "./include/class_radcodes.php";
include_once "./include/class_employment.php";
include_once "./include/functions_employment.php";

SE_Language::_preload_multi(11050101, 11050102, 11050103, 11050109, 11050110);
SE_Language::load();

// SET MAIN MENU VARS
//$plugin_vars[menu_main] = Array('file' => 'search_employment.php', 'title' => 11020106);

// SET USER MENU VARS
if($user->level_info[level_employment_allow] == 1) {
  $plugin_vars[menu_user] = Array('file' => 'user_employment.php', 'icon' => 'employment16.gif', 'title' => 11050102);
}

// SET PROFILE MENU VARS
if($owner->level_info[level_employment_allow] == 1 && $page == "profile") {

  $rc_employment = new rc_employment($owner->user_info[user_id]);
  $employments = $rc_employment->get_employments();
  $total_employments = count($employments);

  
  foreach (explode(',',SE_Language::_get(11050103)) as $letter) {
    $months[++$i] = $letter;
  }
  $employments = $rc_employment->build_searchable_fields($employments);
  foreach ($employments as $k=>$employment) {
    $time_period = array();
    if ($employment['employment_from_month'] > 0) {
      $time_period[] = $employment['search_employment_from_month'];
    }
    if ($employment['employment_from_year'] > 0) {
      $time_period[] = $employment['search_employment_from_year'];
    }
   
    if ($employment['employment_is_current'] || $employment['employment_to_month'] > 0 || $employment['employment_to_year'] > 0) {
      $time_period[] = SE_Language::_get(11050110);
      
      if ($employment['employment_is_current']) {
        $time_period[] = $employment['search_employment_is_current'];
      }
      else {
        if ($employment['employment_to_month'] > 0) {
          $time_period[] = $employment['search_employment_to_month'];
        }
        if ($employment['employment_to_year'] > 0) {
          $time_period[] = $employment['search_employment_to_year'];
        }
      }
    }
    
    $employments[$k]['time_period'] = trim(join(' ',$time_period));
  }

  $smarty->assign('employments', $employments);
  $smarty->assign('total_employments', $total_employments);
  
  // SET PROFILE MENU VARS
  if($total_employments > 0) {

    // DETERMINE WHERE TO SHOW ALBUMS
    $level_employment_profile = explode(",", $owner->level_info[level_employment_profile]);
    if(!in_array($owner->user_info[user_profile_employment], $level_employment_profile)) { $user_profile_employment = $level_employment_profile[0]; } else { $user_profile_employment = $owner->user_info[user_profile_employment]; }

    $user_profile_employment = "tab"; // default to tab for now .. v3.03
    
    // SHOW ALBUM IN APPROPRIATE LOCATION
    if($user_profile_employment == "tab") {
      $plugin_vars[menu_profile_tab] = Array('file'=> 'profile_employment_tab.tpl', 'title' => 11050101);
    } else {
      $plugin_vars[menu_profile_side] = Array('file'=> 'profile_employment_side.tpl', 'title' => 11050101);
    }
  }

}



