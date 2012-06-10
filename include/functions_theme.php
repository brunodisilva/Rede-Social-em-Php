<?php


function rc_theme_get_theme_by_domainname($domain)
{
  global $theme_domains;
  $domain = strtolower($domain);
  return (isset($theme_domains[$domain])) ? $theme_domains[$domain] : null;
}



// put these function somewhere in your application
function rc_theme_db_get_template ($tpl_name, &$tpl_source, &$smarty_obj)
{
  global $user, $database, $setting;
  
  static $themes; // simple cache per request, only load theme once!!!
  
  if ($setting['setting_theme_type'] == 0) {
  	$tpl_source = '';
  	return true;
  }
  
  $ts = explode('/',$tpl_name);
  $block_id = str_replace('block_','',$ts[0]);
  if (isset($ts[1])) {
    $theme_id = str_replace('theme_','',$ts[1]);
  }
  
  $rc_theme = new rc_theme();
  
  //$domain = $_SERVER['HTTP_HOST'];
  //$theme_id = rc_theme_get_theme_by_domainname($domain);
  //rc_toolkit::debug($theme_id, "rc_theme_get_theme_by_domainname = $domain");
  
  if (!$theme_id) {
  	$theme_id = $rc_theme->get_dynamic_theme_id($user);
  	//rc_toolkit::debug($theme_id, 'get_dynamic_theme_id');
  }
  $theme = $rc_theme->get_cached_theme($theme_id);

  if ($block_id == 'css') {
    $tpl_source = $theme['theme_css'];
  }
  elseif ($block_id == 'stylesheet') {
    $tpl_source = $theme['theme_stylesheet'];
  } 
  else {
    $tpl_source = $theme[$rc_theme->get_block_column($block_id)];
  }

  return true;
}

function rc_theme_db_get_timestamp($tpl_name, &$tpl_timestamp, &$smarty_obj)
{
  $tpl_timestamp = time();
  return true;
}

function rc_theme_db_get_secure($tpl_name, &$smarty_obj)
{
    // assume all templates are secure
    return true;
}

function rc_theme_db_get_trusted($tpl_name, &$smarty_obj)
{
    // not used for templates
}


// register the resource name "db"
$smarty->register_resource("theme", array("rc_theme_db_get_template",
                                       "rc_theme_db_get_timestamp",
                                       "rc_theme_db_get_secure",
                                       "rc_theme_db_get_trusted"));
