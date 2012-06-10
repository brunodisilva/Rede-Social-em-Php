<?php

/* $Id: install_group.php 66 2009-02-21 01:42:43Z john $ */

$plugin_name = "Group Plugin";
$plugin_version = "3.05";
$plugin_type = "group";
$plugin_desc = "This plugin lets your users create their own groups. These groups encourage interactivity between users based on mutual interests and characteristics. Users can become leaders, create private groups, invite members, browse each other's memberships, and much more.";
$plugin_icon = "group_group16.gif";
$plugin_menu_title = "2000001";
$plugin_pages_main = "2000002<!>group_group16.gif<!>admin_viewgroups.php<~!~>2000003<!>group_settings16.gif<!>admin_group.php<~!~>";
$plugin_pages_level = "2000004<!>admin_levels_groupsettings.php<~!~>";
$plugin_url_htaccess = "RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*/)?group/([0-9]+)/([^/]*)\$ \$server_info/group.php?group_id=\$1\$2\$3 [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*/)?group/([0-9]+)/album/([0-9]+)/([^/]*)\$ \$server_info/group_album_file.php?group_id=\$2&groupmedia_id=\$3\$4 [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*/)?group/([0-9]+)/discussion/([0-9]+)/([^/]*)\$ \$server_info/group_discussion_view.php?group_id=\$2&grouptopic_id=\$3\$4 [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*/)?group/([0-9]+)/discussion/([0-9]+)/([0-9]+)/([^/]*)\$ \$server_info/group_discussion_view.php?group_id=\$2&grouptopic_id=\$3&grouppost_id=\$4\$5 [L]
";
$plugin_db_charset = 'utf8';
$plugin_db_collation = 'utf8_unicode_ci';
$plugin_reindex_totals = TRUE;


if($install == "group")
{
  //######### GET CURRENT PLUGIN INFORMATION
  $sql = "SELECT * FROM se_plugins WHERE plugin_type='$plugin_type' LIMIT 1";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
 
  $plugin_info = array();
  if( $database->database_num_rows($resource) )
    $plugin_info = $database->database_fetch_assoc($resource);
 
  // Uncomment this line if you already upgraded to v3, but are having issues with everything being private
  //$plugin_info['plugin_version'] = '2.00';


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
					'".str_replace("'", "\'", $plugin_desc)."',
					'$plugin_icon',
					'$plugin_menu_title',
					'$plugin_pages_main',
					'$plugin_pages_level',
					'$plugin_url_htaccess')");


  //######### UPDATE PLUGIN VERSION IN se_plugins
  } else {
    $database->database_query("UPDATE se_plugins SET plugin_name='$plugin_name',
					plugin_version='$plugin_version',
					plugin_desc='".str_replace("'", "\'", $plugin_desc)."',
					plugin_icon='$plugin_icon',
					plugin_menu_title='$plugin_menu_title',
					plugin_pages_main='$plugin_pages_main',
					plugin_pages_level='$plugin_pages_level',
					plugin_url_htaccess='$plugin_url_htaccess' WHERE plugin_type='$plugin_type'");

  }



  //######### CREATE se_groupalbums
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupalbums'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groupalbums` (
        `groupalbum_id`           INT         UNSIGNED  NOT NULL auto_increment,
        `groupalbum_group_id`     INT         UNSIGNED  NOT NULL default '0',
        `groupalbum_datecreated`  INT                   NOT NULL default '0',
        `groupalbum_dateupdated`  INT                   NOT NULL default '0',
        `groupalbum_title`        VARCHAR(64)           NOT NULL default '',
        `groupalbum_desc`         TEXT                      NULL,
        `groupalbum_search`       TINYINT(1)  UNSIGNED  NOT NULL default '0',
        `groupalbum_privacy`      TINYINT(3)  UNSIGNED  NOT NULL default '0',
        `groupalbum_comments`     TINYINT(3)  UNSIGNED  NOT NULL default '0',
        `groupalbum_cover`        INT         UNSIGNED  NOT NULL default '0',
        `groupalbum_views`        INT         UNSIGNED  NOT NULL default '0',
        `groupalbum_totalfiles`   SMALLINT    UNSIGNED  NOT NULL default '0',
        `groupalbum_totalspace`   BIGINT      UNSIGNED  NOT NULL default '0',
        PRIMARY KEY  (`groupalbum_id`),
        KEY `INDEX` (`groupalbum_group_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }

  //######### ADD TAG COLUMNS/VALUES TO GROUP ALBUMS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_groupalbums` LIKE 'groupalbum_tag'")) == 0) {
    $database->database_query("ALTER TABLE se_groupalbums ADD COLUMN `groupalbum_tag` int(2) NOT NULL default '0'");
  }
  
  
  // Add groupalbum_totalfiles
  $sql = "SHOW COLUMNS FROM `se_groupalbums` LIKE 'groupalbum_totalfiles'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $totalfiles_exists = (bool) $database->database_num_rows($resource);
  
  if( !$totalfiles_exists )
  {
    $sql = "ALTER TABLE se_groupalbums ADD COLUMN `groupalbum_totalfiles` SMALLINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  // Populate eventalbum_totalfiles
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_groupmedia'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( $database->database_num_rows($resource) && (!$totalfiles_exists || $plugin_reindex_totals) )
  {
    $sql = "SELECT groupalbum_id FROM se_groupalbums WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_groupalbums SET groupalbum_totalfiles=(SELECT COUNT(groupmedia_id) FROM se_groupmedia WHERE groupmedia_groupalbum_id=groupalbum_id) WHERE groupalbum_id='{$result['groupalbum_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  // Add groupalbum_totalspace
  $sql = "SHOW COLUMNS FROM `se_groupalbums` LIKE 'groupalbum_totalspace'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $totalspace_exists = (bool) $database->database_num_rows($resource);
  
  if( !$totalspace_exists )
  {
    $sql = "ALTER TABLE se_groupalbums ADD COLUMN `groupalbum_totalspace` BIGINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  // Populate eventalbum_totalspace
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_groupmedia'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( $database->database_num_rows($resource) && (!$totalspace_exists || $plugin_reindex_totals) )
  {
    $sql = "SELECT groupalbum_id FROM se_groupalbums WHERE (SELECT COUNT(groupmedia_id) FROM se_groupmedia WHERE groupmedia_groupalbum_id=groupalbum_id)>0";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_groupalbums SET groupalbum_totalspace=(SELECT SUM(groupmedia_filesize) FROM se_groupmedia WHERE groupmedia_groupalbum_id=groupalbum_id) WHERE groupalbum_id='{$result['groupalbum_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  



  //######### CREATE se_groupcats
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupcats'")) == 0) {
    $database->database_query("CREATE TABLE `se_groupcats` (
    `groupcat_id` int(9) NOT NULL auto_increment,
    `groupcat_dependency` int(9) NOT NULL default '0',
    `groupcat_title` varchar(100) NOT NULL default '',
    PRIMARY KEY  (`groupcat_id`)
    )");
  }


  //######### ALTER se_groupcats LANGUAGIFY groupcat_title
  $sql = "SHOW FULL COLUMNS FROM `se_groupcats` LIKE 'groupcat_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  // Fix collation, load data, drop column
  $groupcat_info = array();
  if( $column_info && strtoupper(substr($column_info['Type'], 0, 7))=="VARCHAR" )
  {
    // Fix collation
    if( $column_info['Collation']!=$plugin_db_collation )
    {
      $sql = "ALTER TABLE se_groupcats MODIFY {$column_info['Field']} {$column_info['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
    
    // Languagify title column
    $sql = "SELECT * FROM se_groupcats";
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    if( $database->database_num_rows($resource) )
      while( $result=$database->database_fetch_assoc($resource) )
        $groupcat_info[] = $result;
    
    // Drop column
    $sql = "ALTER TABLE se_groupcats DROP COLUMN groupcat_title";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    unset($column_info);
  }
  
  // Add column
  if( !$column_info )
  {
    $sql = "ALTER TABLE se_groupcats ADD COLUMN groupcat_title INT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  // Update column
  if( !empty($groupcat_info) )
  {
    // Update title
    foreach( $groupcat_info as $groupcat_info_array )
    {
      $groupcat_title_lvid = SE_Language::edit(0, $groupcat_info_array['groupcat_title'], NULL, LANGUAGE_INDEX_FIELDS);
      
      $sql = "
        UPDATE
          se_groupcats
        SET
          groupcat_title='{$groupcat_title_lvid}'
        WHERE
          groupcat_id='{$groupcat_info_array['groupcat_id']}'
        LIMIT
          1
      ";
      
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
  }
  
  
  
  
  //######### ALTER se_groupcats ADD COLUMNS
  $sql = "SHOW COLUMNS FROM `se_groupcats` LIKE 'groupcat_order'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE se_groupcats
      ADD COLUMN groupcat_order  SMALLINT  UNSIGNED  NOT NULL default 0,
      ADD COLUMN groupcat_signup TINYINT   UNSIGNED  NOT NULL default 0
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }




  //######### CREATE se_groupcomments
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupcomments'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groupcomments` (
        `groupcomment_id` int(9) NOT NULL auto_increment,
        `groupcomment_group_id` int(9) NOT NULL default '0',
        `groupcomment_authoruser_id` int(9) NOT NULL default '0',
        `groupcomment_date` int(14) NOT NULL default '0',
        `groupcomment_body` text NULL,
        PRIMARY KEY  (`groupcomment_id`),
        KEY `INDEX` (`groupcomment_group_id`,`groupcomment_authoruser_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }
  
  
  // Ensure utf8 on groupcomment_body
  $sql = "SHOW FULL COLUMNS FROM `se_groupcomments` LIKE 'groupcomment_body'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupcomments MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }




  //######### CREATE se_groupfields
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupfields'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groupfields` (
        `groupfield_id` int(9) NOT NULL auto_increment,
        `groupfield_order` int(3) NOT NULL default '0',
        `groupfield_dependency` int(9) NOT NULL default '0',
        `groupfield_title` varchar(100) NOT NULL default '',
        `groupfield_desc` text NULL,
        `groupfield_error` varchar(250) NOT NULL default '',
        `groupfield_type` int(1) NOT NULL default '0',
        `groupfield_style` varchar(200) NOT NULL default '',
        `groupfield_maxlength` int(3) NOT NULL default '0',
        `groupfield_options` longtext,
        `groupfield_required` int(1) NOT NULL default '0',
        `groupfield_regex` varchar(250) NOT NULL default '',
        PRIMARY KEY  (`groupfield_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }

  //######### ALTER se_groupfields LANGUAGIFY groupfield_title,groupfield_desc,groupfield_error
  $sql = "SHOW FULL COLUMNS FROM `se_groupfields` LIKE 'groupfield_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  // Fix collation, load text, drop columns
  $groupfield_info = array();
  if( $column_info && strtoupper(substr($column_info['Type'], 0, 7))=="VARCHAR" )
  {
    // Fix collation
    if( $column_info['Collation']!=$plugin_db_collation )
    {
      $sql = "
        ALTER TABLE se_groupfields
        MODIFY groupfield_title  VARCHAR(255) CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation} NOT NULL default '',
        MODIFY groupfield_desc   VARCHAR(255) CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation} NOT NULL default '',
        MODIFY groupfield_error  VARCHAR(255) CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation} NOT NULL default ''
      ";
      
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
    
    // Load title column
    $sql = "SELECT * FROM se_groupfields";
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    if( $database->database_num_rows($resource) )
      while( $result=$database->database_fetch_assoc($resource) )
        $groupfield_info[] = $result;
    
    // Crop column
    $sql = "ALTER TABLE se_groupfields DROP COLUMN groupfield_title, DROP COLUMN groupfield_desc, DROP COLUMN groupfield_error";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    unset($column_info);
  }
  
  // Add columns
  if( !$column_info )
  {
    $sql = "
      ALTER TABLE se_groupfields
      ADD COLUMN groupfield_title  INT UNSIGNED NOT NULL default 0,
      ADD COLUMN groupfield_desc   INT UNSIGNED NOT NULL default 0,
      ADD COLUMN groupfield_error  INT UNSIGNED NOT NULL default 0
    ";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  // Update column
  if( !empty($groupfield_info) )
  {
    // Update column
    foreach( $groupfield_info as $groupfield_info_array )
    {
      $groupfield_title_lvid = SE_Language::edit(0, $groupfield_info_array['groupfield_title'], NULL, LANGUAGE_INDEX_FIELDS);
      $groupfield_desc_lvid  = SE_Language::edit(0, $groupfield_info_array['groupfield_desc' ], NULL, LANGUAGE_INDEX_FIELDS);
      $groupfield_error_lvid = SE_Language::edit(0, $groupfield_info_array['groupfield_error'], NULL, LANGUAGE_INDEX_FIELDS);
      
      $sql = "
        UPDATE
          se_groupfields
        SET
          groupfield_title='{$groupfield_title_lvid}',
          groupfield_desc='{$groupfield_desc_lvid}',
          groupfield_error='{$groupfield_error_lvid}'
        WHERE
          groupfield_id='{$groupfield_info_array['groupfield_id']}'
        LIMIT
          1
      ";
      
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
  }
  
  
  // ALTER se_groupfields ADD COLUMNS
  $sql = "SHOW COLUMNS FROM `se_groupfields` LIKE 'groupfield_signup'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE se_groupfields
      ADD COLUMN groupfield_groupcat_id int(9) NOT NULL default '0',
      ADD COLUMN groupfield_signup   TINYINT   UNSIGNED  NOT NULL default 0,
      ADD COLUMN groupfield_link varchar(250) NOT NULL default '',
      ADD COLUMN groupfield_display  TINYINT   UNSIGNED  NOT NULL default 0,
      ADD COLUMN groupfield_special  TINYINT   UNSIGNED  NOT NULL default 0,
      ADD COLUMN groupfield_html varchar(250) NOT NULL default '',
      ADD COLUMN groupfield_search int(1) NOT NULL default '0'
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);

    // UPDATE groupfield_groupcat_id AND SET KEY ON IT
    $groupcat_query = $database->database_query("SELECT groupcat_id FROM se_groupcats LIMIT 1");
    if($database->database_num_rows($groupcat_query) == 1) {
      $groupcat_info = $database->database_fetch_assoc($groupcat_query);
      $database->database_query("UPDATE se_groupfields SET groupfield_groupcat_id={$groupcat_info[groupcat_id]}");
    } else {
      $groupcat_title  = SE_Language::edit(0, "Default", NULL, LANGUAGE_INDEX_FIELDS);
      $database->database_query("INSERT INTO se_groupcats (groupcat_title, groupcat_dependency, groupcat_order, groupcat_signup) VALUES ('$groupcat_title', 0, 1, 0)");
    }
  }
  
  
  // Ensure utf8 on groupfield_style
  $sql = "SHOW FULL COLUMNS FROM `se_groupfields` LIKE 'groupfield_style'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on groupfield_options
  $sql = "SHOW FULL COLUMNS FROM `se_groupfields` LIKE 'groupfield_options'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on groupfield_regex
  $sql = "SHOW FULL COLUMNS FROM `se_groupfields` LIKE 'groupfield_regex'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on groupfield_link
  $sql = "SHOW FULL COLUMNS FROM `se_groupfields` LIKE 'groupfield_link'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on groupfield_html
  $sql = "SHOW FULL COLUMNS FROM `se_groupfields` LIKE 'groupfield_html'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }




  //######### CREATE se_groupmedia
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupmedia'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groupmedia` (
        `groupmedia_id`             INT           UNSIGNED  NOT NULL auto_increment,
        `groupmedia_groupalbum_id`  INT           UNSIGNED  NOT NULL default '0',
        `groupmedia_date`           INT                     NOT NULL default '0',
        `groupmedia_title`          VARCHAR(64)             NOT NULL default '',
        `groupmedia_desc`           TEXT                        NULL,
        `groupmedia_ext`            VARCHAR(8)              NOT NULL default '',
        `groupmedia_filesize`       INT           UNSIGNED  NOT NULL default '0',
        `groupmedia_totalcomments`  SMALLINT      UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`groupmedia_id`),
        KEY `INDEX` (`groupmedia_groupalbum_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }

  // ADD USER ID COLUMNS/VALUES TO GROUP MEDIA TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_groupmedia` LIKE 'groupmedia_user_id'")) == 0) {
    $database->database_query("ALTER TABLE se_groupmedia ADD COLUMN `groupmedia_user_id` int(9) NOT NULL default '0'");
    $database->database_query("ALTER TABLE `se_groupmedia` ADD INDEX ( `groupmedia_user_id` )");
  }
  
  
  // Add groupmedia_totalcomments
  $sql = "SHOW COLUMNS FROM `se_groupmedia` LIKE 'groupmedia_totalcomments'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_groupmedia ADD COLUMN `groupmedia_totalcomments` SMALLINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  if( !$database->database_num_rows($resource) || $plugin_reindex_totals )
  {
    $sql = "SELECT groupmedia_id FROM se_groupmedia WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_groupmedia SET groupmedia_totalcomments=(SELECT COUNT(groupmediacomment_id) FROM se_groupmediacomments WHERE groupmediacomment_groupmedia_id=groupmedia_id) WHERE groupmedia_id='{$result['groupmedia_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  // Ensure utf8 on groupmedia_title
  $sql = "SHOW FULL COLUMNS FROM `se_groupmedia` LIKE 'groupmedia_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupmedia MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on groupmedia_desc
  $sql = "SHOW FULL COLUMNS FROM `se_groupmedia` LIKE 'groupmedia_desc'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupmedia MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on groupmedia_ext
  $sql = "SHOW FULL COLUMNS FROM `se_groupmedia` LIKE 'groupmedia_ext'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupmedia MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }




  //######### CREATE se_groupmediacomments
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupmediacomments'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groupmediacomments` (
        `groupmediacomment_id` int(9) NOT NULL auto_increment,
        `groupmediacomment_groupmedia_id` int(9) NOT NULL default '0',
        `groupmediacomment_authoruser_id` int(9) NOT NULL default '0',
        `groupmediacomment_date` int(14) NOT NULL default '0',
        `groupmediacomment_body` text NULL,
        PRIMARY KEY  (`groupmediacomment_id`),
        KEY `INDEX` (`groupmediacomment_groupmedia_id`,`groupmediacomment_authoruser_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }
  
  
  // Ensure utf8 on groupmediacomment_body
  $sql = "SHOW FULL COLUMNS FROM `se_groupmediacomments` LIKE 'groupmediacomment_body'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupmediacomments MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }




  //######### CREATE se_groupmediatags
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupmediatags'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groupmediatags` (
        `groupmediatag_id` int(9) NOT NULL auto_increment,
        `groupmediatag_groupmedia_id` int(9) NOT NULL default '0',
        `groupmediatag_user_id` int(9) NOT NULL default '0',
        `groupmediatag_x` int(9) NOT NULL default '0',
        `groupmediatag_y` int(9) NOT NULL default '0',
        `groupmediatag_height` int(9) NOT NULL default '0',
        `groupmediatag_width` int(9) NOT NULL default '0',
        `groupmediatag_text` varchar(255) collate utf8_unicode_ci NOT NULL default '',
        `groupmediatag_date` int(14) NOT NULL default '0',
        PRIMARY KEY  (`groupmediatag_id`),
        KEY `INDEX` (`groupmediatag_groupmedia_id`,`groupmediatag_user_id`)
      )
      ENGINE=MyISAM CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }
  
  
  // Ensure utf8 on groupmediatag_text
  $sql = "SHOW FULL COLUMNS FROM `se_groupmediatags` LIKE 'groupmediatag_text'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupmediatags MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }



  //######### CREATE se_groupmembers
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupmembers'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groupmembers` (
        `groupmember_id` int(9) NOT NULL auto_increment,
        `groupmember_user_id` int(9) NOT NULL default '0',
        `groupmember_group_id` int(9) NOT NULL default '0',
        `groupmember_status` int(1) NOT NULL default '0',
        `groupmember_approved` int(1) NOT NULL default '0',
        `groupmember_rank` int(1) NOT NULL default '0',
        `groupmember_title` varchar(50) NOT NULL default '',
        PRIMARY KEY  (`groupmember_id`),
        KEY `INDEX` (`groupmember_user_id`,`groupmember_group_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }
  
  
  // Ensure utf8 on groupmember_title
  $sql = "SHOW FULL COLUMNS FROM `se_groupmembers` LIKE 'groupmember_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupmembers MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }



  //######### CREATE se_groupposts
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupposts'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groupposts` (
        `grouppost_id` int(9) NOT NULL auto_increment,
        `grouppost_grouptopic_id` int(9) NOT NULL default '0',
        `grouppost_authoruser_id` int(9) NOT NULL default '0',
        `grouppost_date` int(14) NOT NULL default '0',
        `grouppost_body` text NULL,
        PRIMARY KEY  (`grouppost_id`),
        KEY `INDEX` (`grouppost_grouptopic_id`,`grouppost_authoruser_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }

  // ADD POST DELETED COLUMNS/VALUES TO GROUP POSTS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_groupposts` LIKE 'grouppost_deleted'")) == 0) {
    $database->database_query("ALTER TABLE se_groupposts ADD COLUMN `grouppost_deleted` int(1) NOT NULL default '0'");
  }
  
  
  // Ensure utf8 on grouppost_body
  $sql = "SHOW FULL COLUMNS FROM `se_groupposts` LIKE 'grouppost_body'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupposts MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  //######### CREATE se_groups
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groups'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groups` (
        `group_id`            INT           UNSIGNED  NOT NULL auto_increment,
        `group_user_id`       INT           UNSIGNED  NOT NULL default '0',
        `group_groupcat_id`   INT           UNSIGNED  NOT NULL default '0',
        `group_datecreated`   INT                     NOT NULL default '0',
        `group_dateupdated`   INT                     NOT NULL default '0',
        `group_views`         INT           UNSIGNED  NOT NULL default '0',
        `group_title`         VARCHAR(128)            NOT NULL default '',
        `group_desc`          TEXT                        NULL,
        `group_photo`         VARCHAR(16)             NOT NULL default '',
        `group_search`        TINYINT(1)    UNSIGNED  NOT NULL default '0',
        `group_privacy`       TINYINT(3)    UNSIGNED  NOT NULL default '0',
        `group_comments`      TINYINT(3)    UNSIGNED  NOT NULL default '0',
        `group_approval`      TINYINT(1)    UNSIGNED  NOT NULL default '0',
        `group_totalcomments` SMALLINT      UNSIGNED  NOT NULL default 0,
        `group_totalmembers`  SMALLINT      UNSIGNED  NOT NULL default 0,
        `group_totaltopics`   SMALLINT      UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`group_id`),
        KEY `INDEX` (`group_user_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }

  //######### ADD DISCUSSION BOARD COLUMNS/VALUES TO GROUPS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_groups` LIKE 'group_discussion'")) == 0) {
    $database->database_query("ALTER TABLE se_groups ADD COLUMN `group_discussion` int(2) NOT NULL default '0'");
  }

  //######### ADD INVITE COLUMNS/VALUES TO GROUPS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_groups` LIKE 'group_invite'")) == 0) {
    $database->database_query("ALTER TABLE se_groups ADD COLUMN `group_invite` int(1) NOT NULL default '0'");
  }

  //######### ADD UPLOAD COLUMNS/VALUES TO GROUPS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_groups` LIKE 'group_upload'")) == 0) {
    $database->database_query("ALTER TABLE se_groups ADD COLUMN `group_upload` int(1) NOT NULL default '0'");
  }
  
  
  // Add group_totalcomments
  $sql = "SHOW COLUMNS FROM `se_groups` LIKE 'group_totalcomments'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_groups ADD COLUMN `group_totalcomments` SMALLINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  if( !$database->database_num_rows($resource) || $plugin_reindex_totals )
  {
    $sql = "SELECT group_id FROM se_groups WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_groups SET group_totalcomments=(SELECT COUNT(groupcomment_id) FROM se_groupcomments WHERE groupcomment_group_id='{$result['group_id']}') WHERE group_id='{$result['group_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  // Add group_totalmembers
  $sql = "SHOW COLUMNS FROM `se_groups` LIKE 'group_totalmembers'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $totalmembers_exists = (bool) $database->database_num_rows($resource);
  
  if( !$totalmembers_exists )
  {
    $sql = "ALTER TABLE se_groups ADD COLUMN `group_totalmembers` SMALLINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  // Populate group_totalmembers
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_groupmembers'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( $database->database_num_rows($resource) && (!$totalmembers_exists || $plugin_reindex_totals) )
  {
    $sql = "SELECT group_id FROM se_groups WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_groups SET group_totalmembers=(SELECT COUNT(groupmember_id) FROM se_groupmembers WHERE groupmember_group_id='{$result['group_id']}' && groupmember_approved=1 && groupmember_status=1) WHERE group_id='{$result['group_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  // Add group_totaltopics
  $sql = "SHOW COLUMNS FROM `se_groups` LIKE 'group_totaltopics'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $totaltopics_exists = (bool) $database->database_num_rows($resource);
  
  if( !$totaltopics_exists )
  {
    $sql = "ALTER TABLE se_groups ADD COLUMN `group_totaltopics` SMALLINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  // Populate group_totaltopics
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_grouptopics'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( $database->database_num_rows($resource) && (!$totaltopics_exists || $plugin_reindex_totals) )
  {
    $sql = "SELECT group_id FROM se_groups WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_groups SET group_totaltopics=(SELECT COUNT(grouptopic_id) FROM se_grouptopics WHERE grouptopic_group_id='{$result['group_id']}') WHERE group_id='{$result['group_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  // Ensure utf8 on group_title
  $sql = "SHOW FULL COLUMNS FROM `se_groups` LIKE 'group_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groups MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on group_desc
  $sql = "SHOW FULL COLUMNS FROM `se_groups` LIKE 'group_desc'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groups MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on group_photo
  $sql = "SHOW FULL COLUMNS FROM `se_groups` LIKE 'group_photo'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groups MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  //######### CREATE se_groupstyles
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupstyles'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groupstyles` (
        `groupstyle_id` int(9) NOT NULL auto_increment,
        `groupstyle_group_id` int(9) NOT NULL default '0',
        `groupstyle_css` text NULL,
        PRIMARY KEY  (`groupstyle_id`),
        KEY `INDEX` (`groupstyle_group_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }
  
  
  // Ensure utf8 on groupstyle_css
  $sql = "SHOW FULL COLUMNS FROM `se_groupstyles` LIKE 'groupstyle_css'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_groupstyles MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  //######### CREATE se_groupsubscribes
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupsubscribes'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groupsubscribes` (
        `groupsubscribe_user_id` int(9) NOT NULL default '0',
        `groupsubscribe_group_id` int(9) NOT NULL default '0',
        `groupsubscribe_time` int(14) NOT NULL default '0',
        UNIQUE KEY `UNIQUE` (`groupsubscribe_user_id`,`groupsubscribe_group_id`)
      )
      ENGINE=MyISAM CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }



  //######### CREATE se_grouptopics
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_grouptopics'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_grouptopics` (
        `grouptopic_id`             int(9)                NOT NULL auto_increment,
        `grouptopic_group_id`       int(9)                NOT NULL default '0',
        `grouptopic_creatoruser_id` int(9)                NOT NULL default '0',
        `grouptopic_date`           int(14)               NOT NULL default '0',
        `grouptopic_subject`        varchar(50)           NOT NULL default '',
        `grouptopic_views`          int(9)                NOT NULL default '0',
        `grouptopic_sticky`         TINYINT(1)  UNSIGNED  NOT NULL default '0',
        `grouptopic_closed`         TINYINT(1)  UNSIGNED  NOT NULL default '0',
        `grouptopic_totalposts`     SMALLINT    UNSIGNED  NOT NULL default '0',
        PRIMARY KEY  (`grouptopic_id`),
        KEY `INDEX` (`grouptopic_group_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }
  
  
  // ADD STICKY/CLOSED COLUMNS/VALUES TO GROUP TOPICS TABLE
  if( !$database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_grouptopics` LIKE 'grouptopic_sticky'")) )
  {
    $database->database_query("
      ALTER TABLE se_grouptopics
        ADD COLUMN `grouptopic_sticky` int(1) NOT NULL default '0', 
        ADD COLUMN `grouptopic_closed` int(1) NOT NULL default '0',
        ADD COLUMN `grouptopic_creatoruser_id` int(9) NOT NULL default '0'
    ");
    $database->database_query("ALTER TABLE `se_grouptopics` ADD INDEX ( `grouptopic_creatoruser_id` )");
    $database->database_query("UPDATE se_grouptopics, se_groupposts SET se_grouptopics.grouptopic_creatoruser_id = se_groupposts.grouppost_authoruser_id WHERE se_groupposts.grouppost_id = ( SELECT min( grouppost_id ) FROM se_groupposts WHERE grouppost_grouptopic_id = se_grouptopics.grouptopic_id )");
  }
  
  
  // Ensure utf8 on grouptopic_subject
  $sql = "SHOW FULL COLUMNS FROM `se_grouptopics` LIKE 'grouptopic_subject'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_grouptopics MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Add grouptopic_totalposts
  $sql = "SHOW COLUMNS FROM `se_grouptopics` LIKE 'grouptopic_totalposts'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_grouptopics ADD COLUMN `grouptopic_totalposts` SMALLINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  if( !$database->database_num_rows($resource) || $plugin_reindex_totals )
  {
    $sql = "SELECT grouptopic_id FROM se_grouptopics WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_grouptopics SET grouptopic_totalposts=(SELECT COUNT(grouppost_id) FROM se_groupposts WHERE grouppost_grouptopic_id=grouptopic_id) WHERE grouptopic_id='{$result['grouptopic_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  
  //######### CREATE se_groupvalues
  if($database->database_num_rows($database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_groupvalues'")) == 0) {
    $database->database_query("
      CREATE TABLE `se_groupvalues` (
        `groupvalue_id` int(9) NOT NULL auto_increment,
        `groupvalue_group_id` int(9) NOT NULL default '0',
        PRIMARY KEY  (`groupvalue_id`),
        KEY `groupvalue_group_id` (`groupvalue_group_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ");
  }
  
  
  
  //######### INSERT se_urls
  $sql = "SELECT url_id FROM se_urls WHERE url_file='group'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "INSERT INTO se_urls (url_title, url_file, url_regular, url_subdirectory) VALUES ('Group URL', 'group', 'group.php?group_id=\$id1', '\group/\$id1/')";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SELECT url_id FROM se_urls WHERE url_file='group_media'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "INSERT INTO se_urls (url_title, url_file, url_regular, url_subdirectory) VALUES ('Group Media URL', 'group_media', 'group_album_file.php?group_id=\$id1&groupmedia_id=\$id2', '\group/\$id1/album/\$id2/')";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SELECT url_id FROM se_urls WHERE url_file='group_discussion'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "INSERT INTO se_urls (url_title, url_file, url_regular, url_subdirectory) VALUES ('Group Discussion URL', 'group_discussion', 'group_discussion_view.php?group_id=\$id1&grouptopic_id=\$id2', '\group/\$id1/discussion/\$id2/')";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SELECT url_id FROM se_urls WHERE url_file='group_discussion_post'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "INSERT INTO se_urls (url_title, url_file, url_regular, url_subdirectory) VALUES ('Group Discussion Post URL', 'group_discussion_post', 'group_discussion_view.php?group_id=\$id1&grouptopic_id=\$id2&grouppost_id=\$id3', '\group/\$id1/discussion/\$id2/\$id3/')";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  //######### INSERT se_actiontypes
  $actiontypes = array();
  if( !$database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='newgroup'")) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('newgroup', 'group_action_newgroup.gif', '1', '1', '2000344', '2000345', '[username],[displayname],[id],[title]', '0')
    ");
    $actiontypes[] = $database->database_insert_id();
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='joingroup'")) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('joingroup', 'group_action_joingroup.gif', '1', '1', '2000346', '2000347', '[username],[displayname],[id],[title]', '0')
    ");
    $actiontypes[] = $database->database_insert_id();
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='leavegroup'")) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('leavegroup', 'group_action_leavegroup.gif', '1', '1', '2000348', '2000349', '[username],[displayname],[id],[title]', '0')
    ");
    $actiontypes[] = $database->database_insert_id();
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='groupcomment'")) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('groupcomment', 'action_postcomment.gif', '1', '1', '2000350', '2000351', '[username],[displayname],,,[comment],[id],[title]', '0')
    ");
    $actiontypes[] = $database->database_insert_id();
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='groupmediacomment'")) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('groupmediacomment', 'action_postcomment.gif', '1', '1', '2000352', '2000353', '[username],[displayname],,,[comment],[id],[title],[parentid]', '0')
    ");
    $actiontypes[] = $database->database_insert_id();
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='newgroupmedia'")) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('newgroupmedia', 'group_action_newgroupmedia.gif', '1', '1', '2000354', '2000355', '[username],[displayname],[id],[title]', '1')
    ");
    $actiontypes[] = $database->database_insert_id();
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='newgrouptag'")) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('newgrouptag', 'group_action_newgrouptag.gif', '1', '1', '2000356', '2000357', '[username],[displayname]', '1')
    ");
    $actiontypes[] = $database->database_insert_id();
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='grouptopic'")) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('grouptopic', 'group_action_discussion16.gif', '1', '1', '2000358', '2000359', '[username],[displayname],[groupid],[groupname],[topicid],[topicname],[postbody]', '1')
    ");
    $actiontypes[] = $database->database_insert_id();
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='grouppost'")) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('grouppost', 'group_action_discussion16.gif', '1', '1', '2000360', '2000361', '[username],[displayname],[groupid],[topicid],[topicname],[postid],[postbody]', '1')
    ");
    $actiontypes[] = $database->database_insert_id();
  }
  
  $actiontypes = array_filter($actiontypes);
  if( !empty($actiontypes) )
  {
    $database->database_query("UPDATE se_usersettings SET usersetting_actions_display = CONCAT(usersetting_actions_display, ',', '".implode(",", $actiontypes)."')");
  }
  
  
  // Update old action types for 3.04
  $sql = "
    UPDATE se_languagevars
    SET languagevar_value='<a href=\"profile.php?user=%1\$s\">%2\$s</a> posted a comment on the group %7\$s\'s <a href=\"group_album_file.php?group_id=%8\$s&groupmedia_id=%6\$s\">photo</a>:<div class=\"recentaction_div\">%5\$s</div>'
    WHERE languagevar_value LIKE '%posted a comment on the group %group_album_file.php?groupmedia_id=%6\$s\">photo%'
    LIMIT 1
  ";
  
  $database->database_query($sql) or die($database->database_error()." SQL was: ".$sql);
  
  $sql = "
    UPDATE se_actiontypes
    SET actiontype_vars='[username],[displayname],,,[comment],[id],[title],[parentid]'
    WHERE actiontype_name='groupmediacomment' && actiontype_vars='[username],[displayname],,,[comment],[id],[title]'
    LIMIT 1
  ";
  
  $database->database_query($sql) or die($database->database_error()." SQL was: ".$sql);
  
  
  
  //######### INSERT se_notifytypes
  if( !$database->database_num_rows($database->database_query("SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='groupcomment'")) )
  {
    $database->database_query("
      INSERT INTO se_notifytypes
        (notifytype_name, notifytype_desc, notifytype_icon, notifytype_url, notifytype_title)
      VALUES
        ('groupcomment', '2000362', 'action_postcomment.gif', 'group.php?group_id=%2\$s&v=comments', '2000363')
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='groupmediacomment'")) )
  {
    $database->database_query("
      INSERT INTO se_notifytypes
        (notifytype_name, notifytype_desc, notifytype_icon, notifytype_url, notifytype_title)
      VALUES
        ('groupmediacomment', '2000364', 'action_postcomment.gif', 'group_album_file.php?group_id=%3\$s&groupmedia_id=%2\$s', '2000365')
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='groupinvite'")) )
  {
    $database->database_query("
      INSERT INTO se_notifytypes
        (notifytype_name, notifytype_desc, notifytype_icon, notifytype_url, notifytype_title, notifytype_group)
      VALUES
        ('groupinvite', '2000366', 'group_action_joingroup.gif', 'group.php?group_id=%2\$s', '2000367', 1)
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='groupmemberrequest'")) )
  {
    $database->database_query("
      INSERT INTO se_notifytypes
        (notifytype_name, notifytype_desc, notifytype_icon, notifytype_url, notifytype_title, notifytype_group)
      VALUES
        ('groupmemberrequest', '2000368', 'group_action_joingroup.gif', 'group.php?group_id=%2\$s', '2000369', 1)
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='newgrouptag'")) )
  {
    $database->database_query("
      INSERT INTO se_notifytypes
        (notifytype_name, notifytype_desc, notifytype_icon, notifytype_url, notifytype_title)
      VALUES
        ('newgrouptag', '2000370', 'group_action_newgrouptag.gif', 'profile_photos_file.php?user=%1\$s&type=%2\$s&media_id=%3\$s', '2000371')
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='groupmediatag'")) )
  {
    $database->database_query("
      INSERT INTO se_notifytypes
        (notifytype_name, notifytype_desc, notifytype_icon, notifytype_url, notifytype_title)
      VALUES
        ('groupmediatag', '2000372', 'group_action_newgrouptag.gif', 'group_album_file.php?group_id=%2\$s&groupmedia_id=%3\$s', '2000373')
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='grouppost'")) )
  {
    $database->database_query("
      INSERT INTO se_notifytypes
        (notifytype_name, notifytype_desc, notifytype_icon, notifytype_url, notifytype_title)
      VALUES
        ('grouppost', '2000374', 'group_action_discussion16.gif', 'group.php?group_id=%1\$s&v=discussions', '2000375')
    ");
  }
  
  // Fix broken notify urls
  $database->database_query("
    UPDATE
      se_notifytypes
    SET
      notifytype_url='group_album_file.php?group_id=%3\$s&groupmedia_id=%2\$s'
    WHERE
      notifytype_name='groupmediacomment' && (
      notifytype_url='group_album_file.php?group_id=%1\$s&groupmedia_id=%2\$s' ||
      notifytype_url='group_album_file.php?groupmedia_id=%2\$s')
  ");
  
  $database->database_query("
    UPDATE
      se_notifytypes
    SET
      notifytype_url='group.php?group_id=%2\$s&v=comments'
    WHERE
      notifytype_name='groupcomment' &&
      notifytype_url='group.php?group_id=%2\$s'
  ");
  
  $database->database_query("
    UPDATE
      se_notifytypes
    SET
      notifytype_url='user_group_edit_members.php?group_id=%2\$s&v=3'
    WHERE
      notifytype_name='groupmemberrequest' &&
      notifytype_url='group.php?group_id=%2\$s'
  ");
  
  $database->database_query("
    UPDATE
      se_notifytypes
    SET
      notifytype_url='user_group.php'
    WHERE
      notifytype_name='groupinvite' &&
      notifytype_url='group.php?group_id=%2\$s'
  ");
  
  
  
  //######### ADD COLUMNS/VALUES TO SYSTEM EMAILS TABLE
  if( !$database->database_num_rows($database->database_query("SELECT systememail_id FROM se_systememails WHERE systememail_name='groupinvite'")) )
  {
    $database->database_query("
      INSERT INTO se_systememails
        (systememail_name, systememail_title, systememail_desc, systememail_subject, systememail_body, systememail_vars)
      VALUES
        ('groupinvite', '2000005', '2000006', '2000376', '2000377', '[displayname],[groupname],[link]')
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT systememail_id FROM se_systememails WHERE systememail_name='groupcomment'")) )
  {
    $database->database_query("
      INSERT INTO se_systememails
        (systememail_name, systememail_title, systememail_desc, systememail_subject, systememail_body, systememail_vars)
      VALUES
        ('groupcomment', '2000008', '2000009', '2000378', '2000379', '[displayname],[commenter],[link]')
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT systememail_id FROM se_systememails WHERE systememail_name='groupmediacomment'")) )
  {
    $database->database_query("
      INSERT INTO se_systememails
        (systememail_name, systememail_title, systememail_desc, systememail_subject, systememail_body, systememail_vars)
      VALUES
        ('groupmediacomment', '2000010', '2000011', '2000380', '2000381', '[displayname],[commenter],[link]')
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT systememail_id FROM se_systememails WHERE systememail_name='groupmemberrequest'")) )
  {
    $database->database_query("
      INSERT INTO se_systememails
        (systememail_name, systememail_title, systememail_desc, systememail_subject, systememail_body, systememail_vars)
      VALUES
        ('groupmemberrequest', '2000012', '2000013', '2000382', '2000383', '[displayname],[requester],[groupname],[link]')
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT systememail_id FROM se_systememails WHERE systememail_name='newgrouptag'")) )
  {
    $database->database_query("
      INSERT INTO se_systememails
        (systememail_name, systememail_title, systememail_desc, systememail_subject, systememail_body, systememail_vars)
      VALUES
        ('newgrouptag', '2000332', '2000333', '2000384', '2000385', '[displayname],[link]')
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT systememail_id FROM se_systememails WHERE systememail_name='groupmediatag'")) )
  {
    $database->database_query("
      INSERT INTO se_systememails
        (systememail_name, systememail_title, systememail_desc, systememail_subject, systememail_body, systememail_vars)
      VALUES
        ('groupmediatag', '2000334', '2000335', '2000386', '2000387', '[displayname],[tagger],[link]')
    ");
  }
  
  if( !$database->database_num_rows($database->database_query("SELECT systememail_id FROM se_systememails WHERE systememail_name='grouppost'")) )
  {
    $database->database_query("
      INSERT INTO se_systememails
        (systememail_name, systememail_title, systememail_desc, systememail_subject, systememail_body, systememail_vars)
      VALUES
        ('grouppost', '2000336', '2000337', '2000388', '2000389', '[displayname],[commenter],[link]')
    ");
  }
  
  
  
  //######### ADD COLUMNS/VALUES TO LEVELS TABLE IF GROUPS HAVE NEVER BEEN INSTALLED
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_group_allow'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( !$column_info )
  {
    $database->database_query("
      ALTER TABLE se_levels 
      ADD COLUMN `level_group_allow` tinyint(1) NOT NULL default '7',
      ADD COLUMN `level_group_photo` tinyint(1) NOT NULL default '1',
      ADD COLUMN `level_group_photo_width` varchar(3) NOT NULL default '200',
      ADD COLUMN `level_group_photo_height` varchar(3) NOT NULL default '200',
      ADD COLUMN `level_group_photo_exts` varchar(50) NOT NULL default 'jpeg,jpg,gif,png',
      ADD COLUMN `level_group_titles` int(1) NOT NULL default '1',
      ADD COLUMN `level_group_officers` int(1) NOT NULL default '1',
      ADD COLUMN `level_group_approval` int(1) NOT NULL default '1',
      ADD COLUMN `level_group_style` int(1) NOT NULL default '1',
      ADD COLUMN `level_group_album_exts` text NULL,
      ADD COLUMN `level_group_album_mimes` text NULL,
      ADD COLUMN `level_group_album_storage` bigint(11) NOT NULL default '5242880',
      ADD COLUMN `level_group_album_maxsize` bigint(11) NOT NULL default '2048000',
      ADD COLUMN `level_group_album_width` varchar(4) NOT NULL default '500',
      ADD COLUMN `level_group_album_height` varchar(4) NOT NULL default '500',
      ADD COLUMN `level_group_maxnum` int(3) NOT NULL default '10',
      ADD COLUMN `level_group_search` int(1) NOT NULL default '1',
      ADD COLUMN `level_group_privacy` varchar(128) NOT NULL default 'a:6:{i:0;s:1:\"3\";i:1;s:1:\"7\";i:2;s:2:\"15\";i:3;s:2:\"31\";i:4;s:2:\"63\";i:5;s:3:\"127\";}',
      ADD COLUMN `level_group_comments` varchar(128) NOT NULL default 'a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}'
    ");
    
    $database->database_query("UPDATE se_levels SET level_group_album_exts='jpg,gif,jpeg,png,bmp,mp3,mpeg,avi,mpa,mov,qt,swf', level_group_album_mimes='image/jpeg,image/pjpeg,image/jpg,image/jpe,image/pjpg,image/x-jpeg,image/x-jpg,image/gif,image/x-gif,image/png,image/x-png,image/bmp,audio/mpeg,video/mpeg,video/x-msvideo,video/avi,video/quicktime,application/x-shockwave-flash'");
  }
  
  
  if( $column_info && strtoupper($column_info['Default'])!="7" )
  {
    $sql = "ALTER TABLE se_levels CHANGE `{$column_info['Field']}` `{$column_info['Field']}` TINYINT(1) UNSIGNED NOT NULL default 7";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "UPDATE se_levels SET {$column_info['Field']}=7 WHERE {$column_info['Field']}=1";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_group_privacy'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( $column_info && strtoupper($column_info['Type'])!=="VARCHAR(128)" )
  {
    $sql = "ALTER TABLE se_levels CHANGE {$column_info['Field']} {$column_info['Field']} VARCHAR(128) NOT NULL default 'a:6:{i:0;s:1:\"3\";i:1;s:1:\"7\";i:2;s:2:\"15\";i:3;s:2:\"31\";i:4;s:2:\"63\";i:5;s:3:\"127\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "SELECT level_id, {$column_info['Field']} FROM se_levels WHERE 1";
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    while( $level_info = $database->database_fetch_assoc($resource) )
    {
      if( unserialize($level_info[$column_info['Field']]) ) continue;
      
      $sql = "UPDATE se_levels SET {$column_info['Field']}='a:6:{i:0;s:1:\"3\";i:1;s:1:\"7\";i:2;s:2:\"15\";i:3;s:2:\"31\";i:4;s:2:\"63\";i:5;s:3:\"127\";}' WHERE level_id='{$level_info['level_id']}' LIMIT 1";
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_group_comments'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( $column_info && strtoupper($column_info['Type'])!=="VARCHAR(128)" )
  {
    $sql = "ALTER TABLE se_levels CHANGE {$column_info['Field']} {$column_info['Field']} VARCHAR(128) NOT NULL default 'a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "SELECT level_id, {$column_info['Field']} FROM se_levels WHERE 1";
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    while( $level_info = $database->database_fetch_assoc($resource) )
    {
      if( unserialize($level_info[$column_info['Field']]) ) continue;
      
      $sql = "UPDATE se_levels SET {$column_info['Field']}='a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}' WHERE level_id='{$level_info['level_id']}' LIMIT 1";
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_group_upload'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( !$column_info )
  {
    $sql = "ALTER TABLE se_levels ADD COLUMN `level_group_upload`   VARCHAR(128)  NOT NULL default 'a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  elseif( $column_info && strtoupper($column_info['Type'])!=="VARCHAR(128)" )
  {
    $sql = "ALTER TABLE se_levels CHANGE {$column_info['Field']} {$column_info['Field']} VARCHAR(128) NOT NULL default 'a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "SELECT level_id, {$column_info['Field']} FROM se_levels WHERE 1";
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    while( $level_info = $database->database_fetch_assoc($resource) )
    {
      if( unserialize($level_info[$column_info['Field']]) ) continue;
      
      $sql = "UPDATE se_levels SET {$column_info['Field']}='a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}' WHERE level_id='{$level_info['level_id']}' LIMIT 1";
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_group_tag'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( !$column_info )
  {
    $sql = "ALTER TABLE se_levels ADD COLUMN `level_group_tag`   VARCHAR(128)  NOT NULL default 'a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  elseif( $column_info && strtoupper($column_info['Type'])!=="VARCHAR(128)" )
  {
    $sql = "ALTER TABLE se_levels CHANGE {$column_info['Field']} {$column_info['Field']} VARCHAR(128) NOT NULL default 'a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "SELECT level_id, {$column_info['Field']} FROM se_levels WHERE 1";
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    while( $level_info = $database->database_fetch_assoc($resource) )
    {
      if( unserialize($level_info[$column_info['Field']]) ) continue;
      
      $sql = "UPDATE se_levels SET {$column_info['Field']}='a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}' WHERE level_id='{$level_info['level_id']}' LIMIT 1";
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_group_discussion'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( !$column_info )
  {
    $sql = "ALTER TABLE se_levels ADD COLUMN `level_group_discussion`   VARCHAR(128)  NOT NULL default 'a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  elseif( $column_info && strtoupper($column_info['Type'])!=="VARCHAR(128)" )
  {
    $sql = "ALTER TABLE se_levels CHANGE {$column_info['Field']} {$column_info['Field']} VARCHAR(128) NOT NULL default 'a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "SELECT level_id, {$column_info['Field']} FROM se_levels WHERE 1";
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    while( $level_info = $database->database_fetch_assoc($resource) )
    {
      if( unserialize($level_info[$column_info['Field']]) ) continue;
      
      $sql = "UPDATE se_levels SET {$column_info['Field']}='a:8:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";i:7;s:3:\"127\";}' WHERE level_id='{$level_info['level_id']}' LIMIT 1";
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
  }
  
  
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_settings` LIKE 'setting_permission_group'")) == 0) {
    $database->database_query("ALTER TABLE se_settings 
					ADD COLUMN `setting_permission_group` int(1) NOT NULL default '1'");
  }

  //######### ADD DISCUSSION BOARD COLUMNS/VALUES TO SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_settings` LIKE 'setting_group_discussion_code'")) == 0) {
    $database->database_query("ALTER TABLE se_settings ADD COLUMN `setting_group_discussion_code` int(1) NOT NULL default '1'");
  }

  //######### ADD DISCUSSION BOARD HTML COLUMNS/VALUES TO SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_settings` LIKE 'setting_group_discussion_html'")) == 0) {
    $database->database_query("ALTER TABLE se_settings ADD COLUMN `setting_group_discussion_html` varchar(250) NOT NULL default ''");
  }
  
  
  
  //######### ADD COLUMNS/VALUES TO USER SETTINGS TABLE
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_usersettings` LIKE 'usersetting_notify_groupinvite'")) == 0) {
    $database->database_query("ALTER TABLE se_usersettings 
					ADD COLUMN `usersetting_notify_groupinvite` int(1) NOT NULL default '1',
					ADD COLUMN `usersetting_notify_groupcomment` int(1) NOT NULL default '1',
					ADD COLUMN `usersetting_notify_groupmediacomment` int(1) NOT NULL default '1',
					ADD COLUMN `usersetting_notify_groupmemberrequest` int(1) NOT NULL default '1'");
  }
  
  if($database->database_num_rows($database->database_query("SHOW COLUMNS FROM `$database_name`.`se_usersettings` LIKE 'usersetting_notify_newgrouptag'")) == 0) {
    $database->database_query("ALTER TABLE se_usersettings 
					ADD COLUMN `usersetting_notify_newgrouptag` int(1) NOT NULL default '1',
					ADD COLUMN `usersetting_notify_groupmediatag` int(1) NOT NULL default '1',
					ADD COLUMN `usersetting_notify_grouppost` int(1) NOT NULL default '1'");
  }
  
  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS NOT BEEN INSTALLED)
  if( !$database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=2000001 LIMIT 1")) )
  {
    $database->database_query("
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES 
        (2000001, 1, 'Group Settings', ''),
        (2000002, 1, 'View User Groups', ''),
        (2000003, 1, 'Global Group Settings', ''),
        (2000004, 1, 'Group Settings', ''),
        (2000005, 1, 'New Group Invitation Email', ''),
        (2000006, 1, 'This is the email that gets sent to a user when they are invited to join a group.', ''),
        (2000007, 1, 'Groups', ''),
        (2000008, 1, 'New Group Comment Email', ''),
        (2000009, 1, 'This is the email that gets sent to a user when a comment is posted on a group they lead.', ''),
        (2000010, 1, 'New Group Photo Comment Email', ''),
        (2000011, 1, 'This is the email that gets sent to a user when a comment is posted on a photo in a group they lead.', ''),
        (2000012, 1, 'New Group Membership Request Email', ''),
        (2000013, 1, 'This is the email that gets sent to a user when someone requests membership to a group they lead.', ''),
        (2000014, 1, 'Only Group Members, Their Friends, and Their Friends\' Friends', ''),
        (2000015, 1, 'Only Group Members and Their Friends', ''),
        (2000016, 1, 'Only Group Members and Group Leader\'s Friends', ''),
        (2000017, 1, 'Only Group Members', ''),
        (2000018, 1, 'Only Group Leader', '')
    ");
  }


  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS BEEN INSTALLED)
  if( !$database->database_num_rows($database->database_query("SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=2000019 LIMIT 1")) )
  {
    // INSERT LANGUAGE HERE
    $database->database_query("
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES 
        (2000019, 1, 'Photo width and height must be integers between 1 and 999.', NULL),
        (2000020, 1, 'Your maximum filesize field must contain an integer between 1 and 204800.', NULL),
        (2000021, 1, 'Your maximum width and height fields must contain integers between 1 and 9999.', NULL),
        (2000022, 1, 'Your maximum allowed albums field must contain an integer between 1 and 999.', NULL),
        (2000023, 1, 'User Group Settings', NULL),
        (2000024, 1, 'If you have enabled user groups, your users will have the option of creating groups and inviting members. This is an excellent way to encourage user interaction. Use this page to configure your user group settings.', NULL),
        (2000025, 1, 'Allow User Groups?', NULL),
        (2000026, 1, 'If you have selected YES, your users will have the option of creating and joining groups. Note that if you switch this from YES to NO, users will lose any current group memberships they have.', NULL),
        (2000027, 1, 'Yes, users can create/join groups.', NULL),
        (2000028, 1, 'No, users cannot create/join groups.', NULL),
        (2000029, 1, 'Allow Group Photos?', NULL),
        (2000030, 1, 'If you enable this feature, users will be able to upload a small photo icon when creating or editing a group. This can be displayed next to the group name on users\' profiles, in search/browse results, etc.', NULL),
        (2000031, 1, 'Yes, users can upload a photo icon when they create/edit a group.', NULL),
        (2000032, 1, 'No, users can not upload a photo icon when they create/edit a group.', NULL),
        (2000033, 1, 'If you have selected YES above, please input the maximum dimensions for the group photos. If your users upload a photo that is larger than these dimensions, the server will attempt to scale them down automatically. This feature requires that your PHP server is compiled with support for the GD Libraries.', NULL),
        (2000034, 1, 'What file types do you want to allow for group photos (gif, jpg, jpeg, or png)? Separate file types with commas, i.e. jpg, jpeg, gif, png', NULL),
        (2000035, 1, 'Allowed File Types:', NULL),
        (2000036, 1, 'Group Privacy Options', NULL),
        (2000037, 1, 'Search Privacy Options', NULL),
        (2000038, 1, 'If you enable this feature, group leaders will be able to exclude their group from search results. Otherwise, all groups will be included in search results.', NULL),
        (2000039, 1, 'Yes, allow group leaders to exclude their groups from search results.', NULL),
        (2000040, 1, 'No, force all groups to be included in search results.', NULL),
        (2000041, 1, 'Overall Group Privacy', NULL),
        (2000042, 1, 'Group leaders can choose from any of the options checked below when they decide who can view their groups. If you do not check any options, everyone will be allowed to view groups.', NULL),
        (2000043, 1, 'Group Comment Options', NULL),
        (2000044, 1, 'Group leaders can choose from any of the options checked below when they decide who can post comments on their groups. If you do not check any options, everyone will be allowed to post comments on groups.', NULL),
        (2000045, 1, 'Group Discussion Options', NULL),
        (2000046, 1, 'Group leaders can choose from any of the options checked below when they decide who can create and post in discussion topics in their groups. If you do not check any options, everyone will be allowed to post discussion topics in groups.', NULL),
        (2000047, 1, 'Allow Member Titles?', NULL),
        (2000048, 1, 'If set to YES, group owners/officers will be able to give group members special titles. (e.g. \"President\", \"Vice President\", \"Treasurer\", etc.)', NULL),
        (2000049, 1, 'Yes, allow member titles.', NULL),
        (2000050, 1, 'No, do not allow member titles.', NULL),
        (2000051, 1, 'Allow Group Officers?', NULL),
        (2000052, 1, 'If set to YES, group owners will be able to promote group members to \"officers\". Officers have all of the abilities of group owners, except that they cannot remove the group owner. Note: If this feature was previously set to YES and you change it to NO, any officers that already exist within groups will be automatically demoted to members.', NULL),
        (2000053, 1, 'Yes, allow group officers.', NULL),
        (2000054, 1, 'No, do not allow group officers.', NULL),
        (2000055, 1, 'Allow Member Approval?', NULL),
        (2000056, 1, 'Do you want to give owners and officers the ability to approve new members? If set to YES, group owners and officers will be able to turn on the \"members can join by approval only\" feature. This forces prospective members to wait for approval before they can become a group member.', NULL),
        (2000057, 1, 'Yes, optionally allow the member approval feature.', NULL),
        (2000058, 1, 'No, do not allow the member approval feature.', NULL),
        (2000059, 1, 'Allow custom CSS styles?', NULL),
        (2000060, 1, 'If you enable this feature, your users will be able to customize the colors and fonts of their groups by altering their CSS styles.', NULL),
        (2000061, 1, 'Yes, allow custom css.', NULL),
        (2000062, 1, 'No, do not allow custom css.', NULL),
        (2000063, 1, 'Group File Settings', NULL),
        (2000064, 1, 'List the following file extensions that users are allowed to upload. Separate file extensions with commas, for example: jpg, gif, jpeg, png, bmp', NULL),
        (2000065, 1, 'To successfully upload a file, the file must have an allowed filetype extension as well as an allowed MIME type. This prevents users from disguising malicious files with a fake extension. Separate MIME types with commas, for example: image/jpeg, image/gif, image/png, image/bmp', NULL),
        (2000066, 1, 'How much storage space should each group have to store its files?', NULL),
        (2000067, 1, 'Unlimited', NULL),
        (2000068, 1, 'Enter the maximum filesize for uploaded files in KB. This must be a number between 1 and 204800.', NULL),
        (2000069, 1, 'Enter the maximum width and height (in pixels) for images uploaded to groups. Images with dimensions outside this range will be sized down accordingly if your server has the GD Libraries installed. Note that unusual image types like BMP, TIFF, RAW, and others may not be resized.', NULL),
        (2000070, 1, 'Maximum Allowed Groups', NULL),
        (2000071, 1, 'Enter the maximum number of groups each user can own. This must be an integer between 1 and 999.', NULL),
        (2000072, 1, 'allowed groups', NULL),
        (2000073, 1, 'This page contains general group settings that affect your entire social network.', NULL),
        (2000074, 1, 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the <a href=\'admin_general.php\'>General Settings</a> page.', NULL),
        (2000075, 1, 'Yes, the public can view groups unless they are made private.', NULL),
        (2000076, 1, 'No, the public cannot view groups.', NULL),
        (2000077, 1, 'Require users to enter validation code when starting or posting in a discussion topic?', NULL),
        (2000078, 1, 'If you have selected Yes, an image containing a random sequence of 6 numbers will be shown to users on the \"start a topic\" and \"post topic reply\" page. Users will be required to enter these numbers into the Verification Code field in order to post their topic/reply. This feature helps prevent users from trying to create discussion topic spam. For this feature to work properly, your server must have the GD Libraries (2.0 or higher) installed and configured to work with PHP. If you are seeing errors, try turning this off.', NULL),
        (2000079, 1, 'Yes, enable validation code for discussion topics.', NULL),
        (2000080, 1, 'No, disable validation code for discussion topics.', NULL),
        (2000081, 1, 'Group Categories/Fields', NULL),
        (2000082, 1, 'You may want to allow your users to categorize their groups by subject, location, etc. Categorized groups make it easier for users to find and join groups that interest them. If you want to allow group categories, you can create them (along with subcategories) below.<br><br>Within each category, you can create group fields. When a group is created, the group creator (owner) will describe the group by filling in some fields with information about the group. Add the fields you want to include below. Some examples of group fields are \"Location\", \"Group Email\", \"Website URL\", etc. Remember that a \"Group Name\" and \"Group Description\" field will always be available and required. Drag the icons next to the categories and fields to reorder them.', NULL),
        (2000083, 1, 'Group Categories', NULL),
        (2000084, 1, 'View User-Created Groups', NULL),
        (2000085, 1, 'This page lists all of the groups that users have created on your social network. You can use this page to monitor these groups and delete offensive or unwanted material if necessary. Entering criteria into the filter fields will help you find specific groups. Leaving the filter fields blank will show all the groups on your social network.', NULL),
        (2000086, 1, 'Leader', NULL),
        (2000087, 1, 'No groups were found.', NULL),
        (2000088, 1, '%1\$d Groups Found', NULL),
        (2000089, 1, 'Members', NULL),
        (2000090, 1, 'Date Created', NULL),
        (2000091, 1, 'view', NULL),
        (2000092, 1, 'Delete Group?', NULL),
        (2000093, 1, 'Are you sure you want to delete this group?', NULL),
        (2000094, 1, 'Group Name', NULL),
        (2000095, 1, 'Create New Group', NULL),
        (2000096, 1, 'Please give us some information about your new group. After you have created your group, you can begin inviting other users to become members.', NULL),
        (2000097, 1, 'Group Details', NULL),
        (2000098, 1, 'Group Description', NULL),
        (2000099, 1, 'Group Settings', NULL),
        (2000100, 1, 'New members must request approval to join.', NULL),
        (2000101, 1, 'Browse Members: %1\$s', NULL),
        (2000102, 1, 'Use this page to list or search for group members.', NULL),
        (2000103, 1, 'Pending Invitations', NULL),
        (2000104, 1, 'Include this group in search/browse results?', NULL),
        (2000105, 1, 'Yes, include this group in search/browse results.', NULL),
        (2000106, 1, 'No, exclude this group from search/browse results.', NULL),
        (2000107, 1, 'Who can see this group?', NULL),
        (2000108, 1, 'You can decide who gets to see this group.', NULL),
        (2000109, 1, 'Allow Comments?', NULL),
        (2000110, 1, 'You can decide who can post comments in this group.', NULL),
        (2000111, 1, 'Allow Discussion Board?', NULL),
        (2000112, 1, 'You can decide who can create and post in discussion topics in this group.', NULL),
        (2000113, 1, 'Add Group', NULL),
        (2000114, 1, 'You have already created the maximum number of groups allowed. To create this new group, you must leave one of the groups you currently own.', NULL),
        (2000115, 1, 'Please enter a name for your group.', NULL),
        (2000116, 1, 'Group Category', NULL),
        (2000117, 1, 'Please select a category for this group.', NULL),
        (2000118, 1, 'Members', NULL),
        (2000119, 1, 'Group Settings', NULL),
        (2000120, 1, 'Back to My Groups', NULL),
        (2000121, 1, 'Edit Group: %1\$s', NULL),
        (2000122, 1, 'All of this group\'s details are displayed and can be changed below.', NULL),
        (2000123, 1, 'Your group was successfully created! You can add a photo and edit the group details below.', NULL),
        (2000124, 1, 'Group Photo', NULL),
        (2000125, 1, 'Images must be less than 4 MB in size with one of the following extensions: %1\$s', NULL),
        (2000126, 1, 'Browse Groups', NULL),
        (2000127, 1, 'Everyone\'s Groups', NULL),
        (2000128, 1, 'My Friends\' Groups', NULL),
        (2000129, 1, 'Most Popular', NULL),
        (2000130, 1, 'Recently Created', NULL),
        (2000131, 1, 'All Groups', NULL),
        (2000132, 1, 'No groups were found matching your criteria.', NULL),
        (2000133, 1, '%1\$d members, led by %2\$s', NULL),
        (2000134, 1, 'updated %1\$s', NULL),
        (2000135, 1, 'Group Settings: %1\$s', NULL),
        (2000136, 1, 'Edit group settings, such as your groups\' style.', NULL),
        (2000137, 1, 'Group Style', NULL),
        (2000138, 1, 'You can change the colors, fonts, and styles of your group by adding CSS code below. The contents of the text area below will be output between &lt;style&gt; tags on your group.', NULL),
        (2000139, 1, 'Member Approval', NULL),
        (2000140, 1, 'When people try to join this group, should they be allowed to join immediately, or should they be forced to wait for approval? Approving/denying members can be managed from the <a href=\'user_group_edit_members.php?group_id=%1\$d\'>members page</a>.', NULL),
        (2000141, 1, 'Note: If you turn member approval off, any new members awaiting approval will be automatically approved.', NULL),
        (2000142, 1, 'New members can join without approval.', NULL),
        (2000143, 1, 'Include this group in search/browse results?', NULL),
        (2000144, 1, 'Yes, include this group in search/browse results.', NULL),
        (2000145, 1, 'No, exclude this group from search/browse results.', NULL),
        (2000146, 1, 'Who can see this group?', NULL),
        (2000147, 1, 'You can decide who gets to see this group.', NULL),
        (2000148, 1, 'Allow Comments?', NULL),
        (2000149, 1, 'You can decide who can post comments in this group.', NULL),
        (2000150, 1, 'Allow Discussion Board?', NULL),
        (2000151, 1, 'You can decide who can create and post in discussion topics in this group.', NULL),
        (2000152, 1, 'Only Group Leader and Group Officers', NULL),
        (2000153, 1, 'My Groups', NULL),
        (2000154, 1, 'Below are all of the groups that you belong to.<br>To search for new groups to join, visit the <a href=\'browse_groups.php\'>Browse Groups page</a>. ', NULL),
        (2000155, 1, 'Group Invitations (%1\$s)', NULL),
        (2000156, 1, 'You are not a member of any groups.', NULL),
        (2000157, 1, '%1\$d member(s) - led by %2\$s', NULL),
        (2000158, 1, 'View Group', NULL),
        (2000159, 1, 'Edit Group', NULL),
        (2000160, 1, 'Leave Group', NULL),
        (2000161, 1, 'You have successfully left this group.', NULL),
        (2000162, 1, 'You have successfully joined this group.', NULL),
        (2000163, 1, 'You have successfully rejected the invitation to join this group.', NULL),
        (2000164, 1, 'You have requested to join this group. A group officer will confirm or reject your request soon.', NULL),
        (2000165, 1, 'Join this Group', NULL),
        (2000166, 1, 'Are you sure you want to leave this group?', NULL),
        (2000167, 1, '<b>Note: You are currently the owner of this group. If you leave this group now, the entire group will be deleted.</b> If you want to leave this group without deleting it, you must first transfer your ownership to another person on the <a href=\'user_group_edit_members.php?group_id=%1\$d\' target=\'_parent\'>Members page</a>.', NULL),
        (2000168, 1, 'Are you sure you want to join this group?', NULL),
        (2000169, 1, 'Current Members', NULL),
        (2000170, 1, 'Only Officers', NULL),
        (2000171, 1, 'View:', NULL),
        (2000172, 1, 'Member Title', NULL),
        (2000173, 1, 'Member Rank', NULL),
        (2000174, 1, 'Invite New Members', NULL),
        (2000175, 1, 'Membership Requests', NULL),
        (2000176, 1, 'No members were found matching your criteria.', NULL),
        (2000177, 1, 'Delete this group.', NULL),
        (2000178, 1, 'Member Rank: %1\$s', NULL),
        (2000179, 1, 'Owner', NULL),
        (2000180, 1, 'Officer', NULL),
        (2000181, 1, 'Member', NULL),
        (2000182, 1, 'Member Rank: %1\$s (%2\$s)', NULL),
        (2000183, 1, 'Last Update:', NULL),
        (2000184, 1, 'Edit Member Details', NULL),
        (2000185, 1, 'Remove Member', NULL),
        (2000186, 1, 'Are you sure you want to delete the group \"%1\$s\"? All of its content will be permanently deleted, and all members will be removed.', NULL),
        (2000187, 1, 'When people try to join this group, should they be allowed to join immediately, or should they be forced to wait for approval?', NULL),
        (2000188, 1, 'Grant Membership', NULL),
        (2000189, 1, 'Reject Request', NULL),
        (2000190, 1, 'Cancel Invitation', NULL),
        (2000191, 1, 'Are you sure you want to remove this member from the group?', NULL),
        (2000192, 1, 'Member Title:', NULL),
        (2000193, 1, 'Member Rank:', NULL),
        (2000194, 1, 'You are currently the group owner. If you want to give your ownership to another member, find them on the <a href=\'user_group_edit_members.php?group_id=%1\$s\' target=\'_parent\'>Group Members</a> page and edit their membership. When you make them the group owner, you will be demoted to the rank of group member.', NULL),
        (2000195, 1, '<b>Warning:</b> You are about to transfer your ownership of this group to this person. Are you sure you want to make this person the new group owner? You will be demoted to member and logged out of the edit group area!', NULL),
        (2000196, 1, 'To invite a friend to join this group, check the box next to their name below. Remember that even if this group is set to be viewable by \"members only\", people that you invite will be able to view the group as though they are members.', NULL),
        (2000197, 1, 'Your invitations have been successfully sent.', NULL),
        (2000198, 1, 'You must invite at least one user to join this group.', NULL),
        (2000199, 1, 'Select All', NULL),
        (2000200, 1, 'Unselect All', NULL),
        (2000201, 1, 'Send Invitations', NULL),
        (2000202, 1, 'You have no friends that are available to join this group.', NULL),
        (2000203, 1, 'Accept/Reject Invitation', NULL),
        (2000204, 1, 'Do you want to accept or reject the invitation to join this group?', NULL),
        (2000205, 1, 'Accept Invitation', NULL),
        (2000206, 1, 'Reject Invitation', NULL),
        (2000207, 1, 'You must wait for the approval of your membership request by a group officer.', NULL),
        (2000208, 1, 'Group Album Upload Options', NULL),
        (2000209, 1, 'Group leaders can choose from any of the options checked below when they decide who can upload photos to their group. If you do not check any options, everyone will be allowed to upload photos to groups.', NULL),
        (2000210, 1, 'Group Album Tag Options', NULL),
        (2000211, 1, 'Group leaders can choose from any of the options checked below when they decide who can tag photos in their group. If you do not check any options, everyone will be allowed to tag photos in groups.', NULL),
        (2000212, 1, 'Allow Uploads?', NULL),
        (2000213, 1, 'You can decide who can upload photos to this group.', NULL),
        (2000214, 1, 'Allow Photo Tagging?', NULL),
        (2000215, 1, 'You can decide who can tag photos within this group.', NULL),
        (2000216, 1, 'Allow group members to invite users?', NULL),
        (2000217, 1, 'Yes, allow group members to invite their friends to join.', NULL),
        (2000218, 1, 'No, only the group leader and group officers may invite users to join.', NULL),
        (2000219, 1, 'The group you are looking for has been deleted or does not exist.', NULL),
        (2000220, 1, 'Group Members', NULL),
        (2000221, 1, 'Search Members', NULL),
        (2000222, 1, 'None of the group members match your search criteria.', NULL),
        (2000223, 1, 'Awaiting Membership Approval', NULL),
        (2000224, 1, 'Subscribe to Group', NULL),
        (2000225, 1, 'Unsubscribe to Group', NULL),
        (2000226, 1, 'Report this Group', NULL),
        (2000227, 1, 'Private Group', NULL),
        (2000228, 1, 'You do not have permission to view this group.', NULL),
        (2000229, 1, 'Group Officers', NULL),
        (2000230, 1, '(leader)', NULL),
        (2000231, 1, 'Group Details', NULL),
        (2000232, 1, 'Photos', NULL),
        (2000233, 1, 'Discussions', NULL),
        (2000234, 1, 'Are you sure you want to subscribe to this group? Once you subscribe, you will receive notifications on your \"What\'s New\" page whenever a comment, photo, or discussion topic is posted in this group.', NULL),
        (2000235, 1, 'Subscribe', NULL),
        (2000236, 1, 'Are you sure you want to unsubscribe to this group? You will no longer receive notifications on your \"What\'s New\" page when there is activity in this group.', NULL),
        (2000237, 1, 'Unsubscribe', NULL),
        (2000238, 1, 'You have successfully subscribed to this group.', NULL),
        (2000239, 1, 'You have successfully unsubscribed to this group.', NULL),
        (2000240, 1, 'Group Subscriptions', NULL),
        (2000241, 1, '%1\$s New Comment(s)', NULL),
        (2000242, 1, '%1\$s New Post(s)', NULL),
        (2000243, 1, '%1\$s New Photo(s)', NULL),
        (2000244, 1, 'No new updates.', NULL),
        (2000245, 1, 'There are no group updates at this time.', NULL),
        (2000246, 1, 'To upload photos from your computer to this group, click the \"Browse\" button, locate them on your computer, then click the \"Upload\" button.', NULL),
        (2000247, 1, 'This group has %1\$s MB of free space remaining.', NULL),
        (2000248, 1, '%1\$s was uploaded successfully.', NULL),
        (2000249, 1, 'You may upload files with sizes up to %1\$s KB and with the following extensions: %2\$s', NULL),
        (2000250, 1, 'This group does not have enough free space to upload %1\$s.', NULL),
        (2000251, 1, 'Add New Photos', NULL),
        (2000252, 1, 'No photos have been added to this group yet.', NULL),
        (2000253, 1, 'What\'s New', NULL),
        (2000254, 1, 'Group Information', NULL),
        (2000255, 1, 'Description', NULL),
        (2000256, 1, 'Category', NULL),
        (2000257, 1, 'Discussion Topics', NULL),
        (2000258, 1, 'Start New Topic', NULL),
        (2000259, 1, 'No discussion topics have been started in this group yet.', NULL),
        (2000260, 1, '<div style=\'font-size: 12pt;\'>%1\$s</div> replies', NULL),
        (2000261, 1, 'Created %1\$s by <a href=\'%2\$s\'>%3\$s</a>', NULL),
        (2000262, 1, '%1\$s views', NULL),
        (2000263, 1, '<a href=\'%1\$s\'>Last post</a> by <a href=\'%2\$s\'>%3\$s</a>', NULL),
        (2000264, 1, 'Created %1\$s by %2\$s', NULL),
        (2000265, 1, '<a href=\'%1\$s\'>Last post</a> by %2\$s', NULL),
        (2000266, 1, 'Delete Topic', NULL),
        (2000267, 1, 'Are you sure you want to delete this discussion topic?', NULL),
        (2000268, 1, 'download audio', NULL),
        (2000269, 1, 'download video', NULL),
        (2000270, 1, 'download this file', NULL),
        (2000271, 1, 'Viewing #%1\$d of %2\$d in <a href=\'%3\$s\'>%4\$s</a>', NULL),
        (2000272, 1, 'Last', NULL),
        (2000273, 1, 'Next', NULL),
        (2000274, 1, 'In this photo: ', NULL),
        (2000275, 1, 'Add Tag', NULL),
        (2000276, 1, 'Uploaded %1\$s by <a href=\'%2\$s\'>%3\$s</a>', NULL),
        (2000277, 1, 'Uploaded %1\$s', NULL),
        (2000278, 1, 'Share This', NULL),
        (2000279, 1, 'To share this photo or embed it on another web page, please copy and paste the code of your choosing:', NULL),
        (2000280, 1, 'Direct Link', NULL),
        (2000281, 1, 'HTML - Embedded Image', NULL),
        (2000282, 1, 'HTML - Text Link', NULL),
        (2000283, 1, 'UBB Code (for forums)', NULL),
        (2000284, 1, 'Close Window', NULL),
        (2000285, 1, 'Edit Photo Details', NULL),
        (2000286, 1, 'Delete Photo', NULL),
        (2000287, 1, 'Are you sure you want to delete this photo?', NULL),
        (2000288, 1, 'Enter a title and description for this photo in the fields below.', NULL),
        (2000289, 1, 'Title:', NULL),
        (2000290, 1, 'Description:', NULL),
        (2000291, 1, '%1\$d groups', NULL),
        (2000292, 1, 'Group: %1\$s', NULL),
        (2000293, 1, 'Group Photo: %1\$s', NULL),
        (2000294, 1, 'Discussion Post: %1\$s', NULL),
        (2000295, 1, '%1\$s', NULL),
        (2000296, 1, 'Media posted in <a href=\'%1\$s\'>%2\$s</a><br>%3\$s', NULL),
        (2000297, 1, 'Topic posted in <a href=\'%1\$s\'>%2\$s</a><br>%3\$s', NULL),
        (2000298, 1, 'Please enter a message to post.', NULL),
        (2000299, 1, 'Please enter a subject.', NULL),
        (2000300, 1, 'Topic Subject', NULL),
        (2000301, 1, 'Your Message', NULL),
        (2000302, 1, 'Post Topic', NULL),
        (2000303, 1, 'Back to Discussion Board', NULL),
        (2000304, 1, 'Reply to Topic', NULL),
        (2000305, 1, 'Make Topic Sticky', NULL),
        (2000306, 1, 'Close Topic', NULL),
        (2000307, 1, 'Posted at %1\$s on %2\$s', NULL),
        (2000308, 1, 'Edit Post', NULL),
        (2000309, 1, 'Delete Post', NULL),
        (2000310, 1, 'Reply To Topic:', NULL),
        (2000311, 1, 'Post Reply', NULL),
        (2000312, 1, '%1\$s', NULL),
        (2000313, 1, '%1\$s', NULL),
        (2000314, 1, '%1\$s: %2\$s', NULL),
        (2000315, 1, 'Unstick Topic', NULL),
        (2000316, 1, 'Open Topic', NULL),
        (2000317, 1, 'Rename Topic', NULL),
        (2000318, 1, 'Rename/Delete Topic', NULL),
        (2000319, 1, 'Use the form below to rename or delete this topic.', NULL),
        (2000320, 1, 'Are you sure you want to delete this post?', NULL),
        (2000321, 1, 'This post has been deleted.', NULL),
        (2000322, 1, 'Quote', NULL),
        (2000323, 1, '%1\$s said:', NULL),
        (2000324, 1, 'Browse Groups', NULL),
        (2000325, 1, 'Browse the groups on our social network.', NULL),
        (2000326, 1, '%1\$s: View Photo', NULL),
        (2000327, 1, 'A photo posted to the %1\$s group.', NULL),
        (2000328, 1, 'Post New Topic', NULL),
        (2000329, 1, 'Post a new topic to this group.', NULL),
        (2000330, 1, 'HTML in Discussion Posts', NULL),
        (2000331, 1, 'By default, the user may not enter any HTML tags into discussion posts. If you want to allow specific tags, you can enter them below (separated by commas). Example: <i>b, img, a, embed, font<i>', NULL),
        (2000332, 1, 'Notification of Being Tagged in Group Photo', NULL),
        (2000333, 1, 'This is the email that gets sent to a user when someone tags them in a group photo.', NULL),
        (2000334, 1, 'New Group Photo Tag Email', NULL),
        (2000335, 1, 'This is the email that gets sent to a user when someone tags one of the photos in a group they lead.', NULL),
        (2000336, 1, 'New Group Discussion Post Email', NULL),
        (2000337, 1, 'This is the email that gets sent to a user when a discussion post is posted on a group they lead.', NULL)
    ") or die(mysql_error());
  }
  
  
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=2000338 LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES 
        (2000338, 1, 'Groups: %1\$d groups', 'home'),
        (2000339, 1, 'Group Comments: %1\$d comments', 'home'),
        (2000340, 1, 'Group Discussion Topics: %1\$d topics', 'home'),
        (2000341, 1, 'Group Discussion Posts: %1\$d posts', 'home'),
        (2000342, 1, 'Group Media: %1\$d media', 'home'),
        (2000343, 1, 'Group Members: %1\$d memberships', 'home')
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=2000344 LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES 
        (2000344, 1, 'Creating a Group', 'actiontypes'),
        (2000345, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> created a new group: <a href=\"group.php?group_id=%3\$s\">%4\$s</a>', 'actiontypes'),
        (2000346, 1, 'Joining a Group', 'actiontypes'),
        (2000347, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> joined a group: <a href=\"group.php?group_id=%3\$s\">%4\$s</a>', 'actiontypes'),
        (2000348, 1, 'Leaving a Group', 'actiontypes'),
        (2000349, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> left a group: <a href=\"group.php?group_id=%3\$s\">%4\$s</a>', 'actiontypes'),
        (2000350, 1, 'Posting a Group Comment', 'actiontypes'),
        (2000351, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> posted a comment on the group <a href=\"group.php?group_id=%6\$s\">%7\$s</a>:<div class=\"recentaction_div\">%5\$s</div>', 'actiontypes'),
        (2000352, 1, 'Posting a Group Media Comment', 'actiontypes'),
        (2000353, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> posted a comment on the group %7\$s\'s <a href=\"group_album_file.php?group_id=%8\$s&groupmedia_id=%6\$s\">photo</a>:<div class=\"recentaction_div\">%5\$s</div>', 'actiontypes'),
        (2000354, 1, 'Uploading Photos to a Group', 'actiontypes'),
        (2000355, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> uploaded new photos to the group <a href=\"group.php?group_id=%3\$s&v=photos\">%4\$s</a><div class=\"recentaction_div_media\">[media]</div>', 'actiontypes'),
        (2000356, 1, 'Getting Tagged in a Group Photo', 'actiontypes'),
        (2000357, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> was tagged in these photos:<div class=\"recentaction_div_media\">[media]</div>', 'actiontypes'),
        (2000358, 1, 'Starting a Group Discussion Topic', 'actiontypes'),
        (2000359, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> posted a discussion topic \"<a href=\"group_discussion_view.php?group_id=%3\$s&grouptopic_id=%5\$s\">%6\$s</a>\" in the group <a href=\"group.php?group_id=%3\$s&v=discussions\">%4\$s</a>:<div class=\"recentaction_div\">%7\$s</div>', 'actiontypes'),
        (2000360, 1, 'Posting in a Group Discussion Topic', 'actiontypes'),
        (2000361, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> posted in the group discussion topic \"<a href=\"group_discussion_view.php?group_id=%3\$s&grouptopic_id=%4\$s&grouppost_id=%6\$s\">%5\$s</a>\":<div class=\"recentaction_div\">%7\$s</div>', 'actiontypes'),
        (2000362, 1, '%1\$d New Group Comment(s): %2\$s', 'notifytypes'),
        (2000363, 1, 'When a new comment is posted on a group I lead.', 'notifytypes'),
        (2000364, 1, '%1\$d New Group Photo Comment(s): %2\$s', 'notifytypes'),
        (2000365, 1, 'When a new comment is posted on a photo in a group I lead.', 'notifytypes'),
        (2000366, 1, '%1\$d New Group Invitation(s)', 'notifytypes'),
        (2000367, 1, 'When I receive a group invitation.', 'notifytypes'),
        (2000368, 1, '%1\$d New Group Member Request(s)', 'notifytypes'),
        (2000369, 1, 'When I receive an invitation request for a group I created.', 'notifytypes'),
        (2000370, 1, '%1\$d New Photo(s) Tagged of You: %2\$s', 'notifytypes'),
        (2000371, 1, 'When I am tagged in a group photo.', 'notifytypes'),
        (2000372, 1, '%1\$d New Tag(s) on Your Group\'s Photo: %2\$s', 'notifytypes'),
        (2000373, 1, 'When someone tags a photo in a group I lead.', 'notifytypes'),
        (2000374, 1, '%1\$d New Group Post(s): %2\$s', 'notifytypes'),
        (2000375, 1, 'When someone posts in a discussion topic in a group I lead.', 'notifytypes'),
        (2000376, 1, 'You have been invited to join %2\$s.', 'systememails'),
        (2000377, 1, 'Hello %1\$s,\n\nYou have been invited to join a group named %2\$s. Please click the following link to login:\n\n%3\$s\n\nBest Regards,\nSocial Network Administration', 'systememails'),
        (2000378, 1, 'New Group Comment', 'systememails'),
        (2000379, 1, 'Hello %1\$s,\n\nA new comment has been posted by %2\$s about a group you lead. Please click the following link to view it:\n\n%3\$s\n\nBest Regards,\nSocial Network Administration', 'systememails'),
        (2000380, 1, 'New Group Photo Comment', 'systememails'),
        (2000381, 1, 'Hello %1\$s,\n\nA new comment has been posted by %2\$s on a photo in a group you lead. Please click the following link to view it:\n\n%3\$s\n\nBest Regards,\nSocial Network Administration', 'systememails'),
        (2000382, 1, 'New Group Membership Request', 'systememails'),
        (2000383, 1, 'Hello %1\$s,\n\n%2\$s would like to join your group \"%3\$s\". Please click the following link to login and confirm their membership:\n\n%4\$s\n\nBest Regards,\nSocial Network Administration', 'systememails'),
        (2000384, 1, 'You have Been Tagged in a Group Photo!', 'systememails'),
        (2000385, 1, 'Hello %1\$s,\n\nYou have been tagged in a group photo. Please click the following link to view it:\n\n%2\$s\n\nBest Regards,\nSocial Network Administration', 'systememails'),
        (2000386, 1, 'New Group Photo Tag', 'systememails'),
        (2000387, 1, 'Hello %1\$s,\n\nA new tag has been posted on one of the photos in a group you lead by %2\$s. Please click the following link to view it:\n\n%3\$s\n\nBest Regards,\nSocial Network Administration', 'systememails'),
        (2000388, 1, 'New Group Discussion Post', 'systememails'),
        (2000389, 1, 'Hello %1\$s,\n\nA new discussion post has been posted by %2\$s in a group you lead. Please click the following link to view it:\n\n%3\$s\n\nBest Regards,\nSocial Network Administration', 'systememails')
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=2000390 LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES
        (2000390, 1, 'Users may view, join, and create groups.', 'admin_levels_groupsettings'),
        (2000391, 1, 'Users may view and join groups.', 'admin_levels_groupsettings'),
        (2000392, 1, 'Users may only view groups.', 'admin_levels_groupsettings'),
        (2000393, 1, 'Users may not use groups.', 'admin_levels_groupsettings')
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  


  ################ UPGRADE EXISTING GROUPS' PRIVACY OPTIONS
  if( !empty($plugin_info) && version_compare($plugin_info['plugin_version'], '3.00', '<') )
  {
    $database->database_query("UPDATE se_groups SET group_privacy='255'  WHERE group_privacy='0' ") or die($database->database_error()." View Privacy Query #1");
    $database->database_query("UPDATE se_groups SET group_privacy='127'  WHERE group_privacy='1' ") or die($database->database_error()." View Privacy Query #2");
    $database->database_query("UPDATE se_groups SET group_privacy='63'  WHERE group_privacy='2' ") or die($database->database_error()." View Privacy Query #3");
    $database->database_query("UPDATE se_groups SET group_privacy='31'   WHERE group_privacy='3' ") or die($database->database_error()." View Privacy Query #4");
    $database->database_query("UPDATE se_groups SET group_privacy='15'   WHERE group_privacy='4' ") or die($database->database_error()." View Privacy Query #5");
    $database->database_query("UPDATE se_groups SET group_privacy='7'   WHERE group_privacy='5' ") or die($database->database_error()." View Privacy Query #6");
    $database->database_query("UPDATE se_groups SET group_privacy='1'   WHERE group_privacy='6' ") or die($database->database_error()." View Privacy Query #7");
    
    $database->database_query("UPDATE se_groups SET group_comments='255' WHERE group_comments='0'") or die($database->database_error()." Comment Privacy Query #1");
    $database->database_query("UPDATE se_groups SET group_comments='127' WHERE group_comments='1'") or die($database->database_error()." Comment Privacy Query #2");
    $database->database_query("UPDATE se_groups SET group_comments='63' WHERE group_comments='2'") or die($database->database_error()." Comment Privacy Query #3");
    $database->database_query("UPDATE se_groups SET group_comments='31'  WHERE group_comments='3'") or die($database->database_error()." Comment Privacy Query #4");
    $database->database_query("UPDATE se_groups SET group_comments='15'  WHERE group_comments='4'") or die($database->database_error()." Comment Privacy Query #5");
    $database->database_query("UPDATE se_groups SET group_comments='7'  WHERE group_comments='5'") or die($database->database_error()." Comment Privacy Query #6");
    $database->database_query("UPDATE se_groups SET group_comments='1'  WHERE group_comments='6'") or die($database->database_error()." Comment Privacy Query #7");
    $database->database_query("UPDATE se_groups SET group_comments='0'  WHERE group_comments='7'") or die($database->database_error()." Comment Privacy Query #7");
    
    $database->database_query("UPDATE se_groups SET group_discussion='255' WHERE group_discussion='0'") or die($database->database_error()." Comment Privacy Query #1");
    $database->database_query("UPDATE se_groups SET group_discussion='127' WHERE group_discussion='1'") or die($database->database_error()." Comment Privacy Query #2");
    $database->database_query("UPDATE se_groups SET group_discussion='63' WHERE group_discussion='2'") or die($database->database_error()." Comment Privacy Query #3");
    $database->database_query("UPDATE se_groups SET group_discussion='31'  WHERE group_discussion='3'") or die($database->database_error()." Comment Privacy Query #4");
    $database->database_query("UPDATE se_groups SET group_discussion='15'  WHERE group_discussion='4'") or die($database->database_error()." Comment Privacy Query #5");
    $database->database_query("UPDATE se_groups SET group_discussion='7'  WHERE group_discussion='5'") or die($database->database_error()." Comment Privacy Query #6");
    $database->database_query("UPDATE se_groups SET group_discussion='1'  WHERE group_discussion='6'") or die($database->database_error()." Comment Privacy Query #7");
    $database->database_query("UPDATE se_groups SET group_discussion='0'  WHERE group_discussion='7'") or die($database->database_error()." Comment Privacy Query #7");
  }
}  

?>
