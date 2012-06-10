<?php



/*
 * poll 
 *
 */
function userpoints_hook_footer_abort_poll( $arguments = array() ) {
  global $is_error, $smarty;
  
  $is_error = 100016015;
  $smarty->assign('is_error', $is_error);
  
}





/*
 * classified
 *
 */
function userpoints_hook_footer_abort_classified( $arguments = array() ) {
  global $is_error, $smarty;
  
  $is_error = 100016012;
  $smarty->assign('is_error', $is_error);

  // cache values
  foreach($_POST as $key => $value) {
    if(substr($key, 0, 11) == 'classified_') {
      $smarty->assign( $key, $value );
    }
  }
  
}





/*
 * event
 *
 */
function userpoints_hook_footer_abort_event( $arguments = array() ) {
  global $is_error, $smarty;
  
  $is_error = 100016013;
  $smarty->assign('is_error', $is_error);

  // Thank you very much for "Initializing variables"!
  
  // cache values
  foreach($_POST as $key => $value) {
    if(substr($key, 0, 6) == 'event_') {
      $smarty->assign( $key, $value );
    }
  }

  // Ouch!
  $eventcat_id = $_POST['eventcat_id'];
  $subeventcat_id = $_POST['subeventcat_id'];
  if($_POST['event_date_start_hour'] == "12") { $_POST['event_date_start_hour'] = 0; }
  if($_POST['event_date_start_ampm'] == "PM") { $_POST['event_date_start_hour'] += 12; }
  $event_date_start = mktime($_POST['event_date_start_hour'], $_POST['event_date_start_minute'], 0, $_POST['event_date_start_month'], $_POST['event_date_start_day'], $_POST['event_date_start_year']);
  if($_POST['event_date_end_hour'] == "12") { $_POST['event_date_end_hour'] = 0; }
  if($_POST['event_date_end_ampm'] == "PM") { $_POST['event_date_end_hour'] += 12; }
  $event_date_end = mktime($_POST['event_date_end_hour'], $_POST['event_date_end_minute'], 0, $_POST['event_date_end_month'], $_POST['event_date_end_day'], $_POST['event_date_end_year']);

  $smarty->assign('eventcat_id', $eventcat_id);
  $smarty->assign('subeventcat_id', $subeventcat_id);
  $smarty->assign('event_date_start', $event_date_start);
  $smarty->assign('event_date_end', $event_date_end);
  
}





/*
 * group
 *
 */
function userpoints_hook_footer_abort_group( $arguments = array() ) {
  global $is_error, $smarty;
  
  $is_error = 100016014;
  $smarty->assign('is_error', $is_error);

  // Thank you very much for "Initializing variables"!
  
  // cache values
  foreach($_POST as $key => $value) {
    if(substr($key, 0, 6) == 'group_') {
      $smarty->assign( $key, $value );
    }
  }

  // Consistency, please!
  $groupcat_id = $_POST['groupcat_id'];
  $subgroupcat_id = $_POST['subgroupcat_id'];

  $smarty->assign('groupcat_id', $groupcat_id);
  $smarty->assign('subgroupcat_id', $subgroupcat_id);
  
}




?>