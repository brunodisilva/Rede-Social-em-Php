<?php


/*** USER POINT EARNER / SPENDER - generic ***/



/*** EARNER ***/


function upearner_generic_ontransactionstart( $params ) {

  $upspender = $params[0];
  $user = $params[1];
  $metadata = $params[2];
  $transaction_params = $params[3];


  if($metadata['t'] == 0) {
    $params['transaction_record'] = 0;
  }

  return true;
}


function upearner_generic_ontransactionfail( $params ) {

}

function upearner_generic_ontransactionsuccess( $params ) {
  global $url, $database, $misc, $setting, $functions_userpoints;
  
  $upearner = $params[0];
  
  $params['transaction_text'] = semods::get_language_text( 100016066 );
  $params['transaction_text'] .=  " ({$upearner->upearner_info['userpointearner_title']})";
  $params['transaction_text'] = addslashes($params['transaction_text']);

  return true;
}

function upearner_generic_ontransactionfinished( $params ) {
  global $url, $database, $misc, $setting;
  
  $upearner = $params[0];
  $user = $params[1];
  $metadata = $params[2];
  $transaction_params = $params[3];
  
  if( !empty($metadata['url']) ) {
    $redirect_url = trim($metadata['url']);
  
  	$params['redirect'] = html_entity_decode($redirect_url, ENT_QUOTES, 'UTF-8');
  
  }

  return true;
}





/*** SPENDER ***/






function upspender_generic_ontransactionstart( $params ) {

  $upspender = $params[0];
  $user = $params[1];
  $metadata = $params[2];
  $transaction_params = $params[3];


  if($metadata['t'] == 0) {
    $params['transaction_record'] = 0;
  }

  return true;
}


function upspender_generic_ontransactionfail( $params ) {

}

function upspender_generic_ontransactionsuccess( $params ) {
  global $url, $database, $misc, $setting, $functions_userpoints;
  
  $upspender = $params[0];
  
  $params['transaction_text'] = semods::get_language_text( 100016066 );
  $params['transaction_text'] .=  " ({$upspender->upspender_info['userpointspender_title']})";
  $params['transaction_text'] = addslashes($params['transaction_text']);

  return true;
}

function upspender_generic_ontransactionfinished( $params ) {
  global $url, $database, $misc, $setting;
  
  $upspender = $params[0];
  $user = $params[1];
  $metadata = $params[2];
  $transaction_params = $params[3];
  
  if( !empty($metadata['url']) ) {
    $redirect_url = trim($metadata['url']);
  
  	$params['redirect'] = html_entity_decode($redirect_url, ENT_QUOTES, 'UTF-8');
  }

  return true;
}


?>