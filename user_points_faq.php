<?
$page = "user_points_faq";
include "header.php";

// ENSURE POINTS ARE ENABLED FOR THIS USER
if(($user->level_info['level_userpoints_allow'] == 0) || ($user->user_info['user_userpoints_allowed'] == 0)){ header("Location: user_home.php"); exit(); }


// *almost* Copy&Paste from admin

$action_group_types =   array(  0  => 'Unknown / Uncategorized',
                                1  =>  'Group',
                                2  =>  'Poll',
                                3  => 'Events',
                                4  => 'Classifieds',
                                5  =>  'Blog',
                                6  =>  'Media / Albums',
                                7  =>  'General',
                                8  => 'Signup / Marketing'
                                );

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

$special_items = array( 'invite', 'refer', 'adclick' );

while($row = $database->database_fetch_assoc($actions_dbr)) {

  // skip uninstalled plugins
  if(empty($row['actiontype_name']) && !in_array($row['action_type'], $special_items))
    continue;

  // skip zero value (disabled) actions
  if(empty($row['action_points']))
    continue;
    
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




// ASSIGN SMARTY VARIABLES AND INCLUDE FOOTER
$smarty->assign('actions', $actions);
$smarty->assign('semods_settings', semods::get_settings());
include "footer.php";
?>
