<?php

/* $Id: install_chat.php 17 2009-01-13 04:54:06Z john $ */

$plugin_name = "Chat Plugin";
$plugin_version = "3.03";
$plugin_type = "chat";
$plugin_desc = "This plugin installs a live chatroom on your social network and adds a link to it on your users\' menu bar. Adding a chatroom is a great way to encourage members to interact more closely and form new connections.";
$plugin_icon = "chat_chat16.gif";
$plugin_menu_title = "3500001";	
$plugin_pages_main = "3500002<!>chat_chat16.gif<!>admin_chat.php<~!~>";
$plugin_pages_level = "3500003<!>admin_levels_chatsettings.php<~!~>";
$plugin_url_htaccess = "";
$plugin_db_charset = 'utf8';
$plugin_db_collation = 'utf8_unicode_ci';


if( $install=="chat" )
{
  // ######### INSERT ROW INTO se_plugins
  $sql = "SELECT plugin_id FROM `$database_name`.`se_plugins` WHERE plugin_type='$plugin_type'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO
        `$database_name`.`se_plugins`
      (
        plugin_name,
        plugin_version,
        plugin_type,
        plugin_desc,
        plugin_icon,
        plugin_menu_title,
        plugin_pages_main,
        plugin_pages_level,
        plugin_url_htaccess
      )
      VALUES
      (
        '$plugin_name',
        '$plugin_version',
        '$plugin_type',
        '$plugin_desc',
        '$plugin_icon',
        '$plugin_menu_title',
        '$plugin_pages_main',
        '$plugin_pages_level',
        '$plugin_url_htaccess'
      )
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }

  // ######### UPDATE PLUGIN VERSION IN se_plugins
  else
  {
    $sql = "
      UPDATE
        `$database_name`.`se_plugins`
      SET
        plugin_name='$plugin_name',
        plugin_version='$plugin_version',
        plugin_desc='$plugin_desc',
        plugin_icon='$plugin_icon',
        plugin_menu_title='$plugin_menu_title',
        plugin_pages_main='$plugin_pages_main',
        plugin_pages_level='$plugin_pages_level',
        plugin_url_htaccess='$plugin_url_htaccess'
      WHERE
        plugin_type='$plugin_type'
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  
  
  
  /* ----------------------------------------------------------------------- *\
  |                                                                           |
  | seIM - IM                                                               |
  |                                                                           |
  \* ----------------------------------------------------------------------- */
  
  // ######### CREATE se_chat_bans
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_chat_bans'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( $database->database_num_rows($resource)==0 )
  {
    $sql = "
      CREATE TABLE
        `$database_name`.`se_chat_bans`
      (
        `chat_ban_id`         INT UNSIGNED  NOT NULL auto_increment,
        `chat_ban_user_id`    INT UNSIGNED  NOT NULL,
        `chat_ban_mask_id`    INT UNSIGNED      NULL,
        `chat_ban_start`      INT SIGNED    NOT NULL default '0',
        `chat_ban_end`        INT SIGNED        NULL,
        PRIMARY KEY  (`chat_ban_id`),
        KEY `INDEX` (`chat_ban_user_id`)
      )
      TYPE=InnoDB CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  
  
  // ######### CREATE se_chat_masks
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_chat_masks'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( $database->database_num_rows($resource)==0 )
  {
    $sql = "
      CREATE TABLE
        `$database_name`.`se_chat_masks`
      (
        `chat_mask_id`          INT         UNSIGNED  NOT NULL auto_increment,
        `chat_mask_type`        TINYINT     UNSIGNED      NULL,
        `chat_mask_public`      TINYINT     UNSIGNED  NOT NULL default 0,
        `chat_mask_title`       VARCHAR(64)               NULL,
        `chat_mask_code`        VARCHAR(8)                NULL,
        
        `chat_mask_status`      TINYINT     UNSIGNED  NOT NULL default 0,
        `chat_mask_lastupdate`  INT         SIGNED    NOT NULL default 0,
        
        PRIMARY KEY  (`chat_mask_id`),
        KEY `UPDATE`  (`chat_mask_status`, `chat_mask_lastupdate`)
      )
      TYPE=InnoDB CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  // Ensure utf8 on chat_mask_title
  $sql = "SHOW FULL COLUMNS FROM `se_chat_masks` LIKE 'chat_mask_title'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  $result = $database->database_fetch_assoc($resource);

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_chat_masks MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  
  
  // ######### CREATE se_chat_mask_users
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_chat_mask_users'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( $database->database_num_rows($resource)==0 )
  {
    $sql = "
      CREATE TABLE
        `$database_name`.`se_chat_mask_users`
      (
        `chat_mask_user_id`           INT     UNSIGNED  NOT NULL auto_increment,
        `chat_mask_user_mask_id`      INT     UNSIGNED  NOT NULL,
        `chat_mask_user_user_id`      INT     UNSIGNED  NOT NULL,
        `chat_mask_user_level`        INT     UNSIGNED  NOT NULL default 0,
        
        `chat_mask_user_status`       TINYINT UNSIGNED  NOT NULL default 0,
        `chat_mask_user_lastupdate`   INT     SIGNED    NOT NULL default 0,
        
        PRIMARY KEY   (`chat_mask_user_id`),
        KEY `INDEX`   (`chat_mask_user_mask_id`, `chat_mask_user_user_id`),
        KEY `UPDATE`  (`chat_mask_user_status`, `chat_mask_user_lastupdate`)
      )
      TYPE=InnoDB CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  
  
  // ######### CREATE se_chat_messages
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_chat_messages'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( $database->database_num_rows($resource)==0 )
  {
    $sql = "
      CREATE TABLE
        `$database_name`.`se_chat_messages`
      (
        `chat_message_id`         INT UNSIGNED  NOT NULL auto_increment,
        `chat_message_mask_id`    INT UNSIGNED  NOT NULL,
        `chat_message_user_id`    INT UNSIGNED  NOT NULL,
        `chat_message_content`    VARCHAR(255)  NOT NULL,
        
        `chat_message_time`       INT SIGNED    NOT NULL,
        
        PRIMARY KEY  (`chat_message_id`),
        KEY `INDEX` (`chat_message_mask_id`, `chat_message_user_id`),
        KEY `UPDATE`  (`chat_message_time`)
      )
      TYPE=InnoDB CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  // Ensure utf8 on chat_message_content
  $sql = "SHOW FULL COLUMNS FROM `se_chat_messages` LIKE 'chat_message_content'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  $result = $database->database_fetch_assoc($resource);

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_chat_messages MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  
  
  // ######### CREATE se_chat_users
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_chat_users'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( $database->database_num_rows($resource)==0 )
  {
    $sql = "
      CREATE TABLE
        `$database_name`.`se_chat_users`
      (
        `chat_user_id`                  INT           UNSIGNED  NOT NULL,
        `chat_user_session_id`          VARCHAR(32)             NOT NULL,
        `chat_user_session_lastupdate`  INT           SIGNED    NOT NULL,
        `chat_user_status`              TINYINT       UNSIGNED  NOT NULL,
        `chat_user_lastupdate`          INT           SIGNED    NOT NULL,
        PRIMARY KEY  (`chat_user_id`),
        KEY `UPDATE`  (`chat_user_status`, `chat_user_lastupdate`)
      )
      TYPE=InnoDB CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_chat_users` LIKE 'chat_user_session_lastupdate'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE `$database_name`.`se_chat_users`
      ADD COLUMN `chat_user_session_lastupdate`     INT           SIGNED    NOT NULL
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  
  
  
  /* ----------------------------------------------------------------------- *\
  |                                                                           |
  | seIM - Chat                                                               |
  |                                                                           |
  \* ----------------------------------------------------------------------- */
  
  //######### CREATE se_chatbans
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_chatbans'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_chatbans` (
        `chatban_id`      int(9)  NOT NULL auto_increment,
        `chatban_user_id` int(9)  NOT NULL default '0',
        `chatban_bandate` int(14) NOT NULL default '0',
        PRIMARY KEY  (`chatban_id`),
        KEY `INDEX` (`chatban_user_id`)
      )
      TYPE=InnoDB CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }




  //######### CREATE se_chatmessages
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_chatmessages'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_chatmessages` (
        `chatmessage_id`                int(12)       NOT NULL auto_increment,
        `chatmessage_time`              int(14)       NOT NULL default '0',
        `chatmessage_user_username`     varchar(64)   NOT NULL default '',
        `chatmessage_user_displayname`  varchar(128)  NOT NULL default '',
        `chatmessage_content`           varchar(255)  NOT NULL default '',
        PRIMARY KEY  (`chatmessage_id`)
      )
      TYPE=InnoDB CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  // Ensure utf8 on chatmessage_content
  $sql = "SHOW FULL COLUMNS FROM `se_chatmessages` LIKE 'chatmessage_content'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  $result = $database->database_fetch_assoc($resource);

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_chatmessages MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  $sql = "SHOW COLUMNS FROM `se_chatmessages` LIKE 'chatmessage_user_displayname'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE `se_chatmessages` ADD COLUMN `chatmessage_user_displayname`  varchar(128) NOT NULL default ''";
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }



  //######### CREATE se_chatusers
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_chatusers'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_chatusers` (
        `chatuser_id`               int(9)        NOT NULL auto_increment,
        `chatuser_user_id`          int(9)        NOT NULL default '0',
        `chatuser_user_username`    varchar(64)   NOT NULL default '',
        `chatuser_user_displayname` varchar(128)  NOT NULL default '',
        `chatuser_lastupdate`       int(14)       NOT NULL default '0',
        `chatuser_user_photo`       varchar(10)   NOT NULL default '',
        PRIMARY KEY  (`chatuser_id`),
        KEY `INDEX` (`chatuser_user_id`)
      )
      TYPE=InnoDB CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  $sql = "SHOW COLUMNS FROM `se_chatusers` LIKE 'chatuser_user_displayname'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE `se_chatusers` ADD COLUMN `chatuser_user_displayname`  varchar(128) NOT NULL default ''";
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  
  
  
  
  
  
  
  /* ----------------------------------------------------------------------- *\
  |                                                                           |
  | seIM - Common                                                           |
  |                                                                           |
  \* ----------------------------------------------------------------------- */
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_settings` LIKE 'setting_chat_enabled'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE `$database_name`.`se_settings`
      ADD COLUMN `setting_chat_enabled`     TINYINT   UNSIGNED  NOT NULL default '1',
      ADD COLUMN `setting_chat_update`      SMALLINT  UNSIGNED  NOT NULL default '2000',
      ADD COLUMN `setting_chat_showphotos`  TINYINT   UNSIGNED  NOT NULL default '1'
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_settings` LIKE 'setting_im_enabled'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE `$database_name`.`se_settings` ADD COLUMN `setting_im_enabled` TINYINT UNSIGNED NOT NULL default '1'";
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_settings` LIKE 'setting_im_html'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE `$database_name`.`se_settings` ADD COLUMN `setting_im_html`       TEXT NULL";
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    
    $sql = "UPDATE se_settings SET setting_im_html='a,b,br,font,i,img'";
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  // Fix the settings to use the new millisecond update format
  if( $setting['setting_chat_update'] && $setting['setting_chat_update']<100 )
  {
    $sql = "UPDATE se_settings SET setting_chat_update='".($setting['setting_chat_update']*1000)."'";
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  
  
  //######### ADD COLUMNS/VALUES TO LEVELS TABLE
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_chat_allow'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( $database->database_num_rows($resource)==0 )
  {
    $sql = "
      ALTER TABLE `$database_name`.`se_levels`
      ADD COLUMN `level_chat_allow`       TINYINT   UNSIGNED  NOT NULL default '1',
      ADD COLUMN `level_im_allow`         TINYINT   UNSIGNED  NOT NULL default '1'
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  
  
  //######### ADD COLUMNS/VALUES TO STATS TABLE
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_stats` LIKE 'stat_chat_requests'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE `$database_name`.`se_stats`
      ADD COLUMN `stat_chat_requests`   BIGINT   UNSIGNED   NOT NULL default 0,
      ADD COLUMN `stat_chat_cpu_time`   FLOAT               NOT NULL default 0
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_stats` LIKE 'stat_chat_bandwidth'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE `$database_name`.`se_stats`
      ADD COLUMN `stat_chat_bandwidth`   BIGINT   UNSIGNED   NOT NULL default 0
    ";
    
    $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }




  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS NOT BEEN INSTALLED)
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=3500001 LIMIT 1";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($database->database_query($sql)) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES 
        (3500001, 1, 'Chat Plugin Settings', ''),
        (3500002, 1, 'Global Chat Settings', ''),
        (3500003, 1, 'Chat Settings', 'admin_chat'),
        
        (3500004, 1, 'Welcome to the chatroom!', 'chat_frame'),
        (3500005, 1, 'Logging you in as %1\$s...', 'chat_frame'),
        (3500007, 1, 'Live Chat', 'chat_frame'),
        (3500008, 1, 'Bold', 'chat_frame'),
        (3500009, 1, 'Italics', 'chat_frame'),
        (3500010, 1, 'Underline', 'chat_frame'),
        (3500011, 1, 'Smilies', 'chat_frame'),
        (3500012, 1, 'Color', 'chat_frame'),
        (3500013, 1, 'Timestamp', 'chat_frame'),
        (3500014, 1, 'Audio On/Off', 'chat_frame'),
        (3500015, 1, '%1\$s person chatting', 'chat_frame'),
        (3500016, 1, '%1\$s people chatting', 'chat_frame'),
        (3500017, 1, 'Your have been logged out of the chatroom.<br>Either you have experienced a connection issue,<br>or you have been kicked by an administrator.<br><br>Please try again in a few minutes.', 'chat_frame'),
        (3500018, 1, 'Your connection to the chatroom has timed out.<br><br>Please <a href=\\\\\"chat_frame.php\\\\\">click here</a> to login again or try again later.', 'chat_frame'),
        (3500019, 1, 'You have been kicked from the chatroom by the administrator.<br>You will be able to login again in a few minutes.', 'chat_frame'),
        (3500020, 1, 'You have been banned from the chatroom by the administrator.', 'chat_frame'),
        (3500021, 1, 'The chatroom has been disabled by the administrator.<br>Check back soon!', 'chat_frame'),
        (3500022, 1, 'You could not login due to a server error.<br>Please notify the administrator!', 'chat_frame'),
        (3500023, 1, '%1\$s has just joined the chat.', 'chat_frame'),
        (3500024, 1, '%1\$s has just left the chat.', 'chat_frame'),
        
        (3500025, 1, 'Chat', 'footer_chat'),
        
        (3501002, 1, 'Live chat is a great way to encourage user interaction on your social network. Use the settings below to configure how your chat room will work. You can also use this page to kick or ban users from the chatroom.', 'admin_chat'),
        
        (3501004, 1, '%1\$s has been kicked from the chatroom.', 'admin_chat'),
        (3501005, 1, 'Users Currently Chatting', 'admin_chat'),
        (3501006, 1, 'The following users are currently chatting in the chatroom. Clicking a username will <b>kick</b> them from the chatroom. Kicking a user will prevent them from logging back into the chat for five minutes. If you want to permanently ban someone, see the box at the bottom of this page.', 'admin_chat'),
        (3501007, 1, 'There are currently no users chatting.', 'admin_chat'),
        
        (3501011, 1, 'milliseconds', 'admin_chat'),
        (3501012, 1, 'Update Frequency', 'admin_chat'),
        (3501013, 1, 'The chatroom application connects to your server (using AJAX) every few seconds to get new data. How often do you want this process to occur? A shorter amount of time will make the chat slightly faster but will also consume more server resources. If your server is experiencing slowdown issues, try increasing this value from the default (2 seconds). <b>Please enter a number between 2000 and 20000 (milliseconds).</b>', 'admin_chat'),
        (3501014, 1, 'seconds', 'admin_chat'),
        (3501015, 1, 'Online User List', 'admin_chat'),
        (3501016, 1, 'The chatroom includes a frame that displays what users are currently logged-in to the chat. Do you want this list to simply be a textual list of usernames (like AIM\'s buddy list), or would you prefer to include each user\'s photo next to their username? If you expect your room to have many online users, you may want to just show a list of usernames.', 'admin_chat'),
        (3501017, 1, 'Yes, show each user\'s photo next to their username.', 'admin_chat'),
        (3501018, 1, 'No, just show a list of usernames.', 'admin_chat'),
        (3501019, 1, 'Ban Usernames', 'admin_chat'),
        (3501020, 1, 'If you want to ban certain users from logging into the chat, you can enter their usernames below (separated by commas only).', 'admin_chat'),
        
        (3501021, 1, 'Chat and IM Settings', 'admin_levels_chatsettings'),
        (3501022, 1, 'If you have allowed users to use the chat room or IM, you can adjust their details from this page.', 'admin_levels_chatsettings'),
        
        (3501023, 1, 'Allow Chat/IM', 'admin_chat, admin_levels_chatsettings'),
        (3501024, 1, 'Do you want to let users chat in the chat room?', 'admin_chat, admin_levels_chatsettings'),
        (3501025, 1, 'Yes, allow chats.', 'admin_chat, admin_levels_chatsettings'),
        (3501026, 1, 'No, do not allow chats.', 'admin_chat, admin_levels_chatsettings'),
        (3501027, 1, 'Do you want to let users have private conversations (IM)?', 'admin_chat, admin_levels_chatsettings'),
        (3501028, 1, 'Yes, allow IM.', 'admin_chat, admin_levels_chatsettings'),
        (3501029, 1, 'No, do not allow IM.', 'admin_chat, admin_levels_chatsettings'),
        
        (3501030, 1, 'HTML in Chat and IM Conversations', 'admin_chat'),
        (3501031, 1, 'By default, the user may not enter any HTML tags into Chat or IM Conversations. If you want to allow specific tags, you can enter them below (separated by commas). Example: a, b, br, font, i, img, div', 'admin_chat'),
        
        (3510001, 1, 'unknown', 'footer_chat'),
        (3510002, 1, 'Conversation', 'footer_chat'),
        
        (3510015, 1, 'Minimize', 'footer_chat'),
        (3510016, 1, 'Close', 'footer_chat'),
        (3510017, 1, 'Loading...', 'footer_chat'),
        (3510018, 1, 'No users have joined yet.', 'footer_chat'),
        (3510019, 1, 'No messages have been sent yet.', 'footer_chat'),
        (3510020, 1, 'You have joined the conversation', 'footer_chat'),
        (3510021, 1, '%1\$s has joined the conversation.', 'footer_chat'),
        (3510022, 1, '%1\$s has left the conversation.', 'footer_chat'),
        (3510023, 1, '%1\$s is now offline.', 'footer_chat'),
        (3510024, 1, 'Friends', 'footer_chat'),
        (3510025, 1, 'None of your friends are online.', 'footer_chat'),
        (3510026, 1, 'Options', 'footer_chat'),
        (3510027, 1, 'Offline', 'footer_chat'),
        (3510028, 1, 'Online', 'footer_chat'),
        (3510029, 1, 'Away', 'footer_chat'),
        (3510030, 1, 'Busy', 'footer_chat'),
        (3510031, 1, 'Custom', 'footer_chat'),
        (3510032, 1, 'Your Status', 'footer_chat'),
        (3510033, 1, 'Send', 'footer_chat'),
        (3510034, 1, 'The user you are trying to message is currently offline. Your message was not received.', 'footer_chat'),
        (3510035, 1, 'New message from %1\$s', 'footer_chat'),
        (3510036, 1, '%1\$s is now online.', 'footer_chat'),
        (3510037, 1, '%1\$s is now away.', 'footer_chat'),
        (3510038, 1, 'Overflow error: too many conversations. Please close an existing conversations in order to open another.', 'footer_chat')
    ";
    
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  //######### FIX LANGUAGE VARS
  $sql = "UPDATE se_languagevars SET languagevar_value='New message from %1\$s' WHERE languagevar_value='New message from {username}' && languagevar_id=3510035";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS BEEN INSTALLED)
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=3510039 LIMIT 1";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($database->database_query($sql)) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES 
        (3510039, 1, 'My Status:', 'footer_chat'),
        (3510040, 1, 'Play sounds?', 'footer_chat'),
        (3510041, 1, 'Friends Online', 'footer_chat')
    ";
    
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=3510042 LIMIT 1";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($database->database_query($sql)) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES 
        (3510042, 1, 'Chat: %1\$d users', 'home'),
        (3510043, 1, 'IM: %1\$d users', 'home')
    ";
    
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
}

?>