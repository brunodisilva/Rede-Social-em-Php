<?php



$plugin_name = "Jobs Plugin";
$plugin_version = "1 by Dylan";
$plugin_type = "job";
$plugin_desc = "This plugin allows your users to post job listings.<br /><br />";
$plugin_icon = "job_job16.gif";
$plugin_menu_title = "6400001";
$plugin_pages_main = "6400002<!>job_job16.gif<!>admin_viewjobs.php<~!~>6400003<!>job_job16.gif<!>admin_job.php<~!~>";
$plugin_pages_level = "6400004<!>admin_levels_jobsettings.php<~!~>";
$plugin_url_htaccess = "RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^/]+)/jobs/([0-9]+)/?$ \$server_info/job.php?user=\$1&job_id=\$2 [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^/]+)/jobs/([0-9]+)/([^/]+)?$ \$server_info/job.php?user=\$1&job_id=\$2\$3 [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^/]+)/jobs/?$ \$server_info/jobs.php?user=\$1 [L]";
$plugin_db_charset = 'utf8';
$plugin_db_collation = 'utf8_unicode_ci';
$plugin_reindex_totals = TRUE;




if($install == "job")
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
  
  
  
  
  //######### CREATE se_jobalbums
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_jobalbums'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_jobalbums` (
        `jobalbum_id`              INT       UNSIGNED  NOT NULL auto_increment,
        `jobalbum_job_id`   INT       UNSIGNED  NOT NULL default 0,
        `jobalbum_datecreated`     INT                 NOT NULL default 0,
        `jobalbum_dateupdated`     INT                 NOT NULL default 0,
        `jobalbum_title`           VARCHAR(64)             NULL,
        `jobalbum_desc`            TEXT                    NULL,
        `jobalbum_search`          TINYINT   UNSIGNED  NOT NULL default 0,
        `jobalbum_privacy`         TINYINT   UNSIGNED  NOT NULL default 0,
        `jobalbum_comments`        TINYINT   UNSIGNED  NOT NULL default 0,
        `jobalbum_cover`           INT       UNSIGNED  NOT NULL default 0,
        `jobalbum_views`           INT       UNSIGNED  NOT NULL default 0,
        `jobalbum_totalfiles`      SMALLINT  UNSIGNED  NOT NULL default 0,
        `jobalbum_totalspace`      BIGINT    UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`jobalbum_id`),
        KEY `INDEX` (`jobalbum_job_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Add jobalbum_totalfiles
  $sql = "SHOW COLUMNS FROM `se_jobalbums` LIKE 'jobalbum_totalfiles'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_jobalbums ADD COLUMN `jobalbum_totalfiles` SMALLINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  if( !$database->database_num_rows($resource) || $plugin_reindex_totals )
  {
    $sql = "SELECT jobalbum_id FROM se_jobalbums WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_jobalbums SET jobalbum_totalfiles=(SELECT COUNT(jobmedia_id) FROM se_jobmedia WHERE jobmedia_jobalbum_id=jobalbum_id) WHERE jobalbum_id='{$result['jobalbum_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  // Add jobalbum_totalspace
  $sql = "SHOW COLUMNS FROM `se_jobalbums` LIKE 'jobalbum_totalspace'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_jobalbums ADD COLUMN `jobalbum_totalspace` BIGINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  if( !$database->database_num_rows($resource) || $plugin_reindex_totals )
  {
    $sql = "SELECT jobalbum_id FROM se_jobalbums WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_jobalbums SET jobalbum_totalspace=(SELECT SUM(jobmedia_filesize) FROM se_jobmedia WHERE jobmedia_jobalbum_id=jobalbum_id) WHERE jobalbum_id='{$result['jobalbum_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  // Ensure utf8 on jobalbum_title
  $sql = "SHOW FULL COLUMNS FROM `se_jobalbums` LIKE 'jobalbum_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_jobalbums MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on jobalbum_desc
  $sql = "SHOW FULL COLUMNS FROM `se_jobalbums` LIKE 'jobalbum_desc'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_jobalbums MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_jobcats
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_jobcats'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_jobcats` (
        `jobcat_id`          INT         UNSIGNED  NOT NULL auto_increment,
        `jobcat_dependency`  INT         UNSIGNED  NOT NULL default 0,
        `jobcat_title`       INT         UNSIGNED  NOT NULL default 0,
        `jobcat_order`       SMALLINT    UNSIGNED  NOT NULL default 0,
        `jobcat_signup`      TINYINT     UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`jobcat_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  //######### ALTER se_jobcats LANGUAGIFY jobcat_title
  $sql = "SHOW FULL COLUMNS FROM `se_jobcats` LIKE 'jobcat_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  // Fix collation, load data, drop column
  $jobcat_info = array();
  if( $column_info && strtoupper(substr($column_info['Type'], 0, 7))=="VARCHAR" )
  {
    // Fix collation
    if( $column_info['Collation']!=$plugin_db_collation )
    {
      $sql = "ALTER TABLE se_jobcats MODIFY {$column_info['Field']} {$column_info['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
    
    // Languagify title column
    $sql = "SELECT * FROM se_jobcats";
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    if( $database->database_num_rows($resource) )
      while( $result=$database->database_fetch_assoc($resource) )
        $jobcat_info[] = $result;
    
    // Drop column
    $sql = "ALTER TABLE se_jobcats DROP COLUMN jobcat_title";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    unset($column_info);
  }
  
  // Add column
  if( !$column_info )
  {
    $sql = "ALTER TABLE se_jobcats ADD COLUMN jobcat_title INT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  // Update column
  if( !empty($jobcat_info) )
  {
    // Update title
    foreach( $jobcat_info as $jobcat_info_array )
    {
      $jobcat_title_lvid = SE_Language::edit(0, $jobcat_info_array['jobcat_title'], NULL, LANGUAGE_INDEX_FIELDS);
      
      $sql = "
        UPDATE
          se_jobcats
        SET
          jobcat_title='{$jobcat_title_lvid}'
        WHERE
          jobcat_id='{$jobcat_info_array['jobcat_id']}'
        LIMIT
          1
      ";
      
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
  }
  
  
  
  
  //######### ALTER se_jobcats ADD COLUMNS
  $sql = "SHOW COLUMNS FROM `se_jobcats` LIKE 'jobcat_order'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE se_jobcats
      ADD COLUMN jobcat_order  SMALLINT  UNSIGNED  NOT NULL default 0,
      ADD COLUMN jobcat_signup TINYINT   UNSIGNED  NOT NULL default 0
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### INSERT se_jobcats
  $sql = "SELECT NULL FROM se_jobcats";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $jobcat_title_lvid = SE_Language::edit(0, "Other", NULL, LANGUAGE_INDEX_FIELDS);
    $sql = "INSERT INTO se_jobcats (jobcat_title) VALUES ('{$jobcat_title_lvid}')";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_jobcomments
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_jobcomments'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_jobcomments` (
        `jobcomment_id`              INT         UNSIGNED  NOT NULL auto_increment,
        `jobcomment_job_id`   INT         UNSIGNED  NOT NULL default 0,
        `jobcomment_authoruser_id`   INT         UNSIGNED  NOT NULL default 0,
        `jobcomment_date`            INT         UNSIGNED  NOT NULL default 0,
        `jobcomment_body`            TEXT                      NULL,
        PRIMARY KEY  (`jobcomment_id`),
        KEY `INDEX` (`jobcomment_job_id`,`jobcomment_authoruser_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on jobcomment_body
  $sql = "SHOW FULL COLUMNS FROM `se_jobcomments` LIKE 'jobcomment_body'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_jobcomments MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_jobfields
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_jobfields'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_jobfields` (
        `jobfield_id`                INT           UNSIGNED  NOT NULL auto_increment,
        `jobfield_jobcat_id`  INT           UNSIGNED  NOT NULL default 0,
        `jobfield_order`             SMALLINT      UNSIGNED  NOT NULL default 0,
        `jobfield_dependency`        INT           UNSIGNED  NOT NULL default 0,
        `jobfield_title`             INT           UNSIGNED  NOT NULL default 0,
        `jobfield_desc`              INT           UNSIGNED  NOT NULL default 0,
        `jobfield_error`             INT           UNSIGNED  NOT NULL default 0,
        `jobfield_type`              TINYINT       UNSIGNED  NOT NULL default 0,
        `jobfield_style`             VARCHAR(255)                NULL,
        `jobfield_maxlength`         SMALLINT      UNSIGNED  NOT NULL default 0,
        `jobfield_link`              VARCHAR(255)                NULL,
        `jobfield_options`           LONGTEXT                    NULL,
        `jobfield_required`          TINYINT       UNSIGNED  NOT NULL default 0,
        `jobfield_regex`             VARCHAR(255)                NULL,
        `jobfield_html`              VARCHAR(255)                NULL,
        `jobfield_search`            TINYINT       UNSIGNED  NOT NULL default 0,
        `jobfield_signup`            TINYINT       UNSIGNED  NOT NULL default 0,
        `jobfield_display`           TINYINT       UNSIGNED  NOT NULL default 0,
        `jobfield_special`           TINYINT       UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`jobfield_id`),
        KEY `INDEX` (`jobfield_jobcat_id`,`jobfield_dependency`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### ALTER se_jobfields LANGUAGIFY jobfield_title,jobfield_desc,jobfield_error
  $sql = "SHOW FULL COLUMNS FROM `se_jobfields` LIKE 'jobfield_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $column_info = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  // Fix collation, load text, drop columns
  $jobfield_info = array();
  if( $column_info && strtoupper(substr($column_info['Type'], 0, 7))=="VARCHAR" )
  {
    // Fix collation
    if( $column_info['Collation']!=$plugin_db_collation )
    {
      $sql = "
        ALTER TABLE se_jobfields
        MODIFY jobfield_title  VARCHAR(255) CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation} NOT NULL default '',
        MODIFY jobfield_desc   VARCHAR(255) CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation} NOT NULL default '',
        MODIFY jobfield_error  VARCHAR(255) CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation} NOT NULL default ''
      ";
      
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
    
    // Load title column
    $sql = "SELECT * FROM se_jobfields";
    $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    if( $database->database_num_rows($resource) )
      while( $result=$database->database_fetch_assoc($resource) )
        $jobfield_info[] = $result;
    
    // Crop column
    $sql = "ALTER TABLE se_jobfields DROP COLUMN jobfield_title, DROP COLUMN jobfield_desc, DROP COLUMN jobfield_error";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    unset($column_info);
  }
  
  // Add columns
  if( !$column_info )
  {
    $sql = "
      ALTER TABLE se_jobfields
      ADD COLUMN jobfield_title  INT UNSIGNED NOT NULL default 0,
      ADD COLUMN jobfield_desc   INT UNSIGNED NOT NULL default 0,
      ADD COLUMN jobfield_error  INT UNSIGNED NOT NULL default 0
    ";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  // Update column
  if( !empty($jobfield_info) )
  {
    // Update column
    foreach( $jobfield_info as $jobfield_info_array )
    {
      $jobfield_title_lvid = SE_Language::edit(0, $jobfield_info_array['jobfield_title'], NULL, LANGUAGE_INDEX_FIELDS);
      $jobfield_desc_lvid  = SE_Language::edit(0, $jobfield_info_array['jobfield_desc' ], NULL, LANGUAGE_INDEX_FIELDS);
      $jobfield_error_lvid = SE_Language::edit(0, $jobfield_info_array['jobfield_error'], NULL, LANGUAGE_INDEX_FIELDS);
      
      $sql = "
        UPDATE
          se_jobfields
        SET
          jobfield_title='{$jobfield_title_lvid}',
          jobfield_desc='{$jobfield_desc_lvid}',
          jobfield_error='{$jobfield_error_lvid}'
        WHERE
          jobfield_id='{$jobfield_info_array['jobfield_id']}'
        LIMIT
          1
      ";
      
      $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    }
  }
  
  
  
  
  //######### ALTER se_jobfields ADD COLUMNS
  $sql = "SHOW COLUMNS FROM `se_jobfields` LIKE 'jobfield_signup'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE se_jobfields
      ADD COLUMN jobfield_signup   TINYINT   UNSIGNED  NOT NULL default 0,
      ADD COLUMN jobfield_display  TINYINT   UNSIGNED  NOT NULL default 0,
      ADD COLUMN jobfield_special  TINYINT   UNSIGNED  NOT NULL default 0
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on jobfield_style
  $sql = "SHOW FULL COLUMNS FROM `se_jobfields` LIKE 'jobfield_style'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_jobfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on jobfield_link
  $sql = "SHOW FULL COLUMNS FROM `se_jobfields` LIKE 'jobfield_link'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_jobfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on jobfield_regex
  $sql = "SHOW FULL COLUMNS FROM `se_jobfields` LIKE 'jobfield_regex'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_jobfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on jobfield_html
  $sql = "SHOW FULL COLUMNS FROM `se_jobfields` LIKE 'jobfield_html'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_jobfields MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_jobmedia
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_jobmedia'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_jobmedia` (
        `jobmedia_id`                  INT           UNSIGNED  NOT NULL auto_increment,
        `jobmedia_jobalbum_id`  INT           UNSIGNED  NOT NULL default 0,
        `jobmedia_date`                INT                     NOT NULL default 0,
        `jobmedia_title`               VARCHAR(128)                NULL default '',
        `jobmedia_desc`                TEXT                        NULL,
        `jobmedia_ext`                 VARCHAR(8)              NOT NULL default '',
        `jobmedia_filesize`            INT           UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`jobmedia_id`),
        KEY `INDEX` (`jobmedia_jobalbum_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on jobmedia_title
  $sql = "SHOW FULL COLUMNS FROM `se_jobmedia` LIKE 'jobmedia_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_jobmedia MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on jobmedia_desc
  $sql = "SHOW FULL COLUMNS FROM `se_jobmedia` LIKE 'jobmedia_desc'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_jobmedia MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_jobs
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_jobs'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_jobs` (
        `job_id`               INT           UNSIGNED  NOT NULL auto_increment,
        `job_user_id`          INT           UNSIGNED  NOT NULL default 0,
        `job_jobcat_id` INT           UNSIGNED  NOT NULL default 0,
        `job_date`             INT                     NOT NULL default 0,
        `job_dateupdated`      INT                     NOT NULL default 0,
        `job_views`            INT           UNSIGNED  NOT NULL default 0,
        `job_title`            VARCHAR(128)            NOT NULL default '',
        `job_body`             TEXT                        NULL,
        `job_photo`            VARCHAR(16)             NOT NULL default '',
        `job_search`           TINYINT       UNSIGNED  NOT NULL default 0,
        `job_privacy`          TINYINT       UNSIGNED  NOT NULL default 0,
        `job_comments`         TINYINT       UNSIGNED  NOT NULL default 0,
        `job_totalcomments`    SMALLINT      UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`job_id`),
        KEY `INDEX` (`job_user_id`, `job_jobcat_id`),
        FULLTEXT `SEARCH` (`job_title`, `job_body`)
      )
      ENGINE=MyISAM CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Add job_totalcomments
  $sql = "SHOW COLUMNS FROM `se_jobs` LIKE 'job_totalcomments'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_jobs ADD COLUMN `job_totalcomments` SMALLINT UNSIGNED NOT NULL default 0";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  if( !$database->database_num_rows($resource) || $plugin_reindex_totals )
  {
    $sql = "SELECT job_id FROM se_jobs WHERE 1";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    while( $result = $database->database_fetch_assoc($resource) )
    {
      $sql = "UPDATE se_jobs SET job_totalcomments=(SELECT COUNT(jobcomment_id) FROM se_jobcomments WHERE jobcomment_job_id=job_id) WHERE job_id='{$result['job_id']}' LIMIT 1";
      $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    }
  }
  
  
  // Ensure utf8 on job_title
  $sql = "SHOW FULL COLUMNS FROM `se_jobs` LIKE 'job_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_jobs MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Ensure utf8 on job_body
  $sql = "SHOW FULL COLUMNS FROM `se_jobs` LIKE 'job_body'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );

  if( $result && $result['Collation']!=$plugin_db_collation )
  {
    $sql = "ALTER TABLE se_jobs MODIFY {$result['Field']} {$result['Type']} CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  // Add full text index (should be after ensuring they are in utf8)
  $sql = "SHOW FULL COLUMNS FROM `se_jobs` LIKE 'job_title'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( $result && !$result['Key'] )
  {
    $sql = "ALTER TABLE `se_jobs` ADD FULLTEXT `SEARCH` (`job_title`, `job_body`)";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_jobstyles
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_jobstyles'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_jobstyles` (
        `jobstyle_id`              INT    NOT NULL auto_increment,
        `jobstyle_user_id`         INT    NOT NULL default 0,
        `jobstyle_css`             TEXT       NULL,
        PRIMARY KEY  (`jobstyle_id`),
        KEY `INDEX` (`jobstyle_user_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### CREATE se_jobvalues
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_jobvalues'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_jobvalues` (
        `jobvalue_id`            INT           UNSIGNED  NOT NULL auto_increment,
        `jobvalue_job_id` INT           UNSIGNED  NOT NULL default 0,
        PRIMARY KEY  (`jobvalue_id`),
        KEY `INDEX` (`jobvalue_job_id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### INSERT se_urls
  $sql = "SELECT url_id FROM se_urls WHERE url_file='jobs'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "INSERT INTO se_urls (url_title, url_file, url_regular, url_subdirectory) VALUES ('Jobs URL', 'jobs', 'jobs.php?user=\$user', '\$user/jobs/')";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SELECT url_id FROM se_urls WHERE url_file='job'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "INSERT INTO se_urls (url_title, url_file, url_regular, url_subdirectory) VALUES ('Job Listing URL', 'job', 'job.php?user=\$user&job_id=\$id1', '\$user/jobs/\$id1/')";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### INSERT se_actiontypes
  $actiontypes = array();
  $sql = "SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='postjob'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('postjob', 'job_action_postjob.gif', '1', '1', '6400148', '6400149', '[username],[displayname],[id],[title]', '0')
    ");
    
    $actiontypes[] = $database->database_insert_id();
  }
  
  
  $sql = "SELECT actiontype_id FROM se_actiontypes WHERE actiontype_name='jobcomment'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $database->database_query("
      INSERT INTO se_actiontypes
        (actiontype_name, actiontype_icon, actiontype_setting, actiontype_enabled, actiontype_desc, actiontype_text, actiontype_vars, actiontype_media)
      VALUES
        ('jobcomment', 'action_postcomment.gif', '1', '1', '6400150', '6400151', '[username1],[displayname1],[username2],[displayname2],[comment],[id]', '0')
    ");
    
    $actiontypes[] = $database->database_insert_id();
  }
  
  $actiontypes = array_filter($actiontypes);
  if( !empty($actiontypes) )
  {
    $database->database_query("UPDATE se_usersettings SET usersetting_actions_display = CONCAT(usersetting_actions_display, ',', '".implode(",", $actiontypes)."')");
  }
  
  
  
  
  //######### INSERT se_notifytypes
  $sql = "SELECT notifytype_id FROM se_notifytypes WHERE notifytype_name='jobcomment'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $database->database_query("
      INSERT INTO se_notifytypes
        (notifytype_name, notifytype_desc, notifytype_icon, notifytype_url, notifytype_title)
      VALUES
        ('jobcomment', '6400152', 'action_postcomment.gif', 'job.php?user=%1\$s&job_id=%2\$s', '6400153')
    ");
  }
  
  
  
  //######### FIX se_notifytypes
  $sql = "UPDATE se_notifytypes SET notifytype_url='job.php?user=%1\$s&job_id=%2\$s' WHERE notifytype_url='job.php?job_id=%2\$s' LIMIT 1";
  $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  
  
  //######### ADD COLUMNS/VALUES TO LEVELS TABLE
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_job_allow'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      ALTER TABLE se_levels 
      ADD COLUMN `level_job_allow` int(1) NOT NULL default '1',
      ADD COLUMN `level_job_entries` int(3) NOT NULL default '50',
      ADD COLUMN `level_job_search` int(1) NOT NULL default '1',
      ADD COLUMN `level_job_privacy` varchar(100) NOT NULL default 'a:6:{i:0;s:1:\"1\";i:1;s:1:\"3\";i:2;s:1:\"7\";i:3;s:2:\"15\";i:4;s:2:\"31\";i:5;s:2:\"63\";}',
      ADD COLUMN `level_job_comments` varchar(100) NOT NULL default 'a:7:{i:0;s:1:\"0\";i:1;s:1:\"1\";i:2;s:1:\"3\";i:3;s:1:\"7\";i:4;s:2:\"15\";i:5;s:2:\"31\";i:6;s:2:\"63\";}',
      ADD COLUMN `level_job_photo` int(1) NOT NULL default '1',
      ADD COLUMN `level_job_photo_width` varchar(3) NOT NULL default '500',
      ADD COLUMN `level_job_photo_height` varchar(3) NOT NULL default '500',
      ADD COLUMN `level_job_photo_exts` varchar(50) NOT NULL default '',
      ADD COLUMN `level_job_album_exts` text NULL,
      ADD COLUMN `level_job_album_mimes` text NULL,
      ADD COLUMN `level_job_album_storage` bigint(14) NOT NULL default '5242880',
      ADD COLUMN `level_job_album_maxsize` bigint(14) NOT NULL default '2048000',
      ADD COLUMN `level_job_album_width` varchar(4) NOT NULL default '500',
      ADD COLUMN `level_job_album_height` varchar(4) NOT NULL default '500',
      ADD COLUMN `level_job_html` text NULL
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "
      UPDATE
        se_levels
      SET
        level_job_html='a,b,br,div,i,img,p,u',
        level_job_photo_exts='jpg,jpeg,gif,png',
        level_job_album_exts='jpg,gif,jpeg,png,bmp,mp3,mpeg,avi,mpa,mov,qt,swf',
        level_job_album_mimes='image/jpeg,image/pjpeg,image/jpg,image/jpe,image/pjpg,image/x-jpeg,x-jpg,image/gif,image/x-gif,image/png,image/x-png,image/bmp,audio/mpeg,video/mpeg,video/x-msvideo,video/quicktime,application/x-shockwave-flash'
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_job_privacy'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( $result && strtoupper($result['Type'])=="VARCHAR(10)" )
  {
    $sql = "ALTER TABLE se_levels CHANGE level_job_privacy level_job_privacy varchar(100) NOT NULL default ''";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "UPDATE se_levels SET level_job_privacy='a:6:{i:0;s:1:\"1\";i:1;s:1:\"3\";i:2;s:1:\"7\";i:3;s:2:\"15\";i:4;s:2:\"31\";i:5;s:2:\"63\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_job_comments'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  $result = ( $database->database_num_rows($resource) ? $database->database_fetch_assoc($resource) : NULL );
  
  if( $result && strtoupper($result['Type'])=="VARCHAR(10)" )
  {
    $sql = "ALTER TABLE se_levels CHANGE level_job_comments level_job_comments varchar(100) NOT NULL default ''";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
    
    $sql = "UPDATE se_levels SET level_job_comments='a:6:{i:0;s:1:\"1\";i:1;s:1:\"3\";i:2;s:1:\"7\";i:3;s:2:\"15\";i:4;s:2:\"31\";i:5;s:2:\"63\";}'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_job_style'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_levels ADD COLUMN `level_job_style` TINYINT NOT NULL default 1";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_levels` LIKE 'level_job_html'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_levels ADD COLUMN `level_job_html`  TEXT NULL";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
    
    $sql = "UPDATE se_levels SET level_job_html='a,b,br,div,i,img,p,u'";
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  
  //######### ADD COLUMNS/VALUES TO SETTINGS TABLE
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_settings` LIKE 'setting_permission_job'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_settings ADD COLUMN `setting_permission_job` int(1) NOT NULL default '1'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### ADD COLUMNS/VALUES TO SYSTEM EMAILS TABLE
  $sql = "SELECT systememail_id FROM se_systememails WHERE systememail_name='jobcomment'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $database->database_query("
      INSERT INTO se_systememails
        (systememail_name, systememail_title, systememail_desc, systememail_subject, systememail_body, systememail_vars)
      VALUES
        ('jobcomment', '6400005', '6400006', '6400154', '6400155', '[displayname],[commenter],[link]')
    ");
  }
  
  
  
  
  //######### ADD COLUMNS/VALUES TO USER SETTINGS TABLE
  $sql = "SHOW COLUMNS FROM `$database_name`.`se_usersettings` LIKE 'usersetting_notify_jobcomment'";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "ALTER TABLE se_usersettings ADD COLUMN `usersetting_notify_jobcomment` int(1) NOT NULL default '1'";
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS NOT BEEN INSTALLED)
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=6400001 LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES 
        (6400001, 1, 'Job Settings', ''),
        (6400002, 1, 'View Job Listings', ''),
        (6400003, 1, 'Global Job Settings', ''),
        (6400004, 1, 'Job Settings', ''),
        (6400005, 1, 'New Job Comment Email', ''),
        (6400006, 1, 'This is the email that gets sent to a user when a new comment is posted on one of their job listings.', ''),
        (6400007, 1, 'Jobs', '')
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS BEEN INSTALLED)
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=6400008 LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES
        /* admin_levels_jobsettings */
        (6400008, 1, 'Your listings per page field must contain an integer between 1 and 999.', 'admin_levels_jobsettings'),
        (6400009, 1, 'Photo width and height must be integers between 1 and 999.', 'admin_levels_jobsettings'),
        (6400010, 1, 'Your maximum filesize field must contain an integer between 1 and 204800.', 'admin_levels_jobsettings'),
        (6400011, 1, 'Your maximum width and height fields must contain integers between 1 and 9999.', 'admin_levels_jobsettings'),
        (6400012, 1, 'If you have allowed users to have jobs listings, you can adjust their details from this page.', 'admin_levels_jobsettings'),
        (6400013, 1, 'Allow Jobs?', 'admin_levels_jobsettings'),
        (6400014, 1, 'Do you want to let users have job listings? If set to no, all other settings on this page will not apply.', 'admin_levels_jobsettings'),
        (6400015, 1, 'Yes, allow job listings.', 'admin_levels_jobsettings'),
        (6400016, 1, 'No, do not allow job listings.', 'admin_levels_jobsettings'),
        (6400017, 1, 'Allow Job Photos?', 'admin_levels_jobsettings'),
        (6400018, 1, 'If you enable this feature, users will be able to upload a small photo icon when creating or editing a job listing. This can be displayed next to the job name in search/browse results, etc.', 'admin_levels_jobsettings'),
        (6400019, 1, 'Yes, users can upload a photo icon when they create/edit a job listing.', 'admin_levels_jobsettings'),
        (6400020, 1, 'No, users can not upload a photo icon when they create/edit a job listing.', 'admin_levels_jobsettings'),
        (6400021, 1, 'If you have selected YES above, please input the maximum dimensions for the job photos. If your users upload a photo that is larger than these dimensions, the server will attempt to scale them down automatically. This feature requires that your PHP server is compiled with support for the GD Libraries.', 'admin_levels_jobsettings'),
        (6400022, 1, 'Maximum Width:', 'admin_levels_jobsettings'),
        (6400023, 1, 'Maximum Height:', 'admin_levels_jobsettings'),
        (6400024, 1, '(in pixels, between 1 and 999)', 'admin_levels_jobsettings'),
        (6400025, 1, 'What file types do you want to allow for job photos (gif, jpg, jpeg, or png)? Separate file types with commas, i.e. jpg, jpeg, gif, png', 'admin_levels_jobsettings'),
        (6400026, 1, 'Allowed File Types:', 'admin_levels_jobsettings'),
        (6400027, 1, 'Listings Per Page', 'admin_levels_jobsettings'),
        (6400028, 1, 'How many job listings will be shown per page? (Enter a number between 1 and 999)', 'admin_levels_jobsettings'),
        (6400029, 1, 'listings per page', 'admin_levels_jobsettings'),
        (6400030, 1, 'Job Privacy Options', 'admin_levels_jobsettings'),
        (6400031, 1, '<b>Search Privacy Options</b><br>If you enable this feature, users will be able to exclude their job listings from search results. Otherwise, all job listings will be included in search results.', 'admin_levels_jobsettings'),
        (6400032, 1, 'Yes, allow users to exclude their job listings from search results.', 'admin_levels_jobsettings'),
        (6400033, 1, 'No, force all job listings to be included in search results.', 'admin_levels_jobsettings'),
        (6400034, 1, '<b>Job Listing Privacy</b><br>Your users can choose from any of the options checked below when they decide who can see their job listings. These options appear on your users\' \"Add listing\" and \"Edit listing\" pages. If you do not check any options, everyone will be allowed to view jobs.', 'admin_levels_jobsettings'),
        (6400035, 1, '<b>Job Comment Options</b><br>Your users can choose from any of the options checked below when they decide who can post comments on their listings. If you do not check any options, everyone will be allowed to post comments on listings.', 'admin_levels_jobsettings'),
        (6400036, 1, 'Job File Settings', 'admin_levels_jobsettings'),
        (6400037, 1, 'List the following file extensions that users are allowed to upload. Separate file extensions with commas, for example: jpg, gif, jpeg, png, bmp', 'admin_levels_jobsettings'),
        (6400038, 1, 'To successfully upload a file, the file must have an allowed filetype extension as well as an allowed MIME type. This prevents users from disguising malicious files with a fake extension. Separate MIME types with commas, for example: image/jpeg, image/gif, image/png, image/bmp', 'admin_levels_jobsettings'),
        (6400039, 1, 'How much storage space should each listing have to store its files?', 'admin_levels_jobsettings'),
        (6400040, 1, 'Unlimited', 'admin_levels_jobsettings'),
        (6400041, 1, '%1\$s KB', 'admin_levels_jobsettings'),
        (6400042, 1, '%1\$s MB', 'admin_levels_jobsettings'),
        (6400043, 1, '%1\$s GB', 'admin_levels_jobsettings'),
        (6400044, 1, 'Enter the maximum filesize for uploaded files in KB. This must be a number between 1 and 204800.', 'admin_levels_jobsettings'),
        (6400045, 1, 'Enter the maximum width and height (in pixels) for images uploaded to listings. Images with dimensions outside this range will be sized down accordingly if your server has the GD Libraries installed. Note that unusual image types like BMP, TIFF, RAW, and others may not be resized.', 'admin_levels_jobsettings'),
        (6400046, 1, 'Maximum Width:', 'admin_levels_jobsettings'),
        (6400047, 1, 'Maximum Height:', 'admin_levels_jobsettings'),
        (6400048, 1, '(in pixels, between 1 and 9999)', 'admin_levels_jobsettings'),
        
        /* admin_viewjobs */
        (6400049, 1, 'This page lists all of the job listings your users have posted. You can use this page to monitor these jobs and delete offensive material if necessary. Entering criteria into the filter fields will help you find specific job listings. Leaving the filter fields blank will show all the job listings on your social network.', 'admin_viewjobs'),
        (6400050, 1, 'No listings were found.', 'admin_viewjobs'),
        (6400051, 1, '%1\$d Job Listings Found', 'admin_viewjobs'),
        (6400052, 1, 'Title', 'admin_viewjobs'),
        (6400053, 1, 'Owner', 'admin_viewjobs'),
        (6400054, 1, 'view', 'admin_viewjobs, jobs'),
        (6400055, 1, 'Are you sure you want to delete this job listing?', 'admin_viewjobs'),
        
        /* job */
        (6400056, 1, '<a href=\"%2\$s\">%1\$s</a>\'s <a href=\"%3\$s\">Job Listings</a>', 'job'),
        (6400057, 1, 'Created: %1\$s', 'job, jobs'),
        (6400058, 1, 'Category:', 'job'),
        (6400059, 1, 'Back to %1\$s\'s Listings', 'job'),
        
        /* jobs */
        (6400060, 1, '<a href=\"%2\$s\">%1\$s</a>\'s Job Listings', 'jobs'),
        (6400061, 1, '<b><a href=\"%2\$s\">%1\$s</a></b> has not posted any job listings.', 'jobs'),
        (6400062, 1, 'Views: %1\$d views', 'jobs'),
        (6400063, 1, 'Comments: %1\$d comments', 'jobs'),
        
        /* profile_job */
        (6400064, 1, 'Posted:', 'profile_job'),
        
        /* user_job */
        (6400065, 1, 'Post New Listing', 'user_job'),
        (6400066, 1, 'Listing Settings', 'user_job'),
        (6400067, 1, 'Search My Listings', 'user_job'),
        (6400068, 1, 'My Job Listings', 'user_job'),
        (6400069, 1, 'Job listings are a great way to find Jobs or post Jobs in your local community.', 'user_job'),
        (6400070, 1, 'No job listings were found.', 'user_job'),
        (6400071, 1, 'You do not have any job listings. <a href=\"%1\$s\">Click here</a> to post one.', 'user_job'),
        (6400072, 1, '%1\$d views', 'browse_jobs, user_job'),
        (6400073, 1, 'View Job', 'user_job'),
        (6400074, 1, 'Edit Job', 'user_job'),
        (6400075, 1, 'Edit Photos', 'user_job'),
        (6400076, 1, 'Delete Job', 'user_job'),
        
        /* admin_job */
        (6400077, 1, 'General Job Settings', 'admin_job'),
        (6400078, 1, 'This page contains general job settings that affect your entire social network.', 'admin_job'),
        (6400079, 1, 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the <a href=\"admin_general.php\">General Settings</a> page.', 'admin_job'),
        (6400080, 1, 'Yes, the public can view jobs unless they are made private.', 'admin_job'),
        (6400081, 1, 'No, the public cannot view jobs.', 'admin_job'),
        (6400082, 1, 'Job Categories/Fields', 'admin_job'),
        (6400083, 1, 'You may want to allow your users to categorize their job listings by subject, location, etc. Categorized job listings make it easier for users to find and jobs that interest them. If you want to allow job listing categories, you can create them (along with subcategories) below.<br /><br />Within each category, you can create job fields. When a job is created, the creator (owner) will describe the job by filling in some fields with information about the job. Add the fields you want to include below. Some examples of job fields are \"Location\", \"Price\", \"Contact Email\", etc. Remember that a \"Job Title\" and \"Job Description\" field will always be available and required. Drag the icons next to the categories and fields to reorder them.', 'admin_job'),
        (6400084, 1, 'Job Categories', 'admin_job'),
        
        /* user_job_listing */
        (6400085, 1, 'Post Job Listing', 'user_job_listing'),
        (6400086, 1, 'Edit Job Listing', 'user_job_listing'),
        (6400087, 1, 'Write your new Job listing below, then click \"Post Job\" to publish the listing.', 'user_job_listing'),
        (6400088, 1, 'Edit the details of this job listing below.', 'user_job_listing'),
        (6400089, 1, 'Job Title', 'user_job_listing'),
        (6400090, 1, 'Job Description', 'user_job_listing'),
        (6400091, 1, 'Job Category', 'user_job_listing'),
        (6400092, 1, 'Include this job in search/browse results?', 'user_job_listing'),
        (6400093, 1, 'Yes, include this listing in search/browse results.', 'user_job_listing'),
        (6400094, 1, 'No, exclude this listing from search/browse results.', 'user_job_listing'),
        (6400095, 1, 'Who can see this job?', 'user_job_listing'),
        (6400096, 1, 'You can decide who gets to see this job.', 'user_job_listing'),
        (6400097, 1, 'Allow Comments?', 'user_job_listing'),
        (6400098, 1, 'You can decide who can post comments on this job.', 'user_job_listing'),
        (6400099, 1, 'Post Job', 'user_job_listing'),
        (6400100, 1, 'Please enter a name for your job.', 'user_job_listing'),
        (6400101, 1, 'Please select a category for this job.', 'user_job_listing'),
        (6400102, 1, 'Back to My Jobs', 'user_job_listing, user_job_media'),
        
        /* user_job_media */
        (6400103, 1, 'Edit Listing Photos', 'user_job_media'),
        (6400104, 1, 'Use this page to change the photos shown on this job listing.', 'user_job_media'),
        (6400105, 1, 'Your job listing has been posted! Do you want to add some photos?', 'user_job_media'),
        (6400106, 1, 'Add Photos Now', 'user_job_media'),
        (6400107, 1, 'Maybe Later', 'user_job_media'),
        (6400108, 1, 'Small Photo', 'user_job_media'),
        (6400109, 1, 'Replace this photo with:', 'user_job_media'),
        (6400110, 1, 'delete photo', 'user_job_media'),
        (6400111, 1, 'Deleting photo...', 'user_job_media'),
        (6400112, 1, 'Add a photo:', 'user_job_media'),
        (6400113, 1, 'Upload', 'user_job_media'),
        (6400114, 1, 'Large Photos', 'user_job_media'),
        
        /* user_job_settings */
        (6400115, 1, 'Edit Job Settings', 'user_job_settings'),
        (6400116, 1, 'Edit settings pertaining to your job listings.', 'user_job_settings'),
        (6400117, 1, 'Custom Job Styles', 'user_job_settings'),
        (6400118, 1, 'You can change the colors, fonts, and styles of your job listing by adding CSS code below. The contents of the text area below will be output between &lt;style&gt; tags on your job listing.', 'user_job_settings'),
        (6400119, 1, 'Job Notifications', 'user_job_settings'),
        (6400120, 1, 'Notify me by email when someone writes a comment on my job listings.', 'user_job_settings'),
        
        /* MISC */
        (6400121, 1, 'Delete Job Listing?', 'user_job'),
        (6400122, 1, 'Are you sure you want to delete this job listing?', 'user_job'),
        (6400123, 1, 'There was an error processing your request.', 'user_job'),
        
        /* browse_jobs */
        (6400124, 1, 'Browse Job Listings', 'browse_jobs'),
        (6400125, 1, 'View:', 'browse_jobs'),
        (6400126, 1, 'Order:', 'browse_jobs'),
        (6400127, 1, 'Everyone\'s Jobs', 'browse_jobs'),
        (6400128, 1, 'My Friends\' Jobs', 'browse_jobs'),
        (6400129, 1, 'Recently Created', 'browse_jobs'),
        (6400130, 1, 'Recently Updated', 'browse_jobs'),
        (6400131, 1, 'Most Viewed', 'browse_jobs'),
        (6400132, 1, 'Most Commented', 'browse_jobs'),
        (6400133, 1, 'All Job Listings', 'browse_jobs'),
        (6400134, 1, 'No jobs were found matching your criteria.', 'browse_jobs'),
        (6400135, 1, 'created %1\$s', 'browse_jobs'),
        (6400136, 1, 'updated %1\$s', 'browse_jobs'),
        
        /* search */
        (6400137, 1, 'Job listing: %1\$s', 'search'),
        (6400138, 1, 'Job listing posted by <a href=\'%1\$s\'>%2\$s</a><br>%3\$s', 'search'),
        (6400139, 1, '%1\$d jobs', 'search'),
        
        /* MISC */
        (6400140, 1, 'HTML in Job Listings', 'admin_levels_jobsettings'),
        (6400141, 1, 'If you want to allow specific HTML tags, you can enter them below (separated by commas). Example: <i>b, img, a, embed, font<i>', 'admin_levels_jobsettings'),
        (6400142, 1, 'Job Photo', 'job'),
        (6400143, 1, '%1\$s\'s job listings', 'header_global'),
        (6400144, 1, '%1\$s\'s job listing - %2\$s', 'header_global')
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=6400145 LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES
        (6400145, 1, 'Jobs: %1\$d jobs', 'home'),
        (6400146, 1, 'Job Comments: %1\$d comments', 'home'),
        (6400147, 1, 'Job Media: %1\$d media', 'home')
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_language_id=1 && languagevar_id=6400148 LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES
        (6400148, 1, 'Posting a Job Listing', 'actiontypes'),
        (6400149, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> posted a job listing: <a href=\"job.php?user=%1\$s&job_id=%3\$s\">%4\$s</a>', 'actiontypes'),
        (6400150, 1, 'Posting a Job Comment', 'actiontypes'),
        (6400151, 1, '<a href=\"profile.php?user=%1\$s\">%2\$s</a> posted a comment on <a href=\"profile.php?user=%3\$s\">%4\$s</a>\'s <a href=\"job.php?user=%3\$s&job_id=%6\$s\">job listing</a>:<div class=\"recentaction_div\">%5\$s</div>', 'actiontypes'),
        (6400152, 1, '%1\$d New Job Comment(s): %2\$s', 'notifytypes'),
        (6400153, 1, 'When I receive a job comment.', 'notifytypes'),
        (6400154, 1, 'New Job Listing Comment', 'systememails'),
        (6400155, 1, 'Hello %1\$s,\n\nA new comment has been posted on one of your job listings by %2\$s. Please click the following link to view it:\n\n%3\$s\n\nBest Regards,\nSocial Network Administration', 'systememails')
    ";
    
    $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  }
  
  
  
  
  ################ UPGRADE EXISTING JOBS' PRIVACY OPTIONS
  if( !empty($plugin_info) && version_compare($plugin_info['plugin_version'], '3.00', '<') )
  {
    $database->database_query("UPDATE se_jobs SET job_privacy='63'  WHERE job_privacy='0' ") or die($database->database_error()." View Privacy Query #1");
    $database->database_query("UPDATE se_jobs SET job_privacy='31'  WHERE job_privacy='1' ") or die($database->database_error()." View Privacy Query #2");
    $database->database_query("UPDATE se_jobs SET job_privacy='15'  WHERE job_privacy='2' ") or die($database->database_error()." View Privacy Query #3");
    $database->database_query("UPDATE se_jobs SET job_privacy='7'   WHERE job_privacy='3' ") or die($database->database_error()." View Privacy Query #4");
    $database->database_query("UPDATE se_jobs SET job_privacy='3'   WHERE job_privacy='4' ") or die($database->database_error()." View Privacy Query #5");
    $database->database_query("UPDATE se_jobs SET job_privacy='1'   WHERE job_privacy='5' ") or die($database->database_error()." View Privacy Query #6");
    $database->database_query("UPDATE se_jobs SET job_privacy='0'   WHERE job_privacy='6' ") or die($database->database_error()." View Privacy Query #7");
    
    $database->database_query("UPDATE se_jobs SET job_comments='63' WHERE job_comments='0'") or die($database->database_error()." Comment Privacy Query #1");
    $database->database_query("UPDATE se_jobs SET job_comments='31' WHERE job_comments='1'") or die($database->database_error()." Comment Privacy Query #2");
    $database->database_query("UPDATE se_jobs SET job_comments='15' WHERE job_comments='2'") or die($database->database_error()." Comment Privacy Query #3");
    $database->database_query("UPDATE se_jobs SET job_comments='7'  WHERE job_comments='3'") or die($database->database_error()." Comment Privacy Query #4");
    $database->database_query("UPDATE se_jobs SET job_comments='3'  WHERE job_comments='4'") or die($database->database_error()." Comment Privacy Query #5");
    $database->database_query("UPDATE se_jobs SET job_comments='1'  WHERE job_comments='5'") or die($database->database_error()." Comment Privacy Query #6");
    $database->database_query("UPDATE se_jobs SET job_comments='0'  WHERE job_comments='6'") or die($database->database_error()." Comment Privacy Query #7");
  }
}

?>