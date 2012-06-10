<?php

/* $Id: user_job.php 7 2009-01-11 06:01:49Z john $ */

$page = "user_job";
include "header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }
if(isset($_POST['search'])) { $search = $_POST['search']; } elseif(isset($_GET['search'])) { $search = $_GET['search']; } else { $search = ""; }

// ENSURE JOBS ARE ENABLED FOR THIS USER
if( !$user->level_info['level_job_allow'] )
{
  header("Location: user_home.php");
  exit();
}

// SET CLAUSES
$sort = "job_date DESC";
if( trim($search) )
  $where = "(job_title LIKE '%$search%' OR job_body LIKE '%$search%')";
else
  $where = NULL;

// CREATE JOB OBJECT
$entries_per_page = 10;
$job = new se_job($user->user_info['user_id']);

// DELETE NECESSARY ENTRIES
//$start = ($p - 1) * $entries_per_page;
//if($task == "delete") { $job->job_delete($start, $entries_per_page, $sort, $where); }

// GET TOTAL ENTRIES
$total_jobs = $job->job_total($where);

// MAKE ENTRY PAGES
$page_vars = make_page($total_jobs, $entries_per_page, $p);

// GET ENTRY ARRAY
$jobs = $job->job_list($page_vars[0], $entries_per_page, $sort, $where);


// ASSIGN VARIABLES AND SHOW VIEW ENTRIES PAGE
$smarty->assign('search', $search);
$smarty->assign('jobs', $jobs);
$smarty->assign('total_jobs', $total_jobs);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($jobs));
include "footer.php";
?>