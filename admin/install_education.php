<?php

$plugin_name = "Education Plugin";
$plugin_version = 3.03;
$plugin_type = "education";
$plugin_desc = "This plugin allows your user to have multiple education listing on their profile.";
$plugin_icon = "education16.gif";
$plugin_menu_title = "11040001"; 
$plugin_pages_main = "11040002<!>education16.gif<!>admin_education.php<~!~>";
$plugin_pages_level = "11040003<!>admin_levels_educationsettings.php<~!~>";
$plugin_url_htaccess = "";

// lang var = 11040000 - 11049999


if($install == "education") {

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
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_educations'")) == 0) {
    $database->database_query("CREATE TABLE `se_educations` (
    `education_id` int(9) NOT NULL auto_increment,
    `education_user_id` int(9) NOT NULL default '0',
    `education_name` varchar(128) NOT NULL default '',
    `education_year` varchar(128) NOT NULL default '',
    `education_for` varchar(128) NOT NULL default '',
    `education_degree` varchar(128) NOT NULL default '',
    `education_concentration1` varchar(128) NOT NULL default '',
    `education_concentration2` varchar(128) NOT NULL default '',
    `education_concentration3` varchar(128) NOT NULL default '',
    PRIMARY KEY  (`education_id`),
    KEY `INDEX` (`education_user_id`)
    )");
  }
  
  //######### ADD COLUMNS/VALUES TO LEVELS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_levels LIKE 'level_education_allow'")) == 0) {
    $database->database_query("ALTER TABLE se_levels 
					ADD COLUMN `level_education_allow` int(1) NOT NULL default '0'");
    $database->database_query("UPDATE se_levels SET level_education_allow='1'");
  }
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_permission_education'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
					ADD COLUMN `setting_permission_education` int(1) NOT NULL default '0',
					ADD COLUMN `setting_education_license` varchar(255) NOT NULL default ''
					");
    $database->database_query("UPDATE se_settings SET setting_permission_education='1', setting_education_license='XXXX-XXXX-XXXX-XXXX'");
  }   
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS NOT BEEN INSTALLED)
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11040001 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES 
          (11040001, 1, 'Education Plugin', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11040002, 1, 'Global Education Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11040003, 1, 'Education Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, ')");
  }  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS BEEN INSTALLED)
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11040101 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES 
('11040101', 1, 'Education', ''),
('11040102', 1, 'Manage Schools', ''),
('11040103', 1, 'Elementary School|High School|College|Graduate School|Professional School', ''),
('11040104', 1, 'Attended for:', ''),
('11040105', 1, 'Degree:', ''),
('11040106', 1, 'Concentration:', ''),
('11040201', 1, 'General Education Settings', ''),
('11040202', 1, 'This page contains general education settings that affect your entire social network.', ''),
('11040203', 1, 'Your changes have been saved.', ''),
('11040204', 1, 'Save Changes', ''),
('11040205', 1, 'License Key', ''),
('11040206', 1, 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.', ''),
('11040207', 1, 'Format: XXXX-XXXX-XXXX-XXXX', ''),
('11040208', 1, 'Please fill in all the fields.', ''),
('11040209', 1, 'Public Permission Defaults', ''),
('11040210', 1, 'Select whether or not you want to let the public (visitors that are not logged-in) to search or browse global education page of your social network', ''),
('11040211', 1, 'Yes, the public can access that page.', ''),
('11040212', 1, 'No, the public cannot access that page.', ''),
('11040301', 1, 'Education Settings', ''),
('11040302', 1, 'If you have enabled educations, your users will have the option of creating educations. Use this page to configure your education settings.', ''),
('11040303', 1, 'Allow Educations?', ''),
('11040304', 1, 'If you have selected YES, your users will have the option of creating educations. Note that if you switch this from YES to NO, users will lose any current educations they have.', ''),
('11040305', 1, 'Yes, users can create educations.', ''),
('11040306', 1, 'No, users cannot create educations.', ''),
('11040307', 1, 'Save Changes', ''),
('11040308', 1, 'Your changes have been saved.', ''),
('11040309', 1, 'Editing User Level:', ''),
('11040310', 1, 'You are currently editing this user level\'s settings. Remember, these settings only apply to the users that belong to this user level. When you\'re finished, you can edit the <a href=\'admin_levels.php\'>other levels here</a>.', ''),
('11040601', 1, 'Search Education', ''),
('11040602', 1, 'You can search for user\'s educational records using the form below.', ''),
('11040603', 1, 'Search Criteria', ''),
('11040604', 1, 'No people matched your search criteria.', ''),
('11040606', 1, 'Name:', ''),
('11040607', 1, 'Attended for:', ''),
('11040608', 1, 'Class Year:', ''),
('11040609', 1, 'Degree:', ''),
('11040610', 1, 'Concentration:', ''),
('11040611', 1, '2nd Concentration:', ''),
('11040612', 1, '3rd Concentration:', ''),
('11040613', 1, 'Search', ''),
('11040614', 1, 'Last Page', ''),
('11040615', 1, 'viewing result', ''),
('11040616', 1, 'viewing results', ''),
('11040617', 1, 'of', ''),
('11040618', 1, 'Next Page', ''),
('11040619', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11040620', 1, 'An Error Has Occurred.', ''),
('11040621', 1, 'Return', ''),
('11040701', 1, 'My Education', ''),
('11040702', 1, 'You can manage your educational history using the form below.', ''),
('11040703', 1, 'Are you sure?', ''),
('11040704', 1, 'Add New School (name is required)', ''),
('11040705', 1, 'Delete This School', ''),
('11040706', 1, 'Name:', ''),
('11040707', 1, 'Attended for:', ''),
('11040708', 1, 'Class Year:', ''),
('11040709', 1, 'Degree:', ''),
('11040710', 1, 'Concentration:', ''),
('11040711', 1, '2nd Concentration:', ''),
('11040712', 1, '3rd Concentration:', ''),
('11040713', 1, 'Save Changes', ''),
('11040714', 1, 'Changes saved.', '')
      ") or die("Insert Into se_languagevars: ".mysql_error());
  }  
  
}
