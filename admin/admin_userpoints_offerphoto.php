<?
$page = "admin_userpoints_offerphoto";
include "admin_header.php";

$task = semods::getpost('task', "main");
$item_id = semods::getpost('item_id', 0);

$upearner = new semods_upearner( $item_id, false );
if($upearner->upearner_exists == 0) {
  header("Location: admin_userpoints_offers.php");
  exit;
}

// UPLOAD SMALL PHOTO
if($task == "upload") {
  $upearner->photo_upload("photo");
  $is_error = $upearner->is_error;
  $error_message = $upearner->error_message;
}

$item_photo = $upearner->photo( "../images/nophoto.gif" );

// ASSIGN VARIABLES AND SHOW ADMIN ADD USER LEVEL PAGE
$smarty->assign('result', $result);
$smarty->assign('is_error', $is_error);
$smarty->assign('error_message', $error_message);

$smarty->assign('item_photo', $item_photo);

$smarty->assign('item_id', $item_id);

include "admin_footer.php";
?>