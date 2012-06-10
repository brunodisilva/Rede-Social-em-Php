<?php
/*
 * Apps v1.0
 *
 * Copyright (c) 2008 SocialEngineMods.Net
 *
 *****************************************/

$page = "ajax_autocomplete_friends";
include "header.php";

/* Expire now */

header("Cache-Control: no-cache");
header("Pragma: nocache");
header("Expire: 0");





// Initialize response object
$response = array();


if($user->user_exists == 0) {

  $text = 'Please <a href="./login.php">login</a> first.';

  // empty response
  $response['status'] = 1;
  $response['msg'] = $text;

  echo json_encode($response);
  exit;
}





$task = semods::request('task', 'get');




/* GET FRIENDS LIST */
if($task == "get") {

  $friends = $user->user_friend_list( 0,    // start
                                      500,  // limit
                                      0,    // direction (def)
                                      1,    // friend_status (def)
                                      'se_users.user_username ASC',  // sort by
                                      ''
                                      );

  $friends_list = array();

  foreach($friends as $friend) {
    $friends_list[] = array(  'i'   =>  $friend->user_info['user_id'],
                              't'  =>  $friend->user_displayname
                            );
  }

  $friends_all['friends'] = $friends_list;

  echo json_encode($friends_all);

}




?>