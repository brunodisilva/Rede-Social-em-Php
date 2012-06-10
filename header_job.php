<?php

/* $Id: header_job.php 7 2009-01-11 06:01:49Z john $ */

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
defined('SE_PAGE') or exit();

// INCLUDE JOBS CLASS FILE
include "./include/class_job.php";

// INCLUDE JOBS FUNCTION FILE
include "./include/functions_job.php";

// PRELOAD LANGUAGE
SE_Language::_preload(6400007);

// SET MAIN MENU VARS
if( ($user->user_exists && $user->level_info['level_job_allow']) || (!$user->user_exists && $setting['setting_permission_job']) )
{
  $plugin_vars['menu_main'] = array('file' => 'browse_jobs.php', 'title' => 6400007);
}

// SET USER MENU VARS
if( ($user->user_exists && $user->level_info['level_job_allow']) )
{
  $plugin_vars['menu_user'] = array('file' => 'user_job.php', 'icon' => 'job_job16.gif', 'title' => 6400007);
}

// SET PROFILE MENU VARS
if( $owner->level_info['level_job_allow'] && $page=="profile" )
{
  // START JOB
  $job = new se_job($owner->user_info['user_id']);
  $listings_per_page = 5;
  $sort = "job_date DESC";

  // GET PRIVACY LEVEL AND SET WHERE
  $privacy_max = $owner->user_privacy_max($user);
  $where = "(job_privacy & $privacy_max)";

  // GET TOTAL LISTINGS
  $total_jobs = $job->job_total($where);

  // GET LISTING ARRAY
  $jobs = $job->job_list(0, $listings_per_page, $sort, $where);

  // ASSIGN ENTRIES SMARY VARIABLE
  $smarty->assign_by_ref('jobs', $jobs);
  $smarty->assign('total_jobs', $total_jobs);
  
  //print_r($jobs);
  
  // SET PROFILE MENU VARS
  if( $total_jobs )
  {
    $plugin_vars['menu_profile_tab'] = array('file'=> 'profile_job.tpl', 'title' => 6400007);
    $plugin_vars['menu_profile_side'] = "";
  }
}



// SET HOOKS
SE_Hook::register("se_search_do", 'search_job');

SE_Hook::register("se_user_delete", 'deleteuser_job');

SE_Hook::register("se_site_statistics", 'site_statistics_job');

?>