<?php

$page = "iheader";

// PREVENT MULTIPLE INCLUSION
if( defined('SE_HEADER') ) return;

// SET ERROR REPORTING
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
ini_set('display_errors', TRUE);

// ATTEMPT TO OVERLOAD STRING FUNCTIONS
if( @extension_loaded('mbstring') ) @ini_set('mbstring.func_overload', 2);

// CHECK FOR PAGE VARIABLE
if( !isset($page) ) $page = "";

// DEFINE SE CONSTANTS
define('SE_DEBUG', FALSE);
define('SE_PAGE', TRUE);
define('SE_ROOT', realpath(dirname(__FILE__)));
define('SE_HEADER', TRUE);

// SET INCLUDE PATH TO ROOT OF SE
set_include_path(get_include_path() . PATH_SEPARATOR . realpath("./"));



// BENCHMARK
include "include/class_benchmark.php";
$_benchmark = SEBenchmark::getInstance('default');
SE_DEBUG ? $_benchmark->start('total') : NULL;
SE_DEBUG ? $_benchmark->start('include') : NULL;



// INITIATE SMARTY
include "include/class_smarty.php";
$smarty =& SESmarty::getInstance();
//$smarty->debugging = TRUE;

// INCLUDE DATABASE INFORMATION
include "include/database_config.php";

// INCLUDE CLASS/FUNCTION FILES
include "include/functions_file.php";
include "include/cache/cache.php";
include "include/cache/storage.php";
include "include/session/session.php";
include "include/session/storage.php";

include "include/class_core.php";

include "include/class_admin.php";
include "include/class_database.php";
include "include/class_datetime.php";
include "include/class_comment.php";
include "include/class_field.php";
include "include/class_hook.php";
include "include/class_language.php";
include "include/class_notify.php";
include "include/class_upload.php";
include "include/class_user.php";
include "include/class_url.php";
include "include/class_misc.php";
include "include/class_ads.php";
include "include/class_actions.php";
include "include/functions_general.php";
include "include/functions_email.php";
include "include/functions_stats.php";

// JS API MOD JSON FUNCTIONS
include "include/class_javascript.php";
if(!function_exists('json_encode'))
{
  include_once "include/xmlrpc/xmlrpc.inc";
  include_once "include/xmlrpc/xmlrpcs.inc";
  include_once "include/xmlrpc/xmlrpc_wrappers.inc";
  include_once "include/jsonrpc/jsonrpc.inc";
  include_once "include/jsonrpc/jsonrpcs.inc";
  include_once "include/jsonrpc/json_extension_api.inc";
}



SE_DEBUG ? $_benchmark->end('include') : NULL;
SE_DEBUG ? $_benchmark->start('initialization') : NULL;



// INITIATE DATABASE CONNECTION
$database =& SEDatabase::getInstance();
// Use this line if you changed the way database connection is loaded
//$database = new SEDatabase($database_host, $database_username, $database_password, $database_name);

// SET DATABASE CONSTANTS
$database->database_query("SET @SE_PRIVACY_SELF = 1, @SE_PRIVACY_FRIEND = 2, @SE_PRIVACY_FRIEND2 = 4, @SE_PRIVACY_SUBNET = 8, @SE_PRIVACY_REGISTERED = 16, @SE_PRIVACY_ANONYMOUS = 32");

// SET LANGUAGE CHARSET
$database->database_set_charset(SE_Language::info('charset'));

// GET SETTINGS
$setting =& SECore::getSettings();

// CREATE URL CLASS
$url = new SEUrl();

// CREATE DATETIME CLASS
$datetime = new se_datetime();

// CREATE MISC CLASS
$misc = new se_misc();

// ENSURE NO SQL INJECTIONS THROUGH POST OR GET ARRAYS
$_POST = security($_POST);
$_GET = security($_GET);
$_COOKIE = security($_COOKIE);

// CREATE SESSION OBJECT
$session_options = ( defined('SE_SESSION_RESUME') && !empty($session_id) ? array('id' => $session_id, 'security' => array()) : array() );
$session = SESession::getInstance($session_options);
if( $session->getState() == 'expired' )
{
  $session->restart();
}

// CHECK FOR PAGE OWNER
if(isset($_POST['user'])) { $user_username = $_POST['user']; } elseif(isset($_GET['user'])) { $user_username = $_GET['user']; } else { $user_username = ""; }
if(isset($_POST['user_id'])) { $user_id = $_POST['user_id']; } elseif(isset($_GET['user_id'])) { $user_id = $_GET['user_id']; } else { $user_id = ""; }
$owner = new SEUser(Array($user_id, $user_username));

// CREATE USER OBJECT AND ATTEMPT TO LOG USER IN
$user = new SEUser();
$user->user_checkCookies();

// INSTANTIATE JAVASCRIPT OBJECT
$se_javascript = new SE_Javascript();


// CREATE ADMIN OBJECT AND ATTEMPT TO LOG ADMIN IN
$admin = new se_admin();
$admin->admin_checkCookies();


// CANNOT ACCESS USER-ONLY AREA IF NOT LOGGED IN
if( !$user->user_exists && substr($page, 0, 5) == "user_" )
{
  header("Location: login.php?return_url=".$url->url_current());
  exit();
}

// SET GLOBAL TIMEZONE
$global_timezone = ( $user->user_exists ? $user->user_info['user_timezone'] : $setting['setting_timezone'] );

// SET UP LANGUAGE VARIABLES
if( !empty($_GET['lang_id']) )
{
  $lang_id = NULL;
  if( $user->user_exists && $setting['setting_lang_allow'] )
  {
    $lang_id = $user->user_info['user_language_id'] = (int)$_GET['lang_id'];
    $database->database_query("UPDATE se_users SET user_language_id='{$user->user_info['user_language_id']}' WHERE user_id='{$user->user_info['user_id']}' LIMIT 1");
  }
  
  if( !$user->user_exists && $setting['setting_lang_anonymous'] )
  {
    $lang_id = (int)$_GET['lang_id'];
  }
  
  if( $lang_id )
  {
    setcookie('se_language_anonymous', $lang_id, time()+99999999, "/");
    $_COOKIE['se_language_anonymous'] = $lang_id;
  }
}

SE_Language::select($user);

if( SE_Language::info('language_setlocale') )
{
  $multi_language = 1;
  setlocale(LC_TIME, SE_Language::info('language_setlocale'));
}

header("Content-Language: ".SE_Language::info('language_code'));


// CREATE ACTIONS CLASS
$actions = new se_actions();

// CREATE NOTIFICATION CLASS
$notify = new se_notify();

// CREATE ADS CLASS
$ads = new se_ads();

// Define SE_PAGE_AJAX in your page before the header include to not load ads or update page views
if( !defined('SE_PAGE_AJAX') && ($page=="chat_frame" || $page=="chat_ajax" || $page=="misc_js" || $page=="ad") )
  define('SE_PAGE_AJAX', TRUE);

if( !defined('SE_PAGE_AJAX') )
{
  // UPDATE STATS TABLE
  update_stats("views");
  
  // LOAD ADS
  $ads->load();
}


// CREATE GLOBAL CSS STYLES VAR (USED FOR CUSTOM USER-DEFINED PROFILE/PLUGIN STYLES)
$global_css = "";


SE_DEBUG ? $_benchmark->end('initialization') : NULL;

SE_DEBUG ? $_benchmark->start('plugins') : NULL;



// INCLUDE RELEVANT PLUGIN FILES
// AND SET PLUGIN HEADER TEMPLATES
$show_menu_user = FALSE;

$global_plugins =& SECore::getPlugins();

foreach( $global_plugins as $plugin_type=>$plugin_info )
{
  $plugin_vars = array();
  if( file_exists("header_{$plugin_info['plugin_type']}.php") )
  {
    include "header_{$plugin_info['plugin_type']}.php";
  }
  
  // Set the hooks for each of the plugin templates if not using the new hooked template includes (backwards compatibility)
  if( empty($plugin_vars['uses_tpl_hooks']) )
  {
    if( file_exists(SE_ROOT."/templates/header_{$plugin_info['plugin_type']}.tpl") )
      $smarty->assign_hook('header', "header_{$plugin_info['plugin_type']}.tpl");
    
    if( file_exists(SE_ROOT."/templates/footer_{$plugin_info['plugin_type']}.tpl") )
      $smarty->assign_hook('footer', "footer_{$plugin_info['plugin_type']}.tpl");
    
    if( !empty($plugin_vars['menu_main']) )
      $smarty->assign_hook('menu_main', $plugin_vars['menu_main']);
    
    if( !empty($plugin_vars['menu_user']) )
      $smarty->assign_hook('menu_user_apps', $plugin_vars['menu_user']);
    
    if( $page=="profile" && !empty($plugin_vars['menu_profile_side']) )
    {
      $plugin_vars['menu_profile_side']['name'] = $plugin_info['plugin_type'];
      $smarty->assign_hook('profile_side', $plugin_vars['menu_profile_side']);
    }
    
    if( $page=="profile" && !empty($plugin_vars['menu_profile_tab']) )
    {
      $plugin_vars['menu_profile_tab']['name'] = $plugin_info['plugin_type'];
      $smarty->assign_hook('profile_tab', $plugin_vars['menu_profile_tab']);
    }
    
    if( $page=="user_home" && !empty($plugin_vars['menu_userhome']) )
    {
      $plugin_vars['menu_userhome']['name'] = $plugin_info['plugin_type'];
      $smarty->assign_hook('user_home', $plugin_vars['menu_userhome']);
    }
  }
  
  // If using the new template hooks, the header should also hook the styles sheets
  
  $global_plugins[$plugin_info['plugin_type']] =& $plugin_vars;
  if( !empty($plugin_vars['menu_user']) ) $show_menu_user = TRUE;
  unset($plugin_vars);
}

$global_plugins['plugin_controls'] = array('show_menu_user' => $show_menu_user);



SE_DEBUG ? $_benchmark->end('plugins') : NULL;
SE_DEBUG ? $_benchmark->start('page') : NULL;



// CHECK TO SEE IF SITE IS ONLINE OR NOT, ADMIN NOT LOGGED IN, DISPLAY OFFLINE PAGE
if( !$setting['setting_online'] && !$admin->admin_exists )
{
  $page = "offline";
  include "ifooter.php";
}




// CALL HEADER HOOK
($hook = SE_Hook::exists('se_header')) ? SE_Hook::call($hook, array()) : NULL;


// CHECK IF LOGGED-IN USER IS ON OWNER'S BLOCKLIST
if( $user->user_exists && $owner->user_exists && $owner->user_blocked($user->user_info['user_id']) )
{
  // ASSIGN VARIABLES AND DISPLAY ERROR PAGE
  $page = "error";
  $smarty->assign('error_header', 639);
  $smarty->assign('error_message', 640);
  $smarty->assign('error_submit', 641);
  include "ifooter.php";
}


if(isset($_POST['task'])) { $task = $_POST['task']; } else { $task = "step1"; }

// SET ERROR VARS
$is_error = 0;


// IF USER IS ALREADY LOGGED IN, FORWARD TO USER HOME PAGE
if( $user->user_exists )
{
  header("Location: user_home.php");
  exit();
}



// CHECK IF USER SIGNUP COOKIES SET (STEPS 3, 4, 5)
$signup_logged_in = 0;
if($task != "step1" && $task != "step1do" && $task != "step2" && $task != "step2do")
{
  if(isset($_COOKIE['signup_id']) && isset($_COOKIE['signup_email']) && isset($_COOKIE['signup_password']))
  {
    // GET USER ROW IF AVAILABLE
    $user_id = $_COOKIE['signup_id'];
    $new_user = new se_user(Array($user_id));
    
    // VERIFY USER LOGIN COOKIE VALUES AND RESET USER LOGIN VARIABLE
    //if($_COOKIE['signup_email'] == crypt($new_user->user_info['user_email'], "$1$".$new_user->user_info['user_code']."$") && $_COOKIE['signup_password'] == $new_user->user_info['user_password'])
    $new_user->user_salt = $new_user->user_info['user_code'];
    if( $_COOKIE['signup_email'] == $new_user->user_password_crypt($new_user->user_info['user_email']) && $_COOKIE['signup_password'] == $new_user->user_info['user_password'] )
    {
      $signup_logged_in = 1;
    }
  }

  if($signup_logged_in != 1) { cheader("signup.php"); exit(); }
}

if($signup_logged_in != 1)
{
  setcookie("signup_id", "", 0, "/");
  setcookie("signup_email", "", 0, "/");
  setcookie("signup_password", "", 0, "/");
  $_COOKIE['signup_id'] = "";
  $_COOKIE['signup_email'] = "";
  $_COOKIE['signup_password'] = "";
  $new_user = new se_user();
  if($task == "step1")
  { 
    if(isset($_GET['signup_email'])) { $signup_email = $_GET['signup_email']; } else { $signup_email = ""; }
    if(isset($_GET['signup_invite'])) { $signup_invite = $_GET['signup_invite']; } 
    $signup_password = ""; 
    $signup_timezone = $setting['setting_timezone'];
  }
}



// PROCESS INPUT FROM FIRST STEP (OR DOUBLE CHECK VALUES), CONTINUE TO SECOND STEP (OR SECOND STEP PROCESSING)
if($task == "step1do" || $task == "step2do")
{
  $signup_email = $_POST['signup_email'];
  $signup_password = $_POST['signup_password'];
  $signup_password2 = $_POST['signup_password2'];
  $step = $_POST['step'];
  
  if($task == "step2do" && $step != "1")
  {
    $signup_password = base64_decode($signup_password);
    $signup_password2 = base64_decode($signup_password2);
  }
  
  $signup_username = $_POST['signup_username'];
  $signup_timezone = $_POST['signup_timezone'];
  $signup_invite = $_POST['signup_invite'];
  $signup_cat = $_POST['signup_cat'];

  // GET LANGUAGE PACK SELECTION
  $signup_lang = ( $setting['setting_lang_allow'] ? $_POST['signup_lang'] : 0 );

  // TEMPORARILY SET PASSWORD IF RANDOM PASSWORD ENABLED
  if($setting['setting_signup_randpass'] != 0)
  {
    $signup_password = "temporary";
    $signup_password2 = "temporary";
  }

  // CHECK USER ERRORS
  $new_user->user_password('', $signup_password, $signup_password2, 0);
  $new_user->user_account($signup_email, $signup_username);
  $is_error = $new_user->is_error;

  // CHECK INVITE CODE IF NECESSARY
  if($setting['setting_signup_invite'] != 0)
  {
    if($setting['setting_signup_invite_checkemail'] != 0)
    {
      $invite = $database->database_query("SELECT invite_id FROM se_invites WHERE invite_code='$signup_invite' AND invite_email='$signup_email'");
      $invite_error_message = 705;
    }
    else
    {
      $invite = $database->database_query("SELECT invite_id FROM se_invites WHERE invite_code='$signup_invite'");
      $invite_error_message = 706;
    }
    if($database->database_num_rows($invite) == 0) { $is_error = $invite_error_message; }
  }

  // CHECK TERMS OF SERVICE AGREEMENT IF NECESSARY
  if($setting['setting_signup_tos'] != 0)
  {
    $signup_agree = $_POST['signup_agree'];
    if($signup_agree != 1)
    {
      $is_error = 707;
    }
  }

  // RETRIEVE AND CHECK SECURITY CODE IF NECESSARY
  if($setting['setting_signup_code'] != 0)
  {
    // NOW IN HEADER
    //session_start();

    $code = $_SESSION['code'];
    if($code == "") { $code = randomcode(); }
    $signup_secure = $_POST['signup_secure'];
    
    if($signup_secure != $code)
    {
      $is_error = 708;
    }
  }

  // IF THERE IS NO ERROR, CONTINUE TO STEP 2 OR PROCESS STEP 2
  if($is_error == 0)
  {
    // ONLY IF ON STEP ONE, CONTINUE TO STEP 2 - ELSE GO TO PROCESSING STEP 2
    if($task == "step1do") { $task = "step2"; }
  }
  
  // IF THERE WAS AN ERROR, GO BACK TO STEP 1
  else
  {
    $task = "step1";
  }

}



if($task == "step1" || $task == "step1do" || $task == "step2" || $task == "step2do") {
  if($database->database_num_rows($database->database_query("SELECT NULL FROM se_profilecats WHERE profilecat_id='$signup_cat' AND profilecat_dependency='0'")) != 1) {
    $cat_info = $database->database_fetch_assoc($database->database_query("SELECT profilecat_id FROM se_profilecats WHERE profilecat_dependency='0' ORDER BY profilecat_order LIMIT 1"));
    $signup_cat = $cat_info[profilecat_id];
  }
  if($task == "step2do") { $validate = 1; } else { $validate = 0; }
  if($task != "step1") { $cat_where = "profilecat_signup='1' AND profilecat_id='$signup_cat'"; } else { $cat_where = "profilecat_signup='1'"; }
  $field = new se_field("profile");
  $field->cat_list($validate, 0, 0, $cat_where, "", "profilefield_signup='1'");
  $cat_array = $field->cats;
  if($task != "step1" && count($cat_array) == 0) { $task = "step1"; }
  if($validate == 1) { $is_error = $field->is_error; }
  if($task != "step1" && count($field->fields_all) == 0) { $task = "step2do"; }
}









if($task == "step2do") {


  // PROFILE FIELD INPUTS PROCESSED AND CHECKED FOR ERRORS ABOVE
  // IF THERE IS NO ERROR, ADD USER AND USER PROFILE AND CONTINUE TO STEP 3
  if($is_error == 0) {
    $new_user->user_create($signup_email, $signup_username, $signup_password, $signup_timezone, $signup_lang, $signup_cat, $field->field_query);

    // INVITE CODE FEATURES
    if($setting[setting_signup_invite] != 0) {
      if($setting[setting_signup_invite_checkemail] != 0) {
        $invitation = $database->database_fetch_assoc($database->database_query("SELECT * FROM se_invites WHERE invite_code='$signup_invite' AND invite_email='$signup_email' LIMIT 1"));
      } else {
        $invitation = $database->database_fetch_assoc($database->database_query("SELECT * FROM se_invites WHERE invite_code='$signup_invite' LIMIT 1"));
      }

      // ADD USER TO INVITER'S FRIENDLIST
      $friend = new se_user(Array($invitation[invite_user_id]));
      if($friend->user_exists == 1) {
        if($setting[setting_connection_allow] == 3 || $setting[setting_connection_allow] == 1 || ($setting[setting_connection_allow] == 2 && $new_user->user_info[user_subnet_id] == $friend->user_info[user_subnet_id])) {
          // SET RESULT, DIRECTION, STATUS
          switch($setting[setting_connection_framework]) {
            case "0":
              $direction = 2;
              $friend_status = 0;
              break;
            case "1":
              $direction = 1;
              $friend_status = 0;
              break;
            case "2": 
              $direction = 2;
              $friend_status = 1;
              break;
            case "3":
              $direction = 1;
              $friend_status = 1;
              break;      
          } 

          // INSERT FRIENDS INTO FRIEND TABLE AND EXPLANATION INTO EXPLAIN TABLE	          
	  $friend->user_friend_add($new_user->user_info[user_id], $friend_status, '', '');
	          
          // IF TWO-WAY CONNECTION AND NON-CONFIRMED, INSERT OTHER DIRECTION
          if($direction == 2 && $friend_status == 1) { $new_user->user_friend_add($friend->user_info[user_id], $friend_status, '', ''); }
        }
      }
      

      // DELETE INVITE CODE
      $database->database_query("DELETE FROM se_invites WHERE invite_id='$invitation[invite_id]' LIMIT 1");
	
    }

    // SET SIGNUP COOKIE
    $id = $new_user->user_info[user_id];
    $em = crypt($new_user->user_info[user_email], "$1$".$new_user->user_info[user_code]."$");
    $pass = $new_user->user_info[user_password];
    setcookie("signup_id", "$id", 0, "/");
    setcookie("signup_email", "$em", 0, "/");
    setcookie("signup_password", "$pass", 0, "/");


    // SEND USER TO PHOTO UPLOAD IF SPECIFIED BY ADMIN
    // OR TO USER INVITE IF NO PHOTO UPLOAD
    if($setting[setting_signup_photo] == 0) { 
      if($setting[setting_signup_invitepage] == 0) {
        $task = "step5";
      } else {
        $task = "step4"; 
      }
    } else { 
      $task = "step3"; 
    }

  // IF THERE WAS AN ERROR, GO BACK TO STEP 2
  } else {
    $task = "step2";
  }
}


// UPLOAD PHOTO
if($task == "step3do")
{
  $new_user->user_photo_upload("photo");
  $is_error = $new_user->is_error;
  $task = "step3";
}




// SEND INVITE EMAILS
if($task == "step4do")
{
  $invite_emails = $_POST['invite_emails'];
  $invite_message = $_POST['invite_message'];

  if($invite_emails != "")
  {
    send_systememail('invite', $invite_emails, Array($new_user->user_displayname, $new_user->user_info['user_email'], $invite_message, "<a href=\"".$url->url_base."signup.php\">".$url->url_base."signup.php</a>"), TRUE);
  }

  // SEND USER TO THANK YOU PAGE
  $task = "step5";
}





// SIGNUP TERMINAL VELOCITY POINT HOOK
($hook = SE_Hook::exists('se_signup_decide')) ? SE_Hook::call($hook, array()) : NULL; 







// SHOW COMPLETION PAGE
if($task == "step5")
{
  // UNSET SIGNUP COOKIES
  setcookie("signup_id", "", 0, "/");
  setcookie("signup_email", "", 0, "/");
  setcookie("signup_password", "", 0, "/");

  // UPDATE SIGNUP STATS
  update_stats("signups");

  // DISPLAY THANK YOU
  $step = 5;
}




// SHOW FOURTH STEP
if($task == "step4")
{
  $step = 4;
  $next_task = "step4do";
  if($setting['setting_signup_invitepage'] == 0) { $task = "step3"; }
}





// SHOW THIRD STEP
if($task == "step3")
{
  $step = 3;
  $next_task = "step3do";
  if($setting['setting_signup_invitepage'] == 0) { $last_task = "step5"; } else { $last_task = "step4"; }
  if($setting['setting_signup_photo'] == 0) { $task = "step2"; }
}





// SHOW SECOND STEP
if($task == "step2")
{
  $step = 2;
  $next_task = "step2do";
  if(count($field->cats) == 0) { $task = "step1"; }
  $signup_password = base64_encode($signup_password);
  $signup_password2 = base64_encode($signup_password2);
}







// SHOW FIRST STEP
if($task == "step1")
{
  $step = 1;
  $next_task = "step1do";

  // GET LANGUAGE PACK LIST
  $lang_packlist = SE_Language::list_packs();
  ksort($lang_packlist);
  $lang_packlist = array_values($lang_packlist);
}

// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('is_error', $is_error);
$smarty->assign('new_user', $new_user);
$smarty->assign('cats', $field->cats);
$smarty->assign('signup_email', $signup_email);
$smarty->assign('signup_password', $signup_password);
$smarty->assign('signup_password2', $signup_password2);
$smarty->assign('signup_username', $signup_username);
$smarty->assign('signup_timezone', $signup_timezone);
$smarty->assign('signup_lang', $signup_lang);
$smarty->assign('signup_invite', $signup_invite);
$smarty->assign('signup_secure', $signup_secure);
$smarty->assign('signup_agree', $signup_agree);
$smarty->assign('signup_cat', $signup_cat);
$smarty->assign('lang_packlist', $lang_packlist);
$smarty->assign('next_task', $next_task);
$smarty->assign('last_task', $last_task);
$smarty->assign('step', $step);






// CHECK TO SEE IF USER HAS BEEN BLOCKED BY IP
$banned_ips = explode(",", $setting['setting_banned_ips']);
if( in_array($_SERVER['REMOTE_ADDR'], $banned_ips) )
{
  // ASSIGN VARIABLES AND DISPLAY ERROR PAGE
  $page = "error";
  $smarty->assign('error_header', 639);
  $smarty->assign('error_message', 807);
  $smarty->assign('error_submit', 641);
  include "footer.php";
}

// IF PREVIOUSLY LOGGED IN EMAIL COOKIE AVAILABLE, SET IT
$prev_email = ( isset($_COOKIE['prev_email']) ? $_COOKIE['prev_email'] : "" );

// UPDATE REFERRING URLS TABLE
update_refurls();

// Get actions feed - Has code in it that is preventing direct caching
$actions_array = $actions->actions_display(0, $setting['setting_actions_actionsperuser']);

$smarty->assign_by_ref('actions', $actions_array);


// ASSIGN SMARTY VARIABLES AND INCLUDE FOOTER
$smarty->assign('prev_email', $prev_email);
$smarty->assign('ip', $_SERVER['REMOTE_ADDR']);

?>