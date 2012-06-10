<?php
$page = "admin_userpoints_give";
include "admin_header.php";
include_once "include/class_notify.php";

$task = semods::getpost('task', 'main');
$subject =  semods::post('subject', semods::get_language_text(100016623));
$message =  semods::post('message', semods::get_language_text(100016624));

$is_error = 0;
$error_message = '';
$result = 0;

$points = semods::getpost('points',0);

// TODO: save suggested from_user_id in settings, setting_userpoints_admin_from_user_id
// semods::get_setting('userpoints_admin_from_user_id');
$from_user_id_suggest = semods::getpost('from_user_id_suggest','');
$from_user_id = $from_user_id_suggest != 0 ? $from_user_id_suggest : semods::getpost('from_user_id','');

if($task == "dogivepoints") {

  // CREATE NOTIFICATION CLASS
  if(!isset($notify))
    $notify = new se_notify();

  $sendto_type = semods::getpost('sendtotype',0);
  $level_id = semods::getpost('level',0);
  $subnet_id = semods::getpost('subnet',0);
  $username = semods::getpost('username',0);
  $send_message = semods::getpost('send_message',0);
  $subject = semods::getpost('subject',0);
  $message = semods::getpost('message',0);
  $set_points = semods::getpost('set_points',0);


  if(is_numeric($from_user_id)) {
    $admin_user = new se_user(array($from_user_id));
  } else {
    $admin_user = new se_user(array(0,$from_user_id));
  }
  if($send_message && !$admin_user->user_exists) {
    $is_error = 1;
    $error_message = 100016625; // TODO -> Message author user doesn't exist
  } else {

    $admin_user->level_info['level_message_allow'] = 2;
    $admin_user->level_info['level_message_recipients'] = 999;

    switch($sendto_type) {

      // All users ..
      // would be great to just inc all pointscoint, but there are some that have no rows
      case 0:
        ignore_user_abort( true );
        set_time_limit( 0 );

        $sql = "
          SELECT
            se_users.user_id,
            se_users.user_username,
            se_levels.level_message_allow
          FROM
            se_users
          LEFT JOIN
            se_levels
            ON se_users.user_level_id = se_levels.level_id";

        $rows = $database->database_query( $sql );
        while($row = $database->database_fetch_assoc($rows)) {
          if($set_points)
            userpoints_set( $row['user_id'], $points );
          else
          userpoints_add( $row['user_id'], $points );
          if($send_message && ($row['level_message_allow'] != 0) && ($row['user_id'] != $admin_user->user_info['user_id']))
            $admin_user->user_message_send( $row['user_username'], $subject, $message );
        }

        break;



      // All users on level..
      case 1:
        ignore_user_abort( true );
        set_time_limit( 0 );

        $sql = "
          SELECT
            se_users.user_id,
            se_users.user_username,
            se_levels.level_message_allow
          FROM
            se_users
          LEFT JOIN
            se_levels
            ON se_users.user_level_id = se_levels.level_id
          WHERE se_users.user_level_id = $level_id";

        $rows = $database->database_query( $sql );
        while($row = $database->database_fetch_assoc($rows)) {
          if($set_points)
            userpoints_set( $row['user_id'], $points );
          else
          userpoints_add( $row['user_id'], $points );
          if($send_message && ($row['level_message_allow'] != 0) && ($row['user_id'] != $admin_user->user_info['user_id']))
            $admin_user->user_message_send( $row['user_username'], $subject, $message );
        }

        break;



      // All users on subnet..
      case 2:
        ignore_user_abort( true );
        set_time_limit( 0 );

        $sql = "
          SELECT
            se_users.user_id,
            se_users.user_username,
            se_levels.level_message_allow
          FROM
            se_users
          LEFT JOIN
            se_levels
            ON se_users.user_level_id = se_levels.level_id
          WHERE se_users.user_subnet_id = $subnet_id";

        $rows = $database->database_query( $sql );
        while($row = $database->database_fetch_assoc($rows)) {
          if($set_points)
            userpoints_set( $row['user_id'], $points );
          else
          userpoints_add( $row['user_id'], $points );
          if($send_message && ($row['level_message_allow'] != 0) && ($row['user_id'] != $admin_user->user_info['user_id']))
            $admin_user->user_message_send( $row['user_username'], $subject, $message );

        }
        break;



      // Specific user
      case 3:
        $happy_user = new se_user( array( 0, $username) );
        if($happy_user->user_exists == 0) {
          $is_error = 1;
          $error_message = 100016625;
        } else {

          if($set_points)
            userpoints_set( $happy_user->user_info['user_id'], $points );
          else
            userpoints_add( $happy_user->user_info['user_id'], $points );

          if($send_message && ($happy_user->level_info['level_message_allow'] != 0) && ($row['user_id'] != $admin_user->user_info['user_id'])) {
            $admin_user->user_message_send( $happy_user->user_info['user_username'], $subject, $message );
          }

        }
        break;

    }

  }

  if($is_error == 0) {
	$result = 1;
  }


}


// LOOP OVER USER LEVELS
$levels = $database->database_query("SELECT level_id, level_name FROM se_levels ORDER BY level_name");
while($level_info = $database->database_fetch_assoc($levels)) {
  $level_array[$level_info['level_id']] = $level_info['level_name'];
}


// LOOP OVER SUBNETWORKS
$subnets = $database->database_query("SELECT subnet_id, subnet_name FROM se_subnets ORDER BY subnet_name");
$subnet_array[0] = Array('subnet_id' => 0, 'subnet_name' => 152);
SE_Language::_preload(152);
while($subnet_info = $database->database_fetch_assoc($subnets)) {
  $subnet_array[$subnet_info[subnet_id]] = Array('subnet_id' => $subnet_info[subnet_id],
		       'subnet_name' => $subnet_info[subnet_name]);
  SE_Language::_preload( $subnet_info[subnet_name] );
}


// GET FIRST 10 USERS - TBD - need user with message sending perms ?
$from_users_suggest = semods::db_query_assoc_all("SELECT * FROM se_users ORDER BY user_id ASC LIMIT 10");
if(!$from_users_suggest) {
  $from_users_suggest = array();
}

foreach($from_users_suggest as $from_users_suggest_key => $from_users_suggest_user) {
  semods_utils::create_user_displayname_ex( $from_users_suggest_user );
  $from_users_suggest[$from_users_suggest_key] = $from_users_suggest_user;
}


// ASSIGN VARIABLES AND SHOW PAGE

$smarty->assign('from_users_suggest', $from_users_suggest);
$smarty->assign('from_user_id_suggest', $from_user_id_suggest);
$smarty->assign('from_user_id', $from_user_id);

$smarty->assign('levels', $level_array);
$smarty->assign('subnets', $subnet_array);

$smarty->assign('sendtotype', $sendto_type);
$smarty->assign('level', $level_id);
$smarty->assign('subnet', $subnet_id);
$smarty->assign('username', $username);

$smarty->assign('subject', $subject);
$smarty->assign('message', $message);
$smarty->assign('send_message', $send_message);
$smarty->assign('set_points', $set_points);
$smarty->assign('points', $points);

$smarty->assign('is_error', $is_error);
$smarty->assign('error_message', $error_message);
$smarty->assign('result', $result);

include "admin_footer.php";
?>