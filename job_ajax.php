<?php

/* $Id: job_ajax.php 16 2009-01-13 04:01:31Z john $ */

ob_start();
$page = "job_ajax";
include "header.php";


// PROCESS INPUT
$task           = ( !empty($_POST['task'])          ? $_POST['task']          : ( !empty($_GET['task'])           ? $_GET['task']           : NULL ) );
$job_id  = ( !empty($_POST['job_id']) ? $_POST['job_id'] : ( !empty($_GET['job_id'])  ? $_GET['job_id']  : NULL ) );



// DELETE
if( $task=="deletejob" )
{
  $job = new se_job($user->user_info['user_id']);
  
  // OUTPUT
  ob_end_clean();
  
  if( $user->user_exists && $job_id && $job->job_delete($job_id) )
    echo '{"result":"success"}';
  else
    echo '{"result":"failure"}';
  
  exit();
}

?>