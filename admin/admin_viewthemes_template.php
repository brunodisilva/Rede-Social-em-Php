<?
$page = "admin_viewthemes_template";
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

if($task == "edittheme") {
  $theme['theme_name'] = $_POST['theme_name'];
  $theme['theme_desc'] = $_POST['theme_desc'];
  $theme['theme_css'] = strip_tags(htmlspecialchars_decode($_POST['theme_css'], ENT_QUOTES));
  
  $rc_validator->is_not_trimmed_blank($theme['theme_name'],$admin_viewthemes_edit[8]);
  
  if (!$rc_validator->has_errors()) {
    $rc_theme->update($theme['theme_id'],$theme);
    $result = $admin_viewthemes_edit[9];
  }

}

$smarty->assign('result',$result);
$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
$smarty->assign('theme', $theme);
include "admin_footer.php";
exit();

