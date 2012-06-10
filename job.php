<?php

/* $Id: job.php 16 2009-01-13 04:01:31Z john $ */

$page = "job";
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
if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }
if(isset($_GET['job_id'])) { $job_id = $_GET['job_id']; } elseif(isset($_POST['job_id'])) { $job_id = $_POST['job_id']; } else { $job_id = 0; }


// DISPLAY ERROR PAGE IF NO OWNER
$job = new se_job($user->user_info['user_id'], $job_id);
if( !$job->job_exists || !$owner->user_exists )
{
  $page = "error";
  $smarty->assign('error_header', 639);
  $smarty->assign('error_message', 828);
  $smarty->assign('error_submit', 641);
  include "footer.php";
}


// GET PRIVACY LEVEL
$privacy_max = $owner->user_privacy_max($user);
$allowed_to_view    = (bool) ($privacy_max & $job->job_info['job_privacy' ]);
$allowed_to_comment = (bool) ($privacy_max & $job->job_info['job_comments']);


// UPDATE JOB VIEWS IF GROUP VISIBLE
if( $allowed_to_view )
{
  $job->job_info['job_views']++;
  $sql = "UPDATE se_jobs SET job_views='{$job->job_info['job_views']}' WHERE job_id='{$job->job_info['job_id']}' LIMIT 1";
  $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
}


// GET JOB CATEGORY
/*
$group_category = "";
$group_category_query = $database->database_query("SELECT groupcat_id, groupcat_title FROM se_groupcats WHERE groupcat_id='".$group->group_info[group_groupcat_id]."' LIMIT 1");
if($database->database_num_rows($group_category_query) == 1) {
  $group_category_info = $database->database_fetch_assoc($group_category_query);
  $group_category = $group_category_info[groupcat_title];
}
*/


// GET JOB COMMENTS
$comment = new se_comment('job', 'job_id', $job->job_info['job_id']);
$total_comments = $comment->comment_total();
$comments = $comment->comment_list(0, 10);


// GET CUSTOM JOB STYLE IF ALLOWED
if( $job->jobowner_level_info['level_job_style'] && $allowed_to_view )
{
  $sql ="SELECT jobstyle_css FROM se_jobstyles WHERE jobstyle_user_id='{$owner->user_info['user_id']}' LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( $database->database_num_rows($resource) )
    $jobstyle_info = $database->database_fetch_assoc($resource);
  
  if( $jobstyle_info )
    $global_css = $jobstyle_info['jobstyle_css'];
}


// MAKE SURE TITLE IS NOT EMPTY, CONVERT BODY HTML CHARACTERS BACK
if( !$job->job_info['job_title'] )
  $job->job_info['job_title'] = 'Untitled';

$job->job_info['job_body'] = str_replace("\r\n", "", html_entity_decode($job->job_info['job_body']));


// GET JOB ALBUM INFO AND MEDIA ARRAY
$sql = "SELECT * FROM se_jobalbums WHERE jobalbum_job_id='{$job->job_info['job_id']}' LIMIT 1";
$resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);

if( $database->database_num_rows($resource) )
{
  $jobalbum_info = $database->database_fetch_assoc($resource);
  
  $file_array = $job->job_media_list(0, 10, "jobmedia_id ASC", "(jobmedia_jobalbum_id='{$jobalbum_info['jobalbum_id']}')", TRUE);
  $total_files = $job->job_media_total($jobalbum_info['jobalbum_id']);
}


// GET SUBCAT IF NECESSARY
$sql = "SELECT jobcat_id, jobcat_dependency FROM se_jobcats WHERE jobcat_id='{$job->job_info['job_jobcat_id']}' LIMIT 1";
$resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
if( $database->database_num_rows($resource) )
  $thiscat = $database->database_fetch_assoc($resource);

if( !$thiscat || !$thiscat['jobcat_dependency'] )
{
  $job->job_info['job_jobsubcat_id'] = 0;
}
else
{
  $job->job_info['job_jobsubcat_id']  = $job->job_info['job_jobcat_id'];
  $job->job_info['job_jobcat_id']     = $thiscat['jobcat_dependency'];
}


// GET FIELDS
$jobcat_info = $database->database_fetch_assoc($database->database_query("SELECT t1.jobcat_id AS subcat_id, t1.jobcat_title AS subcat_title, t1.jobcat_dependency AS subcat_dependency, t2.jobcat_id AS cat_id, t2.jobcat_title AS cat_title FROM se_jobcats AS t1 LEFT JOIN se_jobcats AS t2 ON t1.jobcat_dependency=t2.jobcat_id WHERE t1.jobcat_id='{$job->job_info['job_jobcat_id']}'"));
if( !$jobcat_info['subcat_dependency'] )
{
  $cat_where = "jobcat_id='{$job->job_info['job_jobcat_id']}'";
}
else
{
  $cat_where = "jobcat_id='{$jobcat_info['subcat_dependency']}'";
}
$field = new se_field("job", $job->jobvalue_info);
$field->cat_list(0, 1, 0, $cat_where, "jobcat_id='0'", "");


// DELETE NOTIFICATIONS
if( $user->user_info['user_id']==$owner->user_info['user_id'] )
{
  $database->database_query("
    DELETE FROM
      se_notifys
    USING
      se_notifys
    LEFT JOIN
      se_notifytypes
      ON se_notifys.notify_notifytype_id=se_notifytypes.notifytype_id
    WHERE
      se_notifys.notify_user_id='{$owner->user_info[user_id]}' AND
      se_notifytypes.notifytype_name='jobcomment' AND
      notify_object_id='{$job->job_info['job_id']}'
  ");
}


// SET SEO STUFF
$global_page_content = $job->job_info['job_title'];
$global_page_content = cleanHTML(str_replace('>', '> ', $global_page_content), NULL);
if( strlen($global_page_content)>255 ) $global_page_content = substr($global_page_content, 0, 251).'...';
$global_page_content = addslashes(trim(preg_replace('/\s+/', ' ',$global_page_content)));

$global_page_title = array(
  6400144,
  $owner->user_displayname,
  $global_page_content
);

$global_page_content = $job->job_info['job_body'];
$global_page_content = cleanHTML(str_replace('>', '> ', $global_page_content), NULL);
if( strlen($global_page_content)>255 ) $global_page_content = substr($global_page_content, 0, 251).'...';
$global_page_content = addslashes(trim(preg_replace('/\s+/', ' ',$global_page_content)));

$global_page_description = array(
  6400144,
  $owner->user_displayname,
  $global_page_content
);


// ASSIGN VARIABLES AND DISPLAY JOB PAGE
$smarty->assign_by_ref('job', $job);
$smarty->assign_by_ref('cats', $field->cats);

$smarty->assign('comments', $comments);
$smarty->assign('total_comments', $total_comments);
$smarty->assign('allowed_to_comment', $allowed_to_comment);

$smarty->assign('files', $file_array);
$smarty->assign('total_files', $total_files);
include "footer.php";
?>