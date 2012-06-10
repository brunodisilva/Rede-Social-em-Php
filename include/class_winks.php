<?

/* FUNCTIONS INCLUDED IN THE CLASS se_winks:
	wink_back()
	wink_total()
	wink_total_incoming()
	user_wink_list()
	user_wink_list_incoming()
	wink_remove()
	wink_remove_incoming()
	user_winked()
	user_winked_incoming()
*/

class se_winks {

//WINK
function wink($wink_owner_id,$wink_sender_id) {
	  global $database;
if($database->database_num_rows($database->database_query("SELECT * FROM sp_winks WHERE wink_owner_id='$wink_owner_id' AND wink_sender_id='$wink_sender_id'")) == 0) {
	  $database->database_query("INSERT INTO sp_winks (wink_owner_id, wink_sender_id) VALUES ('$wink_owner_id', '$wink_sender_id')");
	}
}

//WINK BACK
function wink_back($wink_owner_id,$wink_sender_id) {
	  global $database;
	if($database->database_num_rows($database->database_query("SELECT * FROM sp_winks WHERE wink_owner_id='$wink_owner_id' AND wink_sender_id='$wink_sender_id'")) == 0) {  
	$database->database_query("INSERT INTO sp_winks (wink_owner_id, wink_sender_id) VALUES ('$wink_owner_id','$wink_sender_id')");
}
}

//WINK TOTAL	
function wink_total($wink_owner_id) {
	  global $database, $setting;
	  $wink_total = 0;
	  $wink_query = "SELECT wink_owner_id FROM sp_winks LEFT JOIN se_users ON sp_winks.wink_owner_id=se_users.user_id WHERE wink_owner_id='$wink_owner_id'";
      $wink_total = $database->database_num_rows($database->database_query($wink_query));

	  return $wink_total;
} 

//WINK TOTAL INCOMING	
function wink_total_incoming($wink_owner_id) {
	  global $database, $setting;
	  $wink_total = 0;
	  $wink_query = "SELECT wink_owner_id FROM sp_winks LEFT JOIN se_users ON sp_winks.wink_sender_id=se_users.user_id WHERE wink_sender_id='$wink_owner_id'";
      $wink_total = $database->database_num_rows($database->database_query($wink_query));

	  return $wink_total;
}

//WINK LIST	
function user_wink_list($wink_owner_id,$start, $limit, $sort_by = "sp_winks.wink_date DESC") {
	  global $database, $setting;
	  $winks_array = Array();
	    $winks_query = "SELECT sp_winks.*, se_users.* 
		                 FROM sp_winks LEFT JOIN se_users ON sp_winks.wink_sender_id=se_users.user_id 
						 WHERE wink_owner_id='$wink_owner_id' ORDER BY $sort_by LIMIT $start, $limit";
	    $winks = $database->database_query($winks_query);
	    while($wink_info = $database->database_fetch_assoc($winks)) {
	      
		  $wink_user = new se_user();
	      $wink_user->user_info[user_id] = $wink_info[user_id];
	      $wink_user->user_info[user_username] = $wink_info[user_username];
	      $wink_user->user_info[user_photo] = $wink_info[user_photo];
	      $wink_user->user_info[user_lastlogindate] = $wink_info[user_lastlogindate];
	      $wink_user->user_info[user_dateupdated] = $wink_info[user_dateupdated];

	      $winks_array[] = Array('wink_id' => $wink_info[wink_id],
				'wink_date' => $wink_info[wink_date],
				'wink_user' => $wink_user);
	    }
	  return $winks_array;
}

//WINK LIST INCOMING
function user_wink_list_incoming($wink_owner_id,$start, $limit, $sort_by = "sp_winks.wink_date DESC") {
	  global $database, $setting;
	  $winks_array = Array();
	    $winks_query = "SELECT sp_winks.*, se_users.* 
		                 FROM sp_winks LEFT JOIN se_users ON sp_winks.wink_owner_id=se_users.user_id 
						 WHERE wink_sender_id='$wink_owner_id' ORDER BY $sort_by LIMIT $start, $limit";
	    $winks = $database->database_query($winks_query);
		while($wink_info = $database->database_fetch_assoc($winks)) {
		  $wink_user = new se_user();
	      $wink_user->user_info[user_id] = $wink_info[user_id];
	      $wink_user->user_info[user_username] = $wink_info[user_username];
	      $wink_user->user_info[user_photo] = $wink_info[user_photo];
	      $wink_user->user_info[user_lastlogindate] = $wink_info[user_lastlogindate];
	      $wink_user->user_info[user_dateupdated] = $wink_info[user_dateupdated];

	      $winks_array[] = Array('wink_id' => $wink_info[wink_id],
				'wink_date' => $wink_info[wink_date],
				'wink_user' => $wink_user);
	    }
	  return $winks_array;
}

//WINK REMOVE
function wink_remove($wink_owner_id,$wink_sender_id) {
	  global $database, $setting;
          $wink_query = $database->database_query("SELECT wink_id FROM sp_winks WHERE wink_sender_id='$wink_owner_id' AND wink_owner_id='$wink_sender_id'");
          if($database->database_num_rows($wink_query) > 0 ) {
            $delete_wink = $database->database_fetch_assoc($wink_query);
            $database->database_query("DELETE FROM sp_winks WHERE wink_id='$delete_wink[wink_id]'");
          }
}

//WINK REMOVE INCOMING
function wink_remove_incoming($wink_owner_id,$wink_sender_id) {
	  global $database, $setting;
          $wink_query = $database->database_query("SELECT wink_id FROM sp_winks WHERE wink_owner_id='$wink_owner_id' AND wink_sender_id='$wink_sender_id'");
          if($database->database_num_rows($wink_query) > 0 ) {
            $delete_wink = $database->database_fetch_assoc($wink_query);
            $database->database_query("DELETE FROM sp_winks WHERE wink_id='$delete_wink[wink_id]'");
          }
} 	

//USER WINKED
function user_winked($wink_owner_id,$wink_sender_id) {
	  global $database;
	  if($database->database_num_rows($database->database_query("SELECT wink_id FROM sp_winks WHERE wink_owner_id='$wink_owner_id' AND wink_sender_id='$wink_sender_id'")) == 1) {
	    return true;
	  } else {
	    return false;
	  }
}

//USER WINKED INCOMING
function user_winked_incoming($wink_owner_id,$wink_sender_id) {
	  global $database;
	  if($database->database_num_rows($database->database_query("SELECT wink_id FROM sp_winks WHERE wink_sender_id='$wink_owner_id' AND win_owner_id='$wink_sender_id'")) == 1) {
	    return true;
	  } else {
	    return false;
	  }
}
	
}

?>