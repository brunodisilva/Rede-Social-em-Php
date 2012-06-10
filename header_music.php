<?php

/* $Id: header_music.php 11 2009-01-11 06:03:58Z john $ */

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
defined('SE_PAGE') or exit();

// INCLUDE MUSIC CLASS FILES
include "./include/class_music.php";
include "./include/functions_music.php";


// PRELOAD LANGUAGE
SE_Language::_preload(4000004);

// SET MAIN MENU VARS
$plugin_vars['menu_main'] = array('file' => 'browse_music.php', 'title' => 4000004);

// SET USER MENU VARS
if( $user->level_info['level_music_allow'] )
{
  $plugin_vars['menu_user'] = array('file' => 'user_music.php', 'icon' => 'music_music16.gif', 'title' => 4000004);
}

// SET PROFILE MENU VARS
if( $owner->level_info['level_music_allow'] && $page=="profile" )
{
  // GET USER SETTINGS
  $user->user_settings('usersetting_music_profile_autoplay,usersetting_music_site_autoplay,usersetting_xspfskin_id');
  $owner->user_settings('usersetting_music_profile_autoplay,usersetting_music_site_autoplay,usersetting_xspfskin_id');
  
  // GET SKIN INFO
  $owner_music = new se_music($owner->user_info['user_id']);
  $skin_info = $owner_music->skin_info($user->usersetting_info['usersetting_xspfskin_id']);
  
  if( !empty($skin_info ) )
  {
    $smarty->assign('skin_title', $skin_info['xspfskin_title']); 
    $smarty->assign('skin_height', $skin_info['xspfskin_height']);
    $smarty->assign('skin_width', $skin_info['xspfskin_width']);
  }
  
  // AUTOPLAY
  // Rules: +USER+OWNER -> TRUE, +USER-OWNER -> FALSE, -USER+OWNER -> FALSE, -USER-OWNER -> FALSE
  $smarty->assign('autoplay', ($user->usersetting_info['usersetting_music_site_autoplay'] && $owner->usersetting_info['usersetting_music_profile_autoplay']));
  
  // SET PROFILE MENU VARS
  $owner_music_list = $owner_music->music_list();
  if( !empty($owner_music_list) )
  {
    $smarty->assign('music_allow', TRUE);
    $plugin_vars['menu_profile_tab'] = "";
    $plugin_vars['menu_profile_side'] = array('file'=> 'profile_music.tpl', 'title' => 4000004);
  }
}


// SET HOOKS
SE_Hook::register("se_search_do", "search_music");

SE_Hook::register("se_user_delete", "deleteuser_music");

SE_Hook::register("se_site_statistics", "site_statistics_music");

?>