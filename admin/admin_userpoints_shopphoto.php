<?
$page = "admin_userpoints_shopphoto";
include "admin_header.php";

$task = semods::getpost('task', "main");
$item_id = semods::getpost('item_id', 0);

$upspender = new semods_upspender( $item_id, false );
if($upspender->upspender_exists == 0) {
  header("Location: admin_userpoints_shop.php");
  exit;
}

// UPLOAD SMALL PHOTO
if($task == "upload") {
  $upspender->photo_upload("photo");
  $is_error = $upspender->is_error;
  $error_message = $upspender->error_message;
}

$item_photo = $upspender->photo( "../images/nophoto.gif" );

// ASSIGN VARIABLES AND SHOW ADMIN ADD USER LEVEL PAGE
$smarty->assign('result', $result);
$smarty->assign('is_error', $is_error);
$smarty->assign('error_message', $error_message);

$smarty->assign('item_photo', $item_photo);

$smarty->assign('item_id', $item_id);

include "admin_footer.php";
?>