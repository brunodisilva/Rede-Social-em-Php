<?php



/*** USER POINT SPENDER - LEVEL UPGRADE ***/



function upspender_levelupgrade_ontransactionstart( $params ) {
  global $functions_userpoints;

  $user = $params[1];
  $metadata = $params[2];

  // check if transitioning from levelX to levelY
  if( ($metadata['level_from'] != 0) && ($metadata['level_from'] != $user->level_info['level_id'])) {
    $params['err_msg'] = 100016037;
    return false;
  }

  return true;

}


function upspender_levelupgrade_ontransactionfail( $params ) {

}


function upspender_levelupgrade_ontransactionsuccess( $params ) {
  global $database, $functions_userpoints;

  // TBD: need to refresh user ... redirect?

  $user = $params[1];
  $metadata = $params[2];

  $database->database_query( "UPDATE se_users SET user_level_id = {$metadata['level_to']} WHERE user_id = {$user->user_info['user_id']}" );

  $params['transaction_message'] = semods::get_language_text( 100016038 );
  $params['transaction_text'] = semods::get_language_text( 100016036 );

  return true;

}



?>