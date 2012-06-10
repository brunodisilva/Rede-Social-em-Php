<?php

/* $Id: user_job_media.php 16 2009-01-13 04:01:31Z john $ */

$page = "user_job_media";
include "header.php";

if(isset($_GET['task'])) { $task = $_GET['task']; } elseif(isset($_POST['task'])) { $task = $_POST['task']; } else { $task = ""; }
if(isset($_GET['job_id'])) { $job_id = $_GET['job_id']; } elseif(isset($_POST['job_id'])) { $job_id = $_POST['job_id']; } else { $job_id = 0; }
if(isset($_GET['jobmedia_id'])) { $jobmedia_id = $_GET['jobmedia_id']; } elseif(isset($_POST['jobmedia_id'])) { $jobmedia_id = $_POST['jobmedia_id']; } else { $jobmedia_id = 0; }
if(isset($_POST['spot'])) { $spot = $_POST['spot']; } else { $spot = "1"; }
if(isset($_GET['justadded'])) { $justadded = $_GET['justadded']; } elseif(isset($_POST['justadded'])) { $justadded = $_POST['justadded']; } else { $justadded = 0; }

// ENSURE JOB ARE ENABLED FOR THIS USER
if( !$user->level_info['level_job_allow'] )
{
  header("Location: user_home.php");
  exit();
}

// MAKE SURE THIS JOB BELONGS TO THIS USER AND IS NUMERIC
$job = $database->database_query("SELECT * FROM se_jobs WHERE job_id='{$job_id}' AND job_user_id='{$user->user_info['user_id']}' LIMIT 1");
if( !$database->database_num_rows($job) )
{
  header("Location: user_job.php");
  exit();
}
$job_info = $database->database_fetch_assoc($job);

// INITIALIZE JOB OBJECT
$job = new se_job($user->user_info['user_id'], $job_id);

// SHOW BLANK PAGE FOR AJAX
if($task == "blank") {
  exit;
}




// DELETE SMALL PHOTO WITH IFRAME AJAX
if($task == "deletemedia") {

  $job->job_media_delete(0, 1, "se_jobmedia.jobmedia_id DESC", "se_jobmedia.jobmedia_id='{$jobmedia_id}'");
  exit;
}




// UPLOAD LARGE PHOTO WITH IFRAME AJAX
if($task == "uploadmedia") {

  // GET ALBUM INFO
  $jobalbum_info = $database->database_fetch_assoc($database->database_query("SELECT * FROM se_jobalbums WHERE jobalbum_job_id='{$job_id}' LIMIT 1"));

  // GET TOTAL SPACE USED
  $space_used = $job->job_media_space();
  $space_left = ( !empty($job->jobowner_level_info['level_job_album_storage']) ? ($job->jobowner_level_info['level_job_album_storage'] - $space_used) : FALSE );

  $fileid = "file";
  if($_FILES[$fileid]['name'] != "") {
    $file_result[$fileid] = $job->job_media_upload($fileid, $jobalbum_info['jobalbum_id'], $space_left);
    if($file_result[$fileid]['is_error'] == 0) {
      $file_result[$fileid]['message'] = $job->job_dir($job_id).$file_result[$fileid]['jobmedia_id'].".".$file_result[$fileid]['jobmedia_ext'];
      $jobmedia_id_new = $file_result[$fileid]['jobmedia_id'];
    } else {
      $file_result[$fileid]['message'] = addslashes($file_result[$fileid]['error_message']);
    }
  } else {
      $file_result[$fileid]['is_error'] = 1;
      $file_result[$fileid]['message'] = $user_job_edit_media[16];
  }

  $result = $file_result[$fileid]['message'];
  $result_code = $file_result[$fileid]['is_error'];

  echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'></head><body onLoad=\"parent.uploadComplete('$result_code', '$result', '$spot', '$jobmedia_id_new');\"></body></html>";
  exit;
}




// UPLOAD SMALL PHOTO
if($task == "upload") {
  $job->job_photo_upload("photo");
  $is_error = $job->is_error;
  $error_message = $job->error_message;
  if($is_error == 0) { $job->job_lastupdate(); }
}




// GET JOB ALBUM INFO
$jobalbum_info = $database->database_fetch_assoc($database->database_query("SELECT * FROM se_jobalbums WHERE jobalbum_job_id='{$job->job_info['job_id']}' LIMIT 1"));

// GET TOTAL FILES IN JOB ALBUM
$total_files = $job->job_media_total($jobalbum_info[jobalbum_id]);

// MAKE MEDIA PAGES
$files_per_page = 16;
$p = 1;
$page_vars = make_page($total_files, $files_per_page, $p);

// GET MEDIA ARRAY
$file_array = $job->job_media_list($page_vars[0], $files_per_page, "jobmedia_id ASC", "(jobmedia_jobalbum_id='{$jobalbum_info['jobalbum_id']}')");

$smarty->assign('files', $file_array);
$smarty->assign('total_files', $total_files);
$smarty->assign('error_message', $error_message);
$smarty->assign('job', $job);
$smarty->assign('job_id', $job_id);
$smarty->assign('justadded', $justadded);
include "footer.php";
?>