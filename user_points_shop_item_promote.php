<?
$page = "user_points_shop_item_promote";
include "header.php";

// ENSURE POINTS ARE ENABLED FOR THIS USER
if(($user->level_info['level_userpoints_allow'] == 0) || ($user->user_info['user_userpoints_allowed'] == 0)){ header("Location: user_home.php"); exit(); }

$task = semods::getpost('task', 'main');
$upspender_id = intval(semods::getpost('shopitem_id',0));

$upspender = new semods_upspender( $upspender_id );

if($upspender->upspender_exists == 0) {
  header("Location: user_points_shop.php");
  exit();
}

switch( $upspender->upspender_info['userpointspender_type'] ) {
    // promote classified - classified_id, classified_photo, classified_photowidth, classified_title
    case 101:
	
	  $classified = new se_classified($user->user_info['user_id']);
	  $classifieds = $classified->classified_list( 0, 999 );
	  
	  $smarty->assign( 'classifieds', $classifieds );
	  break;


    // promote event - eventid, event_photo, event_photowidth, event_name, 
    case 102:
	  $event = new se_event( $user->user_info['user_id'] );
	  $events = $event->event_list( 0, 999 );

	  $smarty->assign( 'events', $events );
	  break;


    // promote group - group_id, group_photo,group_photowidth, group_title
    case 103:
	  $group = new se_group( $user->user_info['user_id'] );
	  $groups = $group->group_list( 0, 999 );

	  $smarty->assign( 'groups', $groups );
	  break;


    // promote poll - username, pollid, poll_photo, poll_photowidth, poll_title 
    case 104:
	  $poll = new se_poll( $user->user_info['user_id'] );
	  $polls = $poll->poll_list( 0, 999 );

	  $smarty->assign( 'polls', $polls);
	  break;
	
  default:
	header("Location: user_points_shop.php");
	exit();
  
}


// ASSIGN VARIABLES AND INCLUDE FOOTER

$smarty->assign('upspender', $upspender);

$smarty->assign('error_message', $error_message);
$smarty->assign('is_error', $is_error);


include "footer.php";
?>