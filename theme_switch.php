<?
$page = "theme_switch";
include "header.php";

$theme_id = rc_toolkit::get_request('theme_id');
if ($user->user_exists == 0 || $setting['setting_theme_type'] == 0 || !is_numeric($theme_id)) {
	rc_toolkit::redirect("user_home.php");
}

$user_id = $user->user_info['user_id'];
$database->database_query("UPDATE se_users SET user_theme_id='$theme_id' WHERE user_id = '$user_id'");

$redirect_url = $_SERVER['HTTP_REFERER'];
if (!$redirect_url || strstr($redirect_url,$page)) {
	$redirect_url = 'user_home.php';
}
rc_toolkit::redirect($redirect_url);
