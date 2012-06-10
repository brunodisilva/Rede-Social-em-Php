<?php


/*** USER POINT EARNER - AFFILIATE ***/



function upearner_affiliate_ontransactionstart( $params ) {

  $upspender = $params[0];
  $user = $params[1];
  $metadata = $params[2];
  $transaction_params = $params[3];


  // if requirements filled (called when actual vote is being casted)
  if( semods::g($transaction_params, 'gotvars', 0) == 1 ) {
    // THIS SHOULD BE CALLED FROM A CALLBACK WITH SOME VARIABLE

    // ABORT TRANSACTION
    return false;
  }

  return true;
}


function upearner_affiliate_ontransactionfail( $params ) {

}

function upearner_affiliate_ontransactionsuccess( $params ) {
  global $url, $database, $misc, $setting, $functions_userpoints;

  $upearner = $params[0];

  $params['transaction_text'] = semods::get_language_text( 100016030 );
  $params['transaction_text'] .=  " ({$upearner->upearner_info['userpointearner_title']})";
  $params['transaction_text'] = addslashes($params['transaction_text']);

  return true;
}

function upearner_affiliate_ontransactionfinished( $params ) {
  global $url, $database, $misc, $setting;

  $upearner = $params[0];
  $user = $params[1];
  $metadata = $params[2];
  $transaction_params = $params[3];

  $params['redirect'] = str_replace( array( '[userid]',
                                            '[username]',
                                            '[transactionid]'
                                            ),
                                     array( $user->user_info['user_id'],
                                            $user->user_info['user_username'],
                                            $transaction_params['transaction_id']
                                           ),
                                           html_entity_decode($metadata['url'], ENT_QUOTES, 'UTF-8')

                                    );

  return true;
}



?>