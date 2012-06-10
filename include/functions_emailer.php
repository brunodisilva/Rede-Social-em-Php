<?php

$mail_queue_log_buffer = '';
$mail_queue_log_has_errors = false;

$queued_email_types = array( 0 => 'General',
                             1 => 'Invitation Email'
                              );

/*********************** BACKGROUND EMAIL FUNCTIONS *********************/



function emailer_count_queued_emails( $type = null ) {

    $sql = "SELECT COUNT(*) FROM se_semods_email_queue WHERE success = 0";
    if(!is_null($type)) {
      $sql .= " AND type = $type";
    }
    if( semods::get_setting('emailer_max_email_retries') )
        $sql .= ' AND attempts <= ' . semods::get_setting('emailer_max_email_retries');

    return semods::db_query_count( $sql );
}


function semods_send_mail_queued($from_name, $from_email, $to_email, $subject, $message, $type = 1){
	global $database;

	$from_name = addslashes( $from_name );
	$from_email = addslashes( $from_email );
	$to_email = addslashes( $to_email);
	$subject = addslashes( $subject);
	$message = addslashes( $message );

	$query = "INSERT INTO se_semods_email_queue (from_name, from_email, to_email, subject, message, type) " .
			 "VALUES ('$from_name', '$from_email', '$to_email', '$subject', '$message', $type) ";

	$database->database_query($query);
}




/*********************** UTILITY EMAIL FUNCTIONS *********************/


function emailer_send_note_to_admin($subject, $message) {
	global $setting;

	// DECODE SUBJECT AND EMAIL FOR SENDING
    $from_email = "$setting[setting_email_fromname] <$setting[setting_email_fromemail]>";
    $admin_email = $setting['setting_email_fromemail'];
	$subject = htmlspecialchars_decode($subject, ENT_QUOTES);
	$message = htmlspecialchars_decode($message, ENT_QUOTES);

	// ENCODE SUBJECT FOR UTF8
	$subject="=?UTF-8?B?".base64_encode($subject)."?=";

	// REPLACE CARRIAGE RETURNS WITH BREAKS
	$message = str_replace("\n", "<br>", $message);

	// SET HEADERS
	$headers = "MIME-Version: 1.0"."\n";
	$headers .= "Content-type: text/html; charset=utf-8"."\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\n";
	$headers .= "From: $from_email"."\n";
	$headers .= "Return-Path: $from_email"."\n";
	$headers .= "Reply-To: $from_email";

	// SEND MAIL
	@mail($admin_email, $subject, $message, $headers);

	return true;
} // END send_helpcontact() FUNCTION



function emailer_send_mail_queue_log() {
  global $mail_queue_log_has_errors;
  global $mail_queue_log_buffer;
  
  if(empty($mail_queue_log_buffer))
    return;
  
  if($mail_queue_log_has_errors) {
    
    if (semods::get_setting('emailer_notify_on_error')) {
      emailer_send_note_to_admin( 'Background email - Notification of ERRORS', $mail_queue_log_buffer );
    }
    
  } else {

    if (semods::get_setting('emailer_notify_on_success')) {
      emailer_send_note_to_admin( 'Background email - Notification of success', $mail_queue_log_buffer );
    }
    
  }
  
  echo $mail_queue_log_buffer . "\n";
}


function emailer_mail_queue_log($message, $failure = false) {
  global $mail_queue_log_buffer, $mail_queue_log_has_errors;
  
  $mail_queue_log_buffer .= $message . "\n";
  
  if($failure)
    $mail_queue_log_has_errors = true;
}

function emailer_mail_queue_log_error($message) {
  emailer_mail_queue_log($message, true);
}





/*********************** SCHEDULED JOBS *********************/


function emailer_scheduler_job() {
  global $database;

  $base_dir = dirname(__FILE__) . "/../";
  include_once $base_dir . 'include/class.phpmailer.php';

  $use_smtp = semods::get_setting('emailer_use_smtp');   
  $smtpHost = semods::get_setting('emailer_smtp_host');
  $smtpPort = intval(semods::get_setting('emailer_smtp_port'));
  $smtpUser = semods::get_setting('emailer_smtp_user');
  $smtpPass = semods::get_setting('emailer_smtp_pass');
  $max_emails_per_batch = semods::get_setting('emailer_max_emails_per_batch');
  $max_retries = semods::get_setting('emailer_max_email_retries');
  
  $mail_queue_log_buffer = '';
  $mail_queue_log_has_errors = false;
  

  // Find all unsent emails
  
  $sql    = 'SELECT * FROM se_semods_email_queue WHERE success = 0';
  
  if($max_retries)
    $sql .= ' AND attempts <= ' . $max_retries;
  
  if($max_emails_per_batch)
    $sql .= ' LIMIT ' . $max_emails_per_batch;
  
  $result = $database->database_query($sql);
  
  if (!$result) {
    emailer_mail_queue_log_error( 'DB Error, could not query the database.' . ' MySQL Error: ' . mysql_error() );
    emailer_send_mail_queue_log();
    return;
  }
  
  if(0 == $database->database_num_rows($result))
    return;
    
  $mail = new PHPMailer();
  $mail->SetLanguage( "en", $base_dir . './lang/' );
  
  if($use_smtp)
    $mail->Mailer	= "smtp";
  else
    $mail->Mailer	= "mail";
    
  $mail->Host = $smtpHost;
  $mail->CharSet  = "UTF-8";
  
  if(!empty($smtpUser)) {
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
  }
  
  // loop through records and send emails
  while ($queued_mail = $database->database_fetch_assoc($result)) {
    
    $mail->From     = $queued_mail['from_email'];
    $mail->FromName = $queued_mail['from_name'];
    $mail->Subject  = $queued_mail['subject'];
    
    $mail->MsgHTML( $queued_mail['message'], false );
  
    $mail->AddAddress( $queued_mail['to_email'] );
   
    if(!$mail->Send()) {
      $database->database_query( 'UPDATE se_semods_email_queue SET attempts = attempts + 1, last_attempt = NOW() WHERE id = ' . $queued_mail['id'] );
      emailer_mail_queue_log_error( 'Error sending message to ' . $queued_mail['to_email'] . ' ( ' . $mail->ErrorInfo . ' ) ' );
    } else {
      //$database->database_query( 'UPDATE se_semods_email_queue SET attempts = attempts + 1, last_attempt = NOW(), success = 1, date_sent = NOW() WHERE id = ' . $queued_mail['id'] );
      $database->database_query( 'DELETE FROM se_semods_email_queue WHERE id = ' . $queued_mail['id'] );
      emailer_mail_queue_log( 'Message to ' . $queued_mail['to_email'] . ' successfully sent!' );
    }
    
    $mail->ClearAddresses();
    
  }
  
  emailer_send_mail_queue_log();    
    
}


?>