<?
$page = "admin_viewthemes_edit";
include "admin_header.php";

$task = rc_toolkit::get_request('task','main');
$theme_id = rc_toolkit::get_request('theme_id',0);

// VALIDATE THEME ID
$rc_theme = new rc_theme();
$rc_validator = new rc_validator();
$result = "";
$theme = $rc_theme->get_record($theme_id);
if (!$theme) {
  rc_toolkit::redirect("admin_viewthemes.php");
}

$rc_themeblock = new rc_themeblock();
$blocks = $rc_themeblock->get_records();

if($task == "edittheme") {
  $theme['theme_name'] = $_POST['theme_name'];
  $theme['theme_desc'] = $_POST['theme_desc'];
  $theme['theme_stylesheet'] = $_POST['theme_stylesheet'];
  $theme['theme_css'] = strip_tags(htmlspecialchars_decode($_POST['theme_css'], ENT_QUOTES));
  
  foreach ($blocks as $k=>$block) {
    $col_block_id = $rc_theme->get_block_column($block);
    $theme[$col_block_id] = (htmlspecialchars_decode($_POST[$col_block_id], ENT_QUOTES));
  }  
  
  $rc_validator->is_not_trimmed_blank($theme['theme_name'],11120508);
  
  if (!$rc_validator->has_errors()) {
    $rc_theme->update($theme['theme_id'],$theme);
    $result = 11120509;
  }

}

foreach ($blocks as $k=>$block) {
  $col_block_id = $rc_theme->get_block_column($block);  
  $blocks[$k]['template'] = $theme[$col_block_id];
}


$smarty->assign('result',$result);
$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
$smarty->assign('theme', $theme);
$smarty->assign('blocks', $blocks);
include "admin_footer.php";
exit();

