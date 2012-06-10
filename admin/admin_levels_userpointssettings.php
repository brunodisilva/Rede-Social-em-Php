<?
$page = "admin_levels_userpointssettings";
include "admin_header.php";

$task = semods::getpost('task', 'main');
$level_id = semods::getpost('level_id', 0);

// VALIDATE LEVEL ID
$level_info = semods::db_query_assoc( "SELECT * FROM se_levels WHERE level_id = $level_id" );
if(!$level_info) {
  header("Location: admin_levels.php");
  exit();
}

// SET RESULT VARIABLE
$result = 0;
$is_error = 0;
$error_message = "";


// SAVE CHANGES
if($task == "dosave") {

  $level_userpoints_allow = semods::post('level_userpoints_allow', 0);
  $level_userpoints_allow_transfer = semods::post('level_userpoints_allow_transfer', 0);
  $level_userpoints_max_transfer = intval(semods::post('level_userpoints_max_transfer', 0));

  // CHECK MAX TRANSFER
  if($level_userpoints_max_transfer < 0) {
	$level_userpoints_max_transfer = 0;
  }
  
  $database->database_query("UPDATE se_levels SET 
		  level_userpoints_allow=$level_userpoints_allow,
		  level_userpoints_allow_transfer=$level_userpoints_allow_transfer,
		  level_userpoints_max_transfer=$level_userpoints_max_transfer
		  WHERE level_id = $level_id"
		  );

/*
  // damn verification
  
  $level_values  array();
  foreach($_POST as $key => $value) {
	if(strncmp( $key, "level_userpoints_", 17) == 0 {
      $level_values[] = $key . '=' . "'" . $value . "'";
	}
  }
  
  $query = "UPDATE se_levels SET " . implode( ',', $level_values ) . " WHERE level_id = $level_id";

  $database->database_query( $query );
  
*/

  // refresh	
  $level_info = semods::db_query_assoc( "SELECT * FROM se_levels WHERE level_id = $level_id" );
  $result = 1;

  
} // END DOSAVE TASK


// ASSIGN VARIABLES AND SHOW ALBUM SETTINGS PAGE
$smarty->assign('result', $result);
$smarty->assign('is_error', $is_error);
$smarty->assign('error_message', $error_message);
$smarty->assign('level_info', $level_info);
$smarty->assign('level_id', $level_info['level_id']);
$smarty->assign('level_name', $level_info['level_name']);
$smarty->assign('level_userpoints_allow', $level_info['level_userpoints_allow']);
$smarty->assign('level_userpoints_allow_transfer', $level_info['level_userpoints_allow_transfer']);
$smarty->assign('level_userpoints_max_transfer', $level_info['level_userpoints_max_transfer']);
include "admin_footer.php";
?>