<?php
$page = "admin_levels_recommendedsettings";
include "admin_header.php";

$task = rc_toolkit::get_request('task','main');
$level_id = rc_toolkit::get_request('level_id',0);

// VALIDATE LEVEL ID
$level = $database->database_query("SELECT * FROM se_levels WHERE level_id='$level_id'");
if($database->database_num_rows($level) != 1) { 
  header("Location: admin_levels.php");
  exit();
}
$level_info = $database->database_fetch_assoc($level);


// SET RESULT AND ERROR VARS
$result = 0;
$is_error = 0;

// SAVE CHANGES
if($task == "dosave") {
  $level_recommended_allow = $_POST['level_recommended_allow'] ? 1 : 0;

  // IF THERE WERE NO ERRORS, SAVE CHANGES
  if($is_error == 0) {
    $database->database_query("UPDATE se_levels SET level_recommended_allow='$level_recommended_allow' WHERE level_id='$level_info[level_id]'");
    $level_info = $database->database_fetch_assoc($database->database_query("SELECT * FROM se_levels WHERE level_id='$level_info[level_id]'"));
    $result = 1;
  }
}


// ASSIGN VARIABLES AND SHOW USER EVENTS PAGE
$smarty->assign('level_info', $level_info);

$smarty->assign('level_id', $level_info[level_id]);
$smarty->assign('level_name', $level_info[level_name]);
$smarty->assign('result', $result);
$smarty->assign('is_error', $is_error);
$smarty->assign('error_message', $error_message);
$smarty->assign('recommended_allow', $level_info[level_recommended_allow]);
include "admin_footer.php";
exit();
?>
