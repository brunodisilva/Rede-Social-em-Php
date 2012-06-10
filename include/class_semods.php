<?php
/* 
 * SEMods static class
 *
 * Copyright (c) 2008 SocialEngineMods.Net
 *
 */

$semods_settings_cache = null;

class semods {
  // cached settings


  function g(&$var, $key, $default = null){ 
      return isset($var[$key]) ? $var[$key] : $default;
  }
  
  function get($key, $default = null)     { return semods::g($_GET, $key, $default);      }
  function session($key, $default = null) { return semods::g($_SESSION, $key, $default);  }
  function post($key, $default = null)    { return semods::g($_POST, $key, $default);     }
  function request($key, $default = null) { return semods::g($_REQUEST, $key, $default);  }
  
  function getpost($key, $default = null) { return isset($_GET[$key]) ? $_GET [$key] : (isset($_POST[$key]) ? $_POST[$key] : $default); }

  
  /* DATABASE */

  function db_exec($query) {
      global $database;
      
      return mysql_unbuffered_query($query, $database->database_connection);
  }

  function db_query($query) {
      global $database;
      
      return $database->database_query($query);
  }
  
  function db_query_array($query) {
      global $database;
      
      $result = $database->database_query($query);
      return $result ? $database->database_fetch_array($result) : false;
  }

  function db_query_array_all($query) {
      global $database;
    
      $items = array();
      $rows = $database->database_query($query);
      while($row = $database->database_fetch_array($rows)) {
        $items[] = $row;
      }
      return $rows ? $items : false;
  }
  
  function db_query_assoc($query) {
      global $database;
      
      $result = $database->database_query($query);
      return $result ? $database->database_fetch_assoc($result) : false;
  }

  function db_query_assoc_all($query) {
      global $database;
    
      $items = array();
      $rows = $database->database_query($query);
      while($row = $database->database_fetch_assoc($rows)) {
        $items[] = $row;
      }
      return $rows ? $items : false;
  }
  
  function db_query_count($query) {
      $dbr = semods::db_query_array($query );
      if($dbr === false)
          return 0;
      
      return $dbr[0];
  }

  function db_query_affected_rows($query) {
      global $database;

      $result = $database->database_query($query);
      return $result ? $database->database_affected_rows($database->database_connection) : false;
  }
  

  /* Settings */


  function &get_settings() {
      global $semods_settings_cache;
      
      if(is_null($semods_settings_cache)) {
          $semods_settings_cache = semods::db_query_assoc( 'SELECT * FROM se_semods_settings' );
      }
      
      return $semods_settings_cache;
  }
  
  function get_setting($setting, $default_value = null) {
    $setting_key = 'setting_' . $setting;
    $settings = semods::get_settings();
    if($settings && isset($settings[$setting_key]))
      return $settings[$setting_key];
    
    return $default_value;
  }
  
  
  /* UTILITIES */

  function remove_array_empty_values($array, $remove_null_number = true) {
    $new_array = array();

    $null_exceptions = array();

    foreach ($array as $key => $value) {
      $value = trim($value);

      if($remove_null_number)
        $null_exceptions[] = '0';

      if(!in_array($value, $null_exceptions) && $value != "")
        $new_array[] = $value;
    }
    
    return $new_array;
  }
  
}

?>