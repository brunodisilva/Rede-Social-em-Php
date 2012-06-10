<?php


/*** USER POINT SPENDER - CHARGING ***/



function upspender_charge_ontransactionstart( $params ) {
  return true;
}


function upspender_charge_ontransactionfail( $params ) {

}

function upspender_charge_ontransactionsuccess( $params ) {

  $upspender = $params[0];

  $params['transaction_text'] =  $upspender->upspender_info['userpointspender_title'];

  return true;
}

function upspender_charge_ontransactionfinished( $params ) {
  return true;
}



?>