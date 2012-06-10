<?
$page = "admin_userpoints_pointranks";
include "admin_header.php";

$task = semods::getpost('task', 'main');


// SET RESULT VARIABLE
$result = 0;

// SAVE CHANGES
if($task == "dosave") {

  $setting_userpoints_enable_pointrank = semods::post('setting_userpoints_enable_pointrank', 0);
  
  $database->database_query("UPDATE se_semods_settings SET 
			setting_userpoints_enable_pointrank=$setting_userpoints_enable_pointrank
			");
  
  $point_rank_points = $_POST['point_rank_points'];
  $point_rank_text = $_POST['point_rank_text'];
  

  
  $database->database_query("TRUNCATE se_semods_userpointranks");
  foreach($point_rank_points as $key => $value ) {
	if(($value !== ''))
	  $database->database_query("INSERT INTO se_semods_userpointranks( userpointrank_amount, userpointrank_text ) VALUES ( $value, '{$point_rank_text[$key]}' )");
  }
  
  // recompile static rankings
  $smarty->clear_compiled_tpl('user_points_staticrank.tpl');
  
  $result = 1;

}

// Load from DB
$rows = $database->database_query("SELECT * FROM se_semods_userpointranks ORDER BY userpointrank_amount");
while($row = $database->database_fetch_assoc( $rows) ) {
  $point_ranks[] = $row;
}
$point_ranks_count = count($points_ranks);

// ASSIGN VARIABLES AND SHOW GENERAL SETTINGS PAGE
$smarty->assign('result', $result);
$smarty->assign('error', $error);

$smarty->assign('point_ranks', $point_ranks);
$smarty->assign('point_ranks_count', $point_ranks_count);
$smarty->assign('setting_userpoints_enable_pointrank', semods::get_setting('userpoints_enable_pointrank') );

include "admin_footer.php";
?>