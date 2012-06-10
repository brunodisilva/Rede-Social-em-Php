<?php

/* $Id: user_job_listing.php 16 2009-01-13 04:01:31Z john $ */

$page = "user_job_listing";
include "header.php";


$task           = ( !empty($_POST['task'])          ? $_POST['task']          : ( !empty($_GET['task'])           ? $_GET['task']           : NULL ) );
$job_id  = ( !empty($_POST['job_id']) ? $_POST['job_id'] : ( !empty($_GET['job_id'])  ? $_GET['job_id']  : NULL ) );


// ENSURE JOBS ARE ENABLED FOR THIS USER
if( !$user->level_info['level_job_allow'] )
{
  header("Location: user_home.php");
  exit();
}


// GET PRIVACY SETTINGS
$level_job_privacy = unserialize($user->level_info['level_job_privacy']);
rsort($level_job_privacy);
$level_job_comments = unserialize($user->level_info['level_job_comments']);
rsort($level_job_comments);


// INITIALIZE VARIABLES
$is_error = FALSE;

$job = new se_job($user->user_info['user_id'], $job_id);

if( $job->job_exists && $user->user_info['user_id']!=$job->job_info['job_user_id'] )
{
  header('user_home.php');
  exit();
}

if( !$job->job_exists ) $job->job_info = array
(
  'job_title'                => '',
  'job_body'                 => '',
  'job_jobcat_id'     => 0,
  'job_jobsubcat_id'  => 0,
  'job_search'               => 1,
  'job_privacy'              => $level_job_privacy[0],
  'job_comments'             => $level_job_comments[0]
);


// BEGIN POST ENTRY TASK
if( $task=="dosave" )
{
  $job->job_info['job_id']                   = $_POST['job_id'];
  $job->job_info['job_title']                = censor($_POST['job_title']);
  $job->job_info['job_body']                 = censor(str_replace("\r\n", "<br />", $_POST['job_body']));
  $job->job_info['job_search']               = $_POST['job_search'];
  $job->job_info['job_privacy']              = $_POST['job_privacy'];
  $job->job_info['job_comments']             = $_POST['job_comments'];
  $job->job_info['job_jobcat_id']     = $_POST['job_jobcat_id'];
  $job->job_info['job_jobsubcat_id']  = $_POST['job_jobsubcat_id'];
  
  // GET FIELDS
  $field = new se_field("job");
  $field->cat_list(1, 0, 0, "jobcat_id='{$job->job_info[job_jobcat_id]}'", "", "");
  $selected_fields = $field->fields_all;
  $is_error = $field->is_error;
  
  if( !$job->job_info['job_id'] )
    $job->job_info['job_id'] = NULL;
  
  // CHECK TO MAKE SURE TITLE HAS BEEN ENTERED
  if( !trim($job->job_info['job_title']) )
    $is_error = 6400100;

  // CHECK TO MAKE SURE CATEGORY HAS BEEN SELECTED
  if( !$job->job_info['job_jobcat_id'] )
    $is_error = 6400101;
    
  // MAKE SURE SUBMITTED PRIVACY OPTIONS ARE ALLOWED, IF NOT, SET TO EVERYONE
  if( !in_array($job->job_info['job_privacy'] , $level_job_privacy ) )
    $job->job_info['job_privacy']  = $level_job_privacy[0] ;
  if( !in_array($job->job_info['job_comments'], $level_job_comments) )
    $job->job_info['job_comments'] = $level_job_comments[0];
  
  // CHECK THAT SEARCH IS NOT BLANK
  if( !$user->level_info['level_job_search'] )
    $job->job_info['job_search'] = 1;
  
  
  // IF NO ERROR, SAVE GROUP
  if( !$is_error )
  {
    // SET job CATEGORY ID
    if( $job->job_info['job_jobsubcat_id'] && $job->job_info['job_jobsubcat_id'] )
      $job->job_info['job_jobcat_id'] = $job->job_info['job_jobsubcat_id'];
    
    $job->job_info['job_id'] = $job->job_post(
      $job->job_info['job_id'],
      $job->job_info['job_title'],
      $job->job_info['job_body'],
      $job->job_info['job_jobcat_id'],
      $job->job_info['job_search'],
      $job->job_info['job_privacy'],
      $job->job_info['job_comments'],
      $field->field_query
    );
    
    // UPDATE LAST UPDATE DATE (SAY THAT 10 TIMES FAST)
    $user->user_lastupdate();
    
    // INSERT ACTION
    if( $job_id )
    {
      $job_title_short = $job->job_info['job_title'];
      if( strlen($job_title_short) > 100 ) $job_title_short = substr($job_title_short, 0, 97); $job_title_short .= "...";
      $actions->actions_add(
        $user,
        "postjob",
        array(
          $user->user_info['user_username'],
          $user->user_displayname,
          $job->job_info['job_id'],
          $job_title_short
        )
      );
    }
    
    header($job_id ? "Location: user_job.php" : "Location: user_job_media.php?job_id={$job->job_info['job_id']}&justadded=1" );
    exit();
  }
}





// GET PREVIOUS PRIVACY SETTINGS
for($c=0;$c<count($level_job_privacy);$c++) {
  if(user_privacy_levels($level_job_privacy[$c]) != "") {
    SE_Language::_preload(user_privacy_levels($level_job_privacy[$c]));
    $privacy_options[$level_job_privacy[$c]] = user_privacy_levels($level_job_privacy[$c]);
  }
}

for($c=0;$c<count($level_job_comments);$c++) {
  if(user_privacy_levels($level_job_comments[$c]) != "") {
    SE_Language::_preload(user_privacy_levels($level_job_comments[$c]));
    $comment_options[$level_job_comments[$c]] = user_privacy_levels($level_job_comments[$c]);
  }
}


// GET FIELDS
$field = new se_field("job", $job->jobvalue_info);
$field->cat_list(0, 0, 0, "", "", "");
$cat_array = $field->cats;
if( $is_error && $job_info['job_jobcat_id'] )
{
  $selected_cat_array = array_filter($cat_array, create_function('$a', 'if($a["cat_id"] == "'.$job_info['job_jobcat_id'].'") { return $a; }'));
  foreach( $selected_cat_array as $key=>$val )
  {
    $cat_array[$key]['fields'] = $selected_fields;
  }
}


// GET SUBCAT IF NECESSARY
$thiscat = $database->database_fetch_assoc($database->database_query("SELECT jobcat_id, jobcat_dependency FROM se_jobcats WHERE jobcat_id='{$job->job_info[job_jobcat_id]}'"));
if( !$thiscat['jobcat_dependency'] )
{
  $job->job_info['job_jobsubcat_id'] = 0;
}
else
{
  $job->job_info['job_jobsubcat_id'] = $job->job_info['job_jobcat_id'];
  $job->job_info['job_jobcat_id'] = $thiscat['jobcat_dependency'];
}


// REMOVE BREAKS
$job->job_info['job_body'] = str_replace("<br />", "\r\n", $job->job_info['job_body']);




// ASSIGN VARIABLES AND SHOW ADD GROUPS PAGE
$smarty->assign('is_error', $is_error);

$smarty->assign_by_ref('job', $job);
$smarty->assign_by_ref('cats', $cat_array);

$smarty->assign('privacy_options', $privacy_options);
$smarty->assign('comment_options', $comment_options);
include "footer.php";
?>