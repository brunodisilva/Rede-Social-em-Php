<?
$page = "admin_userpoints_userquotas";
include "admin_header.php";

$task = semods::post('task', 'main');
$user_id = semods::getpost('user_id', 0);

// VALIDATE USER ID OR RETURN TO VIEW USERS
$user = new se_user(array($user_id));
if($user->user_exists == 0) {
  semods::redirect("admin_userpoints_viewusers.php");
}

// SET RESULT VARIABLE
$result = 0;


// SAVE CHANGES
if($task == "dosave") {

  $result = 1;

}


$actions_dbr = $database->database_query( "SELECT A.actiontype_name,
                                                  P.action_id, P.action_type, IFNULL(P.action_name,A.actiontype_name) AS action_name, P.action_points, P.action_requiredplugin, P.action_group, P.action_pointsmax, P.action_rolloverperiod 
                                           FROM se_actiontypes A
                                           JOIN se_semods_actionpoints P ON A.actiontype_name = P.action_type
                                           UNION SELECT A.actiontype_name, P.action_id, P.action_type, IFNULL(P.action_name,A.actiontype_name) AS action_name, P.action_points, P.action_requiredplugin, P.action_group, P.action_pointsmax, P.action_rolloverperiod 
                                           FROM se_actiontypes A
                                           RIGHT JOIN se_semods_actionpoints P ON A.actiontype_name = P.action_type
                                           WHERE P.action_id >=100 AND NOT( NOT ISNULL(P.action_requiredplugin) AND ISNULL(actiontype_name) )
                                           ORDER BY action_group DESC, action_id" );

$user_quota = array();
$user_quota_dbr = $database->database_query( "SELECT * FROM se_semods_userpointcounters WHERE userpointcounters_user_id = $user_id" );
while($row = $database->database_fetch_assoc($user_quota_dbr)) {
  $user_quota[$row['userpointcounters_action_id']] = $row;
}

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

  // merge user quotas
  if(isset($user_quota[$row['action_id']])) {
    $row['userpointcounters_amount'] = $user_quota[$row['action_id']]['userpointcounters_amount']; 
    $row['userpointcounters_cumulative'] = $user_quota[$row['action_id']]['userpointcounters_cumulative']; 
    $row['userpointcounters_lastrollover'] = $user_quota[$row['action_id']]['userpointcounters_lastrollover']; 
  } else {
    $row['userpointcounters_amount'] = 0;
    $row['userpointcounters_cumulative'] = 0;
    $row['userpointcounters_lastrollover'] = 0;
  }
  
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

$smarty->assign('user_id', $user_id);
$smarty->assign('user_username', $setting['setting_username'] ? $user->user_info['user_username'] : $user->user_displayname );
include "admin_footer.php";
?>