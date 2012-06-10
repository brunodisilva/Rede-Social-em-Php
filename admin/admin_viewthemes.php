<?
$page = "admin_viewthemes";
include "admin_header.php";


$task = rc_toolkit::get_request('task','main');
$theme_id = rc_toolkit::get_request('theme_id',0);

// CREATE THEME OBJECT
$rc_theme = new rc_theme();
$rc_validator = new rc_validator();

$result = "";

if ($task == "delete") {
  $theme = $rc_theme->get_record($theme_id);
  if (!$theme) {
    rc_toolkit::redirect("admin_viewthemes.php");
  }

  $rc_validator->validate(!$theme['theme_default'],11120317);

  if (!$rc_validator->has_errors()) {
   
    $res = $rc_theme->delete($theme['theme_id']);
    $result = 11120314;
   
    
  }

}
elseif ($task == "setdefault") {
  if ($rc_theme->set_default($theme_id)) {
    $result = 11120316;
  }
  else {
    rc_toolkit::redirect("admin_viewthemes.php");
  }
}
elseif ($task == "setstatus") {
  $theme = $rc_theme->get_record($theme_id);
  if (!$theme) {
    rc_toolkit::redirect("admin_viewthemes.php");
  }
  $theme_status = rc_toolkit::get_request('value',1) != 0 ? 1 : 0;
  $rc_theme->update($theme_id,array('theme_status'=>$theme_status));
  $result = 11120319;
}

// SELECT AND LOOP THROUGH THEMES
$themes = $rc_theme->get_records("ORDER BY theme_id");
foreach ($themes as $k => $theme) {
  $themes[$k]['theme_desc'] = rc_toolkit::truncate_text($theme['theme_desc'],90);
}

// ASSIGN VARIABLES AND SHOW VIEW ENTRIES PAGE

$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
$smarty->assign('themes', $themes);
$smarty->assign('result', $result);
include "admin_footer.php";
exit();
