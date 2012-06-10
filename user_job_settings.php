<?php

/* $Id: user_job_settings.php 7 2009-01-11 06:01:49Z john $ */

$page = "user_job_settings";
include "header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }

// ENSURE jobS ARE ENABLED FOR THIS USER
if( !$user->level_info['level_job_allow'] )
{
  header("Location: user_home.php");
  exit();
}

// SET VARS
$result = FALSE;

// SAVE NEW CSS
if($task == "dosave")
{
  $style_job = addslashes(str_replace("-moz-binding", "", strip_tags(htmlspecialchars_decode($_POST['style_job'], ENT_QUOTES))));
  $usersetting_notify_jobcomment = $_POST['usersetting_notify_jobcomment'];
  
  // STYLES
  $sql = "UPDATE se_jobstyles SET jobstyle_css='{$style_job}' WHERE jobstyle_user_id='{$user->user_info[user_id]}'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  // USERSETTINGS
  $sql = "
    UPDATE
      se_usersettings
    SET
      usersetting_notify_jobcomment='{$usersetting_notify_jobcomment}'
    WHERE
      usersetting_user_id='{$user->user_info['user_id']}'
    LIMIT
      1
  ";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  
  $user->user_lastupdate();
  $user = new se_user(Array($user->user_info['user_id'])); // HUH?
  $result = TRUE;
}


// GET THIS USER'S JOB CSS
$sql = "SELECT jobstyle_css FROM se_jobstyles WHERE jobstyle_user_id='{$user->user_info['user_id']}' LIMIT 1";
$resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");

if( $database->database_num_rows($resource) )
{ 
  $style_info = $database->database_fetch_assoc($resource); 
}
else
{
  $sql = "INSERT INTO se_jobstyles (jobstyle_user_id, jobstyle_css) VALUES ('{$user->user_info['user_id']}', '')";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  $style_info = array
  (
    'jobstyle_id'      => $database->database_insert_id(),
    'jobstyle_user_id' => $user->user_info['user_id'],
    'jobstyle_css'     => ''
  );
}


// ASSIGN USER SETTINGS
$user->user_settings();


// ASSIGN SMARTY VARIABLES AND DISPLAY job STYLE PAGE
$smarty->assign('style_job', htmlspecialchars($style_info['jobstyle_css'], ENT_QUOTES, 'UTF-8'));
$smarty->assign('result', $result);
include "footer.php";
?>