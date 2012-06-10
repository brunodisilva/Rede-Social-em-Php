<?php

/* $Id: header_event.php 9 2009-01-11 06:03:21Z john $ */

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
defined('SE_PAGE') or exit();

// INCLUDE EVENT CLASS FILE
include "./include/class_event.php";

// INCLUDE EVENT FUNCTION FILE
include "./include/functions_event.php";

// PRELOAD LANGUAGE
SE_Language::_preload(3000007);

// SET MAIN MENU VARS
if( ($user->user_exists && ($user->level_info['level_event_allow'] & 1)) || (!$user->user_exists && $setting['setting_permission_event']) )
{
  $plugin_vars['menu_main'] = Array('file' => 'browse_events.php', 'title' => 3000007);
}

// SET USER MENU VARS
if( ($user->level_info['level_event_allow'] & 1) )
{
  $plugin_vars['menu_user'] = Array('file' => 'user_event.php', 'icon' => 'event_event16.gif', 'title' => 3000007);
}

// SET PROFILE MENU VARS
if( ($owner->level_info['level_event_allow'] & 6) && $page=="profile" )
{
  // START CLASSIFIED
  $event = new se_event($owner->user_info['user_id']);
  $events_per_page = 5;
  $sort = "event_date_start DESC";

  // GET PRIVACY LEVEL AND SET WHERE
  $privacy_max = $owner->user_privacy_max($user);
  $where = "(event_privacy & $privacy_max)";

  // GET TOTAL LISTINGS
  $total_events = $event->event_total($where);

  // GET LISTING ARRAY
  $events = $event->event_list(0, $events_per_page, $sort, $where);

  // ASSIGN ENTRIES SMARY VARIABLE
  $smarty->assign_by_ref('events', $events);
  $smarty->assign('total_events', $total_events);
  
  if( $total_events ) $plugin_vars['menu_profile_tab'] = array('file'=> 'profile_event_list.tpl', 'title' => 3000007);
  $plugin_vars['menu_profile_side'] = Array('file'=> 'profile_event.tpl', 'title' => 3000007);
}

// SET EVENT MENU VARS
if( $page=="event" )
{
  // SET PROFILE MENU VARS
  $plugin_vars['menu_event_tab'] = NULL;
  $plugin_vars['menu_event_side'] = NULL;
}
  



// SET HOOKS
SE_Hook::register("se_search_do", 'search_event');

SE_Hook::register("se_user_delete", 'deleteuser_event');

SE_Hook::register("se_mediatag", 'mediatag_event');

SE_Hook::register("se_action_privacy", 'action_privacy_event');

SE_Hook::register("se_site_statistics", 'site_statistics_event');

?>