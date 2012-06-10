<?php

/* $Id: browse_jobs.php 16 2009-01-13 04:01:31Z john $ */

$page = "browse_jobs";
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


// PARSE GET/POST
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }
if(isset($_POST['s'])) { $s = $_POST['s']; } elseif(isset($_GET['s'])) { $s = $_GET['s']; } else { $s = "job_datecreated DESC"; }
if(isset($_POST['v'])) { $v = $_POST['v']; } elseif(isset($_GET['v'])) { $v = $_GET['v']; } else { $v = 0; }
if(isset($_POST['jobcat_id'])) { $jobcat_id = $_POST['jobcat_id']; } elseif(isset($_GET['jobcat_id'])) { $jobcat_id = $_GET['jobcat_id']; } else { $jobcat_id = 0; }
if(isset($_POST['job_search'])) { $job_search = $_POST['job_search']; } elseif(isset($_GET['job_search'])) { $job_search = $_GET['job_search']; } else { $job_search = NULL; }

// ENSURE SORT/VIEW ARE VALID
if($s != "job_date DESC" && $s != "job_dateupdated DESC" && $s != "job_views DESC" && $s != "total_comments DESC") { $s = "job_date DESC"; }
if($v != "0" && $v != "1") { $v = 0; }


// SET WHERE CLAUSE
$where = "CASE
	    WHEN se_jobs.job_user_id='{$user->user_info['user_id']}'
	      THEN TRUE
	    WHEN ((se_jobs.job_privacy & @SE_PRIVACY_REGISTERED) AND '{$user->user_exists}'<>0)
	      THEN TRUE
	    WHEN ((se_jobs.job_privacy & @SE_PRIVACY_ANONYMOUS) AND '{$user->user_exists}'=0)
	      THEN TRUE
	    WHEN ((se_jobs.job_privacy & @SE_PRIVACY_FRIEND) AND '{$user->user_exists}'<>0 AND (SELECT TRUE FROM se_friends WHERE friend_user_id1=se_jobs.job_user_id AND friend_user_id2='{$user->user_info['user_id']}' AND friend_status='1' LIMIT 1))
	      THEN TRUE
	    WHEN ((se_jobs.job_privacy & @SE_PRIVACY_SUBNET) AND '{$user->user_exists}'<>0 AND (SELECT TRUE FROM se_users WHERE user_id=se_jobs.job_user_id AND user_subnet_id='{$user->user_info['user_subnet_id']}' LIMIT 1))
	      THEN TRUE
	    WHEN ((se_jobs.job_privacy & @SE_PRIVACY_FRIEND2) AND '{$user->user_exists}'<>0 AND (SELECT TRUE FROM se_friends AS friends_primary LEFT JOIN se_users ON friends_primary.friend_user_id1=se_users.user_id LEFT JOIN se_friends AS friends_secondary ON friends_primary.friend_user_id2=friends_secondary.friend_user_id1 WHERE friends_primary.friend_user_id1=se_jobs.job_user_id AND friends_secondary.friend_user_id2='{$user->user_info['user_id']}' AND se_users.user_subnet_id='{$user->user_info['user_subnet_id']}' LIMIT 1))
	      THEN TRUE
	    ELSE FALSE
	END";



// ONLY MY FRIENDS' JOBS
if( $v=="1" && $user->user_exists )
{
  // SET WHERE CLAUSE
  $where .= " AND (SELECT TRUE FROM se_friends WHERE friend_user_id1='{$user->user_info['user_id']}' AND friend_user_id2=se_jobs.job_user_id AND friend_status=1)";
}



// SPECIFIC JOB CATEGORY
if( $jobcat_id )
{
  $sql = "SELECT jobcat_id, jobcat_title, jobcat_dependency FROM se_jobcats WHERE jobcat_id={$jobcat_id} LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( $database->database_num_rows($resource) )
  {
    $jobcat = $database->database_fetch_assoc($resource);
    
    if( !$jobcat['jobcat_dependency'] )
    {
      $cat_ids[] = $jobcat['jobcat_id'];
      $depcats = $database->database_query("SELECT jobcat_id FROM se_jobcats WHERE jobcat_id='{$jobcat['jobcat_id']}' OR jobcat_dependency='{$jobcat['jobcat_id']}'");
      while($depcat_info = $database->database_fetch_assoc($depcats)) { $cat_ids[] = $depcat_info['jobcat_id']; }
      $where .= " AND se_jobs.job_jobcat_id IN('".implode("', '", $cat_ids)."')";
    }
    else
    {
      $where .= " AND se_jobs.job_jobcat_id='{$jobcat['jobcat_id']}'";
      $jobsubcat = $jobcat;
      $jobcat = $database->database_fetch_assoc($database->database_query("SELECT jobcat_id, jobcat_title FROM se_jobcats WHERE jobcat_id='{$jobcat['jobcat_dependency']}'"));
    }
  }
}


// GET CATS
$field = new se_field("job");
$field->cat_list(0, 0, 0, "", "", "");
$cat_menu_array = $field->cats;

//$field->cat_list(0, 0, 1, "jobcat_id='{$jobcat['jobcat_id']}'", "", "");
$field->field_list(0, 0, 1, "jobfield_jobcat_id='{$jobcat['jobcat_id']}' && jobfield_search<>'0'");


// BEGIN CONSTRUCTING SEARCH QUERY
//echo $field->field_query;
if( $field->field_query )
  $where .= " && ".$field->field_query;

if( !empty($job_search) )
{
  $where .= " && MATCH(job_title, job_body) AGAINST ('{$job_search}' IN BOOLEAN MODE) ";
}


// CREATE JOB OBJECT, GET TOTAL JOBS, MAKE ENTRY PAGES, GET JOB ARRAY
$job = new se_job();

$total_jobs = $job->job_total($where, TRUE);
$jobs_per_page = 10;
$page_vars = make_page($total_jobs, $jobs_per_page, $p);

$job_array = $job->job_list($page_vars[0], $jobs_per_page, $s, $where, TRUE);




// ASSIGN SMARTY VARIABLES AND DISPLAY JOBS PAGE
$smarty->assign('jobcat_id', $jobcat_id);
$smarty->assign('jobcat', $jobcat);
$smarty->assign('jobsubcat', $jobsubcat);
$smarty->assign('job_search', $job_search);

$smarty->assign_by_ref('cats_menu', $cat_menu_array);
$smarty->assign_by_ref('cats', $field->cats);
$smarty->assign_by_ref('fields', $field->fields);
$smarty->assign_by_ref('url_string', $field->url_string);

$smarty->assign('jobs', $job_array);
$smarty->assign('total_jobs', $total_jobs);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($job_array));
$smarty->assign('s', $s);
$smarty->assign('v', $v);
include "footer.php";
?>