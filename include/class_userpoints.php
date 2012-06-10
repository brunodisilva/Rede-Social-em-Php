<?

//  THIS CLASS IS USED TO OUTPUT AND UPDATE RECENT ACTIVITY ACTIONS
//  METHODS IN THIS CLASS:
//  class semods_uporder
//  class semods_upearner
//  class semods_upspender





/******************  CLASS semods_uporder  ******************/




class semods_uporder {
    
    var $uporder_exists = 0;
    var $uporder_info;
    
    function semods_uporder( $order_hash ) {
        if(!preg_match('/^[A-Fa-f0-9]{32}$/',$order_hash))
            return;
        $this->uporder_info = semods::db_query_assoc("SELECT * FROM se_semods_uporders WHERE uporder_hash = '$order_hash'");
        if($this->uporder_info) {
            $this->uporder_exists = 1;
        }
        
    }
    
    function is_completed() {
        return $this->uporder_info['uporder_state'] == 0;
    }
    
    function complete() {
        global $database;
        
        $database->database_query("UPDATE se_semods_uporders SET uporder_state = 0 WHERE uporder_id = " . $this->uporder_info['uporder_id'] ).
        $this->uporder_info['uporder_state'] = 0;
    }
}





/******************  CLASS semods_uptransaction  ******************/




class semods_uptransaction {
    
    var $uptransaction_exists = 0;
    var $uptransaction_info;
    
    function semods_uptransaction( $transaction_id ) {
        
        $this->uptransaction_info = semods::db_query_assoc("SELECT * FROM se_semods_uptransactions WHERE uptransaction_id = $transaction_id");
        if($this->uptransaction_info) {
            $this->uptransaction_exists = 1;
        }
        
    }
    
    function is_completed() {
        return $this->uptransaction_info['uptransaction_state'] == 0;
    }
    
    function complete() {
        global $database;
        
        if(($this->uptransaction_exists == 0) || $this->is_completed() )
            return false;
        
        $database->database_query("UPDATE se_semods_uptransactions SET uptransaction_state = 0 WHERE uptransaction_id = {$this->uptransaction_info['uptransaction_id']}");
        
        // FINISH TRANSACTION - REWARD USER IF "EARNER", DO NOTHING IF "SPENDER"
        // TBD: if user was deleted - junk row

        if($this->uptransaction_info['uptransaction_cat'] == 1)  {
            userpoints_add( $this->uptransaction_info['uptransaction_user_id'], $this->uptransaction_info['uptransaction_amount'] );
        }
        
        $this->uptransaction_info['uptransaction_state'] = 0;
        
        return true;
    }
    
    function cancel() {
        global $database;

        if(($this->uptransaction_exists == 0) || $this->is_completed() )
            return false;

        $database->database_query("UPDATE se_semods_uptransactions SET uptransaction_state = 2 WHERE uptransaction_id = {$this->uptransaction_info['uptransaction_id']}");

        // REFUND POINTS IF "SPENDER", DO NOTHING IF "EARNER"
        if($this->uptransaction_info['uptransaction_cat'] == 2)  {
            userpoints_add( $this->uptransaction_info['uptransaction_user_id'],
                            abs($this->uptransaction_info['uptransaction_amount']),
                            false // do not update "total earned"
                            );
        }

        $this->uptransaction_info['uptransaction_state'] = 2;

        return true;
    }
    
}




/******************  CLASS semods_upearner  ******************/




class semods_upearner {

    
    var $upearner_exists = 0;
    var $upearner_info;
    
    var $err_msg;
    
    var $transaction_message;

    function semods_upearner( $upearner_id, $onlyenabled = true ) {

        if($onlyenabled) 
            $this->upearner_info = semods::db_query_assoc("SELECT * FROM se_semods_userpointearner E LEFT JOIN se_semods_userpointearnertypes T ON E.userpointearner_type = T.userpointearnertype_type WHERE E.userpointearner_enabled != 0 AND E.userpointearner_id = $upearner_id");
        else
            $this->upearner_info = semods::db_query_assoc("SELECT * FROM se_semods_userpointearner E LEFT JOIN se_semods_userpointearnertypes T ON E.userpointearner_type = T.userpointearnertype_type WHERE E.userpointearner_id = $upearner_id");
    
        if($this->upearner_info) {
            $this->upearner_exists = 1;

            // CONVERT HTML CHARACTERS BACK
            $this->upearner_info['userpointearner_body'] = str_replace("\r\n", "", html_entity_decode( $this->upearner_info['userpointearner_body'] ));

            if(empty($this->upearner_info['userpointearner_photo'])) {
              $this->upearner_info['userpointearner_photo'] = './images/nophoto.gif';
            }
            
        }
    }
    
    function factory($item_id) {
        
    }
    
    
    function delete() {
        global $database;
        
        if($this->upearner_exists == 0)
            return;
        
        $database->database_query("DELETE FROM se_semods_userpointearner WHERE userpointearner_id = " . $this->upearner_info['userpointearner_id'] );
    }
    
    function enable($enabled=true) {
        global $database;
        
        if($this->upearner_exists == 0)
            return;
        
        // bool -> int
        $enabled = intval($enabled);
        
        $database->database_query("UPDATE se_semods_userpointearner SET userpointearner_enabled = $enabled WHERE userpointearner_id = " . $this->upearner_info['userpointearner_id'] );
    }

    function total_items( $onlyenabled = true, $where = '' ) {
        if($onlyenabled)
            return semods::db_query_count( "SELECT COUNT(*) FROM se_semods_userpointearner WHERE userpointearner_type >= 100 AND userpointearner_enabled != 0" );
        else
            return semods::db_query_count( "SELECT COUNT(*) FROM se_semods_userpointearner WHERE userpointearner_type >= 100" );
    }

    

    function transact($user, $transaction_params = array() ) {
        global $database, $functions_userpoints;
        
        if(!file_exists("include/functions_userpoints_{$this->upearner_info['userpointearnertype_name']}.php")) {
            $this->err_msg = 100016028;
            return false;
        }
        
        include_once "include/functions_userpoints_{$this->upearner_info['userpointearnertype_name']}.php";
        
        
        /** BEFORE TRANSACTION **/
        
        
        
        $metadata = !empty( $this->upearner_info['userpointearner_metadata'] ) ? unserialize($this->upearner_info['userpointearner_metadata']) : array();
        $params = array( $this, $user, $metadata, $transaction_params );
        if(is_callable("upearner_{$this->upearner_info['userpointearnertype_name']}_ontransactionstart")) {
            if( !call_user_func_array( "upearner_{$this->upearner_info['userpointearnertype_name']}_ontransactionstart", array( &$params ) ) ) {
                if( isset($params['redirect']) ) {
                    header("Location: " . $params['redirect']);
                    exit;
                }
                
                $this->err_msg = isset($params['err_msg']) ? $params['err_msg'] : 100016027;
                return false;
            }
        }



        /** TRANSACTION **/

        // Instantly completed
        if( $this->upearner_info['userpointearner_transact_state'] == 0) {
            userpoints_add( $user->user_info['user_id'], $this->upearner_info['userpointearner_cost'] );
        }
        


        /** AFTER TRANSACTION SUCCESS **/



        if(is_callable("upearner_{$this->upearner_info['userpointearnertype_name']}_ontransactionsuccess")) {
            if( !call_user_func_array( "upearner_{$this->upearner_info['userpointearnertype_name']}_ontransactionsuccess", array( &$params ) ) ) {

                $this->err_msg = $params['err_msg'];

                // rollback

                return false;
            }
        }

        $this->transaction_message = isset($params['transaction_message']) ? $params['transaction_message'] : '';

        if( semods::g($params, 'transaction_record', 1) != 0) {

        $transaction_text = isset($params['transaction_text']) ? $params['transaction_text'] : '';
        $transaction_id = userpoints_new_transaction( $user->user_info['user_id'],
                                                      $this->upearner_info['userpointearner_type'],
                                                1,
                                                      $this->upearner_info['userpointearner_transact_state'],
                                                      $transaction_text,
                                                      $this->upearner_info['userpointearner_cost']
                                                     );
        
        $params[3]['transaction_id'] = $transaction_id;

        }


        /** UPDATE ENGAGEMENTS COUNTER **/

        $database->database_query( "UPDATE se_semods_userpointearner SET userpointearner_engagements = userpointearner_engagements + 1 WHERE userpointearner_id = " . $this->upearner_info['userpointearner_id'] );


                                            

        if(is_callable("upearner_{$this->upearner_info['userpointearnertype_name']}_ontransactionfinished")) {
            if( !call_user_func_array( "upearner_{$this->upearner_info['userpointearnertype_name']}_ontransactionfinished", array( &$params ) ) ) {
                $this->err_msg = $params['err_msg'];

                // rollback ?

                return false;
            }
        }

        // Redirection after transaction completed
        if( isset($params['redirect']) ) {
            header("Location: " . $params['redirect']);
            exit;
        }

        return array( 'transaction_message'  => $this->transacton_message );
    }



	function dir($item_id = 0) {

        if($item_id == 0 & $this->upearner_exists) {
          $item_id = $this->upearner_info['userpointearner_id'];
        }

	  //$subdir = $item_id+999-(($item_id-1)%1000);
	  //$itemdir = "../uploads_userpoints/$subdir/$item_id/";
      $itemdir = "../uploads_userpoints/";
	  return $itemdir;

	}



	function photo($nophoto_image = "") {

	  $item_photo = $this->dir() . $this->upearner_info['userpointearner_photo'];
	  if(!file_exists($item_photo) | $this->upearner_info['userpointearner_photo'] == "") {
        $item_photo = $nophoto_image;
        }
	  return $item_photo;
	  
	}

	function public_dir($item_id = 0) {

        if(($item_id == 0) && $this->upearner_exists) {
          $item_id = $this->upearner_info['userpointearner_id'];
        }

      $itemdir = "./uploads_userpoints/";
	  return $itemdir;

	}
    
	function public_photo($nophoto_image = "") {

	  $item_photo = $this->public_dir() . $this->upearner_info['userpointearner_photo'];
	  if(!file_exists($item_photo) | $this->upearner_info['userpointearner_photo'] == "") {
        $item_photo = $nophoto_image;
        }
	  return $item_photo;
	  
	}
    


	function photo_delete() {
	  global $database;
	  $item_photo = $this->photo();
	  if($item_photo != "") {
	    unlink($item_photo);
	    $database->database_query("UPDATE se_semods_userpointearner SET userpointearner_photo='' WHERE userpointearner_id='".$this->upearner_info['userpointearner_id']."'");
	    $this->upearner_info['userpointearner_photo'] = "";
	  }
	}


	function photo_upload($photo_name) {
	  global $database, $url;

	  // SET KEY VARIABLES
	  $file_maxsize = "4194304";
	  $file_exts = explode(",", str_replace(" ", "", strtolower( "jpg,jpeg,gif,png" )));
	  $file_types = explode(",", str_replace(" ", "", strtolower("image/jpeg, image/jpg, image/jpe, image/pjpeg, image/pjpg, image/x-jpeg, x-jpg, image/gif, image/x-gif, image/png, image/x-png")));
	  $file_maxwidth = 200;
	  $file_maxheight = 200;
	  $photo_newname = "0_".rand(1000, 9999).".jpg";
	  $file_dest = $this->dir() . $photo_newname;

	  $new_photo = new se_upload();
	  $new_photo->new_upload($photo_name, $file_maxsize, $file_exts, $file_types, $file_maxwidth, $file_maxheight);

	  // UPLOAD AND RESIZE PHOTO IF NO ERROR
	  if($new_photo->is_error == 0) {

	    // DELETE OLD AVATAR IF EXISTS
	    $this->photo_delete();

	    // CHECK IF IMAGE RESIZING IS AVAILABLE, OTHERWISE MOVE UPLOADED IMAGE
	    if($new_photo->is_image == 1) {
	      $new_photo->upload_photo($file_dest);
	    } else {
	      $new_photo->upload_file($file_dest);
	    }

	    // UPDATE INFO WITH IMAGE IF STILL NO ERROR
	    if($new_photo->is_error == 0) {
	      $database->database_query("UPDATE se_semods_userpointearner SET userpointearner_photo='$photo_newname' WHERE userpointearner_id='".$this->upearner_info['userpointearner_id']."'");
	      $this->upearner_info['userpointearner_photo'] = $photo_newname;
	    }
	  }
	
	  $this->is_error = $new_photo->is_error;
	  $this->error_message = $new_photo->error_message;

	}
    

}






/******************  CLASS semods_upspender  ******************/





class semods_upspender {

    
    var $upspender_exists = 0;
    var $upspender_info;
    
    var $err_msg;
    
    var $transaction_message;


    function semods_upspender( $upspender_id, $onlyenabled = true ) {

        if($onlyenabled) 
            $this->upspender_info = semods::db_query_assoc("SELECT * FROM se_semods_userpointspender S LEFT JOIN se_semods_userpointspendertypes T ON S.userpointspender_type = T.userpointspendertype_type WHERE S.userpointspender_enabled != 0 AND S.userpointspender_id = $upspender_id");
        else
            $this->upspender_info = semods::db_query_assoc("SELECT * FROM se_semods_userpointspender S LEFT JOIN se_semods_userpointspendertypes T ON S.userpointspender_type = T.userpointspendertype_type WHERE S.userpointspender_id = $upspender_id");

        if($this->upspender_info) {
            $this->upspender_exists = 1;

            // CONVERT HTML CHARACTERS BACK
            $this->upspender_info['userpointspender_body'] = str_replace("\r\n", "", html_entity_decode( $this->upspender_info['userpointspender_body'] ));

            if(empty($this->upspender_info['userpointspender_photo'])) {
              $this->upspender_info['userpointspender_photo'] = './images/nophoto.gif';
            }
            
        }
    }
    
    
    function factory($item_id) {
        
    }


    function delete() {
        global $database;
        
        if($this->upspender_exists == 0)
            return;
        
        $database->database_query("DELETE FROM se_semods_userpointspender WHERE userpointspender_id = " . $this->upspender_info['userpointspender_id'] );
    }
    
    
    function enable($enabled=true) {
        global $database;
        
        if($this->upspender_exists == 0)
            return;
        
        // bool -> int
        $enabled = intval($enabled);
        
        $database->database_query("UPDATE se_semods_userpointspender SET userpointspender_enabled = $enabled WHERE userpointspender_id = " . $this->upspender_info['userpointspender_id'] );
    }
    
    
    function total_items( $onlyenabled = true, $where = '' ) {
        if($onlyenabled)
            return semods::db_query_count( "SELECT COUNT(*) FROM se_semods_userpointspender WHERE userpointspender_type >= 100 AND userpointspender_enabled != 0" );
        else
            return semods::db_query_count( "SELECT COUNT(*) FROM se_semods_userpointspender WHERE userpointspender_type >= 100" );
    }


    function transact($user) {
        global $database, $functions_userpoints;
        
        if(!file_exists("include/functions_userpoints_{$this->upspender_info['userpointspendertype_name']}.php")) {
            $this->err_msg = 100016027;
            return false;
        }
        
        include_once "include/functions_userpoints_{$this->upspender_info['userpointspendertype_name']}.php";
        
        
        
        /** BEFORE TRANSACTION **/
        
        
        
        $metadata = !empty( $this->upspender_info['userpointspender_metadata'] ) ? unserialize($this->upspender_info['userpointspender_metadata']) : array();
        $params = array( $this, $user, $metadata );
        if(is_callable("upspender_{$this->upspender_info['userpointspendertype_name']}_ontransactionstart")) {
            if( !call_user_func_array( "upspender_{$this->upspender_info['userpointspendertype_name']}_ontransactionstart", array( &$params ) ) ) {

                if( isset($params['redirect']) ) {
                    header("Location: " . $params['redirect']);
                    exit;
                }
                
                $this->err_msg = isset($params['err_msg']) ? $params['err_msg'] : 100016028;
                return false;
            }
        }



        /** TRANSACTION **/
        
        
        
        if( !userpoints_deduct( $user->user_info['user_id'], $this->upspender_info['userpointspender_cost'] ) ) {
            $this->err_msg = 100016029;

            if(is_callable("upspender_{$this->upspender_info['userpointspendertype_name']}_ontransactionfail")) {
                call_user_func_array( "upspender_{$this->upspender_info['userpointspendertype_name']}_ontransactionstart", array( &$params ) );
            }
            
            return false;
        }
        


        /** AFTER TRANSACTION SUCCESS **/



        if(is_callable("upspender_{$this->upspender_info['userpointspendertype_name']}_ontransactionsuccess")) {
            if( !call_user_func_array( "upspender_{$this->upspender_info['userpointspendertype_name']}_ontransactionsuccess", array( &$params ) ) ) {
                $this->err_msg = $params['err_msg'];

                // rollback, uses "deduct" with negative amount to also update "spent points"
                userpoints_deduct( $user->user_info['user_id'], -$this->upspender_info['userpointspender_cost'] );
                return false;
            }
        }

        $this->transaction_message = isset($params['transaction_message']) ? $params['transaction_message'] : '';

        if( semods::g($params, 'transaction_record', 1) != 0) {

        $transaction_text = isset($params['transaction_text']) ? $params['transaction_text'] : '';
        $transaction_id = userpoints_new_transaction( $user->user_info['user_id'],
                                                      $this->upspender_info['userpointspender_type'],
                                                2,
                                                      $this->upspender_info['userpointspender_transact_state'],
                                                      $transaction_text,
                                                      -$this->upspender_info['userpointspender_cost']
                                                     );

            $params[3]['transaction_id'] = $transaction_id;

        }

        /** UPDATE ENGAGEMENTS COUNTER **/

        $database->database_query( "UPDATE se_semods_userpointspender SET userpointspender_engagements = userpointspender_engagements + 1 WHERE userpointspender_id = " . $this->upspender_info['userpointspender_id'] );

        if(is_callable("upspender_{$this->upspender_info['userpointspendertype_name']}_ontransactionfinished")) {
            if( !call_user_func_array( "upspender_{$this->upspender_info['userpointspendertype_name']}_ontransactionfinished", array( &$params ) ) ) {
                $this->err_msg = $params['err_msg'];

                // rollback ?

                return false;
            }
        }

        // Redirection after transaction completed
        if( isset($params['redirect']) ) {
            header("Location: " . $params['redirect']);
            exit;
        }

                                            
        return array( 'transaction_message'  => $this->transacton_message );
    }
    

	function dir($item_id = 0) {

        if($item_id == 0 & $this->upspender_exists) {
          $item_id = $this->upspender_info['userpointspender_id'];
        }

	  //$subdir = $item_id+999-(($item_id-1)%1000);
	  //$itemdir = "../uploads_userpoints/$subdir/$item_id/";
      $itemdir = "../uploads_userpoints/";
	  return $itemdir;

	}


	function photo($nophoto_image = "") {

	  $item_photo = $this->dir() . $this->upspender_info['userpointspender_photo'];
	  if(!file_exists($item_photo) | $this->upspender_info['userpointspender_photo'] == "") {
        $item_photo = $nophoto_image;
        }
	  return $item_photo;
	  
	}


	function public_dir($item_id = 0) {

        if(($item_id == 0) && $this->upspender_exists) {
          $item_id = $this->upspender_info['userpointspender_id'];
        }

	  //$subdir = $item_id+999-(($item_id-1)%1000);
	  //$itemdir = "../uploads_userpoints/$subdir/$item_id/";
      $itemdir = "./uploads_userpoints/";
	  return $itemdir;

	}
    
    
	function public_photo($nophoto_image = "") {

	  $item_photo = $this->public_dir() . $this->upspender_info['userpointspender_photo'];
	  if(!file_exists($item_photo) | $this->upspender_info['userpointspender_photo'] == "") {
        $item_photo = $nophoto_image;
        }
	  return $item_photo;
	  
	}
    

	function photo_delete() {
	  global $database;
	  $item_photo = $this->photo();
	  if($item_photo != "") {
	    unlink($item_photo);
	    $database->database_query("UPDATE se_semods_userpointspender SET userpointspender_photo='' WHERE userpointspender_id='".$this->upspender_info['userpointspender_id']."'");
	    $this->upspender_info['userpointspender_photo'] = "";
	  }
	}


	function photo_upload($photo_name) {
	  global $database, $url;

	  // SET KEY VARIABLES
	  $file_maxsize = "4194304";
	  $file_exts = explode(",", str_replace(" ", "", strtolower( "jpg,jpeg,gif,png" )));
	  $file_types = explode(",", str_replace(" ", "", strtolower("image/jpeg, image/jpg, image/jpe, image/pjpeg, image/pjpg, image/x-jpeg, x-jpg, image/gif, image/x-gif, image/png, image/x-png")));
	  $file_maxwidth = 200;
	  $file_maxheight = 200;
	  $photo_newname = "0_".rand(1000, 9999).".jpg";
	  $file_dest = $this->dir() . $photo_newname;

	  $new_photo = new se_upload();
	  $new_photo->new_upload($photo_name, $file_maxsize, $file_exts, $file_types, $file_maxwidth, $file_maxheight);

	  // UPLOAD AND RESIZE PHOTO IF NO ERROR
	  if($new_photo->is_error == 0) {

	    // DELETE OLD AVATAR IF EXISTS
	    $this->photo_delete();

	    // CHECK IF IMAGE RESIZING IS AVAILABLE, OTHERWISE MOVE UPLOADED IMAGE
	    if($new_photo->is_image == 1) {
	      $new_photo->upload_photo($file_dest);
	    } else {
	      $new_photo->upload_file($file_dest);
	    }

	    // UPDATE INFO WITH IMAGE IF STILL NO ERROR
	    if($new_photo->is_error == 0) {
	      $database->database_query("UPDATE se_semods_userpointspender SET userpointspender_photo='$photo_newname' WHERE userpointspender_id='".$this->upspender_info['userpointspender_id']."'");
	      $this->upspender_info['userpointspender_photo'] = $photo_newname;
	    }
	  }
	
	  $this->is_error = $new_photo->is_error;
	  $this->error_message = $new_photo->error_message;

	}

}







/*****  PAYMENT  *****/





/*
 * CART ITEM CLASS
 *
 */

// be sure to have this hanging around. TBD: factory? plugin headers inclusion order is uncontrollable.
if(!class_exists("semods_cartitem")) {
  // is it enough?
  @include_once 'include/class_semods_cart.php';
}

// double check
if(class_exists("semods_cartitem")) {

  class semods_userpoints_cartitem extends semods_cartitem {
  
    function semods_userpoints_cartitem( $transaction_id, $item_name, $item_id, $item_price, $item_quantity = 1 ) {
      parent::semods_cartitem( 4, $item_name, $item_id, $item_price, $item_quantity, array(), 'userpoints_payment_notify', array( 'tid' => $transaction_id ) );
      
      $this->item_quantity_fixed = 1;
      $this->item_shortdesc = "TRANSACTIONID: $transaction_id";
    }
    
  }

}



?>