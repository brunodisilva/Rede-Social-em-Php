<?php
// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
if(!defined('SE_PAGE')) { exit(); }

//include_once "./lang/lang_".$global_lang."_gmap.php";
include_once "./include/class_radcodes.php";
include_once "./include/class_gmap.php";
include_once "./include/functions_gmap.php";

SE_Language::_preload_multi(11080101, 11080102, 11080103, 11080104);


// SET MAIN MENU VARS
if($user->user_exists != 0 || $setting[setting_permission_gmap] != 0) {
  $plugin_vars[menu_main] = Array('file' => 'search_gmap.php', 'title' => 11080104);
}
// SET USER MENU VARS
if($user->level_info[level_gmap_allow] == 1) {
  $plugin_vars[menu_user] = Array('file' => 'user_gmap_settings.php', 'icon' => 'gmap16.gif', 'title' => 11080102);
  
  if (empty($owner->usersetting_info)) {
    $owner->user_settings(); // oh yeah :-(
  }  
  
  // rc_toolkit::debug($owner);
  
  if ($owner->usersetting_info['usersetting_permission_gmap']) {
    if ($page == "profile" || $page == "gmap_location") {
          $rc_gmap = new rc_gmap();
          $rc_gmap->setWidth('100%');
          $rc_gmap->setHeight('100%');
          //$rc_gmap->disableMapControls();
          $rc_gmap->setControlSize('small');
          //$rc_gmap->disableTypeControls();
          //$rc_gmap->disableSidebar();
          //$rc_gmap->disableDirections();
          //$rc_gmap->disableZoomEncompass();
          //$rc_gmap->disableInfoWindow();
          $gmap_marker = false;
          
            $gmap_location = $rc_gmap->parse_user_location($owner);
            $gmap_location_address = join(', ',$gmap_location);
            $gmap_marker = $rc_gmap->add_user_location($owner, $gmap_location_address);


          $smarty->assign('rc_gmap',$rc_gmap);
          $smarty->assign('gmap_location_address',$gmap_location_address);
          $smarty->assign('gmap_marker',$gmap_marker !== false);
    
          
          
      // SET PROFILE MENU VARS
      if(!empty($gmap_location)) {
    
        // DETERMINE WHERE TO SHOW ALBUMS
        $level_gmap_profile = explode(",", $owner->level_info[level_gmap_profile]);
        if(!in_array($owner->user_info[user_profile_gmap], $level_gmap_profile)) { $user_profile_gmap = $level_gmap_profile[0]; } else { $user_profile_gmap = $owner->user_info[user_profile_gmap]; }
    
        $user_profile_gmap = "tab";
        
        // SHOW ALBUM IN APPROPRIATE LOCATION
        if($setting['setting_gmap_profile_embed']) {
          $plugin_vars[menu_profile_tab] = Array('file'=> 'profile_gmap_tab.tpl', 'title' => 11080101);
        } else {
          $plugin_vars[menu_profile_side] = Array('file'=> 'profile_gmap_side.tpl', 'title' => 11080101);
        }
      }
    }
  }
}



