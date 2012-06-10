<?
$page = "admin_viewthemes_levels";
include "admin_header.php";


$task = rc_toolkit::get_request('task','main');
$theme_id = rc_toolkit::get_request('theme_id',0);
$level_ids = rc_toolkit::get_request('level_ids',array());


$rc_theme = new rc_theme();
$rc_validator = new rc_validator();
$result = "";

// CREATE THEME
if($task == "update") {
  
  if ($theme_id != 0) {
    $theme = $rc_theme->get_record($theme_id);
    $rc_validator->validate($theme != null, "Please select a valid theme.");
  }
  
  $rc_validator->validate(count($level_ids)>0,"Please check at least one level.");
  
  if (!$rc_validator->has_errors()) {
    $database->database_query("UPDATE se_levels SET level_theme_id='$theme_id' WHERE level_id in ('".join("','",array_keys($level_ids))."')");
    $result = 11120603;
  }

}


$themes = $rc_theme->get_records();
$theme_names = array(0=>'Default Theme');
foreach ($themes as $theme) {
  $theme_names[$theme['theme_id']] = $theme['theme_name'];
}

// GET SUBNETWORK ARRAY
$levels = $database->database_query("SELECT * FROM se_levels ORDER BY level_name asc");
$level_array = array();

// LOOP OVER SUBNETWORKS
while($level_info = $database->database_fetch_assoc($levels)) {
  $level_theme_id = $level_info['level_theme_id'];
  $level_info['level_theme_name'] = (isset($theme_names[$level_theme_id])) ? $theme_names[$level_theme_id] : 'Default Theme';
  $level_array[] = $level_info;
}

$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
$smarty->assign('result', $result);
$smarty->assign('levels', $level_array);
$smarty->assign('theme_options', $theme_names);
include "admin_footer.php";
exit();
?>