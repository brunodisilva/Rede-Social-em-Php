<?
$page = "admin_userpoints_assign";
include "admin_header.php";

$task = semods::post('task', 'main');

// SET RESULT VARIABLE
$result = 0;

// SAVE CHANGES
if($task == "dosave") {
  $actions = $_POST['actions'];
  $actionsmax = $_POST['actionsmax'];
  $actionsrollover = $_POST['actionsrollover'];
  $actionsname = $_POST['actionsname'];

  foreach($actions as $key => $value){

    // days -> seconds
    $rollover_period = intval($actionsrollover[$key]) * 86400;
    
    // new, previously unknown actiontype
    if( intval($key) == 0 ) {

      $database->database_query( "INSERT INTO se_semods_actionpoints (
                                action_type,
                                action_name,
                                action_points,
                                action_pointsmax,
                                action_rolloverperiod)
                                VALUES (
                                '$key',
                                '{$actionsname[$key]}',
                                " . intval($value) . ",
                                " . intval($actionsmax[$key]) . ",
                                $rollover_period
                                )" );
    } else {
      
      // unknown item, changing name
      if(isset($actionsname[$key])) {
        $database->database_query( "UPDATE se_semods_actionpoints SET
                                      action_name = '{$actionsname[$key]}',
                                      action_points = " . intval($value) . ",
                                      action_pointsmax = " . intval($actionsmax[$key]) . ",
                                      action_rolloverperiod = $rollover_period
                                    WHERE action_id = " . intval($key) );
      } else {
      $database->database_query( "UPDATE se_semods_actionpoints SET
                                    action_points = " . intval($value) . ",
                                    action_pointsmax = " . intval($actionsmax[$key]) . ",
                                    action_rolloverperiod = $rollover_period
                                  WHERE action_id = " . intval($key) );
    }
    }
    
  }

  $result = 1;

}


$actions_dbr = $database->database_query( "SELECT A.actiontype_name,
                                                  P.action_id, P.action_type, IFNULL(P.action_name,A.actiontype_name) AS action_name, P.action_points, P.action_requiredplugin, P.action_group, P.action_pointsmax, P.action_rolloverperiod 
                                           FROM se_actiontypes A
                                           LEFT JOIN se_semods_actionpoints P ON A.actiontype_name = P.action_type
                                           UNION SELECT A.actiontype_name, P.action_id, P.action_type, IFNULL(P.action_name,A.actiontype_name) AS action_name, P.action_points, P.action_requiredplugin, P.action_group, P.action_pointsmax, P.action_rolloverperiod 
                                           FROM se_actiontypes A
                                           RIGHT JOIN se_semods_actionpoints P ON A.actiontype_name = P.action_type
                                           WHERE P.action_id >=100
                                           ORDER BY action_group DESC, action_id" );
$actions = array();
$action_group_previd = -1;
$action_group_id = -1;

while($row = $database->database_fetch_assoc($actions_dbr)) {

  $action_group_id = $row['action_group'];
  if($action_group_id != $action_group_previd) {
    if($action_group_previd != -1) {
      $actions[] = $action_group;
      $action_types[] = $action_group_types[intval($action_group_previd)];
      $action_group = array();
    } else {
      
    }
    $action_group_previd = $action_group_id;
  }

  // seconds -> days
  $row['action_rolloverperiod'] = $row['action_rolloverperiod'] / 86400; 

  $action_group[] = $row;
  

}

if(!empty($action_group)) {
  $actions[] = $action_group;
  $action_types[] = $action_group_types[intval($action_group_previd)];
}


// ASSIGN VARIABLES AND SHOW GENERAL SETTINGS PAGE
$smarty->assign('result', $result);
$smarty->assign('error', $error);

$smarty->assign('actions', $actions);
$smarty->assign('action_types', $action_types);

include "admin_footer.php";
?>