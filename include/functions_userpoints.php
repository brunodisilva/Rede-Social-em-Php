<?php

//  THIS FILE CONTAINS USER POINTS FUNCTIONS
//  FUNCTIONS IN THIS FILE:

// userpoints_update_points()

// TBD: base class

# For PHP < 5.2
include_once 'functions_semods_compat_jsonencode.php';



/*********************** QUASI CONSTANTS *********************/



$uptransaction_states = array(  0 => 100016024,
                                1 => 100016025,
                                2 => 100016026 );



$action_group_types =   array(  0  => 'Unknown / Uncategorized',
                                1  => 'Group',
                                2  => 'Poll',
                                3  => 'Events',
                                4  => 'Classifieds',
                                5  => 'Blog',
                                6  => 'Media / Albums',
                                9  => 'Music',
                                100  => 'General',
                                101  => 'Signup / Marketing'
                                );




/*********************** MAIN "FINANCIAL" FUNCTIONS *********************/


/*
 * Get current points balance
 *
 * @param $user_id user_id
 * @return int Current balance
 *
 *
 */
function userpoints_get_points($user_id) {
  return semods::db_query_count( "SELECT userpoints_count FROM se_semods_userpoints WHERE userpoints_user_id = $user_id" );
}



/*
 * Try deducting amount to see if user has enough points (points are NOT deducted)
 *
 * @param $user_id user_id
 * @param $amount
 *
 * @return bool Success or failure of points try deduction
 *
 */
function userpoints_try_deduct($user_id, $amount) {
  return semods::db_query_count( "SELECT COUNT(*) FROM se_semods_userpoints WHERE userpoints_user_id = $user_id AND (userpoints_count - $amount) >= 0" ) == 1;
}


/*
 * Deduct points
 *
 * @param $user_id User_id
 * @param $amount Amount to process
 * @param $allowNegativeCredit if to allow "overdraft" - negative credit
 *
 * @return bool Success or failure of points deduction
 *
 * @todo boundary condition: if allowNegativeCredit is true and no rows (i.e. 0 points) this will fail
 */
function userpoints_deduct($user_id, $amount, $allowNegativeCredit = false) {

  if($allowNegativeCredit) {
    $success = semods::db_query_affected_rows( "UPDATE se_semods_userpoints SET userpoints_count = userpoints_count - $amount, userpoints_totalspent = userpoints_totalspent + $amount WHERE userpoints_user_id = $user_id" ) == 1;
  } else {
    $success = semods::db_query_affected_rows( "UPDATE se_semods_userpoints SET userpoints_count = userpoints_count - $amount, userpoints_totalspent = userpoints_totalspent + $amount WHERE userpoints_user_id = $user_id AND userpoints_count >= $amount" ) == 1;
  }

  if($success)
    userpoints_update_stats( $user_id, "spend", $amount );

  return $success;
}



/*
 * Add points
 *
 * @param $user_id user_id
 * @param $amount amount
 *
 *
 */

function userpoints_add($user_id, $amount, $update_totalearned = true) {
    global $database;

    if($update_totalearned) {
    $database->database_query("INSERT INTO se_semods_userpoints (userpoints_user_id, userpoints_count, userpoints_totalearned) VALUES ( $user_id, $amount, $amount ) ON DUPLICATE KEY UPDATE userpoints_count = userpoints_count + $amount, userpoints_totalearned = userpoints_totalearned + $amount ");
      userpoints_update_stats( $user_id, "earn", $amount );
    } else {
      $database->database_query("INSERT INTO se_semods_userpoints (userpoints_user_id, userpoints_count, userpoints_totalearned) VALUES ( $user_id, $amount, $amount ) ON DUPLICATE KEY UPDATE userpoints_count = userpoints_count + $amount");
    }

}


/*
 * Set points
 *
 * @param $user_id user_id
 * @param $amount amount
 *
 *
 */

function userpoints_set($user_id, $amount) {
    global $database;

    $database->database_query("INSERT INTO se_semods_userpoints (userpoints_user_id, userpoints_count) VALUES ( $user_id, $amount ) ON DUPLICATE KEY UPDATE userpoints_count = $amount");

}




/*********************** CUSTOM "FINANCIAL" FUNCTIONS *********************/




/*

 use userpointspender_type as subquery to get amount

*/

function userpoints_try_deduct_bytype($user_id, $type) {
  return semods::db_query_count( "SELECT COUNT(*) FROM se_semods_userpoints WHERE userpoints_user_id = $user_id AND (userpoints_count - (SELECT userpointspender_cost FROM se_semods_userpointspender  WHERE userpointspender_type = $type LIMIT 1)) >= 0" ) == 1;
}


function userpoints_deduct_bytype($user_id, $type, $allowNegativeCredit = false) {
  $amount = semods::db_query_count("SELECT userpointspender_cost FROM se_semods_userpointspender WHERE userpointspender_type = $type LIMIT 1");
  return userpoints_deduct( $user_id, $amount, $allowNegativeCredit );
}





/*********************** STATISTICS FUNCTIONS *********************/




function userpoints_update_stats($user_id, $type, $amount = 1) {
  global $database;

  if(semods::get_setting('userpoints_enable_statistics')) {
    $database->database_query("INSERT INTO se_semods_userpointstats (userpointstat_user_id, userpointstat_date, userpointstat_$type) VALUES ($user_id, UNIX_TIMESTAMP(CURDATE()), $amount) 
                               ON DUPLICATE KEY UPDATE userpointstat_$type = userpointstat_$type+$amount");
  }

}





/*********************** CUSTOM FUNCTIONS *********************/





/*
 * retrieves user rank, based on total earned points
 * @param user_id
 * @return int user rank
 *
 */
function userpoints_get_rank($user_id) {

/*
  // SLOWER but more exact ranking, 2 queries required. Each call is rebuilding the whole table

  global $database;

  $database->database_query( "SET @rownum := 0" );
  return semods::db_query_count( "SELECT rank FROM (
                                    SELECT @rownum := @rownum+1 AS rank, userpoints_user_id
                                    FROM se_semods_userpoints
                                    ORDER BY  userpoints_count DESC, userpoints_user_id
                                  ) AS rank_table WHERE userpoints_user_id=$user_id
                                ");
*/

/*
  // FAST. This query shares the place for equal score and "floors" shared rank
  return semods::db_query_count( "SELECT COUNT(*)+1 AS rank
                                  FROM se_semods_userpoints
                                  WHERE userpoints_totalearned >= (SELECT userpoints_totalearned FROM se_semods_userpoints WHERE userpoints_user_id=$user_id)" );
*/


/*
 * user   points    rank
 * user1  1000      1
 * user2  500       2
 * user3  500       2
 * user4  400       4
 * user5  0         5
 * user5  0         5
 *
 */

  // FAST. This query shares the place for equal score and makes "ceiling" for shared rank positions
  return semods::db_query_count( "SELECT COUNT(*)+1 AS rank
                                  FROM se_semods_userpoints
                                  WHERE userpoints_totalearned > (SELECT userpoints_totalearned FROM se_semods_userpoints WHERE userpoints_user_id=$user_id)" );


}





/*********************** MAIN REWARDING FUNCTION *********************/




// THIS FUNCTION REWARDS USER FOR SPECIFIC ACTION, WITH LIMITS PER ACTION
function userpoints_update_points($user_id, $type, $amount = 1) {
	global $database;

   /*
    $action_data = semods::db_query_assoc( "SELECT P.action_id, P.action_points, P.action_pointsmax, P.action_rolloverperiod,
                                                         C.userpointcounters_lastrollover, C.userpointcounters_amount
                                            FROM se_semods_actionpoints P
                                            LEFT JOIN se_semods_userpointcounters C
                                              ON P.action_id = C.userpointcounters_action_id
                                            WHERE P.action_type = '$type'
                                              AND (C.userpointcounters_user_id = $user_id OR ISNULL(C.userpointcounters_user_id))" );
   */

    $action_data = semods::db_query_assoc( "SELECT P.action_id, P.action_points, P.action_pointsmax, P.action_rolloverperiod,
                                                         C.userpointcounters_lastrollover, C.userpointcounters_amount
                                            FROM se_semods_actionpoints P
                                            JOIN se_semods_userpointcounters C
                                              ON P.action_id = C.userpointcounters_action_id
                                            WHERE P.action_type = '$type'
                                              AND C.userpointcounters_user_id = $user_id" );

    // NO POINTS AWARDED
    if( $action_data && ($action_data['action_points'] == 0))
      return;

    // THIS USER HAS NO RECORD OF THIS ACTIVITY, FETCH ACTIVITY DATA
    if($action_data === false) {
      $action_data = semods::db_query_assoc( "SELECT P.action_id, P.action_points, P.action_pointsmax, P.action_rolloverperiod,
                                                           0 AS userpointcounters_lastrollover, 0 AS userpointcounters_amount
                                              FROM se_semods_actionpoints P
                                              WHERE P.action_type = '$type' " );
    }

    // NO POINTS AWARDED
    if(($action_data === false) || ($action_data['action_points'] == 0))
      return;

    $points_to_add = $amount * $action_data['action_points'];

    // check if not reached max points / rollover date
    // if action_pointsmax is 0 - ignore ==> V3 updated to include, to accumulate stats
    // otherwise if empty userpointcounters_lastrollover or userpointcounters_lastrollover + rollover_period >= current_time  ==> rollover and assign amount
    // otherwise if userpointcounters_amount + amount > action_pointsmax ==> STOP

    if($action_data && ($action_data['action_pointsmax'] == 0)) {

      $sql = "INSERT INTO se_semods_userpointcounters (
                userpointcounters_user_id,
                userpointcounters_action_id,
                userpointcounters_amount, 
                userpointcounters_cumulative )
              VALUES (
                $user_id,
                {$action_data['action_id']},
                $points_to_add,
                $points_to_add )
              ON DUPLICATE KEY UPDATE
                userpointcounters_amount = userpointcounters_amount + $points_to_add,
                userpointcounters_cumulative = userpointcounters_cumulative + $points_to_add";
      
    } elseif($action_data && ($action_data['action_pointsmax'] != 0)) {

        $now = time();

        // TIME FOR ROLLOVER OR NEVER HAD POINTS FOR THIS ACTION
        if(empty($action_data['userpointcounters_lastrollover']) || ( ($action_data['action_rolloverperiod'] != 0) && ($now - intval($action_data['userpointcounters_lastrollover']) >= intval($action_data['action_rolloverperiod']) )) ) {

          // CUT IF ADDING MORE THAN MAX
          if($points_to_add > $action_data['action_pointsmax'] ) {
            $points_to_add = $action_data['action_pointsmax'];
          }

          $sql = "INSERT INTO se_semods_userpointcounters (
                    userpointcounters_user_id,
                    userpointcounters_action_id,
                    userpointcounters_lastrollover,
                    userpointcounters_amount, 
                    userpointcounters_cumulative )
                  VALUES (
                    $user_id,
                    {$action_data['action_id']},
                    $now,
                    $points_to_add,
                    $points_to_add )
                  ON DUPLICATE KEY UPDATE
                    userpointcounters_lastrollover = $now,
                    userpointcounters_amount = $points_to_add,
                    userpointcounters_cumulative = userpointcounters_cumulative + $points_to_add";

        } else {
          // ROLLOVER DATE NOT REACHED, SEE IF HIT MAX

          if($action_data['userpointcounters_amount'] + $points_to_add <= $action_data['action_pointsmax'] ) {
            // DIDN'T HIT MAX, OK


          // this one checks if can add at least one "whole" action-points
          // } elseif (($amount > 1) && ($action_data['userpointcounters_amount'] + $action_data['action_points'] <= $action_data['action_pointsmax'])) {

          // this one adds partial amount
          } elseif (($amount > 1) && ($action_data['action_pointsmax'] - $action_data['userpointcounters_amount'] > 0)) {
            // HIT MAX, ADD PARTIAL (? CHECK IF AMOUNT > 1 AND WE CAN STILL SQUEEZE SOME)

            $points_to_add = $action_data['action_pointsmax'] - $action_data['userpointcounters_amount'];

          } else {

            $points_to_add = 0;

          }

          if($points_to_add != 0)
            $sql = "UPDATE se_semods_userpointcounters
                    SET userpointcounters_amount = userpointcounters_amount + $points_to_add, userpointcounters_cumulative = userpointcounters_cumulative + $points_to_add
                    WHERE userpointcounters_user_id = $user_id AND userpointcounters_action_id = {$action_data['action_id']}";

        }

    }

    // negative is OK
    if( $points_to_add != 0 ) {
        !empty($sql) ? $database->database_query( $sql ) : 0;
        userpoints_add( $user_id, $points_to_add );
    }
}






function userpoints_get_all($user_id) {
  return semods::db_query_assoc( "SELECT * FROM se_semods_userpoints WHERE userpoints_user_id = $user_id" );
}



function userpoints_reward_votepoll($poll_id) {
    global $user, $owner;

    $upearner_id = semods::db_query_count( "SELECT userpointearner_id FROM se_semods_userpointearner WHERE userpointearner_enabled = 1 AND userpointearner_type = 300 AND userpointearner_field1 = $poll_id" );

    if($upearner_id) {
        $upearner = new semods_upearner( $upearner_id );
        // actually poll_id is taken from db upentry next
        $upearner->transact( $user, array( 'poll_id' => $poll_id, 'gotvars' => 1) );
    }
}





/*********************** THE MIGHTY TRANSFERRING FUNCTION *********************/


// THIS FUNCTION TRANSFERS POINTS FROM ONE USER TO ANOTHER
function userpoints_transfer_points(&$sender, $receiver_id, $amount) {
  global $database, $url, $functions_userpoints;


  $is_error = 0;
  $message = '';

  $receiver_id = intval($receiver_id);
  $amount = intval($amount);

  for(;;) {

    // if allowed to transfer points
    if(($sender->level_info['level_userpoints_allow'] == 0) || ($sender->level_info['level_userpoints_allow_transfer'] == 0) || ($sender->user_info['user_userpoints_allowed'] == 0)) {
      $is_error = 1;
      $message = 100016061;
      break;
    }

    // check points
    if(!($amount > 0)) {
      $is_error = 1;
      $message = 100016059;
      break;
    }

    // check receiver exists
    $ruser = new se_user( array($receiver_id) );
    if($ruser->user_exists == 0) {
      $is_error = 1;
      $message = 100016059;
      break;
    }


    // check points quota / limitations
    if($sender->level_info['level_userpoints_max_transfer'] != 0) {
      // TBD: refactor userpoints_update_points

      $action_data = semods::db_query_assoc( "SELECT P.action_id, P.action_points, P.action_pointsmax, P.action_rolloverperiod,
                                                           C.userpointcounters_lastrollover, C.userpointcounters_amount
                                              FROM se_semods_actionpoints P
                                              JOIN se_semods_userpointcounters C
                                                ON P.action_id = C.userpointcounters_action_id
                                              WHERE P.action_type = 'transferpoints'
                                                AND C.userpointcounters_user_id = {$sender->user_info['user_id']}" );

      // THIS USER HAS NO RECORD OF THIS ACTIVITY, FETCH ACTIVITY DATA
      if($action_data == false) {
        $action_data = semods::db_query_assoc( "SELECT P.action_id, P.action_points, P.action_pointsmax, P.action_rolloverperiod,
                                                             0 AS userpointcounters_lastrollover, 0 AS userpointcounters_amount
                                                FROM se_semods_actionpoints P
                                                WHERE P.action_type = 'transferpoints' " );
      }

      // check if not reached max points / rollover date
      // if action_pointsmax is 0 - ignore
      // otherwise if empty userpointcounters_lastrollover or userpointcounters_lastrollover + rollover_period >= current_time  ==> rollover and assign amount
      // otherwise if userpointcounters_amount + amount > action_pointsmax ==> STOP

      if($action_data) {

          $action_data['action_rolloverperiod'] = 86400;  // one day
          $action_data['action_pointsmax'] = $sender->level_info['level_userpoints_max_transfer'];

          $now = time();

          // TIME FOR ROLLOVER OR NEVER HAD POINTS FOR THIS ACTION
          if(empty($action_data['userpointcounters_lastrollover']) || ( ($action_data['action_rolloverperiod'] != 0) && ($now - intval($action_data['userpointcounters_lastrollover']) >= intval($action_data['action_rolloverperiod']) )) ) {

            // IF ADDING MORE THAN MAX
            if($amount > $action_data['action_pointsmax'] ) {
              $is_error = 1;
              $message = sprintf( semods::get_language_text( 100016062 ), $action_data['action_pointsmax'] );
              break;
            }

            $sql = "INSERT INTO se_semods_userpointcounters (
                      userpointcounters_user_id,
                      userpointcounters_action_id,
                      userpointcounters_lastrollover,
                      userpointcounters_amount )
                    VALUES (
                      {$sender->user_info['user_id']},
                      {$action_data['action_id']},
                      $now,
                      $amount )
                    ON DUPLICATE KEY UPDATE
                      userpointcounters_lastrollover = $now,
                      userpointcounters_amount = $amount";

          } else {
            // ROLLOVER DATE NOT REACHED, SEE IF HIT MAX

            if($action_data['userpointcounters_amount'] + $amount > $action_data['action_pointsmax'] ) {
              // HIT MAX, SEE IF SOME LEFT

              $amount_left = $action_data['action_pointsmax'] - $action_data['userpointcounters_amount'];
              if($amount_left == 0) {
                $message = 100016063;
              } else {
                $message = sprintf( semods::get_language_text( 100016062 ), $amount_left );
              }

              $is_error = 1;
              break;

            }

            $sql = "UPDATE se_semods_userpointcounters
                    SET userpointcounters_amount = userpointcounters_amount + $amount
                    WHERE userpointcounters_user_id = {$sender->user_info['user_id']} AND userpointcounters_action_id = {$action_data['action_id']}";

          }

      }

    }


    /*** TRY TRANSFERRING POINTS ***/

    // check points left
    if(!userpoints_deduct( $sender->user_info['user_id'], $amount ) ) {
      $is_error = 1;
      $message = 100016057;
      break;
    }

    userpoints_add( $receiver_id,
                    $amount,
                    false // do not update "total points earned"
                  );

    // Transaction - Sender
    $transaction_id = userpoints_new_transaction( $sender->user_info['user_id'],
                                                  0,//$this->upspender_info['userpointspender_type'],
                                                  2,  // "spender" ?
                                                  0,  // state - completed
                                                  semods::get_language_text(100016064) . " <a href=\"". $url->url_create("profile", $ruser->user_info['user_username']) ."\">{$ruser->user_displayname}</a>",
                                                  -$amount
                                                 );

    // Transaction - Receiver
    $transaction_id = userpoints_new_transaction( $receiver_id,
                                                  0,//$this->upspender_info['userpointspender_type'],
                                                  1,    // "earner" ?
                                                  0,    // state - completed
                                                  semods::get_language_text(100016065) . "  <a href=\"". $url->url_create("profile", $sender->user_info['user_username']) ."\">{$sender->user_displayname}</a>",
                                                  $amount
                                                 );

    // update quotas, if needed
    !empty($sql) ? $database->database_query( $sql ) : 0;

    $message = 100016060;
    break;
  }

  if(is_numeric($message))
    $message = semods::get_language_text( $message );
    
  return array( 'is_error' => $is_error, 'message' => $message );

}





function userpoints_new_transaction( $user_id, $type, $cat, $state, $text, $amount ) {
  global $database;
  
  $database->database_query( "INSERT INTO se_semods_uptransactions
                              (uptransaction_user_id,
                               uptransaction_type,
                               uptransaction_cat,
                               uptransaction_state,
                               uptransaction_text,
                               uptransaction_date,
                               uptransaction_amount)
                              VALUES( $user_id,
                                      $type,
                                      $cat,
                                      $state,
                                      '$text',
                                      UNIX_TIMESTAMP( NOW() ),
                                      $amount )
                                      " );
  
  $transaction_id = $database->database_insert_id();

  return $transaction_id;
}



/*********************** HOOKS *********************/


/*
 * after successful registration
 *
 */
function userpoints_hook_signup_success( $arguments = array() ) {
  global $referer; // created in friends inviter
  global $setting;
  
  // entrance as step2do, exit !"step2" => user created
  if( $referer && $referer->user_exists ) {

      // AWARD ACTIVITY POINTS, DEFER TO EMAIL VERIFICATION, IF SET. 
      if($setting['setting_signup_verify'] == 0) {
        userpoints_update_points( $referer->user_info['user_id'], "refer" );  
      }
    
  }
  
}



/*
 * signup_verify page footer 
 *
 */
function userpoints_hook_footer_signup_verify( $arguments = array() ) {
  global $task, $is_error, $new_user;

  // AWARD ACTIVITY POINTS, IF EMAIL VERIFICATION IS SET
  if( ($is_error == 0) && ($task == "main") ) {

    // FIND REFERER - can make it all in one query, but then admin (userid=0) will get recorded as well, though it's ok
    $referer_user_id = semods::db_query_count( "SELECT user_referer FROM se_users WHERE user_id = " . $new_user->user_info['user_id'] );

    if( $referer_user_id  ) {
      userpoints_update_points( $referer_user_id, "refer" );  
    }

  }
  
}





/********************* PAYMENT NOTIFICATION FUNCTIONS *********************/





function userpoints_payment_notify( $params ) {

  $transaction_id = $params['tid'];
  
  // COMPLETE TRANSACTION & REWARD USER
  // TBD: if user was deleted - junk row
  $uptransaction = new semods_uptransaction($transaction_id);
  $uptransaction->complete();
 
}





/********************* SYSTEM FUNCTIONS *********************/





function deleteuser_userpoints( $user_id ) {
  global $database;

  // Remove counters
  $database->database_query("DELETE FROM se_semods_userpointcounters WHERE userpointcounters_user_id = $user_id");

  // Remove transactions
  $database->database_query("DELETE FROM se_semods_uptransactions WHERE uptransaction_user_id = $user_id");

  // Remove user points
  $database->database_query("DELETE FROM se_semods_userpoints WHERE userpoints_user_id = $user_id");

}





?>