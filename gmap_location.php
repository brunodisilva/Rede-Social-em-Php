<?php
$page = "gmap_location";
include "header.php";

// DISPLAY ERROR PAGE IF USER IS NOT LOGGED IN AND ADMIN SETTING REQUIRES REGISTRATION
if($user->user_exists == 0 && $setting[setting_permission_profile] == 0) {
  $page = "error";
  $smarty->assign('error_header', 639);
  $smarty->assign('error_message', 656);
  $smarty->assign('error_submit', 641);
  include "footer.php";
}

// DISPLAY ERROR PAGE IF NO OWNER
if($owner->user_exists == 0) {
  $page = "error";
  $smarty->assign('error_header', 639);
  $smarty->assign('error_message', 828);
  $smarty->assign('error_submit', 641);
  include "footer.php";
}

// SET GLOBAL PAGE TITLE
$global_page_title[0] = 509;
$global_page_title[1] = $owner->user_displayname;
$global_page_description[0] = 1158;
$global_page_description[1] = $owner->user_displayname;
$global_page_description[2] = strip_tags(implode(" - ", $field->field_values));

include "footer.php";
