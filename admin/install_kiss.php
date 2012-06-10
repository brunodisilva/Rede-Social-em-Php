<?

$plugin_name = "Member Kiss Plugin";
$plugin_version = "1.00";
$plugin_type = "kiss";
$plugin_desc = "This plugin allows members to quickly and easily say Hi. This is both fun and addicting and will keep your members active on your site.";
$plugin_icon = "kiss16.gif";
$plugin_menu_title = "90000037";
$plugin_pages_main = "90000038<!>kiss16.gif<!>admin_kiss.php<~!~>";
$plugin_pages_level = "90000027<!>admin_levels_kisssettings.php<~!~>";
$plugin_url_htaccess = "";

if($install == "kiss") {

  //######### INSERT ROW INTO se_plugins
  if($database->database_num_rows($database->database_query("SELECT plugin_id FROM se_plugins WHERE plugin_type='$plugin_type'")) == 0) {
    $database->database_query("INSERT INTO se_plugins (plugin_name,
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
					'$plugin_desc',
					'$plugin_icon',
					'$plugin_menu_title',
					'$plugin_pages_main',
					'$plugin_pages_level',
					'$plugin_url_htaccess')");


  //######### UPDATE PLUGIN VERSION IN se_plugins
  } else {
    $database->database_query("UPDATE se_plugins SET plugin_name='$plugin_name',
					plugin_version='$plugin_version',
					plugin_desc='$plugin_desc',
					plugin_icon='$plugin_icon',
					plugin_menu_title='$plugin_menu_title',
					plugin_pages_main='$plugin_pages_main',
					plugin_pages_level='$plugin_pages_level',
					plugin_url_htaccess='$plugin_url_htaccess' WHERE plugin_type='$plugin_type'");

  }

//######### CREATE sp_kiss
if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'sp_kiss'")) == 0) {
  $database->database_query("CREATE TABLE `sp_kiss` (
  `kiss_id` int(9) NOT NULL auto_increment,
  `kiss_date` int(14) NOT NULL default '0',
  `kiss_owner_id` int(9) NOT NULL default '0',
  `kiss_sender_id` int(9) NOT NULL default '0',
  PRIMARY KEY  (`kiss_id`),
  KEY `INDEX` (`kiss_owner_id`,`kiss_sender_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48;");
}

//######### INSERT se_actiontypes
  if($database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='sendkiss'")) == 0) {
    $database->database_query("INSERT INTO se_actiontypes(`actiontype_name` ,`actiontype_icon` ,`actiontype_setting` ,`actiontype_enabled` ,`actiontype_desc` ,`actiontype_text` ,`actiontype_vars`)VALUES ('sendkiss', 'action_sendkiss.gif', '1', '1', '90000022', '90000023', '[kiss_owner],[displayname],[kiss_sender],[displayname2]')");
    }
	
//######### INSERT se_notifytypes
  if($database->database_num_rows($database->database_query("SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='kiss'")) == 0) {
    $database->database_query("INSERT INTO `se_notifytypes` (`notifytype_icon`, `notifytype_name`, `notifytype_title`, `notifytype_url`, `notifytype_desc`, `notifytype_group`) VALUES
('action_sendkiss.gif', 'kiss', 90000103, 'user_kiss_incoming.php?task=confirm', 90000104, 1)");
    $actiontypes[] = $database->database_insert_id();
	  }
	if(count($actiontypes) != 0) {
    $database->database_query("UPDATE se_usersettings SET usersetting_actions_display = CONCAT(usersetting_actions_display, ',', '".implode(",", $actiontypes)."')");
  }
  
  //######### ADD COLUMNS/VALUES TO LEVELS TABLE IF kiss HAVE NEVER BEEN INSTALLED
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_levels LIKE 'level_kiss_allow'")) == 0) {
    $database->database_query("ALTER TABLE se_levels 
					ADD COLUMN `level_kiss_allow` int(1) NOT NULL default '0',
					ADD COLUMN `level_kiss_privacy` varchar(10) NOT NULL default ''");
    $database->database_query("UPDATE se_levels SET level_kiss_allow='1',level_kiss_privacy='012'");
}

  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_permission_kiss'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
					ADD COLUMN `setting_permission_kiss` int(1) NOT NULL default '0',
					ADD COLUMN `setting_email_kissrequest_subject` varchar(200) NOT NULL default '',
					ADD COLUMN `setting_email_kissrequest_message` text NULL");
    $database->database_query("UPDATE se_settings SET setting_permission_kiss='1', setting_email_kissrequest_subject='New kiss', setting_email_kissrequest_message='Hello [kiss_receiver], \n\n[kiss_sender] has sent you a kiss. Please click the following link to login and view your kiss:\n\n[link]\n\n\Best Regards,\nSocial Network Administration'");
  }

  //######### ADD COLUMNS/VALUES TO USER SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_usersettings LIKE 'usersetting_notify_kiss'")) == 0) {
    $database->database_query("ALTER TABLE se_usersettings 
					ADD COLUMN `usersetting_notify_kiss` int(1) NOT NULL default '0'");
    $database->database_query("UPDATE se_usersettings SET usersetting_notify_kiss='0'");
  }
  
  //######### ADD kiss PRIVACY TO USER TABLE
if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_users LIKE 'user_privacy_kiss'")) == 0) {
  $database->database_query("ALTER TABLE `se_users` ADD `user_privacy_kiss` VARCHAR( 1 ) NOT NULL DEFAULT '0'");
  }
  
  //######### ADD TOTAL kiss TO USER TABLE
if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_users LIKE 'kiss_total'")) == 0) {
  $database->database_query("ALTER TABLE `se_users` ADD `kiss_total` BIGINT NOT NULL DEFAULT '0'");
  }

  //######### INSERT LANGUAGE VARS
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id='90000022'")) == 0) {
  $database->database_query("INSERT INTO `se_languagevars`(`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
	VALUES
	('90000022','1','Sending a kiss','user_home,user_account_privacy'),
	('90000023','1','<a href=''profile.php?user=%1\$s''>%2\$s</a>  sent <a href=''profile.php?user=%3\$s''>%4\$s</a> a kiss','user_home, profile, network, home, admin_viewusers_edit, admin_activity'),
	('90000024','1','kisses','header_kiss'),
	('90000025','1','Editing User Level:','admin_levels_kisssettings'),
	('90000026','1','You are currently editing this user levels settings. Remember, these settings only apply to the users that belong to this user level.','admin_levels_kisssettings'),
	('90000027','1','kiss Settings','admin_levels_kisssettings'),
	('90000028','1','If you have allowed users to send kisses, you can adjust the settings from this page.','admin_levels_kisssettings'),
	('90000029','1','Your changes have been saved.','admin_levels_kisssettings'),
	(90000030,1,'Allow kisses?','admin_levels_kisssettings'),
	(90000031,1,'Do you want to let users send kisses? If set to no, all other settings on this page will not apply.','admin_levels_kisssettings'),
	(90000032,1,'Yes, allow kisses.','admin_levels_kisssesttings'),
	(90000033,1,'No, do not allow kisses.','admin_levels_kisssettings'),
	(90000034,1,'kisses Privacy Options','admin_levels_kissssettings'),
	(90000035,1,'Your users can choose from any of the options checked below when they decide who send them kiss. If you do not check any options, everyone will be allowed to send kiss.','admin_levels_kisssettings'),
	(90000036,1,'Save Changes','admin_levels_kisssettings'),
	(90000037,1,'kiss Plugin Settings',''),
	(90000038,1,'Global kiss Settings',''),
	(90000039,1,'kiss Settings',''),
	(90000040,1,'General kiss Settings','admin_kiss'),
	(90000041,1,'This page contains general kiss settings that affect your entire social network.','admin_kiss'),
	(90000042,1,'Your changes have been saved.','admin_kiss'),
	(90000043,1,'Enable/Disable kiss','admin_kiss'),
	(90000044,1,'Select whether or not you want to let your users send and receive kiss. Regardless if kiss is enabled for User Levels, this setting will take control.','admin_kiss'),
	(90000045,1,'Enabled kiss','admin_kiss'),
	(90000046,1,'Disable kiss','admin_kiss'),
	(90000047,1,'New kiss Email','admin_kiss'),
	(90000048,1,'This is the email that gets sent to a user when they receive a new kiss.','admin_kiss'),
	(90000049,1,'Subject','admin_kiss'),
	(90000050,1,'Message','admin_kiss'),
	(90000051,1,'[kiss_receiver] - replaced with the username who received the kiss.<br>[kiss_sender] - replaced with the name of the user who sent the kiss.<br>[link] - replaced with the link to login.','admin_kiss'),
	(90000052,1,'Save Changes','admin_kiss'),
	(90000053,1,'Pending kiss Sent','profile,profile_kiss_link'),
	(90000054,1,'Send a kiss','profile,profile_kiss_link'),
	(90000055,1,'Send a kiss to','profile, profile_kiss_footer'),
	(90000056,1,'You are about send a kiss to','profile, profile_kiss_footer'),
	(90000057,1,'They will receive a notification the next time they log in.','profile, profile_kiss_footer'),
	(90000058,1,'kiss','profile, profile_kiss_footer'),
	(90000059,1,'Cancel','profile, profile_kiss_footer'),
	(90000060,1,'My kisses','user_kiss_incoming, user_kiss_outgoing_user_kiss_settings, user_kiss_topusers'),
	(90000061,1,'Outgoing kisses','user_kiss_incoming, user_kiss_outgoing_user_kiss_settings, user_kiss_topusers'),
	(90000062,1,'kiss Settings','user_kiss_incoming, user_kiss_outgoing_user_kiss_settings, user_kiss_topusers'),
	(90000063,1,'Most kissed - Top 100','user_kiss_incoming, user_kiss_outgoing_user_kiss_settings, user_kiss_topusers'),
	(90000064,1,'My kisses','user_kiss_incoming'),
	(90000065,1,'When someone sends you a kiss, it will appear here. You can kiss back or Remove their kiss.','user_kiss_incoming'),
	(90000066,1,'Last Page','user_kiss_incoming, user_kiss_outgoing'),
	(90000067,1,'viewing kisses','user_kiss_incoming, user_kiss_outgoing'),
	(90000068,1,'of','user_kiss_incoming, user_kiss_outgoing'),
	(90000069,1,'viewing kiss','user_kiss_incoming, user_kiss_outgoing'),
	(90000070,1,'Next Page','user_kiss_incoming, user_kiss_outgoing'),
	(90000071,1,'Last Update:','user_kiss_incoming, user_kiss_outgoing'),
	(90000072,1,'Last Login:','user_kiss_incoming, user_kiss_outgoing'),
	(90000073,1,'kiss Back','user_kiss_incoming'),
	(90000074,1,'Remove kiss','user_kiss_incoming'),
	(90000075,1,'Send Message','user_kiss_incoming'),
	(90000076,1,'Outgoing kisses','user_kiss_outgoing'),
	(90000077,1,'When you send a kiss to someone, you will see it here until a kiss is sent back or removed.','user_kiss_outgoing'),
	(90000078,1,'You do not have any outgoing kiss at this time.','user_kiss_outgoing'),
	(90000079,1,'Cancel kiss','user_kiss_outgoing'),
	(90000080,1,'Edit your kiss privacy settings.','user_kiss_settings'),
	(90000081,1,'Your changes have been saved.','user_kiss_settings'),
	(90000082,1,'kiss Privacy','user_kiss_settings'),
	(90000083,1,'Who can send you kiss?','user_kiss_settings'),
	(90000084,1,'kiss Notification','user_kiss_settings'),
	(90000085,1,'Do you want to be notified by email when someone sends you a kiss?','user_kiss_settings'),
	(90000086,1,'Yes, notify me when someone sends me a kiss.','user_kiss_settings'),
	(90000087,1,'Save Changes','user_kiss_settings'),
	(90000088,1,'Check here to see who has been kissed at the Most, and to see if you made the Top 100 List.','user_kiss_topusers'),
	(90000089,1,'No kiss Have been Sent Yet!','user_kiss_topusers'),
	(90000090,1,'kiss','user_kiss_topusers'),
	(90000091,1,'Error','user_kiss_kiss'),
	(90000092,1,'You cannot send','user_kiss_kiss'),
	(90000093,1,'a kiss!','user_kiss_kiss'),
	(90000094,1,'You Have just sent','user_kiss_kiss'),
	(90000095,1,'Return to Profile','user_kiss_kiss'),
	(90000096,1,'You have already sent this user a kiss. You must wait until they receive this before you can kiss again.','user_kiss_kiss'),
	(90000097,1,'This user will receive your kiss the next time they login.','user_kiss_kiss'),
	(90000098,1,'You have','user_kiss_notify, user_home'),
	(90000099,1,'kiss','user_kiss_notify, user_home'),
	(90000100,1,'kiss Sent by:','user_kiss_notify, user_home'),
	(90000101,1,'kiss Back','user_kiss_notify, user_home'),
	(90000102,1,'Remove kiss','user_kiss_notify, user_home'),
	(90000103,1,'When I receive a kiss',''),
	(90000104,1,'%1\$d kiss(s)','user_report, user_home, user_friends_requests, user_friends_manage, user_account_privacy, user_account_pass, user_account_delete, user_account, search, profile, network'),
	(90000105,1,'All Registered Users','user_account_privacy, admin_levels_kisssettings, user_kiss_settings'),
	(90000106,1,'Only My Friends','user_account_privacy, admin_levels_kisssettings, user_kiss_settings'),
	(90000107,1,'Nobody','user_account_privacy, admin_levels_kisssettings, user_kiss_settings'),
	(90000108,1,'kiss Sent!','user_kiss_kiss'),
	(90000109,1,'kiss Sent:','user_kiss_incoming, user_kiss_outgoing')

	;");
  }
}  

?>