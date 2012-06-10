<?

/* FUNCTIONS INCLUDED IN THE CLASS se_kiss:
	kiss_back()
	kiss_total()
	kiss_total_incoming()
	user_kiss_list()
	user_kiss_list_incoming()
	kiss_remove()
	kiss_remove_incoming()
	user_kissed()
	user_kissed_incoming()
*/

class se_kiss {

//kiss
function kiss($kiss_owner_id,$kiss_sender_id) {
	  global $database;
if($database->database_num_rows($database->database_query("SELECT * FROM sp_kiss WHERE kiss_owner_id='$kiss_owner_id' AND kiss_sender_id='$kiss_sender_id'")) == 0) {
	  $database->database_query("INSERT INTO sp_kiss (kiss_owner_id, kiss_sender_id) VALUES ('$kiss_owner_id', '$kiss_sender_id')");
	}
}

//kiss BACK
function kiss_back($kiss_owner_id,$kiss_sender_id) {
	  global $database;
	if($database->database_num_rows($database->database_query("SELECT * FROM sp_kiss WHERE kiss_owner_id='$kiss_owner_id' AND kiss_sender_id='$kiss_sender_id'")) == 0) {  
	$database->database_query("INSERT INTO sp_kiss (kiss_owner_id, kiss_sender_id) VALUES ('$kiss_owner_id','$kiss_sender_id')");
}
}

//kiss TOTAL	
function kiss_total($kiss_owner_id) {
	  global $database, $setting;
	  $kiss_total = 0;
	  $kiss_query = "SELECT kiss_owner_id FROM sp_kiss LEFT JOIN se_users ON sp_kiss.kiss_owner_id=se_users.user_id WHERE kiss_owner_id='$kiss_owner_id'";
      $kiss_total = $database->database_num_rows($database->database_query($kiss_query));

	  return $kiss_total;
} 

//kiss TOTAL INCOMING	
function kiss_total_incoming($kiss_owner_id) {
	  global $database, $setting;
	  $kiss_total = 0;
	  $kiss_query = "SELECT kiss_owner_id FROM sp_kiss LEFT JOIN se_users ON sp_kiss.kiss_sender_id=se_users.user_id WHERE kiss_sender_id='$kiss_owner_id'";
      $kiss_total = $database->database_num_rows($database->database_query($kiss_query));

	  return $kiss_total;
}

//kiss LIST	
function user_kiss_list($kiss_owner_id,$start, $limit, $sort_by = "sp_kiss.kiss_date DESC") {
	  global $database, $setting;
	  $kiss_array = Array();
	    $kiss_query = "SELECT sp_kiss.*, se_users.* 
		                 FROM sp_kiss LEFT JOIN se_users ON sp_kiss.kiss_sender_id=se_users.user_id 
						 WHERE kiss_owner_id='$kiss_owner_id' ORDER BY $sort_by LIMIT $start, $limit";
	    $kiss = $database->database_query($kiss_query);
	    while($kiss_info = $database->database_fetch_assoc($kiss)) {
	      
		  $kiss_user = new se_user();
	      $kiss_user->user_info[user_id] = $kiss_info[user_id];
	      $kiss_user->user_info[user_username] = $kiss_info[user_username];
	      $kiss_user->user_info[user_photo] = $kiss_info[user_photo];
	      $kiss_user->user_info[user_lastlogindate] = $kiss_info[user_lastlogindate];
	      $kiss_user->user_info[user_dateupdated] = $kiss_info[user_dateupdated];

	      $kiss_array[] = Array('kiss_id' => $kiss_info[kiss_id],
				'kiss_date' => $kiss_info[kiss_date],
				'kiss_user' => $kiss_user);
	    }
	  return $kiss_array;
}

//kiss LIST INCOMING
function user_kiss_list_incoming($kiss_owner_id,$start, $limit, $sort_by = "sp_kiss.kiss_date DESC") {
	  global $database, $setting;
	  $kiss_array = Array();
	    $kiss_query = "SELECT sp_kiss.*, se_users.* 
		                 FROM sp_kiss LEFT JOIN se_users ON sp_kiss.kiss_owner_id=se_users.user_id 
						 WHERE kiss_sender_id='$kiss_owner_id' ORDER BY $sort_by LIMIT $start, $limit";
	    $kiss = $database->database_query($kiss_query);
		while($kiss_info = $database->database_fetch_assoc($kiss)) {
		  $kiss_user = new se_user();
	      $kiss_user->user_info[user_id] = $kiss_info[user_id];
	      $kiss_user->user_info[user_username] = $kiss_info[user_username];
	      $kiss_user->user_info[user_photo] = $kiss_info[user_photo];
	      $kiss_user->user_info[user_lastlogindate] = $kiss_info[user_lastlogindate];
	      $kiss_user->user_info[user_dateupdated] = $kiss_info[user_dateupdated];

	      $kiss_array[] = Array('kiss_id' => $kiss_info[kiss_id],
				'kiss_date' => $kiss_info[kiss_date],
				'kiss_user' => $kiss_user);
	    }
	  return $kiss_array;
}

//kiss REMOVE
function kiss_remove($kiss_owner_id,$kiss_sender_id) {
	  global $database, $setting;
          $kiss_query = $database->database_query("SELECT kiss_id FROM sp_kiss WHERE kiss_sender_id='$kiss_owner_id' AND kiss_owner_id='$kiss_sender_id'");
          if($database->database_num_rows($kiss_query) > 0 ) {
            $delete_kiss = $database->database_fetch_assoc($kiss_query);
            $database->database_query("DELETE FROM sp_kiss WHERE kiss_id='$delete_kiss[kiss_id]'");
          }
}

//kiss REMOVE INCOMING
function kiss_remove_incoming($kiss_owner_id,$kiss_sender_id) {
	  global $database, $setting;
          $kiss_query = $database->database_query("SELECT kiss_id FROM sp_kiss WHERE kiss_owner_id='$kiss_owner_id' AND kiss_sender_id='$kiss_sender_id'");
          if($database->database_num_rows($kiss_query) > 0 ) {
            $delete_kiss = $database->database_fetch_assoc($kiss_query);
            $database->database_query("DELETE FROM sp_kiss WHERE kiss_id='$delete_kiss[kiss_id]'");
          }
} 	

//USER kissED
function user_kissed($kiss_owner_id,$kiss_sender_id) {
	  global $database;
	  if($database->database_num_rows($database->database_query("SELECT kiss_id FROM sp_kiss WHERE kiss_owner_id='$kiss_owner_id' AND kiss_sender_id='$kiss_sender_id'")) == 1) {
	    return true;
	  } else {
	    return false;
	  }
}

//USER kissED INCOMING
function user_kissed_incoming($kiss_owner_id,$kiss_sender_id) {
	  global $database;
	  if($database->database_num_rows($database->database_query("SELECT kiss_id FROM sp_kiss WHERE kiss_sender_id='$kiss_owner_id' AND win_owner_id='$kiss_sender_id'")) == 1) {
	    return true;
	  } else {
	    return false;
	  }
}
	
}

?>