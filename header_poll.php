<?php

/* $Id: header_poll.php 12 2009-01-11 06:04:12Z john $ */

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
defined('SE_PAGE') or exit();

// INCLUDE pollS CLASS FILE
include "./include/class_poll.php";

// INCLUDE pollS FUNCTION FILE
include "./include/functions_poll.php";


// PRELOAD LANGUAGE
SE_Language::_preload(2500005);

// SET MAIN MENU VARS
if( (!$user->user_exists && $setting['setting_permission_poll']) || ($user->user_exists && $user->level_info['level_poll_allow']) )
{
  $plugin_vars['menu_main'] = array('file' => 'browse_polls.php', 'title' => 2500005);
}

// SET USER MENU VARS
if( $user->user_exists && $user->level_info['level_poll_allow'] )
{
  $plugin_vars['menu_user'] = array('file' => 'user_poll.php', 'icon' => 'poll_poll16.gif', 'title' => 2500005);
}

// SET PROFILE MENU VARS
if( $owner->level_info['level_poll_allow'] && $page=="profile" )
{
  // START poll
  $poll = new se_poll($owner->user_info['user_id']);
  $entries_per_page = 5;
  $sort = "poll_datecreated DESC";

  // GET PRIVACY LEVEL AND SET WHERE
  $privacy_max = $owner->user_privacy_max($user);
  $where = "(poll_privacy & $privacy_max)";

  // GET TOTAL ENTRIES
  $total_polls = $poll->poll_total($where);

  // GET ENTRY ARRAY
  $polls = $poll->poll_list(0, $entries_per_page, $sort, $where);

  // ASSIGN ENTRIES SMARY VARIABLE
  $smarty->assign('polls', $polls);
  $smarty->assign('total_polls', $total_polls);
  
  // SET PROFILE MENU VARS
  $plugin_vars['menu_profile_side'] = NULL;
  if( $total_polls )
  {
    $plugin_vars['menu_profile_tab'] = array('file'=> 'profile_poll.tpl', 'title' => 2500005);
  }
}


// SET HOOKS
SE_Hook::register("se_search_do", "search_poll");

SE_Hook::register("se_user_delete", "deleteuser_poll");

SE_Hook::register("se_site_statistics", "site_statistics_poll");

?>