<?

$plugin_name = "Member Winks Plugin";
$plugin_version = "1.00";
$plugin_type = "winks";
$plugin_desc = "This plugin allows members to quickly and easily say Hi. This is both fun and addicting and will keep your members active on your site.";
$plugin_icon = "winks16.gif";
$plugin_menu_title = "14000037";
$plugin_pages_main = "14000038<!>winks16.gif<!>admin_winks.php<~!~>";
$plugin_pages_level = "14000027<!>admin_levels_winksettings.php<~!~>";
$plugin_url_htaccess = "";

if($install == "winks") {

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

//######### CREATE sp_winks
if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'sp_winks'")) == 0) {
  $database->database_query("CREATE TABLE `sp_winks` (
  `wink_id` int(9) NOT NULL auto_increment,
  `wink_date` int(14) NOT NULL default '0',
  `wink_owner_id` int(9) NOT NULL default '0',
  `wink_sender_id` int(9) NOT NULL default '0',
  PRIMARY KEY  (`wink_id`),
  KEY `INDEX` (`wink_owner_id`,`wink_sender_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48;");
}

//######### INSERT se_actiontypes
  if($database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='sendwink'")) == 0) {
    $database->database_query("INSERT INTO se_actiontypes(`actiontype_name` ,`actiontype_icon` ,`actiontype_setting` ,`actiontype_enabled` ,`actiontype_desc` ,`actiontype_text` ,`actiontype_vars`)VALUES ('sendwink', 'action_sendwink.gif', '1', '1', '14000022', '14000023', '[wink_owner],[displayname],[wink_sender],[displayname2]')");
    }
	
//######### INSERT se_notifytypes
  if($database->database_num_rows($database->database_query("SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='wink'")) == 0) {
    $database->database_query("INSERT INTO `se_notifytypes` (`notifytype_icon`, `notifytype_name`, `notifytype_title`, `notifytype_url`, `notifytype_desc`, `notifytype_group`) VALUES
('action_sendwink.gif', 'wink', 14000103, 'user_winks_incoming.php?task=confirm', 14000104, 1)");
    $actiontypes[] = $database->database_insert_id();
	  }
	if(count($actiontypes) != 0) {
    $database->database_query("UPDATE se_usersettings SET usersetting_actions_display = CONCAT(usersetting_actions_display, ',', '".implode(",", $actiontypes)."')");
  }
  
  //######### ADD COLUMNS/VALUES TO LEVELS TABLE IF WINKS HAVE NEVER BEEN INSTALLED
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_levels LIKE 'level_winks_allow'")) == 0) {
    $database->database_query("ALTER TABLE se_levels 
					ADD COLUMN `level_winks_allow` int(1) NOT NULL default '0',
					ADD COLUMN `level_winks_privacy` varchar(10) NOT NULL default ''");
    $database->database_query("UPDATE se_levels SET level_winks_allow='1',level_winks_privacy='012'");
}

  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_permission_winks'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
					ADD COLUMN `setting_permission_winks` int(1) NOT NULL default '0',
					ADD COLUMN `setting_email_winkrequest_subject` varchar(200) NOT NULL default '',
					ADD COLUMN `setting_email_winkrequest_message` text NULL");
    $database->database_query("UPDATE se_settings SET setting_permission_winks='1', setting_email_winkrequest_subject='New Wink', setting_email_winkrequest_message='Hello [wink_receiver], \n\n[wink_sender] has sent you a Wink. Please click the following link to login and view your Wink:\n\n[link]\n\n\Best Regards,\nSocial Network Administration'");
  }

  //######### ADD COLUMNS/VALUES TO USER SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_usersettings LIKE 'usersetting_notify_winks'")) == 0) {
    $database->database_query("ALTER TABLE se_usersettings 
					ADD COLUMN `usersetting_notify_winks` int(1) NOT NULL default '0'");
    $database->database_query("UPDATE se_usersettings SET usersetting_notify_winks='0'");
  }
  
  //######### ADD WINKS PRIVACY TO USER TABLE
if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_users LIKE 'user_privacy_winks'")) == 0) {
  $database->database_query("ALTER TABLE `se_users` ADD `user_privacy_winks` VARCHAR( 1 ) NOT NULL DEFAULT '0'");
  }
  
  //######### ADD TOTAL WINKS TO USER TABLE
if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_users LIKE 'wink_total'")) == 0) {
  $database->database_query("ALTER TABLE `se_users` ADD `wink_total` BIGINT NOT NULL DEFAULT '0'");
  }

  //######### INSERT LANGUAGE VARS
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id='14000022'")) == 0) {
  $database->database_query("INSERT INTO `se_languagevars`(`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
	VALUES
	('14000022','1','Sending a wink','user_home,user_account_privacy'),
	('14000023','1','<a href=''profile.php?user=%1\$s''>%2\$s</a>  sent <a href=''profile.php?user=%3\$s''>%4\$s</a> a Wink','user_home, profile, network, home, admin_viewusers_edit, admin_activity'),
	('14000024','1','Winks','header_winks'),
	('14000025','1','Editing User Level:','admin_levels_winksettings'),
	('14000026','1','You are currently editing this user levels settings. Remember, these settings only apply to the users that belong to this user level.','admin_levels_winksettings'),
	('14000027','1','Wink Settings','admin_levels_winksettings'),
	('14000028','1','If you have allowed users to send winks, you can adjust the settings from this page.','admin_levels_winksettings'),
	('14000029','1','Your changes have been saved.','admin_levels_winksettings'),
	(14000030,1,'Allow Winks?','admin_levels_winksettings'),
	(14000031,1,'Do you want to let users send winks? If set to no, all other settings on this page will not apply.','admin_levels_winksettings'),
	(14000032,1,'Yes, allow winks.','admin_levels_winksettings'),
	(14000033,1,'No, do not allow winks.','admin_levels_winksettings'),
	(14000034,1,'Winks Privacy Options','admin_levels_winksettings'),
	(14000035,1,'Your users can choose from any of the options checked below when they decide who send them winks. If you do not check any options, everyone will be allowed to send winks.','admin_levels_winksettings'),
	(14000036,1,'Save Changes','admin_levels_winksettings'),
	(14000037,1,'Winks Plugin Settings',''),
	(14000038,1,'Global Wink Settings',''),
	(14000039,1,'Wink Settings',''),
	(14000040,1,'General Wink Settings','admin_winks'),
	(14000041,1,'This page contains general winks settings that affect your entire social network.','admin_winks'),
	(14000042,1,'Your changes have been saved.','admin_winks'),
	(14000043,1,'Enable/Disable Winks','admin_winks'),
	(14000044,1,'Select whether or not you want to let your users send and receive Winks. Regardless if Winks is enabled for User Levels, this setting will take control.','admin_winks'),
	(14000045,1,'Enabled Winks','admin_winks'),
	(14000046,1,'Disable Winks','admin_winks'),
	(14000047,1,'New Wink Email','admin_winks'),
	(14000048,1,'This is the email that gets sent to a user when they receive a new Wink.','admin_winks'),
	(14000049,1,'Subject','admin_winks'),
	(14000050,1,'Message','admin_winks'),
	(14000051,1,'[wink_receiver] - replaced with the username who received the wink.<br>[wink_sender] - replaced with the name of the user who sent the wink.<br>[link] - replaced with the link to login.','admin_winks'),
	(14000052,1,'Save Changes','admin_winks'),
	(14000053,1,'Pending Wink Sent','profile,profile_wink_link'),
	(14000054,1,'Send a Wink','profile,profile_wink_link'),
	(14000055,1,'Send a Wink to','profile, profile_winks_footer'),
	(14000056,1,'You are about send a Wink to','profile, profile_winks_footer'),
	(14000057,1,'They will receive a notification the next time they log in.','profile, profile_winks_footer'),
	(14000058,1,'Wink','profile, profile_winks_footer'),
	(14000059,1,'Cancel','profile, profile_winks_footer'),
	(14000060,1,'My Winks','user_winks_incoming, user_winks_outgoing_user_winks_settings, user_winks_topusers'),
	(14000061,1,'Outgoing Winks','user_winks_incoming, user_winks_outgoing_user_winks_settings, user_winks_topusers'),
	(14000062,1,'Wink Settings','user_winks_incoming, user_winks_outgoing_user_winks_settings, user_winks_topusers'),
	(14000063,1,'Most Winks - Top 100','user_winks_incoming, user_winks_outgoing_user_winks_settings, user_winks_topusers'),
	(14000064,1,'My Winks','user_winks_incoming'),
	(14000065,1,'When someone sends you a Wink, it will appear here. You can Wink back or Remove their Wink.','user_winks_incoming'),
	(14000066,1,'Last Page','user_winks_incoming, user_winks_outgoing'),
	(14000067,1,'viewing wink','user_winks_incoming, user_winks_outgoing'),
	(14000068,1,'of','user_winks_incoming, user_winks_outgoing'),
	(14000069,1,'viewing winks','user_winks_incoming, user_winks_outgoing'),
	(14000070,1,'Next Page','user_winks_incoming, user_winks_outgoing'),
	(14000071,1,'Last Update:','user_winks_incoming, user_winks_outgoing'),
	(14000072,1,'Last Login:','user_winks_incoming, user_winks_outgoing'),
	(14000073,1,'Wink Back','user_winks_incoming'),
	(14000074,1,'Remove Wink','user_winks_incoming'),
	(14000075,1,'Send Message','user_winks_incoming'),
	(14000076,1,'Outgoing Winks','user_winks_outgoing'),
	(14000077,1,'When you send a Wink to someone, you will see it here until a Wink is sent back or removed.','user_winks_outgoing'),
	(14000078,1,'You do not have any outgoing Winks at this time.','user_winks_outgoing'),
	(14000079,1,'Cancel Wink','user_winks_outgoing'),
	(14000080,1,'Edit your Wink privacy settings.','user_winks_settings'),
	(14000081,1,'Your changes have been saved.','user_winks_settings'),
	(14000082,1,'Wink Privacy','user_winks_settings'),
	(14000083,1,'Who can send you Winks?','user_winks_settings'),
	(14000084,1,'Wink Notification','user_winks_settings'),
	(14000085,1,'Do you want to be notified by email when someone sends you a Wink?','user_winks_settings'),
	(14000086,1,'Yes, notify me when someone sends me a Wink.','user_winks_settings'),
	(14000087,1,'Save Changes','user_winks_settings'),
	(14000088,1,'Check here to see who has been Winked at the Most, and to see if you made the Top 100 List.','user_winks_topusers'),
	(14000089,1,'No Winks Have been Sent Yet!','user_winks_topusers'),
	(14000090,1,'Wink','user_winks_topusers'),
	(14000091,1,'Error','user_winks_wink'),
	(14000092,1,'You cannot send','user_winks_wink'),
	(14000093,1,'a Wink!','user_winks_wink'),
	(14000094,1,'You Have just sent','user_winks_wink'),
	(14000095,1,'Return to Profile','user_winks_wink'),
	(14000096,1,'You have already sent his user a Wink. You must wait until they receive this before you can Wink again.','user_winks_wink'),
	(14000097,1,'This user will receive your Wink the next time they login.','user_winks_wink'),
	(14000098,1,'You have','user_winks_notify, user_home'),
	(14000099,1,'Wink','user_winks_notify, user_home'),
	(14000100,1,'Winks Sent by:','user_winks_notify, user_home'),
	(14000101,1,'Wink Back','user_winks_notify, user_home'),
	(14000102,1,'Remove Wink','user_winks_notify, user_home'),
	(14000103,1,'When I receive a wink',''),
	(14000104,1,'%1\$d Wink(s)','user_report, user_home, user_friends_requests, user_friends_manage, user_account_privacy, user_account_pass, user_account_delete, user_account, search, profile, network'),
	(14000105,1,'All Registered Users','user_account_privacy, admin_levels_winksettings, user_winks_settings'),
	(14000106,1,'Only My Friends','user_account_privacy, admin_levels_winksettings, user_winks_settings'),
	(14000107,1,'Nobody','user_account_privacy, admin_levels_winksettings, user_winks_settings'),
	(14000108,1,'Wink Sent!','user_winks_wink'),
	(14000109,1,'Wink Sent:','user_winks_incoming, user_winks_outgoing')

	;");
  }
}  

?>