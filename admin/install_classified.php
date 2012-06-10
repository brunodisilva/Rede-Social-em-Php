<?php

/* $Id: install_classified.php 16 2009-01-13 04:01:31Z john $ */

$plugin_name = "Classified Plugin";
$plugin_version = "3.03";
$plugin_type = "classified";
$plugin_desc = "This plugin allows your users to post classified listings. As the admin, you create the categories (like \"For Sale\", \"Jobs\", \"Personals\", etc.) and your users can post relevant listings. Your users will also be able to search for other listings via a \"browse marketplace\" area, and each users' listings will appear on their profile.";
$plugin_icon = "classified_classified16.gif";
$plugin_menu_title = "4500001";
$plugin_pages_main = "4500002<!>classified_classified16.gif<!>admin_viewclassifieds.php<~!~>4500003<!>classified_classified16.gif<!>admin_classified.php<~!~>";
$plugin_pages_level = "4500004<!>admin_levels_classifiedsettings.php<~!~>";
$plugin_url_htaccess = "RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^/]+)/classifieds/([0-9]+)/?$ \$server_info/classified.php?user=\$1&classified_id=\$2 [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^/]+)/classifieds/([0-9]+)/([^/]+)?$ \$server_info/classified.php?user=\$1&classified_id=\$2\$3 [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^/]+)/classifieds/?$ \$server_info/classifieds.php?user=\$1 [L]";
$plugin_db_charset = 'utf8';
$plugin_db_collation = 'utf8_unicode_ci';
$plugin_reindex_totals = TRUE;




if($install == "classified")
{
  //######### GET CURRENT PLUGIN INFORMATION
  $sql = "SELECT * FROM se_plugins WHERE plugin_type='$plugin_type' LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  $plugin_info = array();
  if( $database->database_num_rows($resource) )
    $plugin_info = $database->database_fetch_assoc($resource);
  
  // Uncomment this line if you already upgraded to v3, but are having issues with everything being private
  //$plugin_info['plugin_version'] = '2.00';
  
  
  
  
  //######### INSERT ROW INTO se_plugins
  $sql = "SELECT plugin_id FROM se_plugins WHERE plugin_type='$plugin_type'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO se_plugins (
        plugin_name,
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
        '$plugin_url_htaccess'
      )
    ";
    
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  //######### UPDATE PLUGIN VERSION IN se_plugins
  else
  {
    $sql = "
      UPDATE
        se_plugins
      SET
        plugin_name='$plugin_name',
        plugin_version='$plugin_version',
        plugin_desc='".str_replace("'", "\'", $plugin_desc)."',
        plugin_icon='$plugin_icon',
        plugin_menu_title='$plugin_menu_title',
        plugin_pages_main='$plugin_pages_main',
        plugin_pages_level='$plugin_pages_level',
        plugin_url_htaccess='$plugin_url_htaccess'
      WHERE
        plugin_type='$plugin_type'
    ";
    
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_classifiedalbums
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_classifiedalbums'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_classifiedalbums` (
        `classifiedalbum_id`              INT       UNSIGNED  NOT NULL auto_increment,
        `classifiedalbum_classified_id`   INT       UNSIGNED  NOT NULL default 0,
        `classifiedalbum_datecreated`     INT                 NOT NULL default 0,
        `classifiedalbum_dateupdated`     INT                 NOT NULL default 0,
        `classifiedalbum_title`           VARCHAR(64)             NULL,
        `classifiedalbum_desc`            TEXT                    NULL,
        `classifiedalbum_search`          TINYINT   UNSIGNED  NOT NULL default 0,
        `classifiedalbum_privacy`         TINYINT   UNSIGNED  NOT NULL default 0,
        `classifiedalbum_comments`        TINYINT   UNSIGNED  NOT NULL default 0,
        `classifiedalbum_cover`           INT       UNSIGNED  NOT NULL default 0,
        `classifiedalbum_views`           INT       UNSIGNED  NOT NULL default 0,
        `classifiedalbum_totalfiles`      SMALLINT  UNSIGNED  NOT NULL default 0,
        `classifiedalbum_totalspace`      BIGINT    UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`classifiedalbum_id`),
        KEY `INDEX` (`classifiedalbum_classified_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Add classifiedalbum_totalfiles
  $sql = "SHOW COLUMNS FROM `se_classifiedalbums` LIKE 'classifiedalbum_totalfiles'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_classifiedalbums ADD COLUMN `classifiedalbum_totalfiles` SMALLINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  if( !$database->database_num_rows($resource) || $plugin_reindex_totals )
  {
    $sql = "SELECT classifiedalbum_id FROM se_classifiedalbums WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_classifiedalbums SET classifiedalbum_totalfiles=(SELECT COUNT(classifiedmedia_id) FROM se_classifiedmedia WHERE classifiedmedia_classifiedalbum_id=classifiedalbum_id) WHERE classifiedalbum_id='{$result['classifiedalbum_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  // Add classifiedalbum_totalspace
  $sql = "SHOW COLUMNS FROM `se_classifiedalbums` LIKE 'classifiedalbum_totalspace'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_classifiedalbums ADD COLUMN `classifiedalbum_totalspace` BIGINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  if( !$database->database_num_rows($resource) || $plugin_reindex_totals )
  {
    $sql = "SELECT classifiedalbum_id FROM se_classifiedalbums WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_classifiedalbums SET classifiedalbum_totalspace=(SELECT SUM(classifiedmedia_filesize) FROM se_classifiedmedia WHERE classifiedmedia_classifiedalbum_id=classifiedalbum_id) WHERE classifiedalbum_id='{$result['classifiedalbum_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  // Ensure utf8 on classifiedalbum_title
  $sql = "SHOW FULL COLUMNS FROM `se_classifiedalbums` LIKE 'classifiedalbum_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_classifiedalbums MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on classifiedalbum_desc
  $sql = "SHOW FULL COLUMNS FROM `se_classifiedalbums` LIKE 'classifiedalbum_desc'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_classifiedalbums MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_classifiedcats
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_classifiedcats'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_classifiedcats` (
        `classifiedcat_id`          INT         UNSIGNED  NOT NULL auto_increment,
        `classifiedcat_dependency`  INT         UNSIGNED  NOT NULL default 0,
        `classifiedcat_title`       INT         UNSIGNED  NOT NULL default 0,
        `classifiedcat_order`       SMALLINT    UNSIGNED  NOT NULL default 0,
        `classifiedcat_signup`      TINYINT     UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`classifiedcat_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  //######### ALTER se_classifiedcats LANGUAGIFY classifiedcat_title
  $sql = "SHOW FULL COLUMNS FROM `se_classifiedcats` LIKE 'classifiedcat_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  // Fix collation, load data, drop column
  $classifiedcat_info = array();
  if( $column_info && strtoupper(substr($column_info['Type'], 0, 7))=="VARCHAR" )
  {
    // Fix collation
    if( $column_info['Collation']!=$plugin_db_collation )
    {
      $sql = "ALTER TABLE se_classifiedcats MODIFY {$column_info['Field']} {$column_info['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
    
    // Languagify title column
    $sql = "SELECT * FROM se_classifiedcats";
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    if( $database->database_num_rows($resource) )
      while( $result=$database->database_fetch_assoc($resource) )
        $classifiedcat_info[] = $result;
    
    // Drop column
    $sql = "ALTER TABLE se_classifiedcats DROP COLUMN classifiedcat_title";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    unset($column_info);
  }
  
  // Add column
  if( !$column_info )
  {
    $sql = "ALTER TABLE se_classifiedcats ADD COLUMN classifiedcat_title INT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  // Update column
  if( !empty($classifiedcat_info) )
  {
    // Update title
    foreach( $classifiedcat_info as $classifiedcat_info_array )
    {
      $classifiedcat_title_lvid = SE_Language::edit(0, $classifiedcat_info_array['classifiedcat_title'], NULL, LANGUAGE_INDEX_FIELDS);
      
      $sql = "
        UPDATE
          se_classifiedcats
        SET
          classifiedcat_title='{$classifiedcat_title_lvid}'
        WHERE
          classifiedcat_id='{$classifiedcat_info_array['classifiedcat_id']}'
        LIMIT
          1
      ";
      
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
  }
  
  
  
  
  //######### ALTER se_classifiedcats ADD COLUMNS
  $sql = "SHOW COLUMNS FROM `se_classifiedcats` LIKE 'classifiedcat_order'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE se_classifiedcats
      ADD COLUMN classifiedcat_order  SMALLINT  UNSIGNED  NOT NULL default 0,
      ADD COLUMN classifiedcat_signup TINYINT   UNSIGNED  NOT NULL default 0
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### INSERT se_classifiedcats
  $sql = "SELECT NULL FROM se_classifiedcats";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $classifiedcat_title_lvid = SE_Language::edit(0, "Other", NULL, LANGUAGE_INDEX_FIELDS);
    $sql = "INSERT INTO se_classifiedcats (classifiedcat_title) VALUES ('{$classifiedcat_title_lvid}')";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_classifiedcomments
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_classifiedcomments'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_classifiedcomments` (
        `classifiedcomment_id`              INT         UNSIGNED  NOT NULL auto_increment,
        `classifiedcomment_classified_id`   INT         UNSIGNED  NOT NULL default 0,
        `classifiedcomment_authoruser_id`   INT         UNSIGNED  NOT NULL default 0,
        `classifiedcomment_date`            INT         UNSIGNED  NOT NULL default 0,
        `classifiedcomment_body`            TEXT                      NULL,
        PRIMARY KEY  (`classifiedcomment_id`),
        KEY `INDEX` (`classifiedcomment_classified_id`,`classifiedcomment_authoruser_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on classifiedcomment_body
  $sql = "SHOW FULL COLUMNS FROM `se_classifiedcomments` LIKE 'classifiedcomment_body'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_classifiedcomments MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_classifiedfields
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_classifiedfields'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_classifiedfields` (
        `classifiedfield_id`                INT           UNSIGNED  NOT NULL auto_increment,
        `classifiedfield_classifiedcat_id`  INT           UNSIGNED  NOT NULL default 0,
        `classifiedfield_order`             SMALLINT      UNSIGNED  NOT NULL default 0,
        `classifiedfield_dependency`        INT           UNSIGNED  NOT NULL default 0,
        `classifiedfield_title`             INT           UNSIGNED  NOT NULL default 0,
        `classifiedfield_desc`              INT           UNSIGNED  NOT NULL default 0,
        `classifiedfield_error`             INT           UNSIGNED  NOT NULL default 0,
        `classifiedfield_type`              TINYINT       UNSIGNED  NOT NULL default 0,
        `classifiedfield_style`             VARCHAR(255)                NULL,
        `classifiedfield_maxlength`         SMALLINT      UNSIGNED  NOT NULL default 0,
        `classifiedfield_link`              VARCHAR(255)                NULL,
        `classifiedfield_options`           LONGTEXT                    NULL,
        `classifiedfield_required`          TINYINT       UNSIGNED  NOT NULL default 0,
        `classifiedfield_regex`             VARCHAR(255)                NULL,
        `classifiedfield_html`              VARCHAR(255)                NULL,
        `classifiedfield_search`            TINYINT       UNSIGNED  NOT NULL default 0,
        `classifiedfield_signup`            TINYINT       UNSIGNED  NOT NULL default 0,
        `classifiedfield_display`           TINYINT       UNSIGNED  NOT NULL default 0,
        `classifiedfield_special`           TINYINT       UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`classifiedfield_id`),
        KEY `INDEX` (`classifiedfield_classifiedcat_id`,`classifiedfield_dependency`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### ALTER se_classifiedfields LANGUAGIFY classifiedfield_title,classifiedfield_desc,classifiedfield_error
  $sql = "SHOW FULL COLUMNS FROM `se_classifiedfields` LIKE 'classifiedfield_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  // Fix collation, load text, drop columns
  $classifiedfield_info = array();
  if( $column_info && strtoupper(substr($column_info['Type'], 0, 7))=="VARCHAR" )
  {
    // Fix collation
    if( $column_info['Collation']!=$plugin_db_collation )
    {
      $sql = "
        ALTER TABLE se_classifiedfields
        MODIFY classifiedfield_title  VARCHAR(255) CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation} NOT NULL default '',
        MODIFY classifiedfield_desc   VARCHAR(255) CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation} NOT NULL default '',
        MODIFY classifiedfield_error  VARCHAR(255) CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation} NOT NULL default ''
      ";
      
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
    
    // Load title column
    $sql = "SELECT * FROM se_classifiedfields";
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    if( $database->database_num_rows($resource) )
      while( $result=$database->database_fetch_assoc($resource) )
        $classifiedfield_info[] = $result;
    
    // Crop column
    $sql = "ALTER TABLE se_classifiedfields DROP COLUMN classifiedfield_title, DROP COLUMN classifiedfield_desc, DROP COLUMN classifiedfield_error";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    unset($column_info);
  }
  
  // Add columns
  if( !$column_info )
  {
    $sql = "
      ALTER TABLE se_classifiedfields
      ADD COLUMN classifiedfield_title  INT UNSIGNED NOT NULL default 0,
      ADD COLUMN classifiedfield_desc   INT UNSIGNED NOT NULL default 0,
      ADD COLUMN classifiedfield_error  INT UNSIGNED NOT NULL default 0
    ";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  // Update column
  if( !empty($classifiedfield_info) )
  {
    // Update column
    foreach( $classifiedfield_info as $classifiedfield_info_array )
    {
      $classifiedfield_title_lvid = SE_Language::edit(0, $classifiedfield_info_array['classifiedfield_title'], NULL, LANGUAGE_INDEX_FIELDS);
      $classifiedfield_desc_lvid  = SE_Language::edit(0, $classifiedfield_info_array['classifiedfield_desc' ], NULL, LANGUAGE_INDEX_FIELDS);
      $classifiedfield_error_lvid = SE_Language::edit(0, $classifiedfield_info_array['classifiedfield_error'], NULL, LANGUAGE_INDEX_FIELDS);
      
      $sql = "
        UPDATE
          se_classifiedfields
        SET
          classifiedfield_title='{$classifiedfield_title_lvid}',
          classifiedfield_desc='{$classifiedfield_desc_lvid}',
          classifiedfield_error='{$classifiedfield_error_lvid}'
        WHERE
          classifiedfield_id='{$classifiedfield_info_array['classifiedfield_id']}'
        LIMIT
          1
      ";
      
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
  }
  
  
  
  
  //######### ALTER se_classifiedfields ADD COLUMNS
  $sql = "SHOW COLUMNS FROM `se_classifiedfields` LIKE 'classifiedfield_signup'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE se_classifiedfields
      ADD COLUMN classifiedfield_signup   TINYINT   UNSIGNED  NOT NULL default 0,
      ADD COLUMN classifiedfield_display  TINYINT   UNSIGNED  NOT NULL default 0,
      ADD COLUMN classifiedfield_special  TINYINT   UNSIGNED  NOT NULL default 0
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on classifiedfield_style
  $sql = "SHOW FULL COLUMNS FROM `se_classifiedfields` LIKE 'classifiedfield_style'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_classifiedfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on classifiedfield_link
  $sql = "SHOW FULL COLUMNS FROM `se_classifiedfields` LIKE 'classifiedfield_link'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_classifiedfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on classifiedfield_regex
  $sql = "SHOW FULL COLUMNS FROM `se_classifiedfields` LIKE 'classifiedfield_regex'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_classifiedfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on classifiedfield_html
  $sql = "SHOW FULL COLUMNS FROM `se_classifiedfields` LIKE 'classifiedfield_html'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_classifiedfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_classifiedmedia
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_classifiedmedia'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_classifiedmedia` (
        `classifiedmedia_id`                  INT           UNSIGNED  NOT NULL auto_increment,
        `classifiedmedia_classifiedalbum_id`  INT           UNSIGNED  NOT NULL default 0,
        `classifiedmedia_date`                INT                     NOT NULL default 0,
        `classifiedmedia_title`               VARCHAR(128)                NULL default '',
        `classifiedmedia_desc`                TEXT                        NULL,
        `classifiedmedia_ext`                 VARCHAR(8)              NOT NULL default '',
        `classifiedmedia_filesize`            INT           UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`classifiedmedia_id`),
        KEY `INDEX` (`classifiedmedia_classifiedalbum_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on classifiedmedia_title
  $sql = "SHOW FULL COLUMNS FROM `se_classifiedmedia` LIKE 'classifiedmedia_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_classifiedmedia MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on classifiedmedia_desc
  $sql = "SHOW FULL COLUMNS FROM `se_classifiedmedia` LIKE 'classifiedmedia_desc'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_classifiedmedia MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_classifieds
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_classifieds'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_classifieds` (
        `classified_id`               INT           UNSIGNED  NOT NULL auto_increment,
        `classified_user_id`          INT           UNSIGNED  NOT NULL default 0,
        `classified_classifiedcat_id` INT           UNSIGNED  NOT NULL default 0,
        `classified_date`             INT                     NOT NULL default 0,
        `classified_dateupdated`      INT                     NOT NULL default 0,
        `classified_views`            INT           UNSIGNED  NOT NULL default 0,
        `classified_title`            VARCHAR(128)            NOT NULL default '',
        `classified_body`             TEXT                        NULL,
        `classified_photo`            VARCHAR(16)             NOT NULL default '',
        `classified_search`           TINYINT       UNSIGNED  NOT NULL default 0,
        `classified_privacy`          TINYINT       UNSIGNED  NOT NULL default 0,
        `classified_comments`         TINYINT       UNSIGNED  NOT NULL default 0,
        `classified_totalcomments`    SMALLINT      UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`classified_id`),
        KEY `INDEX` (`classified_user_id`, `classified_classifiedcat_id`),
        FULLTEXT `SEARCH` (`classified_title`, `classified_body`)
      )
      ENGINE=MyISAM CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Add classified_totalcomments
  $sql = "SHOW COLUMNS FROM `se_classifieds` LIKE 'classified_totalcomments'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_classifieds ADD COLUMN `classified_totalcomments` SMALLINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  if( !$database->database_num_rows($resource) || $plugin_reindex_totals )
  {
    $sql = "SELECT classified_id FROM se_classifieds WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_classifieds SET classified_totalcomments=(SELECT COUNT(classifiedcomment_id) FROM se_classifiedcomments WHERE classifiedcomment_classified_id=classified_id) WHERE classified_id='{$result['classified_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  // Ensure utf8 on classified_title
  $sql = "SHOW FULL COLUMNS FROM `se_classifieds` LIKE 'classified_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_classifieds MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on classified_body
  $sql = "SHOW FULL COLUMNS FROM `se_classifieds` LIKE 'classified_body'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_classifieds MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Add full text index (should be after ensuring they are in utf8)
  $sql = "SHOW FULL COLUMNS FROM `se_classifieds` LIKE 'classified_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( $result && !$result['Key'] )
  {
    $sql = "ALTER TABLE `se_classifieds` ADD FULLTEXT `SEARCH` (`classified_title`, `classified_body`)";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_classifiedstyles
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_classifiedstyles'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_classifiedstyles` (
        `classifiedstyle_id`              INT    NOT NULL auto_increment,
        `classifiedstyle_user_id`         INT    NOT NULL default 0,
        `classifiedstyle_css`             TEXT       NULL,
        PRIMARY KEY  (`classifiedstyle_id`),
        KEY `INDEX` (`classifiedstyle_user_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_classifiedvalues
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_classifiedvalues'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_classifiedvalues` (
        `classifiedvalue_id`            INT           UNSIGNED  NOT NULL auto_increment,
        `classifiedvalue_classified_id` INT           UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`classifiedvalue_id`),
        KEY `INDEX` (`classifiedvalue_classified_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### INSERT se_urls
  $sql = "SELECT url_id FROM se_urls WHERE url_file='classifieds'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "INSERT INTO se_urls (url_title, url_file, url_regular, url_subdirectory) VALUES ('Classifieds URL', 'classifieds', 'classifieds.php?user=\$user', '\$user/classifieds/')";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SELECT url_id FROM se_urls WHERE url_file='classified'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "INSERT INTO se_urls (url_title, url_file, url_regular, url_subdirectory) VALUES ('Classified Listing URL', 'classified', 'classified.php?user=\$user&classified_id=\$id1', '\$user/classifieds/\$id1/')";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### INSERT se_actiontypes
  $actiontypes = array();
  $sql = "SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='postclassified'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('postclassified', 'classified_action_postclassified.gif', '1', '1', '4500148', '4500149', '[username],[displayname],[id],[title]', '0')
    ");
    
    $actiontypes[] = $database->database_insert_id();
  }
  
  
  $sql = "SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='classifiedcomment'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('classifiedcomment', 'action_postcomment.gif', '1', '1', '4500150', '4500151', '[username1],[displayname1],[username2],[displayname2],[comment],[id]', '0')
    ");
    
    $actiontypes[] = $database->database_insert_id();
  }
  
  $actiontypes = array_filter($actiontypes);
  if( !empty($actiontypes) )
  {
    $database->database_query("UPDATE se_usersettings SET usersetting_actions_display = CONCAT(usersetting_actions_display, ',', '".implode(",", $actiontypes)."')");
  }
  
  
  
  
  //######### INSERT se_notifytypes
  $sql = "SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='classifiedcomment'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $database->database_query("
      INSERT INTO se_notifytypes
        (notifytype_name, notifytype_desc, notifytype_icon, notifytype_url, notifytype_title)
      VALUES
        ('classifiedcomment', '4500152', 'action_postcomment.gif', 'classified.php?user=%1\$s&classified_id=%2\$s', '4500153')
    ");
  }
  
  
  
  //######### FIX se_notifytypes
  $sql = "UPDATE se_notifytypes SET notifytype_url='classified.php?user=%1\$s&classified_id=%2\$s' WHERE notifytype_url='classified.php?classified_id=%2\$s' LIMIT 1";
  $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  
  
  //######### ADD COLUMNS/VALUES TO LEVELS TABLE
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_classified_allow'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE se_levels 
      ADD COLUMN `level_classified_allow` int(1) NOT NULL default '1',
      ADD COLUMN `level_classified_entries` int(3) NOT NULL default '50',
      ADD COLUMN `level_classified_search` int(1) NOT NULL default '1',
      ADD COLUMN `level_classified_privacy` varchar(100) NOT NULL default 'a:6:{i:0;s:1:\"1\";i:1;s:1:\"3\";i:2;s:1:\"7\";i:3;s:2:\"15\";i:4;s:2:\"31\";i:5;s:2:\"63\";}',
      ADD COLUMN `level_classified_comments` varchar(100) NOT NULL default 'a:7:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";}',
      ADD COLUMN `level_classified_photo` int(1) NOT NULL default '1',
      ADD COLUMN `level_classified_photo_width` varchar(3) NOT NULL default '500',
      ADD COLUMN `level_classified_photo_height` varchar(3) NOT NULL default '500',
      ADD COLUMN `level_classified_photo_exts` varchar(50) NOT NULL default '',
      ADD COLUMN `level_classified_album_exts` text NULL,
      ADD COLUMN `level_classified_album_mimes` text NULL,
      ADD COLUMN `level_classified_album_storage` bigint(14) NOT NULL default '5242880',
      ADD COLUMN `level_classified_album_maxsize` bigint(14) NOT NULL default '2048000',
      ADD COLUMN `level_classified_album_width` varchar(4) NOT NULL default '500',
      ADD COLUMN `level_classified_album_height` varchar(4) NOT NULL default '500',
      ADD COLUMN `level_classified_html` text NULL
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "
      UPDATE
        se_levels
      SET
        level_classified_html='a,b,br,div,i,img,p,u',
        level_classified_photo_exts='jpg,jpeg,gif,png',
        level_classified_album_exts='jpg,gif,jpeg,png,bmp,mp3,mpeg,avi,mpa,mov,qt,swf',
        level_classified_album_mimes='image/jpeg,image/pjpeg,image/jpg,image/jpe,image/pjpg,image/x-jpeg,x-jpg,image/gif,image/x-gif,image/png,image/x-png,image/bmp,audio/mpeg,video/mpeg,video/x-msvideo,video/quicktime,application/x-shockwave-flash'
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_classified_privacy'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( $result && strtoupper($result['Type'])=="VARCHAR(10)" )
  {
    $sql = "ALTER TABLE se_levels CHANGE level_classified_privacy level_classified_privacy varchar(100) NOT NULL default ''";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "UPDATE se_levels SET level_classified_privacy='a:6:{i:0;s:1:\"1\";i:1;s:1:\"3\";i:2;s:1:\"7\";i:3;s:2:\"15\";i:4;s:2:\"31\";i:5;s:2:\"63\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_classified_comments'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( $result && strtoupper($result['Type'])=="VARCHAR(10)" )
  {
    $sql = "ALTER TABLE se_levels CHANGE level_classified_comments level_classified_comments varchar(100) NOT NULL default ''";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "UPDATE se_levels SET level_classified_comments='a:6:{i:0;s:1:\"1\";i:1;s:1:\"3\";i:2;s:1:\"7\";i:3;s:2:\"15\";i:4;s:2:\"31\";i:5;s:2:\"63\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_classified_style'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_levels ADD COLUMN `level_classified_style` TINYINT NOT NULL default 1";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_classified_html'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_levels ADD COLUMN `level_classified_html`  TEXT NULL";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    
    $sql = "UPDATE se_levels SET level_classified_html='a,b,br,div,i,img,p,u'";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_settings` LIKE 'setting_permission_classified'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_settings ADD COLUMN `setting_permission_classified` int(1) NOT NULL default '1'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### ADD COLUMNS/VALUES TO SYSTEM EMAILS TABLE
  $sql = "SELECT systememail_id FROM se_systememails WHERE systememail_name='classifiedcomment'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $database->database_query("
      INSERT INTO se_systememails
        (systememail_name, systememail_title, systememail_desc, systememail_subject, systememail_body, systememail_vars)
      VALUES
        ('classifiedcomment', '4500005', '4500006', '4500154', '4500155', '[displayname],[commenter],[link]')
    ");
  }
  
  
  
  
  //######### ADD COLUMNS/VALUES TO USER SETTINGS TABLE
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_usersettings` LIKE 'usersetting_notify_classifiedcomment'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_usersettings ADD COLUMN `usersetting_notify_classifiedcomment` int(1) NOT NULL default '1'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS NOT BEEN INSTALLED)
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=4500001 LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES 
        (4500001, 1, 'Classified Settings', ''),
        (4500002, 1, 'View Classified Listings', ''),
        (4500003, 1, 'Global Classified Settings', ''),
        (4500004, 1, 'Classified Settings', ''),
        (4500005, 1, 'New Classified Comment Email', ''),
        (4500006, 1, 'This is the email that gets sent to a user when a new comment is posted on one of their classified listings.', ''),
        (4500007, 1, 'Classifieds', '')
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS BEEN INSTALLED)
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=4500008 LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES
        /* admin_levels_classifiedsettings */
        (4500008, 1, 'Your listings per page field must contain an integer between 1 and 999.', 'admin_levels_classifiedsettings'),
        (4500009, 1, 'Photo width and height must be integers between 1 and 999.', 'admin_levels_classifiedsettings'),
        (4500010, 1, 'Your maximum filesize field must contain an integer between 1 and 204800.', 'admin_levels_classifiedsettings'),
        (4500011, 1, 'Your maximum width and height fields must contain integers between 1 and 9999.', 'admin_levels_classifiedsettings'),
        (4500012, 1, 'If you have allowed users to have classifieds listings, you can adjust their details from this page.', 'admin_levels_classifiedsettings'),
        (4500013, 1, 'Allow Classifieds?', 'admin_levels_classifiedsettings'),
        (4500014, 1, 'Do you want to let users have classified listings? If set to no, all other settings on this page will not apply.', 'admin_levels_classifiedsettings'),
        (4500015, 1, 'Yes, allow classified listings.', 'admin_levels_classifiedsettings'),
        (4500016, 1, 'No, do not allow classified listings.', 'admin_levels_classifiedsettings'),
        (4500017, 1, 'Allow Classified Photos?', 'admin_levels_classifiedsettings'),
        (4500018, 1, 'If you enable this feature, users will be able to upload a small photo icon when creating or editing a classified listing. This can be displayed next to the classified name in search/browse results, etc.', 'admin_levels_classifiedsettings'),
        (4500019, 1, 'Yes, users can upload a photo icon when they create/edit a classified listing.', 'admin_levels_classifiedsettings'),
        (4500020, 1, 'No, users can not upload a photo icon when they create/edit a classified listing.', 'admin_levels_classifiedsettings'),
        (4500021, 1, 'If you have selected YES above, please input the maximum dimensions for the classified photos. If your users upload a photo that is larger than these dimensions, the server will attempt to scale them down automatically. This feature requires that your PHP server is compiled with support for the GD Libraries.', 'admin_levels_classifiedsettings'),
        (4500022, 1, 'Maximum Width:', 'admin_levels_classifiedsettings'),
        (4500023, 1, 'Maximum Height:', 'admin_levels_classifiedsettings'),
        (4500024, 1, '(in pixels, between 1 and 999)', 'admin_levels_classifiedsettings'),
        (4500025, 1, 'What file types do you want to allow for classified photos (gif, jpg, jpeg, or png)? Separate file types with commas, i.e. jpg, jpeg, gif, png', 'admin_levels_classifiedsettings'),
        (4500026, 1, 'Allowed File Types:', 'admin_levels_classifiedsettings'),
        (4500027, 1, 'Listings Per Page', 'admin_levels_classifiedsettings'),
        (4500028, 1, 'How many classified listings will be shown per page? (Enter a number between 1 and 999)', 'admin_levels_classifiedsettings'),
        (4500029, 1, 'listings per page', 'admin_levels_classifiedsettings'),
        (4500030, 1, 'Classified Privacy Options', 'admin_levels_classifiedsettings'),
        (4500031, 1, '<b>Search Privacy Options</b><br>If you enable this feature, users will be able to exclude their classified listings from search results. Otherwise, all classified listings will be included in search results.', 'admin_levels_classifiedsettings'),
        (4500032, 1, 'Yes, allow users to exclude their classified listings from search results.', 'admin_levels_classifiedsettings'),
        (4500033, 1, 'No, force all classified listings to be included in search results.', 'admin_levels_classifiedsettings'),
        (4500034, 1, '<b>Classified Listing Privacy</b><br>Your users can choose from any of the options checked below when they decide who can see their classified listings. These options appear on your users\' \"Add listing\" and \"Edit listing\" pages. If you do not check any options, everyone will be allowed to view classifieds.', 'admin_levels_classifiedsettings'),
        (4500035, 1, '<b>Classified Comment Options</b><br>Your users can choose from any of the options checked below when they decide who can post comments on their listings. If you do not check any options, everyone will be allowed to post comments on listings.', 'admin_levels_classifiedsettings'),
        (4500036, 1, 'Classified File Settings', 'admin_levels_classifiedsettings'),
        (4500037, 1, 'List the following file extensions that users are allowed to upload. Separate file extensions with commas, for example: jpg, gif, jpeg, png, bmp', 'admin_levels_classifiedsettings'),
        (4500038, 1, 'To successfully upload a file, the file must have an allowed filetype extension as well as an allowed MIME type. This prevents users from disguising malicious files with a fake extension. Separate MIME types with commas, for example: image/jpeg, image/gif, image/png, image/bmp', 'admin_levels_classifiedsettings'),
        (4500039, 1, 'How much storage space should each listing have to store its files?', 'admin_levels_classifiedsettings'),
        (4500040, 1, 'Unlimited', 'admin_levels_classifiedsettings'),
        (4500041, 1, '%1\$s KB', 'admin_levels_classifiedsettings'),
        (4500042, 1, '%1\$s MB', 'admin_levels_classifiedsettings'),
        (4500043, 1, '%1\$s GB', 'admin_levels_classifiedsettings'),
        (4500044, 1, 'Enter the maximum filesize for uploaded files in KB. This must be a number between 1 and 204800.', 'admin_levels_classifiedsettings'),
        (4500045, 1, 'Enter the maximum width and height (in pixels) for images uploaded to listings. Images with dimensions outside this range will be sized down accordingly if your server has the GD Libraries installed. Note that unusual image types like BMP, TIFF, RAW, and others may not be resized.', 'admin_levels_classifiedsettings'),
        (4500046, 1, 'Maximum Width:', 'admin_levels_classifiedsettings'),
        (4500047, 1, 'Maximum Height:', 'admin_levels_classifiedsettings'),
        (4500048, 1, '(in pixels, between 1 and 9999)', 'admin_levels_classifiedsettings'),
        
        /* admin_viewclassifieds */
        (4500049, 1, 'This page lists all of the classified listings your users have posted. You can use this page to monitor these classifieds and delete offensive material if necessary. Entering criteria into the filter fields will help you find specific classified listings. Leaving the filter fields blank will show all the classified listings on your social network.', 'admin_viewclassifieds'),
        (4500050, 1, 'No listings were found.', 'admin_viewclassifieds'),
        (4500051, 1, '%1\$d Classified Listings Found', 'admin_viewclassifieds'),
        (4500052, 1, 'Title', 'admin_viewclassifieds'),
        (4500053, 1, 'Owner', 'admin_viewclassifieds'),
        (4500054, 1, 'view', 'admin_viewclassifieds, classifieds'),
        (4500055, 1, 'Are you sure you want to delete this classified listing?', 'admin_viewclassifieds'),
        
        /* classified */
        (4500056, 1, '<a href=\"%2\$s\">%1\$s</a>\'s <a href=\"%3\$s\">Classified Listings</a>', 'classified'),
        (4500057, 1, 'Created: %1\$s', 'classified, classifieds'),
        (4500058, 1, 'Category:', 'classified'),
        (4500059, 1, 'Back to %1\$s\'s Listings', 'classified'),
        
        /* classifieds */
        (4500060, 1, '<a href=\"%2\$s\">%1\$s</a>\'s Classified Listings', 'classifieds'),
        (4500061, 1, '<b><a href=\"%2\$s\">%1\$s</a></b> has not posted any classified listings.', 'classifieds'),
        (4500062, 1, 'Views: %1\$d views', 'classifieds'),
        (4500063, 1, 'Comments: %1\$d comments', 'classifieds'),
        
        /* profile_classified */
        (4500064, 1, 'Posted:', 'profile_classified'),
        
        /* user_classified */
        (4500065, 1, 'Post New Listing', 'user_classified'),
        (4500066, 1, 'Listing Settings', 'user_classified'),
        (4500067, 1, 'Search My Listings', 'user_classified'),
        (4500068, 1, 'My Classified Listings', 'user_classified'),
        (4500069, 1, 'Classified listings are a great way to list something for sale, find items you need, look for jobs or simply meet new people.', 'user_classified'),
        (4500070, 1, 'No classified listings were found.', 'user_classified'),
        (4500071, 1, 'You do not have any classified listings. <a href=\"%1\$s\">Click here</a> to post one.', 'user_classified'),
        (4500072, 1, '%1\$d views', 'browse_classifieds, user_classified'),
        (4500073, 1, 'View Classified', 'user_classified'),
        (4500074, 1, 'Edit Classified', 'user_classified'),
        (4500075, 1, 'Edit Photos', 'user_classified'),
        (4500076, 1, 'Delete Classified', 'user_classified'),
        
        /* admin_classified */
        (4500077, 1, 'General Classified Settings', 'admin_classified'),
        (4500078, 1, 'This page contains general classified settings that affect your entire social network.', 'admin_classified'),
        (4500079, 1, 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the <a href=\"admin_general.php\">General Settings</a> page.', 'admin_classified'),
        (4500080, 1, 'Yes, the public can view classifieds unless they are made private.', 'admin_classified'),
        (4500081, 1, 'No, the public cannot view classifieds.', 'admin_classified'),
        (4500082, 1, 'Classified Categories/Fields', 'admin_classified'),
        (4500083, 1, 'You may want to allow your users to categorize their classified listings by subject, location, etc. Categorized classified listings make it easier for users to find and classifieds that interest them. If you want to allow classified listing categories, you can create them (along with subcategories) below.<br /><br />Within each category, you can create classified fields. When a classified is created, the creator (owner) will describe the classified by filling in some fields with information about the classified. Add the fields you want to include below. Some examples of classified fields are \"Location\", \"Price\", \"Contact Email\", etc. Remember that a \"Classified Title\" and \"Classified Description\" field will always be available and required. Drag the icons next to the categories and fields to reorder them.', 'admin_classified'),
        (4500084, 1, 'Classified Categories', 'admin_classified'),
        
        /* user_classified_listing */
        (4500085, 1, 'Post Classified Listing', 'user_classified_listing'),
        (4500086, 1, 'Edit Classified Listing', 'user_classified_listing'),
        (4500087, 1, 'Write your new listing below, then click \"Post Listing\" to publish the listing on your classified.', 'user_classified_listing'),
        (4500088, 1, 'Edit the details of this classified listing below.', 'user_classified_listing'),
        (4500089, 1, 'Classified Title', 'user_classified_listing'),
        (4500090, 1, 'Classified Description', 'user_classified_listing'),
        (4500091, 1, 'Classified Category', 'user_classified_listing'),
        (4500092, 1, 'Include this classified in search/browse results?', 'user_classified_listing'),
        (4500093, 1, 'Yes, include this group in search/browse results.', 'user_classified_listing'),
        (4500094, 1, 'No, exclude this group from search/browse results.', 'user_classified_listing'),
        (4500095, 1, 'Who can see this classified?', 'user_classified_listing'),
        (4500096, 1, 'You can decide who gets to see this classified.', 'user_classified_listing'),
        (4500097, 1, 'Allow Comments?', 'user_classified_listing'),
        (4500098, 1, 'You can decide who can post comments on this classified.', 'user_classified_listing'),
        (4500099, 1, 'Post Classified', 'user_classified_listing'),
        (4500100, 1, 'Please enter a name for your classified.', 'user_classified_listing'),
        (4500101, 1, 'Please select a category for this classified.', 'user_classified_listing'),
        (4500102, 1, 'Back to My Classifieds', 'user_classified_listing, user_classified_media'),
        
        /* user_classified_media */
        (4500103, 1, 'Edit Listing Photos', 'user_classified_media'),
        (4500104, 1, 'Use this page to change the photos shown on this classified listing.', 'user_classified_media'),
        (4500105, 1, 'Your classified listing has been posted! Do you want to add some photos?', 'user_classified_media'),
        (4500106, 1, 'Add Photos Now', 'user_classified_media'),
        (4500107, 1, 'Maybe Later', 'user_classified_media'),
        (4500108, 1, 'Small Photo', 'user_classified_media'),
        (4500109, 1, 'Replace this photo with:', 'user_classified_media'),
        (4500110, 1, 'delete photo', 'user_classified_media'),
        (4500111, 1, 'Deleting photo...', 'user_classified_media'),
        (4500112, 1, 'Add a photo:', 'user_classified_media'),
        (4500113, 1, 'Upload', 'user_classified_media'),
        (4500114, 1, 'Large Photos', 'user_classified_media'),
        
        /* user_classified_settings */
        (4500115, 1, 'Edit Classified Settings', 'user_classified_settings'),
        (4500116, 1, 'Edit settings pertaining to your classified listings.', 'user_classified_settings'),
        (4500117, 1, 'Custom Classified Styles', 'user_classified_settings'),
        (4500118, 1, 'You can change the colors, fonts, and styles of your classified listing by adding CSS code below. The contents of the text area below will be output between &lt;style&gt; tags on your classified listing.', 'user_classified_settings'),
        (4500119, 1, 'Classified Notifications', 'user_classified_settings'),
        (4500120, 1, 'Notify me by email when someone writes a comment on my classified listings.', 'user_classified_settings'),
        
        /* MISC */
        (4500121, 1, 'Delete Classified Listing?', 'user_classified'),
        (4500122, 1, 'Are you sure you want to delete this classified listing?', 'user_classified'),
        (4500123, 1, 'There was an error processing your request.', 'user_classified'),
        
        /* browse_classifieds */
        (4500124, 1, 'Browse Classified Listings', 'browse_classifieds'),
        (4500125, 1, 'View:', 'browse_classifieds'),
        (4500126, 1, 'Order:', 'browse_classifieds'),
        (4500127, 1, 'Everyone\'s Classifieds', 'browse_classifieds'),
        (4500128, 1, 'My Friends\' Classifieds', 'browse_classifieds'),
        (4500129, 1, 'Recently Created', 'browse_classifieds'),
        (4500130, 1, 'Recently Updated', 'browse_classifieds'),
        (4500131, 1, 'Most Viewed', 'browse_classifieds'),
        (4500132, 1, 'Most Commented', 'browse_classifieds'),
        (4500133, 1, 'All Classified Listings', 'browse_classifieds'),
        (4500134, 1, 'No classifieds were found matching your criteria.', 'browse_classifieds'),
        (4500135, 1, 'created %1\$s', 'browse_classifieds'),
        (4500136, 1, 'updated %1\$s', 'browse_classifieds'),
        
        /* search */
        (4500137, 1, 'Classified listing: %1\$s', 'search'),
        (4500138, 1, 'Classified listing posted by <a href=\'%1\$s\'>%2\$s</a><br>%3\$s', 'search'),
        (4500139, 1, '%1\$d classifieds', 'search'),
        
        /* MISC */
        (4500140, 1, 'HTML in Classified Listings', 'admin_levels_classifiedsettings'),
        (4500141, 1, 'If you want to allow specific HTML tags, you can enter them below (separated by commas). Example: <i>b, img, a, embed, font<i>', 'admin_levels_classifiedsettings'),
        (4500142, 1, 'Classified Photo', 'classified'),
        (4500143, 1, '%1\$s\'s classified listings', 'header_global'),
        (4500144, 1, '%1\$s\'s classified listing - %2\$s', 'header_global')
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=4500145 LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES
        (4500145, 1, 'Classifieds: %1\$d classifieds', 'home'),
        (4500146, 1, 'Classified Comments: %1\$d comments', 'home'),
        (4500147, 1, 'Classified Media: %1\$d media', 'home')
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=4500148 LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES
        (4500148, 1, 'Posting a Classified Listing', 'actiontypes'),
        (4500149, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> posted a classified listing: <a href=\"classified.php?user=%1\$s&classified_id=%3\$s\">%4\$s</a>', 'actiontypes'),
        (4500150, 1, 'Posting a Classified Comment', 'actiontypes'),
        (4500151, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> posted a comment on <a href=\"profile.php?user=%3\$s\">%4\$s</a>\'s <a href=\"classified.php?user=%3\$s&classified_id=%6\$s\">classified listing</a>:<div class=\"recentaction_div\">%5\$s</div>', 'actiontypes'),
        (4500152, 1, '%1\$d New Classified Comment(s): %2\$s', 'notifytypes'),
        (4500153, 1, 'When I receive a classified comment.', 'notifytypes'),
        (4500154, 1, 'New Classified Listing Comment', 'systememails'),
        (4500155, 1, 'Hello %1\$s,\n\nA new comment has been posted on one of your classified listings by %2\$s. Please click the following link to view it:\n\n%3\$s\n\nBest Regards,\nSocial Network Administration', 'systememails')
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  ################ UPGRADE EXISTING CLASSIFIEDS' PRIVACY OPTIONS
  if( !empty($plugin_info) && version_compare($plugin_info['plugin_version'], '3.00', '<') )
  {
    $database->database_query("UPDATE se_classifieds SET classified_privacy='63'  WHERE classified_privacy='0' ") or die($database->database_error()." View Privacy Query #1");
    $database->database_query("UPDATE se_classifieds SET classified_privacy='31'  WHERE classified_privacy='1' ") or die($database->database_error()." View Privacy Query #2");
    $database->database_query("UPDATE se_classifieds SET classified_privacy='15'  WHERE classified_privacy='2' ") or die($database->database_error()." View Privacy Query #3");
    $database->database_query("UPDATE se_classifieds SET classified_privacy='7'   WHERE classified_privacy='3' ") or die($database->database_error()." View Privacy Query #4");
    $database->database_query("UPDATE se_classifieds SET classified_privacy='3'   WHERE classified_privacy='4' ") or die($database->database_error()." View Privacy Query #5");
    $database->database_query("UPDATE se_classifieds SET classified_privacy='1'   WHERE classified_privacy='5' ") or die($database->database_error()." View Privacy Query #6");
    $database->database_query("UPDATE se_classifieds SET classified_privacy='0'   WHERE classified_privacy='6' ") or die($database->database_error()." View Privacy Query #7");
    
    $database->database_query("UPDATE se_classifieds SET classified_comments='63' WHERE classified_comments='0'") or die($database->database_error()." Comment Privacy Query #1");
    $database->database_query("UPDATE se_classifieds SET classified_comments='31' WHERE classified_comments='1'") or die($database->database_error()." Comment Privacy Query #2");
    $database->database_query("UPDATE se_classifieds SET classified_comments='15' WHERE classified_comments='2'") or die($database->database_error()." Comment Privacy Query #3");
    $database->database_query("UPDATE se_classifieds SET classified_comments='7'  WHERE classified_comments='3'") or die($database->database_error()." Comment Privacy Query #4");
    $database->database_query("UPDATE se_classifieds SET classified_comments='3'  WHERE classified_comments='4'") or die($database->database_error()." Comment Privacy Query #5");
    $database->database_query("UPDATE se_classifieds SET classified_comments='1'  WHERE classified_comments='5'") or die($database->database_error()." Comment Privacy Query #6");
    $database->database_query("UPDATE se_classifieds SET classified_comments='0'  WHERE classified_comments='6'") or die($database->database_error()." Comment Privacy Query #7");
  }
}

?>