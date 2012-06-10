<?php


/*** USER POINT EARNER - POLL VOTE ***/



function upearner_votepoll_ontransactionstart( $params ) {
  global $url;

  $upspender = $params[0];
  $user = $params[1];
  $metadata = $params[2];
  $transaction_params = $params[3];


  // if requirements filled (called when actual vote is being casted)
  if( semods::g($transaction_params, 'gotvars', 0) == 1 )
    return true;

  $require_redirect = true;

  // get poll owner
  $poll_id = intval($metadata['p_id']);
  $owner_username = semods::db_query_count( "SELECT user_username FROM se_polls P JOIN se_users U ON P.poll_user_id = U.user_id WHERE P.poll_id = $poll_id" );

  if( !$owner_username ) {
    $params['err_msg'] = "This poll doesn't exist.";
    return false;
  }

  $params['redirect'] = $url->url_create('poll', $owner_username, $poll_id);

  return false;
}


function upearner_votepoll_ontransactionfail( $params ) {

}


function upearner_votepoll_ontransactionsuccess( $params ) {
  global $url, $database, $misc, $setting, $functions_userpoints;

  $upearner = $params[0];
  $user = $params[1];
  $metadata = $params[2];
  $transaction_params = $params[3];

  // get poll owner
  $poll_id = intval($metadata['p_id']);
  $owner_username = semods::db_query_count( "SELECT user_username FROM se_polls P JOIN se_users U ON P.poll_user_id = U.user_id WHERE P.poll_id = $poll_id" );

  if( !$owner_username ) {
    $params['err_msg'] = 100016032;
    return false;
  }

  $params['transaction_text'] = semods::get_language_text( 100016031 ) . " <a href='" . $url->url_create('poll', $owner_username, $poll_id) . "'>" . semods::get_language_text(100016033) . "</a>";
  $params['transaction_text'] .=  " ({$upearner->upearner_info['userpointearner_title']})";
  $params['transaction_text'] = addslashes($params['transaction_text']);

  return true;

}



?>