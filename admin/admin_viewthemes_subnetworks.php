<?
$page = "admin_viewthemes_subnetworks";
include "admin_header.php";


$task = rc_toolkit::get_request('task','main');
$theme_id = rc_toolkit::get_request('theme_id',0);
$subnetwork_ids = rc_toolkit::get_request('subnetwork_ids',array());


$rc_theme = new rc_theme();
$rc_validator = new rc_validator();
$result = "";

// CREATE THEME
if($task == "update") {
  
  if ($theme_id != 0) {
    $theme = $rc_theme->get_record($theme_id);
    $rc_validator->validate($theme != null, "Please select a valid theme.");
  }
  
  $rc_validator->validate(count($subnetwork_ids)>0,"Please check at least one subnetwork.");
  
  if (!$rc_validator->has_errors()) {
    $database->database_query("UPDATE se_subnets SET subnet_theme_id='$theme_id' WHERE subnet_id in ('".join("','",array_keys($subnetwork_ids))."')");
    $result = 11120703;
  }

}


$themes = $rc_theme->get_records();
$theme_names = array(0=>'Default Theme');
foreach ($themes as $theme) {
  $theme_names[$theme['theme_id']] = $theme['theme_name'];
}

// GET SUBNETWORK ARRAY
$subnets = $database->database_query("SELECT * FROM se_subnets ORDER BY subnet_name asc");
$subnet_array = array();

// LOOP OVER SUBNETWORKS
while($subnet_info = $database->database_fetch_assoc($subnets)) {
  $subnet_theme_id = $subnet_info['subnet_theme_id'];
  SE_Language::_preload($subnet_info['subnet_name']);
  $subnet_info['subnet_theme_name'] = (isset($theme_names[$subnet_theme_id])) ? $theme_names[$subnet_theme_id] : 'Default Theme';
  $subnet_array[] = $subnet_info;
}
SE_Language::load();
$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
$smarty->assign('result', $result);
$smarty->assign('subnets', $subnet_array);
$smarty->assign('theme_options', $theme_names);
include "admin_footer.php";
exit();
?>