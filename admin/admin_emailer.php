<?
$page = "admin_emailer";
include "admin_header.php";

$task = semods::getpost('task', 'main');

// SET RESULT VARIABLE
$result = 0;

// TEST SMTP EMAIL
if($task == "test") {

  error_reporting( E_ALL ^ E_NOTICE ^ E_WARNING );
  
  $base_dir = dirname(__FILE__) . "/../";
  include_once $base_dir . 'include/class.phpmailer.php';
  
  $smtpHost = semods::get_setting('emailer_smtp_host');
  $smtpPort = intval(semods::get_setting('emailer_smtp_port'));
  $smtpUser = semods::get_setting('emailer_smtp_user');
  $smtpPass = semods::get_setting('emailer_smtp_pass');

  $mail = new PHPMailer();
  $mail->SetLanguage( "en", $base_dir . './lang/' );
  
  $mail->Mailer	= "smtp";
  $mail->Host = $smtpHost;
  $mail->CharSet  = "UTF-8";
  
  if(!empty($smtpUser)) {
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
  }

  $mail->From     = $setting['setting_email_fromemail'];
  $mail->FromName = $setting['setting_email_fromname'];
  $mail->Subject  = "Test Email";

  $mail->MsgHTML( "Test Message", false );

  $mail->AddAddress( $setting['setting_email_fromemail'] );
 
  if(!$mail->Send()) {
    echo "Error sending message: " . $mail->ErrorInfo;
  } else {
    echo "Everything seems to be ok, now check your mailbox.";
  }

  exit();

}

// SAVE CHANGES
if($task == "dosave") {

  $setting_emailer_smtp_host = $_POST['setting_emailer_smtp_host'];
  $setting_emailer_smtp_port = intval($_POST['setting_emailer_smtp_port']);
  $setting_emailer_smtp_user = $_POST['setting_emailer_smtp_user'];
  $setting_emailer_smtp_pass = $_POST['setting_emailer_smtp_pass'];
  $setting_emailer_use_smtp = $_POST['setting_emailer_use_smtp'];

  $setting_emailer_enabled = semods::post('setting_emailer_enabled', 0);
  $setting_emailer_period = intval($_POST['setting_emailer_period']);

  $setting_emailer_notify_on_error = intval($_POST['setting_emailer_notify_on_error']);
  $setting_emailer_notify_on_success = intval($_POST['setting_emailer_notify_on_success']);

  $setting_emailer_max_email_retries = intval($_POST['setting_emailer_max_email_retries']);
  $setting_emailer_max_emails_per_batch = intval($_POST['setting_emailer_max_emails_per_batch']);
  $setting_emailer_email_ratelimit_delay = semods::post('setting_emailer_email_ratelimit_delay', 0);

  $emailer_prev_state = intval($_POST['emailer_prev_state']);

  $database->database_query("UPDATE se_semods_settings SET 
            setting_emailer_enabled = $setting_emailer_enabled,
            setting_emailer_period = $setting_emailer_period,
            setting_emailer_notify_on_error = $setting_emailer_notify_on_error,
            setting_emailer_notify_on_success = $setting_emailer_notify_on_success,
            setting_emailer_use_smtp = '$setting_emailer_use_smtp',
            setting_emailer_smtp_host = '$setting_emailer_smtp_host',
            setting_emailer_smtp_port = $setting_emailer_smtp_port,
            setting_emailer_smtp_user = '$setting_emailer_smtp_user',
            setting_emailer_smtp_pass = '$setting_emailer_smtp_pass',
            setting_emailer_max_emails_per_batch = $setting_emailer_max_emails_per_batch,
            setting_emailer_max_email_retries = $setting_emailer_max_email_retries,
            setting_emailer_email_ratelimit_delay = $setting_emailer_email_ratelimit_delay
			");

  $result = 1;

  // run crontab functions, if state transitions ...
  // V enabled -> enabled,
  // V enabled -> disabled
  // V disabled -> enabled
  // X disabled -> disabled
  if($setting_emailer_enabled && !$emailer_prev_state) {
  
    scheduler_addjob( "Process Background Email Queue", "emailer_job", 0, "emailer_scheduler_job", $setting_emailer_period*60);
    
  } elseif(!$setting_emailer_enabled && $emailer_prev_state) {
      
    scheduler_removejob( "emailer_job" );
      
  }
  
}



$queued_emails = emailer_count_queued_emails();
$est_time_to_finish = semods::get_setting('emailer_max_emails_per_batch') ? ($queued_emails / semods::get_setting('emailer_max_emails_per_batch') * semods::get_setting('emailer_period') ) : 0;

// ASSIGN VARIABLES AND SHOW GENERAL SETTINGS PAGE
$smarty->assign('result', $result);
$smarty->assign('error', $error);
$smarty->assign('queued_emails', $queued_emails);

$smarty->assign('est_time_to_finish_day', intval($est_time_to_finish/1440));
$smarty->assign('est_time_to_finish_hour', intval($est_time_to_finish/60));
$smarty->assign('est_time_to_finish_min', intval($est_time_to_finish%60));


$smarty->assign('setting_emailer_enabled', semods::get_setting('emailer_enabled'));
$smarty->assign('setting_emailer_max_email_retries', semods::get_setting('emailer_max_email_retries'));
$smarty->assign('setting_emailer_max_emails_per_batch', semods::get_setting('emailer_max_emails_per_batch'));
$smarty->assign('setting_emailer_email_ratelimit_delay', semods::get_setting('emailer_email_ratelimit_delay'));
$smarty->assign('setting_emailer_period', semods::get_setting('emailer_period'));
$smarty->assign('setting_emailer_notify_on_error', semods::get_setting('emailer_notify_on_error'));
$smarty->assign('setting_emailer_notify_on_success', semods::get_setting('emailer_notify_on_success'));
$smarty->assign('setting_emailer_use_smtp', semods::get_setting('emailer_use_smtp'));
$smarty->assign('setting_emailer_smtp_host', semods::get_setting('emailer_smtp_host'));
$smarty->assign('setting_emailer_smtp_port', semods::get_setting('emailer_smtp_port'));
$smarty->assign('setting_emailer_smtp_user', semods::get_setting('emailer_smtp_user'));
$smarty->assign('setting_emailer_smtp_pass', semods::get_setting('emailer_smtp_pass'));


include "admin_footer.php";
?>