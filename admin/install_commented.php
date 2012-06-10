<?php

$plugin_name = "Commented Plugin";
$plugin_version = 3.03;
$plugin_type = "commented";
$plugin_desc = "This plugin allows your social network to have feature to view comments made by user.";
$plugin_icon = "commented16.gif";
$plugin_menu_title = "11130001"; 
$plugin_pages_main = "11130002<!>commented16.gif<!>admin_commented.php<~!~>";
$plugin_pages_level = "11130003<!>admin_levels_commentedsettings.php<~!~>";

$plugin_url_htaccess = "";

// lang var = 11130000 - 11139999


if($install == "commented") {

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
  
  //######### ADD COLUMNS/VALUES TO LEVELS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_levels LIKE 'level_commented_allow'")) == 0) {
    $database->database_query("ALTER TABLE se_levels 
          ADD COLUMN `level_commented_allow` int(1) NOT NULL default '1'");
    $database->database_query("UPDATE se_levels SET level_commented_allow='1'");
  }  
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_permission_commented'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
          ADD COLUMN `setting_permission_commented` int(1) NOT NULL default '1',
          ADD COLUMN `setting_commented_license` varchar(255) NOT NULL default ''");
    $database->database_query("UPDATE se_settings SET setting_permission_commented='1', setting_commented_license='XXXX-XXXX-XXXX-XXXX'");
  }    
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS NOT BEEN INSTALLED)
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11130001 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES 
          (11130001, 1, 'Commented Plugin', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11130002, 1, 'Global Commented Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11130003, 1, 'Commented Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, ')");
  }  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS BEEN INSTALLED)
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11130101 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES 
('11130101', 1, 'Who I Commented', ''),
('11130102', 1, 'Commented', ''),
('11130103', 1, 'user(s)', ''),
('11130104', 1, 'No comment so far', ''),
('11130201', 1, 'General Commented Settings', ''),
('11130202', 1, 'This page contains general settings that affect your entire social network.', ''),
('11130203', 1, 'Your changes have been saved.', ''),
('11130204', 1, 'Save Changes', ''),
('11130205', 1, 'Plugin Activation', ''),
('11130206', 1, 'Would you like to enable this plugin for your user?', ''),
('11130207', 1, 'Yes, allow user to use plugin.', ''),
('11130208', 1, 'No, do not allow user to use plugin.', ''),
('11130209', 1, 'License Key', ''),
('11130210', 1, 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.', ''),
('11130211', 1, 'Format: XXXX-XXXX-XXXX-XXXX', ''),
('11130301', 1, 'Commented Settings', ''),
('11130302', 1, 'Use this page to configure your Commented per level setting', ''),
('11130303', 1, 'Allow Access?', ''),
('11130304', 1, 'If you have selected YES, your users will have access to tool.', ''),
('11130305', 1, 'Yes, users can use plugin.', ''),
('11130306', 1, 'No, users cannot use plugin.', ''),
('11130307', 1, 'Save Changes', ''),
('11130308', 1, 'Your changes have been saved.', ''),
('11130309', 1, 'Editing User Level:', ''),
('11130310', 1, 'You are currently editing this user level\'s settings. Remember, these settings only apply to the users that belong to this user level. When you\'re finished, you can edit the <a href=\'admin_levels.php\'>other levels here</a>.', ''),
('11130401', 1, 'message', ''),
('11130402', 1, '\\o\\n', ''),
('11130403', 1, 'Commented By', ''),
('11130404', 1, 'No comment found.', ''),
('11130405', 1, 'Back to', ''),
('11130406', 1, '\'s Profile', ''),
('11130407', 1, 'Last Page', ''),
('11130408', 1, 'showing user', ''),
('11130409', 1, 'of', ''),
('11130410', 1, 'showing users', ''),
('11130411', 1, 'Next Page', ''),
('11130412', 1, 'Anonymous', ''),
('11130420', 1, 'An Error Has Occurred', ''),
('11130421', 1, 'The profile you are looking for has been deleted or does not exist.', ''),
('11130422', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11130423', 1, 'Return', '')
      ") or die("Insert Into se_languagevars: ".mysql_error());
  }  
  
}
