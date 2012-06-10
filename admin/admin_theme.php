<?

$page = "admin_theme";
include "admin_header.php";


$task = rc_toolkit::get_request('task','main');

$rc_theme = new rc_theme();
$rc_validator = new rc_validator();
$rc_themeblock = new rc_themeblock();
$result = "";

$keys = array(
'setting_theme_license',
'setting_theme_type',
'setting_theme_user_overwrite'

);

if ($task == 'dosave') {
  
  foreach ($keys as $key) {
    $setting[$key] = $data[$key] = $_POST[$key];
  }


  if (!$rc_validator->has_errors()) {
    $database->database_query("UPDATE se_settings SET ".rc_toolkit::db_data_packer($data));
    $setting = $database->database_fetch_assoc($database->database_query("SELECT * FROM se_settings LIMIT 1"));
    
    $themeblock_titles = rc_toolkit::get_request('themeblock_titles',array());
    $cur_themeblocks = $rc_themeblock->get_records("ORDER BY themeblock_id",true);
    
    foreach ($themeblock_titles as $block_id=>$block_title) {
      if (!strlen(trim($block_title))) {
        if (isset($cur_themeblocks[$block_id])) {
          $rc_themeblock->delete($block_id);
        }
      }
      else {
        $block_data = array('themeblock_title'=>$block_title);
        if (isset($cur_themeblocks[$block_id])) {
          $rc_themeblock->update($block_id, $block_data);
        }
        else {
          $rc_themeblock->insert($block_data);
        }
      }
    }
    
    $result = 11120203;
    
  }
  

}

foreach ($keys as $key) {
  $smarty->assign($key, $setting[$key]);
}

$themeblocks = $rc_themeblock->get_records("ORDER BY themeblock_id");
$block_max = 0;
foreach ($themeblocks as $block) {
  if ($block['themeblock_id'] > $block_max) $block_max = $block['themeblock_id'];
}

$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
$smarty->assign('result', $result);
$smarty->assign('themeblocks', $themeblocks);
$smarty->assign('num_blocks', $block_max);
include "admin_footer.php";
exit();