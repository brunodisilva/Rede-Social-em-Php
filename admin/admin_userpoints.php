<?
$page = "admin_userpoints";
include "admin_header.php";

$task = semods::getpost('task', 'main');


// SET RESULT VARIABLE
$result = 0;

// SAVE CHANGES
if($task == "dosave") {

  $setting_userpoints_enable_offers = semods::post('setting_userpoints_enable_offers', 0);
  $setting_userpoints_enable_shop = semods::post('setting_userpoints_enable_shop', 0);
  $setting_userpoints_enable_topusers = semods::post('setting_userpoints_enable_topusers', 0);
  $setting_userpoints_enable_statistics = semods::post('setting_userpoints_enable_statistics', 0);
  
  $database->database_query("UPDATE se_semods_settings SET 
			setting_userpoints_enable_offers = $setting_userpoints_enable_offers,
			setting_userpoints_enable_shop = $setting_userpoints_enable_shop,
            setting_userpoints_enable_topusers = $setting_userpoints_enable_topusers,
            setting_userpoints_enable_statistics = $setting_userpoints_enable_statistics
			");

  $result = 1;

}


// ASSIGN VARIABLES AND SHOW GENERAL SETTINGS PAGE
$smarty->assign('result', $result);
$smarty->assign('error', $error);

$smarty->assign('setting_userpoints_enable_offers', semods::get_setting('userpoints_enable_offers'));
$smarty->assign('setting_userpoints_enable_shop', semods::get_setting('userpoints_enable_shop'));
$smarty->assign('setting_userpoints_enable_topusers', semods::get_setting('userpoints_enable_topusers'));
$smarty->assign('setting_userpoints_enable_statistics', semods::get_setting('userpoints_enable_statistics'));

include "admin_footer.php";
?>