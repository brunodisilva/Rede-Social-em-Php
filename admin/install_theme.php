<?

$plugin_name = "Theme Plugin";
$plugin_version = 3.22;
$plugin_type = "theme";
$plugin_desc = "This plugin allow subnetworks, or user-levels to have its own look-n-feel using CSS, and template blocks. Users have option to overwrite and choose which theme to use.";
$plugin_icon = "theme16.gif";
$plugin_menu_title = "11120001"; 
$plugin_pages_main = "11120002<!>theme16.gif<!>admin_viewthemes.php<~!~>11120003<!>theme16.gif<!>admin_viewthemes_levels.php<~!~>11120004<!>theme16.gif<!>admin_viewthemes_subnetworks.php<~!~>11120005<!>theme16.gif<!>admin_theme.php<~!~>";
$plugin_pages_level = "";
$plugin_url_htaccess = "";

// lang var = 11120000 - 11129999


if($install == "theme") {
  
  //rc_toolkit::validate_plugin_requirements($plugin_type, array('radcodes'=>3.22));
  
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
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_themes'")) == 0) {
    $database->database_query("CREATE TABLE `se_themes` (
    `theme_id` int(9) NOT NULL auto_increment,
    `theme_name` varchar(50) NOT NULL default '',
    `theme_desc` text NULL,
    `theme_default` int(9) NOT NULL default '0',
    `theme_css` text NULL,
    PRIMARY KEY  (`theme_id`)
    )");
  }

    $database->database_query("ALTER TABLE se_subnets 
					ADD COLUMN `subnet_theme_id` int(1) NOT NULL default '0'");  

  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_themeblocks'")) == 0) {
    $database->database_query("CREATE TABLE `se_themeblocks` (
    `themeblock_id` int(9) NOT NULL auto_increment,
    `themeblock_title` varchar(100) NOT NULL default '',
    PRIMARY KEY  (`themeblock_id`)
    )");
  }
    

  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_themetemplates'")) == 0) {
    $database->database_query("CREATE TABLE `se_themetemplates` (
    `themetemplate_id` int(9) NOT NULL auto_increment,
    `themetemplate_block_id` int(9) NOT NULL default '0',
    `themetemplate_theme_id` int(9) NOT NULL default '0',
    `themetemplate_code` text NULL,
    PRIMARY KEY  (`themetemplate_id`),
    KEY `INDEX` (`themetemplate_block_id`,`themetemplate_theme_id`)
    )");
  }  
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_permission_theme'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
					ADD COLUMN `setting_permission_theme` int(1) NOT NULL default '0',
					ADD COLUMN `setting_theme_license` varchar(255) NOT NULL default ''
					");
    $database->database_query("UPDATE se_settings SET setting_permission_theme='1', setting_theme_license='XXXX-XXXX-XXXX-XXXX'");
  } 
  
  // #### v2
  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_levels LIKE 'level_theme_id'")) == 0) {
    $database->database_query("ALTER TABLE se_levels 
          ADD COLUMN `level_theme_id` int(1) NOT NULL default '0'
    ");
    $database->database_query("UPDATE se_levels SET level_theme_id='0'");
  }    
    
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_theme_type'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
          ADD COLUMN `setting_theme_type` int(1) NOT NULL default '1',
          ADD COLUMN `setting_theme_user_overwrite` int(1) NOT NULL default '0'
          ");
    $database->database_query("UPDATE se_settings SET setting_theme_type='1', setting_theme_user_overwrite='0'");
  } 
  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_users LIKE 'user_theme_id'")) == 0) {
    $database->database_query("ALTER TABLE se_users 
          ADD COLUMN `user_theme_id` int(1) NOT NULL default '0'
    ");
    $database->database_query("UPDATE se_users SET user_theme_id='0'");
  }    
  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_themes LIKE 'theme_status'")) == 0) {
    $database->database_query("ALTER TABLE se_themes 
          ADD COLUMN `theme_status` int(1) NOT NULL default '1'
    ");
    $database->database_query("UPDATE se_themes SET theme_status='1'");
  }    
    

  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS NOT BEEN INSTALLED)
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11120001 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES 
          (11120001, 1, 'Theme Plugin', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11120002, 1, 'View Themes', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11120003, 1, 'Theme User-Level Config', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11120004, 1, 'Theme Subnetwork Config', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11120005, 1, 'Global Theme Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, ')");
  }  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS BEEN INSTALLED)
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11120101 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES   
('11120101', 1, 'Theme:', ''),
('11120102', 1, 'Default', ''),
('11120201', 1, 'General Theme Settings', ''),
('11120202', 1, 'This page contains general theme settings that affect your entire social network.', ''),
('11120203', 1, 'Your changes have been saved.', ''),
('11120204', 1, 'Save Changes', ''),
('11120205', 1, 'Template Blocks', ''),
('11120206', 1, 'Please define name blocks that would be available for theme to use in templates. Do NOT delete a block unless you are sure. Once a block is deleted, all of its code in themes would be removed also.', ''),
('11120207', 1, 'Add Block', ''),
('11120208', 1, 'Template Blocks #ID', ''),
('11120209', 1, 'Show Detailed Instructions', ''),
('11120210', 1, '<b>Usage:</b><br>To include a block, put the code below in template file.<br>{include file=\'theme:block_#BLOCK_ID#\'}<br><br>To force system to use a block from certain theme, put the code below in template file.<br>{include file=\'theme:block_#BLOCK_ID#/theme_#THEME_ID#\'}
  <br><br><b>Example:</b><br>Let say you have a block name \'Header\' with ID = 1, which would display differently based on what theme you assigned to user\'s subnetwork. All you have to do is put {include file=\'theme:block_1\'} in template where you want it to show up.
  Now imagine that you have a block name \'Notice\' with ID = 2, and a theme name \'General\' with ID = 3, you want to force system to use General\'s Notice block, you use {include file=\'theme:block_2/theme_3\'} instead.', ''),
('11120211', 1, 'License Key', ''),
('11120212', 1, 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.', ''),
('11120213', 1, 'Format: XXXX-XXXX-XXXX-XXXX', ''),
('11120214', 1, 'Process Engine', ''),
('11120215', 1, 'Please select how the system should select the theme to display', ''),
('11120216', 1, 'Turn off theme, nothing will be shown.', ''),
('11120217', 1, 'Based on <a href=\'admin_viewthemes_subnetworks.php\'>subnetwork theme settings</a>', ''),
('11120218', 1, 'Based on <a href=\'admin_viewthemes_levels.php\'>user-level theme settings</a>', ''),
('11120219', 1, 'Based on user selection', ''),
('11120220', 1, 'If engine is run based on subnetwork or user-level theme settings, then would the user be able to select the theme to use and overwrite their settings?', ''),
('11120221', 1, 'Yes, user can choose theme to use.', ''),
('11120222', 1, 'Yes, user cannot choose theme to use.', ''),
('11120301', 1, 'View Themes', ''),
('11120302', 1, 'This page lists all of the theme that are available for subnetworks on your social network. Please visit <a href=\'admin_viewthemes_subnetworks.php\'>Subnetwork Themes</a> if you want to assign theme to subnetworks.', ''),
('11120303', 1, 'Add Theme', ''),
('11120304', 1, 'ID', ''),
('11120305', 1, 'Name', ''),
('11120306', 1, 'Description', ''),
('11120307', 1, 'Options', ''),
('11120308', 1, 'edit', ''),
('11120309', 1, 'delete', ''),
('11120310', 1, 'Delete Theme', ''),
('11120311', 1, 'Are you sure you want to delete this theme? Warning: Any records associated with it would be reset to default.', ''),
('11120312', 1, 'Delete Theme', ''),
('11120313', 1, 'Cancel', ''),
('11120314', 1, 'The theme has been deleted.', ''),
('11120315', 1, 'Default', ''),
('11120316', 1, 'The default theme has been updated.', ''),
('11120317', 1, 'You cannot delete the default theme. Please set default to other theme first.', ''),
('11120318', 1, 'Public', ''),
('11120319', 1, 'Theme public setting updated.', ''),
('11120401', 1, 'Add Theme', ''),
('11120402', 1, 'To create a theme, complete the following form. Once it is created, you will be able to associate it with subnetworks.', ''),
('11120403', 1, 'Add Theme', ''),
('11120404', 1, 'Cancel', ''),
('11120405', 1, 'Name', ''),
('11120406', 1, 'Description', ''),
('11120407', 1, 'CSS Code', ''),
('11120408', 1, 'Please specify a name for this theme.', ''),
('11120501', 1, 'Edit Theme', ''),
('11120502', 1, 'To modify this theme, complete the following form.', ''),
('11120503', 1, 'Save Changes', ''),
('11120504', 1, 'Cancel', ''),
('11120505', 1, 'Name', ''),
('11120506', 1, 'Description', ''),
('11120507', 1, 'CSS Code', ''),
('11120508', 1, 'Please specify a name for this theme.', ''),
('11120509', 1, 'Your changes have been saved.', ''),
('11120601', 1, 'User-Level Themes', ''),
('11120602', 1, 'This page lists all of the user levels that exist on your social network and the theme that associates with them. 
User-Levels that use \'Default Theme\' would use the theme that you assign as \'Default\' in <a href=\'admin_viewthemes.php\'>View Themes</a> section.', ''),
('11120603', 1, 'Your changes have been saved.', ''),
('11120604', 1, 'ID', ''),
('11120605', 1, 'User-Level Name', ''),
('11120606', 1, 'Theme Name', ''),
('11120607', 1, 'N/A', ''),
('11120608', 1, 'Default User-Level', ''),
('11120609', 1, 'Default Theme', ''),
('11120610', 1, 'Please select a theme that would associated with checked user levels above.', ''),
('11120611', 1, 'Update User-Levels', ''),
('11120701', 1, 'Subnetwork Themes', ''),
('11120702', 1, 'This page lists all of the subnetworks that exist on your social network and the theme that associates with them. 
Subnetworks that use \'Default Theme\' would use the theme that you assign as \'Default\' in <a href=\'admin_viewthemes.php\'>View Themes</a> section.', ''),
('11120703', 1, 'Your changes have been saved.', ''),
('11120704', 1, 'ID', ''),
('11120705', 1, 'Subnetwork Name', ''),
('11120706', 1, 'Theme Name', ''),
('11120707', 1, 'N/A', ''),
('11120708', 1, 'Default Subnetwork', ''),
('11120709', 1, 'Default Theme', ''),
('11120710', 1, 'Please select a theme that would associated with checked subnetworks above.', ''),
('11120711', 1, 'Update Subnetworks', '')
    ") or die("Insert Into se_languagevars: ".mysql_error());
  } 
    
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_themes LIKE 'theme_stylesheet'")) == 0) {
    $database->database_query("ALTER TABLE se_themes 
          ADD COLUMN `theme_stylesheet` varchar(255) NOT NULL default ''
    ");
  } 
  
  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11120721 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES   
('11120721', 1, 'Theme Preference', ''),
('11120722', 1, 'Stylesheet URL', '')
    ") or die("Insert Into se_languagevars: ".mysql_error());
  } 
    
}  

?>