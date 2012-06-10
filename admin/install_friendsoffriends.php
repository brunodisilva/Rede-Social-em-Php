<?php

$plugin_name = "People You May Know Plugin";
$plugin_version = "3.01";
$plugin_type = "friendsoffriends";
$plugin_desc = "This plugin allows your users to have a list of suggested people on their home page. Those people are already friends with their existing friends. They are also displayed on the page user_fof.php which is accessible by clicking the link People You May Know from the menu Friends.";
$plugin_icon = "friend_add16.gif";
$plugin_menu_title = "";
$plugin_pages_main = "";
$plugin_pages_level = "";
$plugin_url_htaccess = "";
$plugin_db_charset = 'utf8';
$plugin_db_collation = 'utf8_unicode_ci';




if( $install=="friendsoffriends" )
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
  $sql = "SELECT NULL FROM se_plugins WHERE plugin_type='$plugin_type'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
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
    
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
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
    
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
  
  
  //######### CREATE se_fof_removed
  $sql = "SHOW TABLES FROM `$database_name` LIKE 'se_fof_removed'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");

  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      CREATE TABLE `se_fof_removed`
      (
        `id`               INT(10)           NOT NULL auto_increment,
        `user_id`          INT(10)           NOT NULL,
        `removed_id`       INT(10)           NOT NULL,
        PRIMARY KEY  (`id`)
      )
      CHARACTER SET {$plugin_db_charset} COLLATE {$plugin_db_collation}
    ";

    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
 
  
    
  //######### INSERT LANGUAGE VARS (v3 COMPATIBLE HAS BEEN INSTALLED)
  $sql = "SELECT languagevar_id FROM se_languagevars WHERE languagevar_id=17000500 LIMIT 1";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  
  if( !$database->database_num_rows($resource) )
  {
    $sql = "
      INSERT INTO `se_languagevars`
        (`languagevar_id`, `languagevar_language_id`, `languagevar_value`, `languagevar_default`)
      VALUES 

        (17000500, 1, 'People You May Know', ''),
        (17000501, 1, 'Check among friends of your friends', ''),
        (17000502, 1, 'Remove', ''),
        (17000503, 1, 'Try to add someone to friends to see more suggestions', ''),
        (17000504, 1, 'See All', ''),
        (17000505, 1, '', '')
    ";
    
    $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");
  }
  
 
  
}  

?>