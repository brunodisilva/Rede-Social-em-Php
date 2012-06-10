<?

$plugin_name = "Member beers Plugin";
$plugin_version = "1.00";
$plugin_type = "beers";
$plugin_desc = "This plugin allows members to quickly and easily say Hi. This is both fun and addicting and will keep your members active on your site.";
$plugin_icon = "beers16.gif";
$plugin_menu_title = "16000037";
$plugin_pages_main = "16000038<!>beers16.gif<!>admin_beers.php<~!~>";
$plugin_pages_level = "16000027<!>admin_levels_beersettings.php<~!~>";
$plugin_url_htaccess = "";

if($install == "beers") {

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

//######### CREATE sp_beers
if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'sp_beers'")) == 0) {
  $database->database_query("CREATE TABLE `sp_beers` (
  `beer_id` int(9) NOT NULL auto_increment,
  `beer_date` int(16) NOT NULL default '0',
  `beer_owner_id` int(9) NOT NULL default '0',
  `beer_sender_id` int(9) NOT NULL default '0',
  PRIMARY KEY  (`beer_id`),
  KEY `INDEX` (`beer_owner_id`,`beer_sender_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48;");
}

//######### INSERT se_actiontypes
  if($database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='sendbeer'")) == 0) {
    $database->database_query("INSERT INTO se_actiontypes(`actiontype_name` ,`actiontype_icon` ,`actiontype_setting` ,`actiontype_enabled` ,`actiontype_desc` ,`actiontype_text` ,`actiontype_vars`)VALUES ('sendbeer', 'action_sendbeer.gif', '1', '1', '16000022', '16000023', '[beer_owner],[displayname],[beer_sender],[displayname2]')");
    }
	
//######### INSERT se_notifytypes
  if($database->database_num_rows($database->database_query("SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='beer'")) == 0) {
    $database->database_query("INSERT INTO `se_notifytypes` (`notifytype_icon`, `notifytype_name`, `notifytype_title`, `notifytype_url`, `notifytype_desc`, `notifytype_group`) VALUES
('action_sendbeer.gif', 'beer', 16000103, 'user_beers_incoming.php?task=confirm', 16000104, 1)");
    $actiontypes[] = $database->database_insert_id();
	  }
	if(count($actiontypes) != 0) {
    $database->database_query("UPDATE se_usersettings SET usersetting_actions_display = CONCAT(usersetting_actions_display, ',', '".implode(",", $actiontypes)."')");
  }
  
  //######### ADD COLUMNS/VALUES TO LEVELS TABLE IF beerS HAVE NEVER BEEN INSTALLED
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_levels LIKE 'level_beers_allow'")) == 0) {
    $database->database_query("ALTER TABLE se_levels 
					ADD COLUMN `level_beers_allow` int(1) NOT NULL default '0',
					ADD COLUMN `level_beers_privacy` varchar(10) NOT NULL default ''");
    $database->database_query("UPDATE se_levels SET level_beers_allow='1',level_beers_privacy='012'");
}

  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_permission_beers'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
					ADD COLUMN `setting_permission_beers` int(1) NOT NULL default '0',
					ADD COLUMN `setting_email_beerrequest_subject` varchar(200) NOT NULL default '',
					ADD COLUMN `setting_email_beerrequest_message` text NULL");
    $database->database_query("UPDATE se_settings SET setting_permission_beers='1', setting_email_beerrequest_subject='New beer', setting_email_beerrequest_message='Hello [beer_receiver], \n\n[beer_sender] has sent you a beer. Please click the following link to login and view your beer:\n\n[link]\n\n\Best Regards,\nSocial Network Administration'");
  }

  //######### ADD COLUMNS/VALUES TO USER SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_usersettings LIKE 'usersetting_notify_beers'")) == 0) {
    $database->database_query("ALTER TABLE se_usersettings 
					ADD COLUMN `usersetting_notify_beers` int(1) NOT NULL default '0'");
    $database->database_query("UPDATE se_usersettings SET usersetting_notify_beers='0'");
  }
  
  //######### ADD beerS PRIVACY TO USER TABLE
if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_users LIKE 'user_privacy_beers'")) == 0) {
  $database->database_query("ALTER TABLE `se_users` ADD `user_privacy_beers` VARCHAR( 1 ) NOT NULL DEFAULT '0'");
  }
  
  //######### ADD TOTAL beerS TO USER TABLE
if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_users LIKE 'beer_total'")) == 0) {
  $database->database_query("ALTER TABLE `se_users` ADD `beer_total` BIGINT NOT NULL DEFAULT '0'");
  }

  //######### INSERT LANGUAGE VARS
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id='16000022'")) == 0) {
  $database->database_query("INSERT INTO `se_languagevars`(`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
	VALUES
	('16000022','1','Sending a beer','user_home,user_account_privacy'),
	('16000023','1','<a href=''profile.php?user=%1\$s''>%2\$s</a>  sent <a href=''profile.php?user=%3\$s''>%4\$s</a> a beer','user_home, profile, network, home, admin_viewusers_edit, admin_activity'),
	('16000024','1','beers','header_beers'),
	('16000025','1','Editing User Level:','admin_levels_beersettings'),
	('16000026','1','You are currently editing this user levels settings. Remember, these settings only apply to the users that belong to this user level.','admin_levels_beersettings'),
	('16000027','1','beer Settings','admin_levels_beersettings'),
	('16000028','1','If you have allowed users to send beers, you can adjust the settings from this page.','admin_levels_beersettings'),
	('16000029','1','Your changes have been saved.','admin_levels_beersettings'),
	(16000030,1,'Allow beers?','admin_levels_beersettings'),
	(16000031,1,'Do you want to let users send beers? If set to no, all other settings on this page will not apply.','admin_levels_beersettings'),
	(16000032,1,'Yes, allow beers.','admin_levels_beersettings'),
	(16000033,1,'No, do not allow beers.','admin_levels_beersettings'),
	(16000034,1,'beers Privacy Options','admin_levels_beersettings'),
	(16000035,1,'Your users can choose from any of the options checked below when they decide who send them beers. If you do not check any options, everyone will be allowed to send beers.','admin_levels_beersettings'),
	(16000036,1,'Save Changes','admin_levels_beersettings'),
	(16000037,1,'beers Plugin Settings',''),
	(16000038,1,'Global beer Settings',''),
	(16000039,1,'beer Settings',''),
	(16000040,1,'General beer Settings','admin_beers'),
	(16000041,1,'This page contains general beers settings that affect your entire social network.','admin_beers'),
	(16000042,1,'Your changes have been saved.','admin_beers'),
	(16000043,1,'Enable/Disable beers','admin_beers'),
	(16000044,1,'Select whether or not you want to let your users send and receive beers. Regardless if beers is enabled for User Levels, this setting will take control.','admin_beers'),
	(16000045,1,'Enabled beers','admin_beers'),
	(16000046,1,'Disable beers','admin_beers'),
	(16000047,1,'New beer Email','admin_beers'),
	(16000048,1,'This is the email that gets sent to a user when they receive a new beer.','admin_beers'),
	(16000049,1,'Subject','admin_beers'),
	(16000050,1,'Message','admin_beers'),
	(16000051,1,'[beer_receiver] - replaced with the username who received the beer.<br>[beer_sender] - replaced with the name of the user who sent the beer.<br>[link] - replaced with the link to login.','admin_beers'),
	(16000052,1,'Save Changes','admin_beers'),
	(16000053,1,'Pending beer Sent','profile,profile_beer_link'),
	(16000054,1,'Send a beer','profile,profile_beer_link'),
	(16000055,1,'Send a beer to','profile, profile_beers_footer'),
	(16000056,1,'You are about send a beer to','profile, profile_beers_footer'),
	(16000057,1,'They will receive a notification the next time they log in.','profile, profile_beers_footer'),
	(16000058,1,'beer','profile, profile_beers_footer'),
	(16000059,1,'Cancel','profile, profile_beers_footer'),
	(16000060,1,'My beers','user_beers_incoming, user_beers_outgoing_user_beers_settings, user_beers_topusers'),
	(16000061,1,'Outgoing beers','user_beers_incoming, user_beers_outgoing_user_beers_settings, user_beers_topusers'),
	(16000062,1,'beer Settings','user_beers_incoming, user_beers_outgoing_user_beers_settings, user_beers_topusers'),
	(16000063,1,'Most beers - Top 100','user_beers_incoming, user_beers_outgoing_user_beers_settings, user_beers_topusers'),
	(16000064,1,'My beers','user_beers_incoming'),
	(16000065,1,'When someone sends you a beer, it will appear here. You can beer back or Remove their beer.','user_beers_incoming'),
	(16000066,1,'Last Page','user_beers_incoming, user_beers_outgoing'),
	(16000067,1,'viewing beer','user_beers_incoming, user_beers_outgoing'),
	(16000068,1,'of','user_beers_incoming, user_beers_outgoing'),
	(16000069,1,'viewing beers','user_beers_incoming, user_beers_outgoing'),
	(16000070,1,'Next Page','user_beers_incoming, user_beers_outgoing'),
	(16000071,1,'Last Update:','user_beers_incoming, user_beers_outgoing'),
	(16000072,1,'Last Login:','user_beers_incoming, user_beers_outgoing'),
	(16000073,1,'beer Back','user_beers_incoming'),
	(16000074,1,'Remove beer','user_beers_incoming'),
	(16000075,1,'Send Message','user_beers_incoming'),
	(16000076,1,'Outgoing beers','user_beers_outgoing'),
	(16000077,1,'When you send a beer to someone, you will see it here until a beer is sent back or removed.','user_beers_outgoing'),
	(16000078,1,'You do not have any outgoing beers at this time.','user_beers_outgoing'),
	(16000079,1,'Cancel beer','user_beers_outgoing'),
	(16000080,1,'Edit your beer privacy settings.','user_beers_settings'),
	(16000081,1,'Your changes have been saved.','user_beers_settings'),
	(16000082,1,'beer Privacy','user_beers_settings'),
	(16000083,1,'Who can send you beers?','user_beers_settings'),
	(16000084,1,'beer Notification','user_beers_settings'),
	(16000085,1,'Do you want to be notified by email when someone sends you a beer?','user_beers_settings'),
	(16000086,1,'Yes, notify me when someone sends me a beer.','user_beers_settings'),
	(16000087,1,'Save Changes','user_beers_settings'),
	(16000088,1,'Check here to see who has been beered at the Most, and to see if you made the Top 100 List.','user_beers_topusers'),
	(16000089,1,'No beers Have been Sent Yet!','user_beers_topusers'),
	(16000090,1,'beer','user_beers_topusers'),
	(16000091,1,'Error','user_beers_beer'),
	(16000092,1,'You cannot send','user_beers_beer'),
	(16000093,1,'a beer!','user_beers_beer'),
	(16000094,1,'You Have just sent','user_beers_beer'),
	(16000095,1,'Return to Profile','user_beers_beer'),
	(16000096,1,'You have already sent his user a beer. You must wait until they receive this before you can beer again.','user_beers_beer'),
	(16000097,1,'This user will receive your beer the next time they login.','user_beers_beer'),
	(16000098,1,'You have','user_beers_notify, user_home'),
	(16000099,1,'beer','user_beers_notify, user_home'),
	(16000100,1,'beers Sent by:','user_beers_notify, user_home'),
	(16000101,1,'beer Back','user_beers_notify, user_home'),
	(16000102,1,'Remove beer','user_beers_notify, user_home'),
	(16000103,1,'When I receive a beer',''),
	(16000104,1,'%1\$d beer(s)','user_report, user_home, user_friends_requests, user_friends_manage, user_account_privacy, user_account_pass, user_account_delete, user_account, search, profile, network'),
	(16000105,1,'All Registered Users','user_account_privacy, admin_levels_beersettings, user_beers_settings'),
	(16000106,1,'Only My Friends','user_account_privacy, admin_levels_beersettings, user_beers_settings'),
	(16000107,1,'Nobody','user_account_privacy, admin_levels_beersettings, user_beers_settings'),
	(16000108,1,'beer Sent!','user_beers_beer'),
	(16000109,1,'beer Sent:','user_beers_incoming, user_beers_outgoing')

	;");
  }
}  

?>