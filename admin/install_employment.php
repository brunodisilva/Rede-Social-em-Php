<?php

$plugin_name = "Employment Plugin";
$plugin_version = 3.03;
$plugin_type = "employment";
$plugin_desc = "This plugin allows your user to have multiple employment listing on their profile.";
$plugin_icon = "employment16.gif";
$plugin_menu_title = "11050001"; 
$plugin_pages_main = "11050002<!>employment16.gif<!>admin_employment.php<~!~>";
$plugin_pages_level = "11050003<!>admin_levels_employmentsettings.php<~!~>";
$plugin_url_htaccess = "";

// lang var = 11050000 - 11059999

if($install == "employment") {

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
  
  
  //######### CREATE TABLES
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_employments'")) == 0) {
    $database->database_query("CREATE TABLE `se_employments` (
    `employment_id` int(9) NOT NULL auto_increment,
    `employment_user_id` int(9) NOT NULL default '0',
    `employment_employer` varchar(128) NOT NULL default '',
    `employment_position` varchar(128) NOT NULL default '',
    `employment_description` text NULL,
    `employment_location` varchar(128) NOT NULL default '',
    `employment_is_current` int(1) NOT NULL default '0',
    `employment_from_month` int(9) NOT NULL default '0',
    `employment_from_year` int(9) NOT NULL default '0',
    `employment_to_month` int(9) NOT NULL default '0',
    `employment_to_year` int(9) NOT NULL default '0',
    PRIMARY KEY  (`employment_id`),
    KEY `INDEX` (`employment_user_id`)
    )");
  }

  //######### ADD COLUMNS/VALUES TO LEVELS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_levels LIKE 'level_employment_allow'")) == 0) {
    $database->database_query("ALTER TABLE se_levels 
					ADD COLUMN `level_employment_allow` int(1) NOT NULL default '0'");
    $database->database_query("UPDATE se_levels SET level_employment_allow='1'");
  }  

  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_permission_employment'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
					ADD COLUMN `setting_permission_employment` int(1) NOT NULL default '0',
					ADD COLUMN `setting_employment_license` varchar(255) NOT NULL default ''
					");
    $database->database_query("UPDATE se_settings SET setting_permission_employment='1', setting_employment_license='XXXX-XXXX-XXXX-XXXX'");
  }   
  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS NOT BEEN INSTALLED)
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11050001 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES 
          (11050001, 1, 'Employement Plugin', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11050002, 1, 'Employement Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11050003, 1, 'Employement Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, ')");
  }  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS BEEN INSTALLED)
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11050101 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES   
('11050101', 1, 'Employment', ''),
('11050102', 1, 'Manage Employments', ''),
('11050103', 1, 'Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec', ''),
('11050104', 1, 'Employer:', ''),
('11050105', 1, 'Position:', ''),
('11050106', 1, 'Description:', ''),
('11050107', 1, 'Location:', ''),
('11050108', 1, 'Time Period:', ''),
('11050109', 1, 'present', ''),
('11050110', 1, 'to', ''),
('11050201', 1, 'General Employment Settings', ''),
('11050202', 1, 'This page contains general employment settings that affect your entire social network.', ''),
('11050203', 1, 'Your changes have been saved.', ''),
('11050204', 1, 'Save Changes', ''),
('11050205', 1, 'License Key', ''),
('11050206', 1, 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.', ''),
('11050207', 1, 'Format: XXXX-XXXX-XXXX-XXXX', ''),
('11050208', 1, 'Please fill in all the fields.', ''),
('11050209', 1, 'Public Permission Defaults', ''),
('11050210', 1, 'Select whether or not you want to let the public (visitors that are not logged-in) to search or browse global employment page of your social network', ''),
('11050211', 1, 'Yes, the public can access that page.', ''),
('11050212', 1, 'No, the public cannot access that page.', ''),
('11050301', 1, 'Employment Settings', ''),
('11050302', 1, 'If you have enabled employments, your users will have the option of creating employments. Use this page to configure your employment settings.', ''),
('11050303', 1, 'Allow Employments?', ''),
('11050304', 1, 'If you have selected YES, your users will have the option of creating employments. Note that if you switch this from YES to NO, users will lose any current employments they have.', ''),
('11050305', 1, 'Yes, users can create employments.', ''),
('11050306', 1, 'No, users cannot create employments.', ''),
('11050307', 1, 'Save Changes', ''),
('11050308', 1, 'Your changes have been saved.', ''),
('11050309', 1, 'Editing User Level:', ''),
('11050310', 1, 'You are currently editing this user level\'s settings. Remember, these settings only apply to the users that belong to this user level. When you\'re finished, you can edit the <a href=\'admin_levels.php\'>other levels here</a>.', ''),
('11050601', 1, 'Search Employment', ''),
('11050602', 1, 'You can search for user\'s employment records using the form below.', ''),
('11050603', 1, 'Search Criteria', ''),
('11050604', 1, 'No people matched your search criteria.', ''),
('11050605', 1, 'Delete This Employment', ''),
('11050606', 1, 'Employer:', ''),
('11050607', 1, 'Position:', ''),
('11050608', 1, 'Description:', ''),
('11050609', 1, 'Location:', ''),
('11050610', 1, 'Time/Period:', ''),
('11050611', 1, 'currently work here.', ''),
('11050612', 1, 'to', ''),
('11050613', 1, 'Search', ''),
('11050614', 1, 'Changes saved.', ''),
('11050615', 1, 'Month:', ''),
('11050616', 1, 'Year:', ''),
('11050617', 1, 'present', ''),
('11050618', 1, 'From Time/Period:', ''),
('11050619', 1, 'To Time/Period:', ''),
('11050620', 1, 'Time/Period:', ''),
('11050621', 1, 'Last Page', ''),
('11050622', 1, 'viewing result', ''),
('11050623', 1, 'viewing results', ''),
('11050624', 1, 'of', ''),
('11050625', 1, 'Next Page', ''),
('11050626', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11050627', 1, 'An Error Has Occurred.', ''),
('11050628', 1, 'Return', ''),
('11050701', 1, 'My Employment', ''),
('11050702', 1, 'You can manage your employmental history using the form below. Employer name is required.', ''),
('11050703', 1, 'Are you sure?', ''),
('11050704', 1, 'Add New Employement (name is required)', ''),
('11050705', 1, 'Delete This Employment', ''),
('11050706', 1, 'Employer:', ''),
('11050707', 1, 'Position:', ''),
('11050708', 1, 'Description:', ''),
('11050709', 1, 'Location:', ''),
('11050710', 1, 'Time/Period:', ''),
('11050711', 1, 'I currently work here.', ''),
('11050712', 1, 'to', ''),
('11050713', 1, 'Save Changes', ''),
('11050714', 1, 'Changes saved.', ''),
('11050715', 1, 'Month:', ''),
('11050716', 1, 'Year:', ''),
('11050717', 1, 'present', '')
      ") or die("Insert Into se_languagevars: ".mysql_error());
  }      
    
}
