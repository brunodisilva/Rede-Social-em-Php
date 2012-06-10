<?
$page = "admin_userpoints_shop_levelupgrade";
include "admin_header.php";

$task = semods::getpost('task', "main");
$item_id = semods::getpost('item_id', 0);

// SET ERROR VARIABLES
$is_error = 0;
$error_message = "";

// Load item data for editing
if( ($task != "addoffer") && ($item_id > 0)) {
  $upspender = new semods_upspender( $item_id, false );

  if($upspender->upspender_exists == 0) 
    semods::redirect("admin_userpoints_shop.php");

  $offer_title = $upspender->upspender_info['userpointspender_title'];
  $offer_enabled = $upspender->upspender_info['userpointspender_enabled'];
  $offer_desc = $upspender->upspender_info['userpointspender_body'];
  $offer_cost = $upspender->upspender_info['userpointspender_cost'];
  $offer_tags = $upspender->upspender_info['userpointspender_tags'];
  $offer_allow_comments = $upspender->upspender_info['userpointspender_comments_allowed'];
  $offer_levels = $upspender->upspender_info['userpointspender_levels'];
  $offer_subnets = $upspender->upspender_info['userpointspender_subnets'];

  
  $metadata = unserialize( $upspender->upspender_info['userpointspender_metadata'] );
  
  $level_from = $metadata['level_from'];
  $level_to = $metadata['level_to'];

} else {
  // Defaults
  $offer_cost = 0;
  $offer_enabled = 1;
  $offer_allow_comments = 1;
  $offer_transact_state = 0;
  $offer_levels = '';  // all levels
  $offer_subnets = '';  // all subnets

  $level_from = 0;
  $level_to = 0;
}

// CREATE / EDIT
if($task == "addoffer") {

  $item_type = semods::getpost('item_type', 0);
  
  $offer_enabled = semods::getpost('offer_enabled',0);
  $offer_title = trim(semods::getpost('offer_title',''));
  $offer_desc = $_POST['offer_desc'];
  $offer_cost = intval(semods::getpost('offer_cost',0));
  $offer_tags = $_POST['offer_tags'];
  $offer_allow_comments = $_POST['offer_allow_comments'];
  $offer_levels_all = semods::post('offer_levels_all',0);
  $offer_levels = $offer_levels_all ? array() : semods::post('offer_levels',array());
  $offer_subnets_all = semods::post('offer_subnets_all',0);
  $offer_subnets = $offer_subnets_all ? array() : semods::post('offer_subnets',array());

  $offer_levels = implode( ',', $offer_levels );
  $offer_subnets = implode( ',', $offer_subnets );

  $offer_desc_encoded = $offer_desc;

  $level_from = semods::post('level_from');
  $level_to = semods::post('level_to');
  
  if(empty($offer_title)) {
    $is_error = 1;
    $error_message = 100016323;
  }

  if($is_error == 0) {
	
    $metadata = array( 'level_from'  => intval($level_from),
                       'level_to'    => intval($level_to) );
    
    $metadata = serialize( $metadata );

	if($item_id == 0) {
	  $database->database_query("INSERT INTO se_semods_userpointspender(
								userpointspender_type,
								userpointspender_name,
								userpointspender_title,
								userpointspender_body,
								userpointspender_date,
								userpointspender_cost,
								userpointspender_metadata,
								userpointspender_enabled,
								userpointspender_tags,
								userpointspender_comments_allowed,
                                userpointspender_levels,
                                userpointspender_subnets
								)
								VALUES(
								200,
								'levelupgrade',
								'$offer_title',
								'$offer_desc_encoded',
								UNIX_TIMESTAMP(NOW()),
								$offer_cost,
								'$metadata',
								$offer_enabled,
								'$offer_tags',
								$offer_allow_comments,
                                '$offer_levels',
                                '$offer_subnets'
								)");
	  $item_id = $database->database_insert_id();
    }
	else
	  $database->database_query("UPDATE se_semods_userpointspender SET
								userpointspender_title = '$offer_title',
								userpointspender_body = '$offer_desc_encoded',
								userpointspender_cost = $offer_cost,
								userpointspender_metadata = '$metadata',
								userpointspender_enabled = $offer_enabled,
								userpointspender_tags = '$offer_tags',
								userpointspender_comments_allowed = $offer_allow_comments,
                                userpointspender_levels = '$offer_levels',
                                userpointspender_subnets = '$offer_subnets'
								WHERE userpointspender_id = $item_id");

	$result = 1;
  }

  // CONVERT HTML CHARACTERS BACK
  $offer_desc = str_replace("\r\n", "", html_entity_decode( $offer_desc ));

}


$offer_levels = empty($offer_levels) ? array() : explode(",", $offer_levels);

// LOOP OVER USER LEVELS, TAKE SELECTED
$levels = $database->database_query("SELECT level_id, level_name, level_default FROM se_levels ORDER BY level_id");
while($level_info = $database->database_fetch_assoc($levels)) {
  $level_info['level_selected'] = in_array($level_info['level_id'], $offer_levels) || $item_id == 0 || empty($offer_levels) ? 1 : 0;
  $level_array[] = $level_info;
}

$offer_subnets = empty($offer_subnets) ? array() : explode(",", $offer_subnets);

// LOOP OVER USER LEVELS, TAKE SELECTED
$rows = $database->database_query("SELECT subnet_id, subnet_name FROM se_subnets");
while($row = $database->database_fetch_assoc($rows)) {
  $row['subnet_selected'] = in_array($row['subnet_id'], $offer_subnets) || $item_id == 0 || empty($offer_subnets) ? 1 : 0;
  $subnet_array[] = $row;
  SE_Language::_preload( $row['subnet_name'] );
}

// ASSIGN VARIABLES AND SHOW ADMIN ADD USER LEVEL PAGE
$smarty->assign('result', $result);
$smarty->assign('is_error', $is_error);
$smarty->assign('error_message', $error_message);

$smarty->assign('levels', $level_array);
$smarty->assign('levels_all', empty($level_array) ? 1 : 0);
$smarty->assign('subnets', $subnet_array);
$smarty->assign('subnets_all', empty($subnet_array) ? 1 : 0);

$smarty->assign('offer_title', $offer_title);
$smarty->assign('offer_enabled', $offer_enabled);
$smarty->assign('offer_desc', $offer_desc);
$smarty->assign('offer_cost', $offer_cost);
$smarty->assign('offer_tags', $offer_tags);
$smarty->assign('offer_allow_comments', $offer_allow_comments);

$smarty->assign('item_id', $item_id);

$smarty->assign('level_from', $level_from);
$smarty->assign('level_to', $level_to);


include "admin_footer.php";
?>