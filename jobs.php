<?php

/* $Id: jobs.php 16 2009-01-13 04:01:31Z john $ */

$page = "jobs";
include "header.php";


// DISPLAY ERROR PAGE IF USER IS NOT LOGGED IN AND ADMIN SETTING REQUIRES REGISTRATION
if( !$user->user_exists && !$setting['setting_permission_job'] )
{
  $page = "error";
  $smarty->assign('error_header', 639);
  $smarty->assign('error_message', 656);
  $smarty->assign('error_submit', 641);
  include "footer.php";
}

// DISPLAY ERROR PAGE IF NO OWNER
if( !$owner->user_exists )
{
  $page = "error";
  $smarty->assign('error_header', 639);
  $smarty->assign('error_message', 828);
  $smarty->assign('error_submit', 641);
  include "footer.php";
}

// ENSURE jobS ARE ENABLED FOR THIS USER
if( !$owner->level_info['level_job_allow'] )
{
  header("Location: ".$url->url_create('profile', $owner->user_info['user_username']));
  exit();
}


// INIT VARS
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }


// SET PRIVACY LEVEL AND WHERE CLAUSE
$privacy_max = $owner->user_privacy_max($user);
$where = "(job_privacy & $privacy_max)";


// CREATE job OBJECT
$entries_per_page = (int)$owner->level_info['level_job_entries'];
if($entries_per_page <= 0) { $entries_per_page = 10; }
$job = new se_job($owner->user_info[user_id]);


// GET TOTAL ENTRIES, MAKE ENTRY PAGES, GET ENTRY ARRAY
$total_jobs = $job->job_total($where);
$page_vars = make_page($total_jobs, $entries_per_page, $p);
$jobs = $job->job_list($page_vars[0], $entries_per_page, "job_date DESC", $where);


// SET SEO STUFF
$global_page_title = array(6400143, $owner->user_displayname);
$global_page_description = array(6400143, $owner->user_displayname);


// ASSIGN VARIABLES AND DISPLAY job PAGE
$smarty->assign('jobs', $jobs);
$smarty->assign('total_jobs', $total_jobs);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($jobs));
include "footer.php";
?>