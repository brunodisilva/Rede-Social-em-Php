<?php

$plugin_name = "Gmap Plugin";
$plugin_version = 3.05;
$plugin_type = "gmap";
$plugin_desc = "This plugin allows you to show member locations on Google Map and visitor can also search for it, visualizing your members geo infos on a big huge map :-) There is also a mini map on member profile. This plugin is extremely fun to play around with. It sure will provide as an awesome add-on interactive tool for your users.";
$plugin_icon = "gmap16.gif";
$plugin_menu_title = "11080001"; 
$plugin_pages_main = "11080002<!>gmap16.gif<!>admin_gmap.php<~!~>";
$plugin_pages_level = "11080003<!>admin_levels_gmapsettings.php<~!~>";
$plugin_url_htaccess = "";

// lang var = 11080000 - 11089999


if($install == "gmap") {

  unset($_SESSION['RC_MODEL_CACHE']);
  
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
					plugin_pages_main='$plugin_pages_main',
					plugin_pages_level='$plugin_pages_level',
					plugin_url_htaccess='$plugin_url_htaccess' WHERE plugin_type='$plugin_type'");

  }
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_permission_gmap'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
					ADD COLUMN `setting_permission_gmap` int(1) NOT NULL default '0',
					ADD COLUMN `setting_gmap_license` varchar(128) NOT NULL default '',
					ADD COLUMN `setting_gmap_api` varchar(128) NOT NULL default '',
					ADD COLUMN `setting_gmap_f_country` int(11) NOT NULL default '0',
					ADD COLUMN `setting_gmap_f_region` int(11) NOT NULL default '0',
					ADD COLUMN `setting_gmap_f_city` int(11) NOT NULL default '0',
					ADD COLUMN `setting_gmap_f_address` int(11) NOT NULL default '0'");
    $database->database_query("UPDATE se_settings SET setting_permission_gmap='1', setting_gmap_license='XXXX-XXXX-XXXX-XXXX'");
  }

  //######### ADD COLUMNS/VALUES TO USER SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_usersettings LIKE 'usersetting_permission_gmap'")) == 0) {
    $database->database_query("ALTER TABLE se_usersettings 
					ADD COLUMN `usersetting_permission_gmap` int(1) NOT NULL default '1',
					ADD COLUMN `usersetting_gmap_f_country` int(11) NOT NULL default '1',
					ADD COLUMN `usersetting_gmap_f_region` int(11) NOT NULL default '1',
					ADD COLUMN `usersetting_gmap_f_city` int(11) NOT NULL default '1',
					ADD COLUMN `usersetting_gmap_f_address` int(11) NOT NULL default '0'");
    $database->database_query("UPDATE se_usersettings SET usersetting_permission_gmap='1', usersetting_gmap_f_country='1', usersetting_gmap_f_region='1', usersetting_gmap_f_city='1', usersetting_gmap_f_address='1'");
  }

  
  //######### CREATE se_groupalbums
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'rc_geocache'")) == 0) {
    $database->database_query("CREATE TABLE rc_geocache (
      address varchar(255) NOT NULL default '',
      lon float default NULL,
      lat float default NULL,
      PRIMARY KEY  (address)
    )");
  }  
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_gmap_profile_embed'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
					ADD COLUMN `setting_gmap_profile_embed` int(1) NOT NULL default '0'
					");
    $database->database_query("UPDATE se_settings SET setting_gmap_profile_embed='1'");
  } 
  
  //######### ADD COLUMNS/VALUES TO LEVELS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_levels LIKE 'level_gmap_allow'")) == 0) {
    $database->database_query("ALTER TABLE se_levels 
          ADD COLUMN `level_gmap_allow` int(1) NOT NULL default '0'");
    $database->database_query("UPDATE se_levels SET level_gmap_allow='1'");
  }  
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_gmap_icon'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
          ADD COLUMN `setting_gmap_icon` varchar(128) NOT NULL default ''");
  }  
  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_gmap_mapfields'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
          ADD COLUMN `setting_gmap_mapfields` text");
    $database->database_query("UPDATE se_settings SET setting_gmap_mapfields='a:0:{}'");
  }  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS NOT BEEN INSTALLED)
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11080001 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES 
          (11080001, 1, 'GMap Plugin', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11080002, 1, 'Global GMap Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11080003, 1, 'GMap Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, ')");
  }
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS BEEN INSTALLED)
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11080101 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES   
('11080101', 1, 'Map', ''),
('11080102', 1, 'GMap Setting', ''),
('11080103', 1, 'Unknown location.', ''),
('11080104', 1, 'GMap', ''),
('11080105', 1, 'View Large Map', ''),
('11080201', 1, 'General GMap Settings', ''),
('11080202', 1, 'This page contains general GMap settings that affect your entire social network.', ''),
('11080203', 1, 'Your changes have been saved.', ''),
('11080204', 1, 'Save Changes', ''),
('11080205', 1, 'Public Permission Defaults', ''),
('11080206', 1, 'Select whether or not you want to let the public (visitors that are not logged-in) to view Map Members page of your social network', ''),
('11080207', 1, 'Yes, the public can view Map Members.', ''),
('11080208', 1, 'No, the public cannot view Map Members.', ''),
('11080209', 1, 'Google Map API Key', ''),
('11080210', 1, 'Please enter your Google Map API Key, if you do not have one, you can get one for free at <a href=\'http://code.google.com/apis/maps/signup.html\' target=\'_blank\'>Google Maps API</a>', ''),
('11080211', 1, 'Format: aVeryLongStringWhichProbablyMuchLongerThanThisOne', ''),
('11080212', 1, 'Please fill in all the fields.', ''),
('11080213', 1, 'Profile Field Mapping', ''),
('11080214', 1, 'Please select the field which best matches with the following fields that would use for member location.', ''),
('11080215', 1, 'Street Address', ''),
('11080216', 1, 'City/Town', ''),
('11080217', 1, 'Region/State/Province', ''),
('11080218', 1, 'Country', ''),
('11080219', 1, 'Your license key is invalid.', ''),
('11080220', 1, 'Please enter Google Map API Key.', ''),
('11080221', 1, 'License Key', ''),
('11080222', 1, 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.', ''),
('11080223', 1, 'Format: XXXX-XXXX-XXXX-XXXX', ''),
('11080224', 1, 'Would you like to embedded mini map on user profile page? The popup vesion always available even though you disable this feature.', ''),
('11080225', 1, 'Yes, embed member location on profile page.', ''),
('11080226', 1, 'No, do not show any map on profile page.', ''),
('11080227', 1, 'Map Icon', ''),
('11080228', 1, 'If you would like to replace the default icon on Google Map, please enter full url to image icon in field below', ''),
('11080301', 1, 'GMap Settings', ''),
('11080302', 1, 'Use this page to configure your gmap per level setting', ''),
('11080303', 1, 'Allow GMap?', ''),
('11080304', 1, 'If you have selected YES, your users will have access to GMap tool.', ''),
('11080305', 1, 'Yes, users can use gmap.', ''),
('11080306', 1, 'No, users cannot use gmap.', ''),
('11080307', 1, 'Save Changes', ''),
('11080308', 1, 'Your changes have been saved.', ''),
('11080309', 1, 'Editing User Level:', ''),
('11080310', 1, 'You are currently editing this user level\'s settings. Remember, these settings only apply to the users that belong to this user level. When you\'re finished, you can edit the <a href=\'admin_levels.php\'>other levels here</a>.', ''),
('11080401', 1, 'Map Members', ''),
('11080402', 1, 'Search through our members with your own keywords and criteria.', ''),
('11080403', 1, 'Search Criteria', ''),
('11080404', 1, 'Update Results', ''),
('11080405', 1, 'Last Update (Asc)', ''),
('11080406', 1, 'Last Login (Asc)', ''),
('11080407', 1, 'Last Signup (Asc)', ''),
('11080408', 1, 'No people matched your search criteria.', ''),
('11080409', 1, 'Online', ''),
('11080410', 1, 'Last Page', ''),
('11080411', 1, 'viewing result', ''),
('11080412', 1, 'viewing results', ''),
('11080413', 1, 'of', ''),
('11080414', 1, 'Next Page', ''),
('11080415', 1, '\'s Profile', ''),
('11080416', 1, 'Browsing members that match', ''),
('11080417', 1, 'We found', ''),
('11080418', 1, 'member(s) with profiles that match', ''),
('11080419', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11080420', 1, 'An Error Has Occurred.', ''),
('11080421', 1, 'Return', ''),
('11080422', 1, 'Sort Results By:', ''),
('11080423', 1, 'Last Update (Desc)', ''),
('11080424', 1, 'Last Login (Desc)', ''),
('11080425', 1, 'Last Signup (Desc)', ''),
('11080426', 1, 'Search Result', ''),
('11080427', 1, 'Click on user to view member location and info.', ''),
('11080428', 1, 'Unknown location', ''),
('11080701', 1, 'GMap Settings', ''),
('11080702', 1, 'Edit GMap settings such as privacy info on this page.', ''),
('11080703', 1, 'GMap Settings', ''),
('11080704', 1, 'Member Locations', ''),
('11080705', 1, 'User GMap on my profile', ''),
('11080706', 1, '', ''),
('11080707', 1, 'Your changes have been saved.', ''),
('11080708', 1, 'Save Changes', ''),
('11080709', 1, 'GMap Activation', ''),
('11080710', 1, 'This allows to you include or exclude yourself from this application.', ''),
('11080711', 1, 'Check this box to activate Gmap on your profile page.', ''),
('11080712', 1, 'Privacy Settings', ''),
('11080713', 1, 'Due to privacy issue, you can opt out certain info to be show up on the map.<br>Please check only fields which you want to show on the map, from the least specific (country) to most specific field (address).', ''),
('11080714', 1, 'Notify me when someone requests an invitation to an article I created.', ''),
('11080715', 1, 'Notify me when someone cancels an article I was invited to.', '')
    ") or die("Insert Into se_languagevars: ".mysql_error());
  }  
   
}
