<?
$page = "user_gmap_settings";
include "header.php";

if($user->level_info[level_gmap_allow] == 0) { rc_toolkit::redirect('user_home.php'); }

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }

$field = new se_field("profile", $user->profile_info);
$field->cat_list(0, 0, 0, "profilecat_id='".$user->user_info[user_profilecat_id]."'");
$subcats = $field->subcats;

//rc_toolkit::debug($cat_array);

// SET VARS
$result = 0;

$rc_gmap = new rc_gmap();

// SAVE NEW SETTINGS
if($task == "dosave") {
  
  $setting_keys = array(
    'usersetting_permission_gmap',
    'usersetting_gmap_f_country',
    'usersetting_gmap_f_region',
    'usersetting_gmap_f_city',
    'usersetting_gmap_f_address'
  );
  
  $updater = array();
  foreach ($setting_keys as $setting_key) {
    $setting_value = $_POST[$setting_key] ? 1 : 0; // dont mess with me !!!
    $updater[] = "$setting_key='$setting_value'";
  }
  $update_string = join(', ',$updater);
  
  // UPDATE DATABASE
  $database->database_query("UPDATE se_usersettings SET $update_string WHERE usersetting_user_id='".$user->user_info[user_id]."'");
  $user = new se_user(Array($user->user_info[user_id]));
  $result = 1;
}

// ASSIGN USER SETTINGS
$user->user_settings();
//rc_toolkit::debug($user->usersetting_info,'$user->usersetting_info');

$setting_mapfields = $rc_gmap->get_setting_mapfields_for_profilecat($user->user_info[user_profilecat_id]);
$locations_fields = array();
foreach ($setting_mapfields as $key=>$field_id) {
  foreach ($subcats as $subcat) {
    foreach ($subcat['fields'] as $k=>$v) {
      if ($field_id == $v['field_id']) {
        $locations_fields["usersetting_gmap_f_$key"] = array(
          'field_value' => $user->usersetting_info["usersetting_gmap_f_$key"],
          'field_title' => $v['field_title']
        );
      }
    }
  }
  
}
//rc_toolkit::debug($locations_fields,'$locations_fields');


/*

// Smarty sux
$locations_fields = array();
foreach ($rc_gmap->location_fields as $key=>$field) {
  $locations_fields["usersetting_gmap_$key"] = array(
    'field_value' => $user->usersetting_info["usersetting_gmap_$key"],
    'field_title' => $field['field_title']
  );
}
*/
$smarty->assign('location_fields', $locations_fields);
// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('result', $result);
include "footer.php";
?>