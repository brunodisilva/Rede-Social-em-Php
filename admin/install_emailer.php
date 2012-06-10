<?php
$plugin_name = "Background Email System";
$plugin_version = "3.0";
$plugin_type = "emailer";
$plugin_desc = "This plugin allows controlled background emailing using smtp server.";
$plugin_icon = "emailer16.gif";
$plugin_menu_title = "100011000";
$plugin_pages_main = "100011001<!>emailer16.gif<!>admin_emailer_viewemails.php<~!~>100011002<!>emailer16.gif<!>admin_emailer.php";
$plugin_pages_level = "";
$plugin_url_htaccess = "";

if($install == "emailer") {

  //######### INSERT ROW INTO se_plugins
  $database->database_query("INSERT INTO se_plugins (

                  plugin_name,
                  plugin_version,
                  plugin_type,
                  plugin_desc,
                  plugin_icon,
                  plugin_menu_title,
                  plugin_pages_main,
                  plugin_pages_level,
                  plugin_url_htaccess
                  ) VALUES (
                  '$plugin_name',
                  '$plugin_version',
                  '$plugin_type',
                  '".str_replace("'", "\'", $plugin_desc)."',
                  '$plugin_icon',
                  '$plugin_menu_title',
                  '$plugin_pages_main',
                  '$plugin_pages_level',
                  '$plugin_url_htaccess')
  
                  ON DUPLICATE KEY UPDATE

                  plugin_version='$plugin_version',
                  plugin_desc='".str_replace("'", "\'", $plugin_desc)."',
                  plugin_icon='$plugin_icon',
                  plugin_menu_title='$plugin_menu_title',
                  plugin_pages_main='$plugin_pages_main',
                  plugin_pages_level='$plugin_pages_level',
                  plugin_url_htaccess='$plugin_url_htaccess'
                  
  ");


  //######### INSERT LANGUAGE VARS
  $database->database_query("INSERT IGNORE INTO se_languagevars (languagevar_id, languagevar_language_id, languagevar_value, languagevar_default) VALUES (100011000, 1, 'Background Emailing', ''),(100011001, 1, 'View Queued Emails', ''),(100011002, 1, 'Emailer Settings', ''),(100011003, 1, 'Background email - Notification of ERRORS', ''),(100011004, 1, 'Background email - Notification of success', ''),(100011005, 1, 'General Emaling Settings', ''),(100011006, 1, 'This page contains Background emaling settings.', ''),(100011007, 1, 'Your changes have been saved.', ''),(100011008, 1, 'Save Changes', ''),(100011009, 1, 'Background email sending', ''),(100011010, 1, 'Currently queued emails:', ''),(100011011, 1, 'Background email sending allows queueing invitation emails and sending them in a much more controlled way in order to prevent server overload and marking host as spammer. Invitation process is much faster - user doesn\'t wait for emails to finish being sent but is instantly presented with next page. <b> Note: </b> Currently works for unix/linux based servers only.', ''),(100011012, 1, 'Enable background email sending', ''),(100011013, 1, 'Maximum retries for sending mail - sometimes sending can fail - instead of silently dropping them, retry upto this amount.', ''),(100011014, 1, 'maximum retries', ''),(100011015, 1, 'Maximum emails to send each run / batch of processing. The background processing script will send this much emails each time it is run. Enter 0 to send all queued emails at once.', ''),(100011016, 1, 'max emails per batch', ''),(100011017, 1, 'Period to run background email process script. Note: Some hosting companies limit crontab jobs period to minimum of 15 minutes.', ''),(100011018, 1, 'min', ''),(100011019, 1, 'hour', ''),(100011020, 1, 'Estimated time to send these emails:', ''),(100011021, 1, 'days', ''),(100011022, 1, 'hours', ''),(100011023, 1, 'minutes', ''),(100011024, 1, 'Notifications from background processing script, will be sent to administrator email', ''),(100011025, 1, 'Notify on error', ''),(100011026, 1, 'Notify on success', ''),(100011027, 1, 'Invitation Emails Rate limit - allows you to control how many emails invitee will be receiving. Example: 10 different users invite their common friend, which results in 10 invitation emails. Please specify minimum time period between two consecutive invitation emails sent to the same email. Set 0 to disable rate limiting.', ''),(100011028, 1, 'seconds', ''),(100011029, 1, 'Shortcuts:', ''),(100011030, 1, 'hour(s)', ''),(100011031, 1, 'day(s)', ''),(100011032, 1, 'SMTP Server Settings', ''),(100011033, 1, 'STMP host is usually \\\"localhost\\\" or \\\"mail.&lt;yourdomain&gt;\\\" (for example: mail.socialenginemods.net). If unsure, please consult your hoster.', ''),(100011034, 1, 'SMTP host', ''),(100011035, 1, 'SMTP port', ''),(100011036, 1, 'SMTP user', ''),(100011037, 1, 'SMTP password', ''),(100011038, 1, 'The following settings are optional and should be changed only if instructed by your hoster.', ''),(100011039, 1, ' You can use SMTP server for email delivery or local mail/sendmail programs. Using SMTP greatly reduces chances of emails ending up in spam folders. <strong><u>Note</u></strong>: SMTP server is used only when background emailing is enabled.', ''),(100011040, 1, ' Use SMTP Server for sending emails ', ''),(100011041, 1, 'You can test above SMTP parameters by clicking test button below. The system will try to send an email to your admin email address. Please SAVE before trying to test.', ''),(100011042, 1, 'Test', ''),(100011043, 1, 'Delete email?', ''),(100011044, 1, 'Are you sure you want to delete this email?', ''),(100011045, 1, ' View Queued Emails ', ''),(100011046, 1, 'Here you can see a list of currently queued emails', ''),(100011047, 1, 'Email:', ''),(100011048, 1, 'Type:', ''),(100011049, 1, 'Any', ''),(100011050, 1, 'Filter', ''),(100011051, 1, 'There are currently no queued emails', ''),(100011052, 1, 'Emails found', ''),(100011053, 1, 'Pages:', ''),(100011054, 1, 'To Email', ''),(100011055, 1, 'Subject', ''),(100011056, 1, 'Type', ''),(100011057, 1, 'Attempts', ''),(100011058, 1, 'Last attempt', ''),(100011059, 1, 'Options', ''),(100011060, 1, 'Never', ''),(100011061, 1, 'delete', '')");

  

  
  


  //######### CREATE DATABASE STRUCTURE

  if(!function_exists('chain_sql')) {
    function chain_sql( $sql ) {
      global $database;

      $rows = explode( ';', $sql);
      foreach($rows as $row) {
        $row = trim($row);
        if(empty($row))
          continue;
        $database->database_query( $row );
      }

    }
  }

  chain_sql(
<<<EOC

CREATE TABLE IF NOT EXISTS `se_semods_email_queue` (
	  `id` int(11) NOT NULL auto_increment,
	  `from_name` varchar(64) default NULL,
	  `from_email` varchar(128) NOT NULL,
	  `to_email` varchar(128) NOT NULL,
	  `subject` varchar(255) NOT NULL,
	  `message` text NOT NULL,
	  `attempts` int(11) NOT NULL default '0',
	  `success` tinyint(1) NOT NULL default '0',
	  `date_published` datetime default NULL,
	  `last_attempt` datetime default NULL,
	  `date_sent` datetime default NULL,
      `type` tinyint(1) NOT NULL default '0',
	  PRIMARY KEY  (`id`),
	  KEY `to_email` (`to_email`)
	);


CREATE TABLE IF NOT EXISTS `se_semods_email_ratelimit` (
      `ratelimit_id` int(11) NOT NULL auto_increment,
      `ratelimit_user_email` varchar(255) NOT NULL,
      `ratelimit_last_sent` int(11) NOT NULL,
      PRIMARY KEY  (`ratelimit_id`),
      KEY `ratelimit_user_email` (`ratelimit_user_email`)
);

EOC
);




  /*** SHARED ELEMENTS ***/



  //######### CREATE se_semods_settings
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_semods_settings'")) == 0) {

    $database->database_query("CREATE TABLE `se_semods_settings` (
	  `setting_emailer_enabled` tinyint(1) NOT NULL default '0',
	  `setting_emailer_period` tinyint(4) NOT NULL default '15',
	  `setting_emailer_notify_on_error` tinyint(4) NOT NULL default '1',
	  `setting_emailer_notify_on_success` tinyint(4) NOT NULL default '0',
	  `setting_emailer_max_email_retries` int(11) NOT NULL default '3',
	  `setting_emailer_max_emails_per_batch` int(4) NOT NULL default '50',
	  `setting_emailer_email_ratelimit_delay` int(4) NOT NULL default '0',
	  `setting_emailer_smtp_host` varchar(255) NOT NULL default 'localhost',
	  `setting_emailer_smtp_user` varchar(255) NOT NULL default '',
	  `setting_emailer_smtp_pass` varchar(255) NOT NULL default '',
	  `setting_emailer_smtp_port` int(4) NOT NULL default '25',
	  `setting_emailer_use_smtp` tinyint(1) NOT NULL default '1'
    )");

    $database->database_query("INSERT INTO `se_semods_settings` (`setting_emailer_use_smtp`) VALUES (1)");

  } else {

    $database->database_query("ALTER TABLE se_semods_settings
	  ADD COLUMN `setting_emailer_enabled` tinyint(1) NOT NULL default '0',
	  ADD COLUMN `setting_emailer_period` tinyint(4) NOT NULL default '15',
	  ADD COLUMN `setting_emailer_notify_on_error` tinyint(4) NOT NULL default '1',
	  ADD COLUMN `setting_emailer_notify_on_success` tinyint(4) NOT NULL default '0',
	  ADD COLUMN `setting_emailer_max_email_retries` int(11) NOT NULL default '3',
	  ADD COLUMN `setting_emailer_max_emails_per_batch` int(4) NOT NULL default '50',
	  ADD COLUMN `setting_emailer_email_ratelimit_delay` int(4) NOT NULL default '0',
	  ADD COLUMN `setting_emailer_smtp_host` varchar(255) NOT NULL default 'localhost',
	  ADD COLUMN `setting_emailer_smtp_user` varchar(255) NOT NULL default '',
	  ADD COLUMN `setting_emailer_smtp_pass` varchar(255) NOT NULL default '',
	  ADD COLUMN `setting_emailer_smtp_port` int(4) NOT NULL default '25',
	  ADD COLUMN `setting_emailer_use_smtp` tinyint(1) NOT NULL default '1'
	");

  }


}


?>