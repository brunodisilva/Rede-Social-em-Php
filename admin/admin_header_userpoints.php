<?php

// INCLUDE SEMODS CLASS
include_once "../include/class_semods.php";
include_once "../include/class_semods_utils.php";

// INCLUDE FUNCTIONS
include_once "../include/functions_userpoints.php";

// INCLUDE CLASS
include_once "../include/class_userpoints.php";

// Hack for Ads Campaign name html
if($page == "admin_ads_modify") {
  $task = semods::getpost('task');
  $ad_name = semods::getpost('ad_name');
  if( ($task == "dosave") && (substr( $ad_name, 0, 9 ) == "Promoting" )) {
    $ad_name = @html_entity_decode( $ad_name, ENT_QUOTES, "UTF-8" );
    $ad_name = str_replace( "'", "&#039;", $ad_name );
    $_POST['ad_name'] = $ad_name;
  }
}
?>