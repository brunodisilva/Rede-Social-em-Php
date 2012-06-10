<?php
/*
 * Compatibility functions
 * json_encode 
 *
 * http://www.socialenginemods.net
 * 
 */


# For PHP < 5.2
if (!function_exists('json_encode')) {
  require_once 'json.php';

  function json_encode($arg) {
      global $services_json;
      if (!isset($services_json)) {
          $services_json = new Services_JSON();
      }
      return $services_json->encode($arg);
  }

  function json_decode($arg) {
      global $services_json;
      if (!isset($services_json)) {
          $services_json = new Services_JSON();
      }
      return $services_json->decode($arg);
  }
}



?>