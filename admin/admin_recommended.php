<?php
$page = "admin_recommended";
include "admin_header.php";

$task = rc_toolkit::get_request('task','main');

$result = "";
$rc_validator = new rc_validator();

$keys = array(
'setting_permission_recommended',
'setting_recommended_license',
'setting_email_recommendedcomment_subject',
'setting_email_recommendedcomment_message'
);

if ($task == 'dosave') {
  
  foreach ($keys as $key) {
    $setting[$key] = $data[$key] = $_POST[$key];
  }
  $rc_validator->license($data['setting_recommended_license'],'recommended','license');
  
  if (!$rc_validator->has_errors()) {
    $database->database_query("UPDATE se_settings SET ".rc_toolkit::db_data_packer($data));
    $setting = $database->database_fetch_assoc($database->database_query("SELECT * FROM se_settings LIMIT 1"));
    $result = 11140303;
  }
}

foreach ($keys as $key) {
  $smarty->assign($key, $setting[$key]);
}


$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
$smarty->assign('result', $result);
include "admin_footer.php";
exit();
