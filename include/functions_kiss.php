<?

/* FUNCTIONS INCLUDED IN THIS FILE:
	deleteuser_kiss()
	kiss_privacy_levels()
	kiss_enabled()
	send_kiss_email()
*/

// WHEN A USER IS DELETED THIS FUNCTION IS RUN
function deleteuser_kiss($user_id) {
	global $database;

	// DELETE USER ACTIONS
	$database->database_query("DELETE FROM se_actions WHERE action_user_id='$user_id'");
	// DELETE USER NOTIFICATIONS
	$database->database_query("DELETE FROM se_notifys WHERE notify_user_id='$user_id' OR notify_object_id='$user_id'");
	// DELETE INCOMING AND OUTGOING kiss
	$database->database_query("DELETE FROM sp_kiss WHERE kiss_owner_id='$user_id' OR kiss_sender_id='$user_id'");

}

// PRIVACY LEVEL OPTIONS
function kiss_privacy_levels($privacy_level) {
	global $database, $functions_general, $functions_kiss;
  
  	$lang_query = $database->database_query("SELECT languagevar_value FROM se_languagevars WHERE languagevar_id = 90000105");
	$lang_array = Array();
	while($item = $database->database_fetch_assoc($lang_query)) {$case0 = $item[languagevar_value];}
  	$lang_query = $database->database_query("SELECT languagevar_value FROM se_languagevars WHERE languagevar_id = 90000106");
	$lang_array = Array();
	while($item = $database->database_fetch_assoc($lang_query)) {$case1 = $item[languagevar_value];}
  	$lang_query = $database->database_query("SELECT languagevar_value FROM se_languagevars WHERE languagevar_id = 90000107");
	$lang_array = Array();
	while($item = $database->database_fetch_assoc($lang_query)) {$case2 = $item[languagevar_value];}

	
	switch($privacy_level) {
	  case 0: $privacy = $case0; break;
	  case 1: $privacy = $case1; break;
	  case 2: $privacy = $case2; break;
	  default: $privacy = ""; break;
	}
	return $privacy;
}

// kiss ENABLED
function kiss_enabled(){
	global $database;
	
	$kiss_query = $database->database_query("SELECT * FROM sp_kiss_settings");
	$kiss_array = Array();
		while($item = $database->database_fetch_assoc($kiss_query)) {
			$kiss_enabled = $item[enabled];
		}
	return $kiss_enabled;
}



// SEND EMAIL
function send_kiss_email($owner, $friendname) {
	global $setting, $url;
	// SET USER SETTINGS
	$owner->user_settings();
	// MAKE SURE USER WANTS TO BE NOTIFIED
	if($owner->usersetting_info[usersetting_notify_kiss] != 0) {
	  // GET SERVER INFO
	  $prefix = $url->url_base;
	  // DECODE SUBJECT AND EMAIL FOR SENDING
	  $subject = htmlspecialchars_decode($setting[setting_email_kissrequest_subject], ENT_QUOTES);
	  $message = htmlspecialchars_decode($setting[setting_email_kissrequest_message], ENT_QUOTES);
	  // REPLACE VARIABLES IN EMAIL SUBJECT AND MESSAGE
	  $subject = str_replace("[kiss_receiver]", $owner->user_info[user_username], $subject);
	  $message = str_replace("[kiss_receiver]", $owner->user_info[user_username], $message);
	  $subject = str_replace("[kiss_sender]", $friendname, $subject);
	  $message = str_replace("[kiss_sender]", $friendname, $message);
	  $subject = str_replace("[link]", "<a href=\"$prefix"."login.php\">$prefix"."login.php</a>", $subject);
	  $message = str_replace("[link]", "<a href=\"$prefix"."login.php\">$prefix"."login.php</a>", $message);
	  // ENCODE SUBJECT FOR UTF8
	  $subject="=?UTF-8?B?".base64_encode($subject)."?=";
	  // REPLACE CARRIAGE RETURNS WITH BREAKS
	  $message = str_replace("\n", "<br>", $message);
	  // SET HEADERS
	  $from_email = "$setting[setting_email_fromname] <$setting[setting_email_fromemail]>";
	  $headers = "MIME-Version: 1.0"."\n";
	  $headers .= "Content-type: text/html; charset=utf-8"."\n";
	  $headers .= "Content-Transfer-Encoding: 8bit"."\n";
	  $headers .= "From: $from_email"."\n";
	  $headers .= "Return-Path: $from_email"."\n";
	  $headers .= "Reply-To: $from_email";
	  // SEND MAIL
	  @mail($owner->user_info[user_email], $subject, $message, $headers);
	}  
	return true;
}
	
?>