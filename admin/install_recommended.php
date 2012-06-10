<?php

$plugin_name = "Recommended Plugin";
$plugin_version = 3.16;
$plugin_type = "recommended";
$plugin_desc = "This plugin allows your social network to have a recommendation system between users.";
$plugin_icon = "recommended16.gif";
$plugin_menu_title = "11140001"; 
$plugin_pages_main = "11140002<!>recommended16.gif<!>admin_recommended.php<~!~>";
$plugin_pages_level = "11140003<!>admin_levels_recommendedsettings.php<~!~>";
$plugin_url_htaccess = "";

// lang var = 11140000 - 11149999


if($install == "recommended") {

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
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_levels LIKE 'level_recommended_allow'")) == 0) {
    $database->database_query("ALTER TABLE se_levels 
          ADD COLUMN `level_recommended_allow` int(1) NOT NULL default '1'");
    $database->database_query("UPDATE se_levels SET level_recommended_allow='1'");
  }
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_permission_recommended'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
          ADD COLUMN `setting_permission_recommended` int(1) NOT NULL default '0',
          ADD COLUMN `setting_recommended_license` varchar(255) NOT NULL default ''
          ");
    $database->database_query("UPDATE se_settings SET setting_permission_recommended='1', setting_recommended_license='Nulled By: Shenwoo'");
  }   
  
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'rc_recommendedvotes'")) == 0) {
    $database->database_query("CREATE TABLE `rc_recommendedvotes` (
      `vote_id` int(14) NOT NULL auto_increment,
      `vote_object_id` int(14) NOT NULL default '0',
      `vote_user_id` int(14) NOT NULL default '0',
      `vote_date` int(14) NOT NULL default '0',
      `vote_comment` TEXT,
      PRIMARY KEY  (`vote_id`),
      UNIQUE KEY `objectname` (`vote_object_id`,`vote_user_id`),
      KEY `object` (`vote_object_id`),
      KEY `name` (`vote_user_id`)
    )");
  }  
  
  
  $actiontype_name = 'recommendedvote';
  $res = $database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='$actiontype_name'");
  $actiontype_vars = '[recommender],[recommender_name],[recommendee],[recommendee_name],[comment]';
  if($database->database_num_rows($res) == 0) {
    $actiontype_desc = SE_Language::edit(0, 'Recommending an user', NULL, LANGUAGE_INDEX_ACTIONS);
    $actiontype_text = SE_Language::edit(0, '<a href="profile.php?user=%1$s">%2$s</a> recommended <a href="profile.php?user=%3$s">%4$s</a>: <div class="recentaction_div">%5$s</div>', NULL, LANGUAGE_INDEX_ACTIONS);
    $database->database_query("INSERT INTO se_actiontypes (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media) VALUES ('$actiontype_name', 'action_recommendedvote.gif', '1', '1', '$actiontype_desc', '$actiontype_text', '$actiontype_vars', '0')");
    $actiontypes[] = $database->database_insert_id();
  }  
  
  $actiontype_name = 'recommendednovote';
  $res = $database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='$actiontype_name'");
  $actiontype_vars = '[recommender],[recommender_name],[recommendee],[recommendee_name]';
  if($database->database_num_rows($res) == 0) {
    $actiontype_desc = SE_Language::edit(0, 'No longer recommending an user', NULL, LANGUAGE_INDEX_ACTIONS);
    $actiontype_text = SE_Language::edit(0, '<a href="profile.php?user=%1$s">%2$s</a> no longer recommending <a href="profile.php?user=%3$s">%4$s</a>', NULL, LANGUAGE_INDEX_ACTIONS);
    $database->database_query("INSERT INTO se_actiontypes (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media) VALUES ('$actiontype_name', 'action_recommendednovote.gif', '1', '1', '$actiontype_desc', '$actiontype_text', '$actiontype_vars', '0')");
    $actiontypes[] = $database->database_insert_id();
  }  
  
  if(count($actiontypes) != 0) {
    $database->database_query("UPDATE se_usersettings SET usersetting_actions_display = CONCAT(usersetting_actions_display, ',', '".implode(",", $actiontypes)."')");
  }  
  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_usersettings` LIKE 'usersetting_notify_recommendedcomment'")) == 0) {
    $database->database_query("ALTER TABLE se_usersettings 
          ADD COLUMN `usersetting_notify_recommendedcomment` int(1) NOT NULL default '1'");
    $database->database_query("UPDATE se_usersettings SET usersetting_notify_recommendedcomment='1'");
  }

  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_settings` LIKE 'setting_email_recommendedcomment_subject'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
          ADD COLUMN `setting_email_recommendedcomment_subject` varchar(200) NOT NULL default '',
          ADD COLUMN `setting_email_recommendedcomment_message` text NULL");
    $database->database_query("UPDATE se_settings SET setting_email_recommendedcomment_subject='You are recommended!', setting_email_recommendedcomment_message='Hello [username],\n\nYou have been recommended by [commenter]. Please click the following link to view it:\n\n[link]\n\nBest Regards,\nSocial Network Administration'");
  }  
  

  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11140001 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES 
          (11140001, 1, 'Recommended Plugin', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11140002, 1, 'Recommended Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11140003, 1, 'Recommended Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, ')");
  } 

  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11140101 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES 
('11140101', 1, 'Recommendation', ''),
('11140102', 1, 'Edit My Recommendation', ''),
('11140103', 1, 'Recommend This User', ''),
('11140104', 1, 'Recommended by', ''),
('11140105', 1, 'users', ''),
('11140106', 1, 'View All Recommenders', ''),
('11140107', 1, 'Recommends', ''),
('11140108', 1, 'users', ''),
('11140109', 1, 'View All Recommendees', ''),
('11140110', 1, 'Recommended Users', ''),
('11140111', 1, 'Active Recommenders', ''),
('11140112', 1, 'Recommended Settings', ''),
('11140201', 1, 'Recommended Settings', ''),
('11140202', 1, 'Use this page to configure your recommended per level setting', ''),
('11140203', 1, 'Allow Access?', ''),
('11140204', 1, 'If you have selected YES, your users will have access to tool.', ''),
('11140205', 1, 'Yes, users can use plugin.', ''),
('11140206', 1, 'No, users cannot use plugin.', ''),
('11140207', 1, 'Save Changes', ''),
('11140208', 1, 'Your changes have been saved.', ''),
('11140209', 1, 'Editing User Level:', ''),
('11140210', 1, 'You are currently editing this user level\'s settings. Remember, these settings only apply to the users that belong to this user level. When you\'re finished, you can edit the <a href=\'admin_levels.php\'>other levels here</a>.', ''),
('11140301', 1, 'General Recommended Settings', ''),
('11140302', 1, 'This page contains general recommended settings that affect your entire social network.', ''),
('11140303', 1, 'Your changes have been saved.', ''),
('11140304', 1, 'Save Changes', ''),
('11140305', 1, 'License Key', ''),
('11140306', 1, 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.', ''),
('11140307', 1, 'Format: XXXX-XXXX-XXXX-XXXX', ''),
('11140308', 1, 'Please fill in all the fields.', ''),
('11140309', 1, 'Public Permission Defaults', ''),
('11140310', 1, 'Select whether or not you want to let the public (visitors that are not logged-in) to browse global recommendation pages of your social network.', ''),
('11140311', 1, 'Yes, the public can access that page.', ''),
('11140312', 1, 'No, the public cannot access that page.', ''),
('11140313', 1, 'New Recommendation Email', ''),
('11140314', 1, 'This is the email that gets sent to a user whom just got recommended. For more system emails, please visit the <a href=\'admin_emails.php\'>System Emails</a> page.', ''),
('11140315', 1, 'Subject', ''),
('11140316', 1, 'Message', ''),
('11140317', 1, '[username] - replaced with the username of the recepient.<br>[commenter] - replaced with the name of the user who gives recommendation.<br>[link] - replaced with the link to profile page.', ''),
('11140501', 1, 'An Error Has Occurred', ''),
('11140502', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11140503', 1, 'Return', ''),
('11140504', 1, 'Recommendation: ', ''),
('11140505', 1, 'If you would like to recommend this user, please complete the form below.', ''),
('11140506', 1, 'Recommend:', ''),
('11140507', 1, 'Comment:', ''),
('11140508', 1, 'Submit', ''),
('11140509', 1, 'Please enter your comment.', ''),
('11140601', 1, 'Recommended by ', ''),
('11140602', 1, 'Below are users whom have been recommended.', ''),
('11140603', 1, 'No recommendation has been made.', ''),
('11140604', 1, 'You can also view users who ', ''),
('11140605', 1, ' has recommended', ''),
('11140606', 1, 'Last Page', ''),
('11140607', 1, 'showing user', ''),
('11140608', 1, 'of', ''),
('11140609', 1, 'showing users', ''),
('11140610', 1, 'Next Page', ''),
('11140611', 1, 'An Error Has Occurred', ''),
('11140612', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11140613', 1, 'Return', ''),
('11140614', 1, 'Edit Comment', ''),
('11140615', 1, 'Remove Entry', ''),
('11140701', 1, 'Recommended for ', ''),
('11140702', 1, 'Below are users who have made recommendations.', ''),
('11140703', 1, 'No user has been found.', ''),
('11140704', 1, 'You can also view users whom ', ''),
('11140705', 1, ' has recommended.', ''),
('11140706', 1, 'Last Page', ''),
('11140707', 1, 'showing user', ''),
('11140708', 1, 'of', ''),
('11140709', 1, 'showing users', ''),
('11140710', 1, 'Next Page', ''),
('11140711', 1, 'An Error Has Occurred', ''),
('11140712', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11140713', 1, 'Return', ''),
('11140801', 1, 'Recommended Users', ''),
('11140802', 1, 'Below are users who have been highly recommended by other users.', ''),
('11140803', 1, 'No recommendation has been made.', ''),
('11140804', 1, 'recommenders', ''),
('11140805', 1, '', ''),
('11140806', 1, 'Last Page', ''),
('11140807', 1, 'showing user', ''),
('11140808', 1, 'of', ''),
('11140809', 1, 'showing users', ''),
('11140810', 1, 'Next Page', ''),
('11140811', 1, 'An Error Has Occurred', ''),
('11140812', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11140813', 1, 'Return', ''),
('11140901', 1, 'Active Recommenders', ''),
('11140902', 1, 'Below are users who have made the most recommendations for other users.', ''),
('11140903', 1, 'No user has been found.', ''),
('11140904', 1, 'recommends', ''),
('11140905', 1, '', ''),
('11140906', 1, 'Last Page', ''),
('11140907', 1, 'showing user', ''),
('11140908', 1, 'of', ''),
('11140909', 1, 'showing users', ''),
('11140910', 1, 'Next Page', ''),
('11140911', 1, 'An Error Has Occurred', ''),
('11140912', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11140913', 1, 'Return', ''),
('11141001', 1, 'An Error Has Occurred', ''),
('11141002', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11141003', 1, 'Return', ''),
('11141004', 1, 'Recommendation: ', ''),
('11141005', 1, 'If you would like to recommend this user, please complete the form below.', ''),
('11141006', 1, 'User:', ''),
('11141007', 1, 'Comment:', ''),
('11141008', 1, 'Submit', ''),
('11141009', 1, 'Please enter your comment.', ''),
('11141010', 1, 'Your changes have been saved.', ''),
('11141012', 1, 'Back to User\'s Profile', ''),
('11141101', 1, 'An Error Has Occurred', ''),
('11141102', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11141103', 1, 'Return', ''),
('11141104', 1, 'Recommendation: ', ''),
('11141105', 1, 'To edit your recommendation for this user, please complete the form below.', ''),
('11141106', 1, 'User:', ''),
('11141107', 1, 'Comment:', ''),
('11141108', 1, 'Submit', ''),
('11141109', 1, 'Please enter your comment.', ''),
('11141110', 1, 'Your changes have been saved.', ''),
('11141111', 1, 'Remove My Recommendation', ''),
('11141112', 1, 'Back to User\'s Profile', ''),
('11141113', 1, 'Thank you for making recommendation.', ''),
('11141201', 1, 'Recommended Settings', ''),
('11141202', 1, 'Edit your recommended settings such as email notifications on this page.', ''),
('11141203', 1, 'Your changes have been saved.', ''),
('11141204', 1, 'Save Changes', ''),
('11141205', 1, 'Recommended Notifications', ''),
('11141206', 1, 'Check the boxes next to the email notifications you would like to receive.', ''),
('11141207', 1, 'Notify me when someone recommends me.', ''),
('11141208', 1, 'Who Recommend Me', ''),
('11141209', 1, 'Who I Recommend', '')
      ") or die("Insert Into se_languagevars: ".mysql_error());
  }
  
  
}
