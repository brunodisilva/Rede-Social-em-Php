<?php
$page = "admin_gmap";
include "admin_header.php";

$task = rc_toolkit::get_request('task','main');

$rc_validator = new rc_validator();

$result = 0;

$keys = array('setting_permission_gmap',
'setting_gmap_license',
'setting_gmap_api',
'setting_gmap_icon',
'setting_gmap_profile_embed'
);

$mapfields = unserialize($setting['setting_gmap_mapfields']);

if ($task == 'dosave') {
  
  foreach ($keys as $key) {
    $setting[$key] = $data[$key] = $_POST[$key];
  }

  /* Nulled by TrioxX
  $rc_validator->license($data['setting_gmap_license'],'gmap','license');
	*/
	
  $rc_validator->is_not_trimmed_blank($data['setting_gmap_api'],11080220,'api');
  
  $mapfields = rc_toolkit::get_request('mapfields',array());
  
  if (!$rc_validator->has_errors()) {
    $data['setting_gmap_mapfields'] = serialize($mapfields);
    $database->database_query("UPDATE se_settings SET ".rc_toolkit::db_data_packer($data));
    $setting = $database->database_fetch_assoc($database->database_query("SELECT * FROM se_settings LIMIT 1"));
    $result = 11080203;
  }
}

foreach ($keys as $key) {
  $smarty->assign($key, $setting[$key]);
}

//$profile_fields = rc_toolkit::get_profile_fields();
$se_field = new se_field('profile');
//$se_field->field_list();
//$profile_fields = $se_field->fields;

$se_field->cat_list();

$smarty->assign('mapfields',$mapfields);
//rc_toolkit::debug($mapfields);

//rc_toolkit::debug($se_field->cats);
$smarty->assign('cats', $se_field->cats);

$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
$smarty->assign('result', $result);
$smarty->assign('rc_gmap', $rc_gmap);
include "admin_footer.php";
exit();