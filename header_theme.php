<?


// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
if(!defined('SE_PAGE')) { exit(); }

// INCLUDE brand LANGUAGE FILE
//include_once "./lang/lang_".$global_lang."_theme.php";

// INCLUDE brandS CLASS FILE
include_once "./include/class_theme.php";

// INCLUDE brandS FUNCTION FILE
include_once "./include/functions_theme.php";

rc_theme_db_get_template('css',$theme_css,$smarty);
$global_css .= $theme_css;
rc_theme_db_get_template('stylesheet',$theme_stylesheet,$smarty);
$smarty->assign('theme_stylesheet', $theme_stylesheet);

if ($user->user_exists != 0 && $setting['setting_theme_type'] > 0 && ($setting['setting_theme_type'] == 3 || $setting['setting_theme_user_overwrite'] != 0)) {

  
  if ($page == "user_home") {
    
    $rc_theme = new rc_theme();
    $theme_switcher_options = $rc_theme->get_theme_switcher_options();
    $smarty->assign('theme_switcher_options', $theme_switcher_options);    
    
    $plugin_vars[menu_userhome] = Array('file'=> 'user_home_theme.tpl');
  }
  
}