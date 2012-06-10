<?
$page = "admin_beers";
include "admin_header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }

// SET RESULT VARIABLE
$result = 0;

// SAVE CHANGES
if($task == "dosave") {
  $setting_beers_enabled = $_POST['setting_beers_enabled'];
  $setting_email_beerrequest_subject = $_POST['setting_email_beerrequest_subject'];
  $setting_email_beerrequest_message = $_POST['setting_email_beerrequest_message'];

  // SAVE CHANGES
  $database->database_query("UPDATE se_settings SET 
			setting_email_beerrequest_subject ='$setting_email_beerrequest_subject',
			setting_email_beerrequest_message='$setting_email_beerrequest_message'");
			
  $setting = $database->database_fetch_assoc($database->database_query("SELECT * FROM se_settings LIMIT 1"));
  $result = 1;
   
}

// ASSIGN VARIABLES AND SHOW GENERAL SETTINGS PAGE
$smarty->assign('result', $result);
$smarty->assign('setting_email_beerrequest_subject', $setting[setting_email_beerrequest_subject]);
$smarty->assign('setting_email_beerrequest_message', $setting[setting_email_beerrequest_message]);
include "admin_footer.php";
?>