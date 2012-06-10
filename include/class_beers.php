<?

/* FUNCTIONS INCLUDED IN THE CLASS se_beers:
	beer_back()
	beer_total()
	beer_total_incoming()
	user_beer_list()
	user_beer_list_incoming()
	beer_remove()
	beer_remove_incoming()
	user_beered()
	user_beered_incoming()
*/

class se_beers {

//BEER
function beer($beer_owner_id,$beer_sender_id) {
$nowdate = time();
      global $database;
if($database->database_num_rows($database->database_query("SELECT * FROM sp_beers WHERE beer_owner_id='$beer_owner_id' AND beer_sender_id='$beer_sender_id'")) == 0) {
      $database->database_query("INSERT INTO sp_beers (beer_date,beer_owner_id, beer_sender_id) VALUES ('$nowdate','$beer_owner_id', '$beer_sender_id')");
    }
}

//BEER BACK
function beer_back($beer_owner_id,$beer_sender_id) {
$nowdate = time();
      global $database;
    if($database->database_num_rows($database->database_query("SELECT * FROM sp_beers WHERE beer_owner_id='$beer_owner_id' AND beer_sender_id='$beer_sender_id'")) == 0) {  
    $database->database_query("INSERT INTO sp_beers (beer_date,beer_owner_id, beer_sender_id) VALUES ('$nowdate','beer_owner_id','$beer_sender_id')");
}
}

//beer TOTAL	
function beer_total($beer_owner_id) {
	  global $database, $setting;
	  $beer_total = 0;
	  $beer_query = "SELECT beer_owner_id FROM sp_beers LEFT JOIN se_users ON sp_beers.beer_owner_id=se_users.user_id WHERE beer_owner_id='$beer_owner_id'";
      $beer_total = $database->database_num_rows($database->database_query($beer_query));

	  return $beer_total;
} 

//beer TOTAL INCOMING	
function beer_total_incoming($beer_owner_id) {
	  global $database, $setting;
	  $beer_total = 0;
	  $beer_query = "SELECT beer_owner_id FROM sp_beers LEFT JOIN se_users ON sp_beers.beer_sender_id=se_users.user_id WHERE beer_sender_id='$beer_owner_id'";
      $beer_total = $database->database_num_rows($database->database_query($beer_query));

	  return $beer_total;
}

//beer LIST	
function user_beer_list($beer_owner_id,$start, $limit, $sort_by = "sp_beers.beer_date DESC") {
	  global $database, $setting;
	  $beers_array = Array();
	    $beers_query = "SELECT sp_beers.*, se_users.* 
		                 FROM sp_beers LEFT JOIN se_users ON sp_beers.beer_sender_id=se_users.user_id 
						 WHERE beer_owner_id='$beer_owner_id' ORDER BY $sort_by LIMIT $start, $limit";
	    $beers = $database->database_query($beers_query);
	    while($beer_info = $database->database_fetch_assoc($beers)) {
	      
		  $beer_user = new se_user();
	      $beer_user->user_info[user_id] = $beer_info[user_id];
	      $beer_user->user_info[user_username] = $beer_info[user_username];
	      $beer_user->user_info[user_photo] = $beer_info[user_photo];
	      $beer_user->user_info[user_lastlogindate] = $beer_info[user_lastlogindate];
	      $beer_user->user_info[user_dateupdated] = $beer_info[user_dateupdated];

	      $beers_array[] = Array('beer_id' => $beer_info[beer_id],
				'beer_date' => $beer_info[beer_date],
				'beer_user' => $beer_user);
	    }
	  return $beers_array;
}

//beer LIST INCOMING
function user_beer_list_incoming($beer_owner_id,$start, $limit, $sort_by = "sp_beers.beer_date DESC") {
	  global $database, $setting;
	  $beers_array = Array();
	    $beers_query = "SELECT sp_beers.*, se_users.* 
		                 FROM sp_beers LEFT JOIN se_users ON sp_beers.beer_owner_id=se_users.user_id 
						 WHERE beer_sender_id='$beer_owner_id' ORDER BY $sort_by LIMIT $start, $limit";
	    $beers = $database->database_query($beers_query);
		while($beer_info = $database->database_fetch_assoc($beers)) {
		  $beer_user = new se_user();
	      $beer_user->user_info[user_id] = $beer_info[user_id];
	      $beer_user->user_info[user_username] = $beer_info[user_username];
	      $beer_user->user_info[user_photo] = $beer_info[user_photo];
	      $beer_user->user_info[user_lastlogindate] = $beer_info[user_lastlogindate];
	      $beer_user->user_info[user_dateupdated] = $beer_info[user_dateupdated];

	      $beers_array[] = Array('beer_id' => $beer_info[beer_id],
				'beer_date' => $beer_info[beer_date],
				'beer_user' => $beer_user);
	    }
	  return $beers_array;
}

//beer REMOVE
function beer_remove($beer_owner_id,$beer_sender_id) {
	  global $database, $setting;
          $beer_query = $database->database_query("SELECT beer_id FROM sp_beers WHERE beer_sender_id='$beer_owner_id' AND beer_owner_id='$beer_sender_id'");
          if($database->database_num_rows($beer_query) > 0 ) {
            $delete_beer = $database->database_fetch_assoc($beer_query);
            $database->database_query("DELETE FROM sp_beers WHERE beer_id='$delete_beer[beer_id]'");
          }
}

//beer REMOVE INCOMING
function beer_remove_incoming($beer_owner_id,$beer_sender_id) {
	  global $database, $setting;
          $beer_query = $database->database_query("SELECT beer_id FROM sp_beers WHERE beer_owner_id='$beer_owner_id' AND beer_sender_id='$beer_sender_id'");
          if($database->database_num_rows($beer_query) > 0 ) {
            $delete_beer = $database->database_fetch_assoc($beer_query);
            $database->database_query("DELETE FROM sp_beers WHERE beer_id='$delete_beer[beer_id]'");
          }
} 	

//USER beerED
function user_beered($beer_owner_id,$beer_sender_id) {
	  global $database;
	  if($database->database_num_rows($database->database_query("SELECT beer_id FROM sp_beers WHERE beer_owner_id='$beer_owner_id' AND beer_sender_id='$beer_sender_id'")) == 1) {
	    return true;
	  } else {
	    return false;
	  }
}

//USER beerED INCOMING
function user_beered_incoming($beer_owner_id,$beer_sender_id) {
	  global $database;
	  if($database->database_num_rows($database->database_query("SELECT beer_id FROM sp_beers WHERE beer_sender_id='$beer_owner_id' AND win_owner_id='$beer_sender_id'")) == 1) {
	    return true;
	  } else {
	    return false;
	  }
}
	
}

?>