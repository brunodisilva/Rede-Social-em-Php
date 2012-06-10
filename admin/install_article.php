<?php

$plugin_name = "Article Plugin";
$plugin_version = 3.03;
$plugin_type = "article";
$plugin_desc = "This plugin allows your social network users to post and share articles, attach photos, comments. User can save article as draft and publish article later. Admin can approve or reject article, make article featured, and many more. Nulled By NiceNancy.";
$plugin_icon = "article16.gif";
$plugin_menu_title = "11150001"; 
$plugin_pages_main = "11150004<!>article16.gif<!>admin_viewarticles.php<~!~>11150002<!>article_settings16.gif<!>admin_article.php<~!~>";
$plugin_pages_level = "11150003<!>admin_levels_articlesettings.php<~!~>";
$plugin_url_htaccess = "RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^/]+)/articles/([^/]+)?\$ \$server_info/articles.php?user=\$1\$2 [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^/]+)/articles/?\$ \$server_info/articles.php?user=\$1 [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^article/([0-9]+)(/[^/]*)?$ \$server_info/article.php?article_id=\$1&article_slug=\$2 [L]";

// lang var = 11150000 - 11159999

if($install == "article") {

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
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_permission_article'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
					ADD COLUMN `setting_permission_article` int(1) NOT NULL default '0',
					ADD COLUMN `setting_article_license` varchar(255) NOT NULL default '',
					ADD COLUMN `setting_article_approval` int(1) NOT NULL default '0'");
    $database->database_query("UPDATE se_settings SET setting_permission_article='0', setting_article_approval='0', setting_article_license='XXXX-XXXX-XXXX-XXXX'");
  }
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_settings LIKE 'setting_email_articlecomment_subject'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
					ADD COLUMN `setting_email_articlecomment_subject` varchar(200) NOT NULL default '',
					ADD COLUMN `setting_email_articlecomment_message` text NULL,
					ADD COLUMN `setting_email_articlemediacomment_subject` varchar(200) NOT NULL default '',
					ADD COLUMN `setting_email_articlemediacomment_message` text NULL");
    $database->database_query("UPDATE se_settings SET setting_email_articlecomment_subject='New Article Comment', setting_email_articlecomment_message='Hello [username],\n\nA new comment has been posted by [commenter] about the article &quot;[articlename]&quot;. Please click the following link to view it:\n\n[link]\n\nBest Regards,\nSocial Network Administration', setting_email_articlemediacomment_subject='New Article Photo Comment', setting_email_articlemediacomment_message='Hello [username],\n\nA new comment has been posted by [commenter] on one of the photos in the article &quot;[articlename]&quot;. Please click the following link to view it:\n\n[link]\n\nBest Regards,\nSocial Network Administration'");
  }  
  
  //######### CREATE se_articles
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_articles'")) == 0) {
    $database->database_query("CREATE TABLE `se_articles` (
  `article_id` int(9) NOT NULL auto_increment,
  `article_user_id` int(9) NOT NULL default '0',
  `article_articlecat_id` int(9) NOT NULL default '0',
  `article_date_start` int(14) NOT NULL default '0',
  `article_dateupdated` int(14) NOT NULL default '0',
  `article_title` varchar(128) NOT NULL default '',
  `article_body` text,
  `article_views` int(9) NOT NULL default '0',
  `article_draft` int(1) NOT NULL default '0',
  `article_approved` int(1) NOT NULL default '0',
  `article_search` int(1) NOT NULL default '0',
  `article_privacy` int(1) NOT NULL default '0',
  `article_comments` int(1) NOT NULL default '0',
  `article_featured` int(1) NOT NULL default '0',
  `article_photo` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`article_id`),
  KEY `INDEX` (`article_user_id`)
    )");
  }

  //######### CREATE se_articlecats
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_articlecats'")) == 0) {
    $database->database_query("CREATE TABLE `se_articlecats` (
    `articlecat_id` int(9) NOT NULL auto_increment,
    `articlecat_dependency` int(9) NOT NULL default '0',
    `articlecat_title` varchar(100) NOT NULL default '',
    PRIMARY KEY  (`articlecat_id`)
    )");
  }



  //######### CREATE se_articlealbums
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_articlealbums'")) == 0) {
    $database->database_query("CREATE TABLE `se_articlealbums` (
    `articlealbum_id` int(9) NOT NULL auto_increment,
    `articlealbum_article_id` int(9) NOT NULL default '0',
    `articlealbum_datecreated` int(14) NOT NULL default '0',
    `articlealbum_dateupdated` int(14) NOT NULL default '0',
    `articlealbum_title` varchar(50) NOT NULL default '',
    `articlealbum_desc` text NULL,
    `articlealbum_search` int(1) NOT NULL default '0',
    `articlealbum_privacy` int(1) NOT NULL default '0',
    `articlealbum_comments` int(1) NOT NULL default '0',
    `articlealbum_cover` int(9) NOT NULL default '0',
    `articlealbum_views` int(9) NOT NULL default '0',
    PRIMARY KEY  (`articlealbum_id`),
    KEY `INDEX` (`articlealbum_article_id`)
    )");
  }
  


  //######### CREATE se_articlecomments
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_articlecomments'")) == 0) {
    $database->database_query("CREATE TABLE `se_articlecomments` (
    `articlecomment_id` int(9) NOT NULL auto_increment,
    `articlecomment_article_id` int(9) NOT NULL default '0',
    `articlecomment_authoruser_id` int(9) NOT NULL default '0',
    `articlecomment_date` int(14) NOT NULL default '0',
    `articlecomment_body` text NULL,
    PRIMARY KEY  (`articlecomment_id`),
    KEY `INDEX` (`articlecomment_article_id`,`articlecomment_authoruser_id`)
    )");
  }




  //######### CREATE se_articlemedia
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_articlemedia'")) == 0) {
    $database->database_query("CREATE TABLE `se_articlemedia` (
    `articlemedia_id` int(9) NOT NULL auto_increment,
    `articlemedia_articlealbum_id` int(9) NOT NULL default '0',
    `articlemedia_date` int(14) NOT NULL default '0',
    `articlemedia_title` varchar(50) NOT NULL default '',
    `articlemedia_desc` text NULL,
    `articlemedia_ext` varchar(8) NOT NULL default '',
    `articlemedia_filesize` int(9) NOT NULL default '0',
    PRIMARY KEY  (`articlemedia_id`),
    KEY `INDEX` (`articlemedia_articlealbum_id`)
    )");
  }




  //######### CREATE se_articlemediacomments
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_articlemediacomments'")) == 0) {
    $database->database_query("CREATE TABLE `se_articlemediacomments` (
    `articlemediacomment_id` int(9) NOT NULL auto_increment,
    `articlemediacomment_articlemedia_id` int(9) NOT NULL default '0',
    `articlemediacomment_authoruser_id` int(9) NOT NULL default '0',
    `articlemediacomment_date` int(14) NOT NULL default '0',
    `articlemediacomment_body` text NULL,
    PRIMARY KEY  (`articlemediacomment_id`),
    KEY `INDEX` (`articlemediacomment_articlemedia_id`,`articlemediacomment_authoruser_id`)
    )");
  }


  //######### ADD COLUMNS/VALUES TO LEVELS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_levels LIKE 'level_article_allow'")) == 0) {
    $database->database_query("ALTER TABLE se_levels 
					ADD COLUMN `level_article_allow` int(1) NOT NULL default '1',
					ADD COLUMN `level_article_photo` int(1) NOT NULL default '1',
					ADD COLUMN `level_article_photo_width` varchar(3) NOT NULL default '200',
					ADD COLUMN `level_article_photo_height` varchar(3) NOT NULL default '200',
					ADD COLUMN `level_article_photo_exts` varchar(50) NOT NULL default 'jpeg,jpg,gif,png',
					ADD COLUMN `level_article_inviteonly` int(1) NOT NULL default '0',
					ADD COLUMN `level_article_approved` int(1) NOT NULL default '0',
					ADD COLUMN `level_article_album_exts` text NULL,
					ADD COLUMN `level_article_album_mimes` text NULL,
					ADD COLUMN `level_article_album_storage` bigint(11) NOT NULL default '0',
					ADD COLUMN `level_article_album_maxsize` bigint(11) NOT NULL default '2048000',
					ADD COLUMN `level_article_album_width` varchar(4) NOT NULL default '500',
					ADD COLUMN `level_article_album_height` varchar(4) NOT NULL default '500',
					ADD COLUMN `level_article_search` int(1) NOT NULL default '1',
          ADD COLUMN `level_article_privacy` varchar(100) NOT NULL default 'a:6:{i:0;s:1:\"1\";i:1;s:1:\"3\";i:2;s:1:\"7\";i:3;s:2:\"15\";i:4;s:2:\"31\";i:5;s:2:\"63\";}',
          ADD COLUMN `level_article_comments` varchar(100) NOT NULL default 'a:7:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";}'");
    $database->database_query("UPDATE se_levels SET level_article_allow='1', level_article_photo='1', level_article_photo_width='200', level_article_photo_height='200', level_article_photo_exts='jpeg,jpg,gif,png', level_article_inviteonly='1', level_article_approved='1', level_article_album_exts='jpg,gif,jpeg,png,bmp,mp3,mpeg,avi,mpa,mov,qt,swf', level_article_album_mimes='image/jpeg,image/pjpeg,image/jpg,image/jpe,image/pjpg,image/x-jpeg,x-jpg,image/gif,image/x-gif,image/png,image/x-png,image/bmp,audio/mpeg,video/mpeg,video/x-msvideo,video/quicktime,application/x-shockwave-flash', level_article_album_storage='5242880', level_article_album_maxsize='2048000', level_article_album_width='500', level_article_album_height='500', level_article_search='1'");
  } else {
    $columns = $database->database_query("SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_article_privacy'");
    while($column_info = $database->database_fetch_assoc($columns)) {
      $field_name = $column_info['Field'];
      $field_type = $column_info['Type'];
      $field_default = $column_info['Default'];
      if($field_type == "varchar(10)") {
        $database->database_query("ALTER TABLE se_levels CHANGE level_article_privacy level_article_privacy varchar(100) NOT NULL default ''");
        $database->database_query("UPDATE se_levels SET level_article_privacy='a:6:{i:0;s:1:\"1\";i:1;s:1:\"3\";i:2;s:1:\"7\";i:3;s:2:\"15\";i:4;s:2:\"31\";i:5;s:2:\"63\";}'");
        $database->database_query("UPDATE se_articles SET article_privacy='63' WHERE article_privacy='0'");
        $database->database_query("UPDATE se_articles SET article_privacy='31' WHERE article_privacy='1'");
        $database->database_query("UPDATE se_articles SET article_privacy='15' WHERE article_privacy='2'");
        $database->database_query("UPDATE se_articles SET article_privacy='7' WHERE article_privacy='3'");
        $database->database_query("UPDATE se_articles SET article_privacy='3' WHERE article_privacy='4'");
        $database->database_query("UPDATE se_articles SET article_privacy='1' WHERE article_privacy='5'");
      }
    }
    $columns = $database->database_query("SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_article_comments'");
    while($column_info = $database->database_fetch_assoc($columns)) {
      $field_name = $column_info['Field'];
      $field_type = $column_info['Type'];
      $field_default = $column_info['Default'];
      if($field_type == "varchar(10)") {
        $database->database_query("ALTER TABLE se_levels CHANGE level_article_comments level_article_comments varchar(100) NOT NULL default ''");
        $database->database_query("UPDATE se_levels SET level_article_comments='a:7:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";}'");
        $database->database_query("UPDATE se_articles SET article_comments='63' WHERE article_comments='0'");
        $database->database_query("UPDATE se_articles SET article_comments='31' WHERE article_comments='1'");
        $database->database_query("UPDATE se_articles SET article_comments='15' WHERE article_comments='2'");
        $database->database_query("UPDATE se_articles SET article_comments='7' WHERE article_comments='3'");
        $database->database_query("UPDATE se_articles SET article_comments='3' WHERE article_comments='4'");
        $database->database_query("UPDATE se_articles SET article_comments='1' WHERE article_comments='5'");
        $database->database_query("UPDATE se_articles SET article_comments='0' WHERE article_comments='6'");
      }
    }
  }
  
  
  //######### ADD COLUMNS/VALUES TO USER SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_usersettings LIKE 'usersetting_notify_articlecomment'")) == 0) {
    $database->database_query("ALTER TABLE se_usersettings 
					ADD COLUMN `usersetting_notify_articlecomment` int(1) NOT NULL default '0',
					ADD COLUMN `usersetting_notify_articlemediacomment` int(1) NOT NULL default '0'");
    $database->database_query("UPDATE se_usersettings SET usersetting_notify_articlecomment='1', usersetting_notify_articlemediacomment='1'");
  }  
  
  $actiontype_name = 'newarticle';
  $res = $database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='$actiontype_name'");
  $actiontype_vars = '[username],[displayname],[id],[title],[publish_date]';
  if($database->database_num_rows($res) == 0) {
    $actiontype_desc = SE_Language::edit(0, 'Creating an Article', NULL, LANGUAGE_INDEX_ACTIONS);
    $actiontype_text = SE_Language::edit(0, '<a href="profile.php?user=%1$s">%2$s</a> posted a new article: <a href="article.php?article_id=%3$s">%4$s</a> to be published on %5$s', NULL, LANGUAGE_INDEX_ACTIONS);
    $database->database_query("INSERT INTO se_actiontypes (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media) VALUES ('$actiontype_name', 'action_newarticle.gif', '1', '1', '$actiontype_desc', '$actiontype_text', '$actiontype_vars', '0')");
    $actiontypes[] = $database->database_insert_id();
  }
  else {
    $actiontype = $database->database_fetch_assoc($res);
    if (!$actiontype[actiontype_desc]) {
      $actiontype_desc = SE_Language::edit(0, 'Creating an Article', NULL, LANGUAGE_INDEX_ACTIONS);
      $actiontype_text = SE_Language::edit(0, '<a href="profile.php?user=%1$s">%2$s</a> posted a new article: <a href="article.php?article_id=%3$s">%4$s</a> to be published on %5$s', NULL, LANGUAGE_INDEX_ACTIONS);
      $database->database_query("UPDATE se_actiontypes SET actiontype_setting='1', actiontype_desc='$actiontype_desc', actiontype_text='$actiontype_text', actiontype_vars='$actiontype_vars' WHERE actiontype_name='$actiontype_name'");
      $actiontypes[] = $actiontype[actiontype_id];
    }
  }
  
  $actiontype_name = 'articlecomment';
  $res = $database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='$actiontype_name'");
  $actiontype_vars = '[username],[displayname],[id],[title],[comment]';
  if($database->database_num_rows($res) == 0) {
    $actiontype_desc = SE_Language::edit(0, 'Posting an Article Comment', NULL, LANGUAGE_INDEX_ACTIONS);
    $actiontype_text = SE_Language::edit(0, '<a href="profile.php?user=%1$s">%2$s</a> posted a comment on article <a href="article.php?article_id=%3$s">%4$s</a>:<div class="recentaction_div">%5$s</div>', NULL, LANGUAGE_INDEX_ACTIONS);
    $database->database_query("INSERT INTO se_actiontypes (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media) VALUES ('$actiontype_name', 'action_postcomment.gif', '1', '1', '$actiontype_desc', '$actiontype_text', '$actiontype_vars', '0')");
    $actiontypes[] = $database->database_insert_id();
  }
  else {
    $actiontype = $database->database_fetch_assoc($res);
    if (!$actiontype[actiontype_desc]) {
      $actiontype_desc = SE_Language::edit(0, 'Posting an Article Comment', NULL, LANGUAGE_INDEX_ACTIONS);
      $actiontype_text = SE_Language::edit(0, '<a href="profile.php?user=%1$s">%2$s</a> posted a comment on article <a href="article.php?article_id=%3$s">%4$s</a>:<div class="recentaction_div">%5$s</div>', NULL, LANGUAGE_INDEX_ACTIONS);
      $database->database_query("UPDATE se_actiontypes SET actiontype_setting='1', actiontype_desc='$actiontype_desc', actiontype_text='$actiontype_text', actiontype_vars='$actiontype_vars' WHERE actiontype_name='$actiontype_name'");
      $actiontypes[] = $actiontype[actiontype_id];
    }
  }
  
  $actiontype_name = 'articleapprove';
  $res = $database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='$actiontype_name'");
  $actiontype_vars = '[username],[displayname],[id],[title],[publish_date]';
  if($database->database_num_rows($res) == 0) {
    $actiontype_desc = SE_Language::edit(0, 'Admin approved an Article', NULL, LANGUAGE_INDEX_ACTIONS);
    $actiontype_text = SE_Language::edit(0, '<a href="profile.php?user=%1$s">%2$s</a> has been approved for article <a href="article.php?article_id=%3$s">%4$s</a> published on %5$s', NULL, LANGUAGE_INDEX_ACTIONS);
    $database->database_query("INSERT INTO se_actiontypes (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media) VALUES ('$actiontype_name', 'action_newarticle.gif', '1', '1', '$actiontype_desc', '$actiontype_text', '$actiontype_vars', '0')");
    $actiontypes[] = $database->database_insert_id();
  }
  else {
    $actiontype = $database->database_fetch_assoc($res);
    if (!$actiontype[actiontype_desc]) {
      $actiontype_desc = SE_Language::edit(0, 'Admin approved an Article', NULL, LANGUAGE_INDEX_ACTIONS);
      $actiontype_text = SE_Language::edit(0, '<a href="profile.php?user=%1$s">%2$s</a> has been approved for article <a href="article.php?article_id=%3$s">%4$s</a> published on %5$s', NULL, LANGUAGE_INDEX_ACTIONS);
      $database->database_query("UPDATE se_actiontypes SET actiontype_setting='1', actiontype_desc='$actiontype_desc', actiontype_text='$actiontype_text', actiontype_vars='$actiontype_vars' WHERE actiontype_name='$actiontype_name'");
      $actiontypes[] = $actiontype[actiontype_id];
    }
  }

  $actiontype_name = 'articlemediacomment';
  $res = $database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='$actiontype_name'");
  $actiontype_vars = '[username],[displayname],[id],[title],[comment],[mediaid]';
  if($database->database_num_rows($res) == 0) {
    $actiontype_desc = SE_Language::edit(0, 'Posting an Article Photo Comment', NULL, LANGUAGE_INDEX_ACTIONS);
    $actiontype_text = SE_Language::edit(0, '<a href="profile.php?user=%1$s">%2$s</a> posted a comment on article <a href="article.php?article_id=%3$s">%4$s</a>\'s <a href="article_album_file.php?article_id=%3$s&articlemedia_id=%6$s">photo</a>:<div class="recentaction_div">%5$s</div>', NULL, LANGUAGE_INDEX_ACTIONS);
    $database->database_query("INSERT INTO se_actiontypes (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media) VALUES ('$actiontype_name', 'action_postcomment.gif', '1', '1', '$actiontype_desc', '$actiontype_text', '$actiontype_vars', '0')");
    $actiontypes[] = $database->database_insert_id();
  }
  else {
    $actiontype = $database->database_fetch_assoc($res);
    if (!$actiontype[actiontype_desc]) {
      $actiontype_desc = SE_Language::edit(0, 'Posting an Article Photo Comment', NULL, LANGUAGE_INDEX_ACTIONS);
      $actiontype_text = SE_Language::edit(0, '<a href="profile.php?user=%1$s">%2$s</a> posted a comment on article <a href="article.php?article_id=%3$s">%4$s</a>\'s <a href="article_album_file.php?article_id=%3$s&articlemedia_id=%6$s">photo</a>:<div class="recentaction_div">%5$s</div>', NULL, LANGUAGE_INDEX_ACTIONS);
      $database->database_query("UPDATE se_actiontypes SET actiontype_setting='1', actiontype_desc='$actiontype_desc', actiontype_text='$actiontype_text', actiontype_vars='$actiontype_vars' WHERE actiontype_name='$actiontype_name'");
      $actiontypes[] = $actiontype[actiontype_id];
    }
  }
  
  
  if(count($actiontypes) != 0) {
    $database->database_query("UPDATE se_usersettings SET usersetting_actions_display = CONCAT(usersetting_actions_display, ',', '".implode(",", $actiontypes)."')");
  }  
  
  //######### INSERT se_urls
  if($database->database_num_rows($database->database_query("SELECT url_id FROM se_urls WHERE url_file='articles'")) == 0) {
    $database->database_query("INSERT INTO se_urls (url_title, url_file, url_regular, url_subdirectory) VALUES ('User Articles', 'articles', 'articles.php?user=\$user', '\$user/articles/')");
  }  
  

  
  //######### CREATE tag
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_articletags'")) == 0) {
    $database->database_query("CREATE TABLE `se_articletags` (
      `tag_id` int(14) NOT NULL auto_increment,
      `tag_object_id` int(14) NOT NULL default '0',
      `tag_name` varchar(128) NOT NULL default '',
      PRIMARY KEY  (`tag_id`),
      UNIQUE KEY `objectname` (`tag_object_id`,`tag_name`),
      KEY `object` (`tag_object_id`),
      KEY `name` (`tag_name`)
    )");
  }
  
  if($database->database_num_rows($database->database_query("SELECT url_id FROM se_urls WHERE url_file='article'")) == 0) {
    $database->database_query("INSERT INTO se_urls (url_title, url_file, url_regular, url_subdirectory) VALUES ('Article', 'article', 'article.php?article_id=\$id1', 'article/\$id1/\$id2')");
  } 
  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM ".$database_name.".se_articles LIKE 'article_slug'")) == 0) {
    $database->database_query("ALTER TABLE se_articles 
          ADD COLUMN `article_slug` varchar(128) NOT NULL default ''
          ");
    $aids = array();
    $res = $database->database_query("SELECT * FROM se_articles");
    while ($row = $database->database_fetch_assoc($res)) {
    	$aids[] = $aid = $row['article_id'];

		$text = strtolower($row['article_title']);
		$text = preg_replace('/\W/', ' ', $text);
		$text = preg_replace('/\ +/', '-', $text);
		$text = preg_replace('/\-$/', '', $text);
		$text = preg_replace('/^\-/', '', $text);
		$slug = $text;

    	$database->database_query("UPDATE se_articles SET article_slug='$slug' WHERE article_id='$aid'");
    }
    
    $aids_str = join("','", $aids);
    $database->database_query("DELETE FROM se_articletags WHERE tag_object_id NOT IN ('$aids_str')");
  } 
  

  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11150001 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES 
          (11150001, 1, 'Article Plugin', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11150002, 1, 'Article Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11150004, 1, 'View Articles', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, '),
          (11150003, 1, 'Article Settings', 'admin_viewusers_edit, admin_viewusers, admin_viewreports, admin_viewplugins, admin_viewadmins, admin_url, admin_templates, admin_subnetworks, admin_stats, admin_signup, admin_profile, admin_lostpass_reset, admin_lostpass, admin_login, admin_log, admin_levels_usersettings, admin_levels_messagesettings, admin_levels_edit, admin_levels_albumsettings, admin_levels, admin_language_edit, admin_language, admin_invite, admin_home, admin_general, admin_fields, admin_faq, admin_emails, admin_connections, admin_banning, admin_announcements, admin_ads_modify, admin_ads, admin_activity, ')");
  } 

  if($database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=11150101 LIMIT 1")) == 0) {
    $database->database_query("INSERT INTO `se_languagevars` (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`) VALUES 
('11150101', 1, 'Articles', ''),
('11150102', 1, 'My Articles', ''),
('11150201', 1, 'General Article Settings', ''),
('11150202', 1, 'This page contains general article settings that affect your entire social network.', ''),
('11150203', 1, 'Your changes have been saved.', ''),
('11150204', 1, 'Subject', ''),
('11150205', 1, 'Message', ''),
('11150209', 1, 'Save Changes', ''),
('11150210', 1, 'Public Permission Defaults', ''),
('11150211', 1, 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the <a href=\'admin_general.php\'>General Settings</a> page.', ''),
('11150212', 1, 'Articles', ''),
('11150213', 1, 'Yes, the public can view articles unless they are made private.', ''),
('11150214', 1, 'No, the public cannot view articles.', ''),
('11150215', 1, 'New Article Comment Email', ''),
('11150216', 1, 'This is the email that gets sent to a user when a new comment is posted about an article they have created. For more system emails, please visit the <a href=\'admin_emails.php\'>System Emails</a> page.', ''),
('11150217', 1, '[username] - replaced with the username of the recepient.<br>[commenter] - replaced with the name of the user who posted the comment.<br>[articlename] - replaced with the name of the article.<br>[link] - replaced with the link to the article.', ''),
('11150218', 1, 'New Article Media Comment Email', ''),
('11150219', 1, 'This is the email that gets sent to a user when a new comment is posted on one of the photos/files for an article they have created. For more system emails, please visit the <a href=\'admin_emails.php\'>System Emails</a> page.', ''),
('11150220', 1, '[username] - replaced with the username of the recepient.<br>[commenter] - replaced with the name of the user who posted the comment.<br>[articlename] - replaced with the name of the article.<br>[link] - replaced with the link to the photo.', ''),
('11150221', 1, 'Article Categories', ''),
('11150222', 1, 'You may want to allow your users to categorize their articles by subject, location, etc. Categorized articles make it easier for users to find and attend articles that interest them. If you want to allow article categories, you can create them (along with subcategories) below.', ''),
('11150223', 1, 'Add Category', ''),
('11150224', 1, 'Add Subcategory', ''),
('11150225', 1, 'License Key', ''),
('11150226', 1, 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.', ''),
('11150227', 1, 'Format: XXXX-XXXX-XXXX-XXXX', ''),
('11150301', 1, 'Article Settings', ''),
('11150302', 1, 'If you have enabled articles, your users will have the option of creating articles. Use this page to configure your article settings.', ''),
('11150303', 1, 'Allow Articles?', ''),
('11150304', 1, 'If you have selected YES, your users will have the option of creating articles. Note that if you switch this from YES to NO, users will lose any current articles they have.', ''),
('11150305', 1, 'Yes, users can create articles.', ''),
('11150306', 1, 'No, users cannot create articles.', ''),
('11150307', 1, 'Save Changes', ''),
('11150308', 1, 'Your changes have been saved.', ''),
('11150309', 1, 'Allow Article Photos?', ''),
('11150310', 1, 'If you enable this feature, users will be able to upload a small photo icon when creating or editing an article. This can be displayed next to the article name.', ''),
('11150311', 1, 'Yes, users can upload a photo icon when they create/edit an article.', ''),
('11150312', 1, 'No, users can not upload a photo icon when they create/edit an article.', ''),
('11150313', 1, 'If you have selected YES above, please input the maximum dimensions for the article photos. If your users upload a photo that is larger than these dimensions, the server will attempt to scale them down automatically. This feature requires that your PHP server is compiled with support for the GD Libraries.', ''),
('11150314', 1, 'Maximum Width:', ''),
('11150315', 1, '(in pixels, between 1 and 999)', ''),
('11150316', 1, 'Maximum Height:', ''),
('11150317', 1, 'What file types do you want to allow for article photos (gif, jpg, jpeg, or png)? Separate file types with commas, i.e. jpg, jpeg, gif, png', ''),
('11150318', 1, 'Allowed File Types:', ''),
('11150319', 1, '<b>Search Privacy Options</b><br>If you enable this feature, article leaders will be able to exclude their article from search results. Otherwise, all articles will be included in search results.', ''),
('11150320', 1, 'Yes, allow article to exclude their articles from search results.', ''),
('11150321', 1, 'No, force all articles to be included in search results.', ''),
('11150322', 1, 'Article Privacy Options', ''),
('11150323', 1, 'Editing User Level:', ''),
('11150324', 1, 'You are currently editing this user level\'s settings. Remember, these settings only apply to the users that belong to this user level. When you\'re finished, you can edit the <a href=\'admin_levels.php\'>other levels here</a>.', ''),
('11150325', 1, '<b>Overall Article Privacy</b><br>Article Creators can choose from any of the options checked below when they decide who can view their articles. If you do not check any options, everyone will be allowed to view articles.', ''),
('11150326', 1, '<b>Article Comment Options</b><br>Article Creators can choose from any of the options checked below when they decide who can post comments on their articles. If you do not check any options, everyone will be allowed to post comments on articles.', ''),
('11150327', 1, 'Allow Invite-Only Articles?', ''),
('11150328', 1, 'Do you want to give article creators the ability to create invite-only articles? If set to YES, article creators will be able to set articles to \"invite-only\". This means only invited users will be able to RSVP to the article and un-invited users will need to request an invitation', ''),
('11150329', 1, 'Yes, optionally allow invite-only articles.', ''),
('11150330', 1, 'No, do not allow invite-only articles.', ''),
('11150331', 1, 'Require article approval?', ''),
('11150332', 1, 'If you enable this feature, Admin will need to manually approve article before its published.', ''),
('11150333', 1, 'Yes, require admin approval on submition.', ''),
('11150334', 1, 'No, admin does not have to approve article.', ''),
('11150335', 1, 'Article File Settings', ''),
('11150336', 1, 'List the following file extensions that users are allowed to upload. Separate file extensions with commas, for example: jpg, gif, jpeg, png, bmp', ''),
('11150337', 1, 'To successfully upload a file, the file must have an allowed filetype extension as well as an allowed MIME type. This prarticles users from disguising malicious files with a fake extension. Separate MIME types with commas, for example: image/jpeg, image/gif, image/png, image/bmp', ''),
('11150338', 1, 'How much storage space should each article have to store its files?', ''),
('11150339', 1, 'Unlimited', ''),
('11150340', 1, 'Enter the maximum filesize for uploaded files in KB. This must be a number between 1 and 204800.', ''),
('11150341', 1, 'Enter the maximum width and height (in pixels) for images uploaded to articles. Images with dimensions outside this range will be sized down accordingly if your server has the GD Libraries installed. Note that unusual image types like BMP, TIFF, RAW, and others may not be resized.', ''),
('11150342', 1, 'Maximum Width:', ''),
('11150343', 1, 'Maximum Height:', ''),
('11150344', 1, '(in pixels, between 1 and 9999)', ''),
('11150348', 1, 'Photo width and height must be integers between 1 and 999.', ''),
('11150349', 1, 'Your maximum filesize field must contain an integer between 1 and 204800.', ''),
('11150350', 1, 'Your maximum width and height fields must contain integers between 1 and 9999.', ''),
('11150401', 1, 'View Articles', ''),
('11150402', 1, 'Article Settings', ''),
('11150403', 1, 'View Articles', ''),
('11150404', 1, 'Use this page to search for and manage article entries.  To <strong>Approve</strong> or <strong>Feature</strong> an article, just click on the icon, it will automate turn on and off per that setting. To edit, delete, or manage an article, please <strong>login</strong> as that user, and perform your actions.', ''),
('11150405', 1, 'Options', ''),
('11150406', 1, 'Yes', ''),
('11150407', 1, 'No', ''),
('11150408', 1, 'Search articles for:', ''),
('11150409', 1, 'Last Page', ''),
('11150410', 1, 'viewing entry', ''),
('11150411', 1, 'of', ''),
('11150412', 1, 'viewing entries', ''),
('11150413', 1, 'Next Page', ''),
('11150414', 1, 'No article entries were found.', ''),
('11150415', 1, 'There are no article entries.', ''),
('11150416', 1, 'Click here', ''),
('11150417', 1, 'to write one.', ''),
('11150418', 1, 'Publish', ''),
('11150419', 1, 'Title', ''),
('11150420', 1, 'Comments', ''),
('11150421', 1, 'Untitled', ''),
('11150422', 1, 'comments', ''),
('11150423', 1, 'edit', ''),
('11150424', 1, 'delete', ''),
('11150425', 1, 'Delete Selected', ''),
('11150426', 1, 'Search', ''),
('11150427', 1, 'Author', ''),
('11150428', 1, 'Draft', ''),
('11150429', 1, 'Approved', ''),
('11150430', 1, 'Featured', ''),
('11150431', 1, 'not yet publish', ''),
('11150432', 1, 'Category', ''),
('11150433', 1, 'Uncategorized', ''),
('11150501', 1, 'You do not have permission to view this article.', ''),
('11150502', 1, 'This article has not been approved by administrator.', ''),
('11150503', 1, 'This article has not been published yet.', ''),
('11150504', 1, 'Attend this Article', ''),
('11150505', 1, 'Article Statistics', ''),
('11150506', 1, 'by', ''),
('11150507', 1, 'Views:', ''),
('11150508', 1, 'views', ''),
('11150511', 1, 'Photos:', ''),
('11150512', 1, 'photos', ''),
('11150513', 1, 'Last Update:', ''),
('11150517', 1, 'Category:', ''),
('11150518', 1, 'All Articles', ''),
('11150520', 1, 'Published on', ''),
('11150521', 1, 'Photos', ''),
('11150522', 1, 'view all photos', ''),
('11150523', 1, 'Comments', ''),
('11150524', 1, 'Post Comment', ''),
('11150525', 1, 'view all comments', ''),
('11150526', 1, 'Anonymous', ''),
('11150527', 1, 'message', ''),
('11150528', 1, 'An Error Has Occurred', ''),
('11150529', 1, 'Browse Photos', ''),
('11150530', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11150531', 1, 'Write Something...', ''),
('11150532', 1, 'Posting...', ''),
('11150533', 1, 'Please enter a message.', ''),
('11150534', 1, 'You have entered the wrong security code.', ''),
('11150535', 1, '\\o\\n', ''),
('11150536', 1, 'Enter the numbers you see in this image into the field to its right. This helps keep our site free of spam.', ''),
('11150538', 1, 'Report this Article', ''),
('11150539', 1, 'Return', ''),
('11150540', 1, 'reply', ''),
('11150541', 1, 'The article you are looking for has been deleted or does not exist.', ''),
('11150542', 1, 'My Articles', ''),
('11150543', 1, 'Article Settings', ''),
('11150544', 1, 'Browse Articles', ''),
('11150554', 1, 'Browse Aricles', ''),
('11150555', 1, 'Tags:', ''),
('11150601', 1, 'My Articles', ''),
('11150602', 1, 'Articles', ''),
('11150603', 1, 'Article Settings', ''),
('11150604', 1, 'Browse Articles', ''),
('11150605', 1, 'Select a category below on the right to view articles within that category.', ''),
('11150606', 1, 'Article Categories', ''),
('11150607', 1, 'articles', ''),
('11150608', 1, 'article', ''),
('11150609', 1, 'Uncategorized', ''),
('11150610', 1, 'No articles were found in this category.', ''),
('11150611', 1, 'Published on', ''),
('11150612', 1, 'All Articles', ''),
('11150613', 1, 'by', ''),
('11150614', 1, 'View Article', ''),
('11150615', 1, 'Tags:', ''),
('11150616', 1, 'Last Page', ''),
('11150617', 1, 'viewing article', ''),
('11150618', 1, 'of', ''),
('11150619', 1, 'viewing articles', ''),
('11150620', 1, 'Next Page', ''),
('11150621', 1, 'Article Settings', ''),
('11150622', 1, '&#0102;&#0114;&#0111;&#0109;', ''),
('11150623', 1, 'until', ''),
('11150624', 1, 'Sort by:', ''),
('11150625', 1, 'Publish Date', ''),
('11150626', 1, 'Popularity', ''),
('11150627', 1, 'Article Title', ''),
('11150628', 1, 'Article Author', ''),
('11150629', 1, 'GO', ''),
('11150630', 1, 'Keywords:', ''),
('11150631', 1, 'Featured Article:', ''),
('11150632', 1, 'Tag:', ''),
('11150633', 1, 'comment(s)', ''),
('11150634', 1, 'Popular Tags', ''),
('11150636', 1, 'An Error Has Occurred', ''),
('11150637', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11150638', 1, 'Return', ''),
('11150801', 1, 'You do not have permission to view this album.', ''),
('11150802', 1, '\'s Photos', ''),
('11150803', 1, 'An Error Has Occurred', ''),
('11150804', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11150805', 1, 'Last Page', ''),
('11150806', 1, 'viewing image', ''),
('11150807', 1, 'of', ''),
('11150808', 1, 'viewing images', ''),
('11150809', 1, 'Next Page', ''),
('11150810', 1, 'Return', ''),
('11150901', 1, 'You do not have permission to view this file.', ''),
('11150902', 1, '\'s Photo', ''),
('11150903', 1, 'Untitled', ''),
('11150904', 1, 'photos', ''),
('11150905', 1, 'download audio', ''),
('11150906', 1, 'download video', ''),
('11150907', 1, 'download this file', ''),
('11150908', 1, 'Return to', ''),
('11150909', 1, '\'s Album', ''),
('11150910', 1, 'Post Comment', ''),
('11150911', 1, 'Report Inappropriate Content', ''),
('11150912', 1, 'Comments', ''),
('11150913', 1, 'on', ''),
('11150914', 1, 'Anonymous', ''),
('11150915', 1, 'An Error Has Occurred', ''),
('11150916', 1, 'By', ''),
('11150917', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11150918', 1, 'Write Something...', ''),
('11150919', 1, 'Posting...', ''),
('11150920', 1, 'Please enter a message.', ''),
('11150921', 1, 'You have entered the wrong security code.', ''),
('11150922', 1, '\\o\\n', ''),
('11150923', 1, 'message', ''),
('11150924', 1, 'Enter the numbers you see in this image into the field to its right. This helps keep our site free of spam.', ''),
('11150925', 1, 'Return', ''),
('11151001', 1, 'message', ''),
('11151002', 1, '\\o\\n', ''),
('11151003', 1, 'Comments About', ''),
('11151005', 1, 'Back to', ''),
('11151007', 1, 'Last Page', ''),
('11151008', 1, 'showing comment', ''),
('11151009', 1, 'of', ''),
('11151010', 1, 'showing comments', ''),
('11151011', 1, 'Next Page', ''),
('11151012', 1, 'Anonymous', ''),
('11151014', 1, 'Write Something...', ''),
('11151015', 1, 'Posting...', ''),
('11151016', 1, 'Please enter a message.', ''),
('11151017', 1, 'You have entered the wrong security code.', ''),
('11151018', 1, 'Post Comment', ''),
('11151019', 1, 'Enter the numbers you see in this image into the field to its right. This helps keep our site free of spam.', ''),
('11151020', 1, 'An Error Has Occurred', ''),
('11151022', 1, 'You must be logged in to view this page. <a href=\'login.php\'>Click here</a> to login.', ''),
('11151023', 1, 'Return', ''),
('11151201', 1, 'My Articles', ''),
('11151202', 1, 'Article Settings', ''),
('11151203', 1, 'My Articles', ''),
('11151204', 1, 'Use this page to search for and manage article entries that you have already written.', ''),
('11151205', 1, 'Add New Article', ''),
('11151206', 1, 'Search Articles', ''),
('11151207', 1, 'Browse Articles', ''),
('11151208', 1, 'Search articles for:', ''),
('11151209', 1, 'Last Page', ''),
('11151210', 1, 'viewing entry', ''),
('11151211', 1, 'of', ''),
('11151212', 1, 'viewing entries', ''),
('11151213', 1, 'Next Page', ''),
('11151214', 1, 'No article entries were found.', ''),
('11151215', 1, 'You do not have any article entries.', ''),
('11151216', 1, 'Click here', ''),
('11151217', 1, 'to write one.', ''),
('11151218', 1, 'Publish', ''),
('11151219', 1, 'Title', ''),
('11151220', 1, 'Comments', ''),
('11151221', 1, 'Untitled', ''),
('11151222', 1, 'comments', ''),
('11151223', 1, 'edit', ''),
('11151224', 1, 'delete', ''),
('11151225', 1, 'Delete Selected', ''),
('11151226', 1, 'Search', ''),
('11151227', 1, 'Options', ''),
('11151228', 1, 'Draft', ''),
('11151229', 1, 'Approved', ''),
('11151230', 1, 'Featured', ''),
('11151231', 1, 'not yet publish', ''),
('11151232', 1, 'Category', ''),
('11151233', 1, 'Uncategorized', ''),
('11151301', 1, 'Please enter a title for your article.', ''),
('11151302', 1, 'Article Details', ''),
('11151303', 1, 'Please complete all required fields.', ''),
('11151304', 1, 'at', ''),
('11151305', 1, 'My Articles', ''),
('11151307', 1, 'Browse Articles', ''),
('11151308', 1, 'Create New Article', ''),
('11151309', 1, 'Write your new article below, then click &quot;Post Article&quot; to publish your article.', ''),
('11151310', 1, 'Article Title', ''),
('11151311', 1, 'Article Body', ''),
('11151312', 1, 'Article Category', ''),
('11151313', 1, 'Article Settings', ''),
('11151318', 1, 'Include this article in search/browse results?', ''),
('11151319', 1, 'Yes, include this article in search/browse results.', ''),
('11151320', 1, 'No, exclude this article from search/browse results.', ''),
('11151321', 1, 'Who can see this article?', ''),
('11151322', 1, 'You can decide who gets to see this article.', ''),
('11151323', 1, 'Allow Comments?', ''),
('11151324', 1, 'You can decide who can post comments in this article.', ''),
('11151325', 1, 'Publish Article', ''),
('11151326', 1, 'Cancel', ''),
('11151327', 1, 'Article Settings', ''),
('11151328', 1, 'Publish Time', ''),
('11151330', 1, 'Invalid end time - make sure your article\'s end time is after its start time.', ''),
('11151331', 1, 'Invalid start time - make sure your article\'s start time is in the future.', ''),
('11151332', 1, 'Draft:', ''),
('11151333', 1, 'Tick box to save this article as a draft', ''),
('11151334', 1, 'Save as Draft', ''),
('11151335', 1, 'Tags:', ''),
('11151336', 1, 'Seperate each tag with a comma. Ex: tata, tutu, toto', ''),
('11151401', 1, 'Photos must be less than 4MB in size', ''),
('11151402', 1, 'Photos must have one of the following extensions:', ''),
('11151403', 1, 'Photos must be less than or equal to the following dimensions:', ''),
('11151404', 1, 'pixels', ''),
('11151405', 1, 'Your photo failed to upload. Please try again. If this error persists, please contact support.', ''),
('11151406', 1, 'Please complete all required fields.', ''),
('11151408', 1, 'Please enter a name for your article.', ''),
('11151409', 1, 'Your changes have been saved.', ''),
('11151410', 1, 'Article Details', ''),
('11151412', 1, 'at', ''),
('11151413', 1, 'Photos', ''),
('11151414', 1, 'Comments', ''),
('11151416', 1, 'Delete Article', ''),
('11151417', 1, 'Edit Article:', ''),
('11151418', 1, 'All of this article\'s details are displayed and can be changed below.', ''),
('11151419', 1, 'Your article was successfully created! You can add a photo and edit the article details below.', ''),
('11151420', 1, 'Article Photo', ''),
('11151421', 1, 'Current Photo', ''),
('11151422', 1, 'remove photo', ''),
('11151423', 1, 'Upload Photo', ''),
('11151424', 1, 'Upload', ''),
('11151425', 1, 'Images must be less than 4 MB in size with one of the following extensions:', ''),
('11151426', 1, 'Article Details', ''),
('11151427', 1, 'Article Title', ''),
('11151428', 1, 'Article Body', ''),
('11151429', 1, 'Article Category', ''),
('11151430', 1, 'Article Settings', ''),
('11151436', 1, 'Include this article in search/browse results?', ''),
('11151437', 1, 'Yes, include this article in search/browse results.', ''),
('11151438', 1, 'No, exclude this article from search/browse results.', ''),
('11151439', 1, 'Who can see this article?', ''),
('11151440', 1, 'You can decide who gets to see this article.', ''),
('11151441', 1, 'Allow Comments?', ''),
('11151442', 1, 'You can decide who can post comments in this article.', ''),
('11151443', 1, 'Save Changes', ''),
('11151445', 1, 'Cancel', ''),
('11151446', 1, '&#171; Back to My Articles', ''),
('11151447', 1, 'Publish Time', ''),
('11151449', 1, 'Invalid end time - make sure your article\'s end time is after its start time.', ''),
('11151450', 1, 'Invalid start time - make sure your article\'s start time is in the future.', ''),
('11151451', 1, 'Draft:', ''),
('11151452', 1, 'Tick box to save this article as a draft', ''),
('11151453', 1, 'Publish Article', ''),
('11151454', 1, 'Save as Draft', ''),
('11151455', 1, 'Tags:', ''),
('11151456', 1, 'Seperate each tag with a comma. Ex: tata, tutu, toto', ''),
('11151501', 1, 'Article Details', ''),
('11151504', 1, 'Photos', ''),
('11151505', 1, 'Comments', ''),
('11151507', 1, 'Delete Article', ''),
('11151508', 1, 'Back to My Articles', ''),
('11151509', 1, 'Article Comments:', ''),
('11151510', 1, 'The comments below have been written about this article by other people. To delete comments, click their checkboxes and then click the \"Delete Selected\" button below the comment list.', ''),
('11151511', 1, 'Last Page', ''),
('11151512', 1, 'comment', ''),
('11151513', 1, 'of', ''),
('11151514', 1, 'comments', ''),
('11151515', 1, 'Next Page', ''),
('11151516', 1, 'No comments have been posted about this article.', ''),
('11151517', 1, 'Anonymous', ''),
('11151518', 1, 'Delete Selected', ''),
('11151519', 1, '\\o\\n', ''),
('11151520', 1, 'select all comments', ''),
('11151601', 1, 'Article Details', ''),
('11151604', 1, 'Photos', ''),
('11151605', 1, 'Comments', ''),
('11151607', 1, 'Delete Article', ''),
('11151608', 1, 'Back to My Articles', ''),
('11151609', 1, 'Delete Article:', ''),
('11151610', 1, 'Are you sure you want to delete this article? All of associated records will be permanently deleted also.', ''),
('11151611', 1, 'Delete Article', ''),
('11151612', 1, 'Do Not Delete', ''),
('11151701', 1, 'Your changes have been saved.', ''),
('11151702', 1, 'Article Details', ''),
('11151705', 1, 'Photos', ''),
('11151706', 1, 'Comments', ''),
('11151708', 1, 'Delete Article', ''),
('11151709', 1, 'Back to My Articles', ''),
('11151710', 1, 'Article Photos:', ''),
('11151711', 1, 'Manage this article\'s photos and files from this page.<br>Total files in this album: ', ''),
('11151712', 1, 'Add New Photos', ''),
('11151713', 1, 'Title', ''),
('11151714', 1, 'comments', ''),
('11151715', 1, 'Caption', ''),
('11151716', 1, 'Delete Photo', ''),
('11151717', 1, 'Save Changes', ''),
('11151801', 1, 'Article Details', ''),
('11151804', 1, 'Photos', ''),
('11151805', 1, 'Comments', ''),
('11151807', 1, 'Delete Article', ''),
('11151808', 1, 'Back to My Articles', ''),
('11151809', 1, 'Photo Comments', ''),
('11151810', 1, 'To delete comments, click their checkboxes and then click the \"Delete Selected\" button below the comment list.', ''),
('11151811', 1, 'Last Page', ''),
('11151812', 1, 'comment', ''),
('11151813', 1, 'of', ''),
('11151814', 1, 'comments', ''),
('11151815', 1, 'Next Page', ''),
('11151816', 1, 'No comments have been posted about this photo.', ''),
('11151818', 1, 'Delete Selected', ''),
('11151819', 1, '\\o\\n', ''),
('11151820', 1, 'select all comments', ''),
('11151821', 1, 'Back to Photos', ''),
('11151901', 1, 'Article Details', ''),
('11151904', 1, 'Photos', ''),
('11151905', 1, 'Comments', ''),
('11151907', 1, 'Delete Article', ''),
('11151908', 1, 'Back to My Articles', ''),
('11151909', 1, 'Upload Photos:', ''),
('11151910', 1, 'To upload photos from your computer, click the \"Browse\" button, locate them on your computer, then click the \"Upload Photos\" button.', ''),
('11151911', 1, 'Back to Photos', ''),
('11151912', 1, 'You have', ''),
('11151913', 1, 'of free space remaining.', ''),
('11151914', 1, 'You may upload files of the following types:', ''),
('11151915', 1, 'You may upload files with sizes up to', ''),
('11151916', 1, 'File 1:', ''),
('11151917', 1, 'File 2:', ''),
('11151918', 1, 'File 3:', ''),
('11151919', 1, 'File 4:', ''),
('11151920', 1, 'File 5:', ''),
('11151921', 1, 'Upload Photos', ''),
('11151922', 1, 'UPLOADING', ''),
('11151923', 1, 'was uploaded successfully.', ''),
('11152001', 1, 'My Articles', ''),
('11152003', 1, 'Browse Articles', ''),
('11152004', 1, 'Article Settings', ''),
('11152005', 1, 'Article Settings', ''),
('11152006', 1, 'Edit your article settings such as email notifications on this page.', ''),
('11152007', 1, 'Your changes have been saved.', ''),
('11152008', 1, 'Save Changes', ''),
('11152009', 1, 'Article Notifications', ''),
('11152010', 1, 'Check the boxes next to the email notifications you would like to receive.', ''),
('11152012', 1, 'Notify me when someone writes a comment on an article I created.', ''),
('11152013', 1, 'Notify me when someone writes a comment on one of the photos in an article I created.', '')
      ") or die("Insert Into se_languagevars: ".mysql_error());
  }
    
  
}
