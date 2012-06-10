<?
$page = "admin_userpoints_shop_promote";
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
  
  $promotion_template = $metadata['ad_id'];
  $promotion_type = $upspender->upspender_info['userpointspender_type'];
  $promotion_html	= $metadata['html'];
  $promotion_duration = $metadata['duration'];
  $promotion_reqapproval = $metadata['reqapproval'];
  $promotion_delaystart = $metadata['delaystart'];

} else {
  // Defaults
  $offer_cost = 0;
  $offer_enabled = 1;
  $offer_allow_comments = 1;
  $offer_levels = '';  // all levels
  $offer_subnets = '';  // all subnets

  $promotion_duration = 1;    // 1day
  $promotion_reqapproval = 0; // no approval 
  $promotion_delaystart = 1;  // delay 1 day
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

  $promotion_template = semods::getpost('promotion_template',0);
  $promotion_type = semods::getpost('promotion_type',0);
  $promotion_html_array = semods::getpost('promotion_html',0);
  $promotion_html = $promotion_html_array[$promotion_type];
  $promotion_duration = intval(semods::getpost('promotion_duration',0));
  $promotion_reqapproval = intval(semods::getpost('promotion_reqapproval',0));
  $promotion_delaystart = intval(semods::getpost('promotion_delaystart',0));
  
  $promotion_html = htmlspecialchars( $promotion_html );
  
  $promotion_classes = array( 100 =>  'se_user',
                              101 =>  'se_classified',
                              102 =>  'se_event',
                              103 =>  'se_group',
                              104 =>  'se_poll' );

  $promotion_errors =  array( 101 =>  100016365,
                              102 =>  100016366,
                              103 =>  100016367,
                              104 =>  100016368
                              );

  for(;;) {
    $is_error = 1;

    if( !array_key_exists( $promotion_type, $promotion_classes ) ) {
      $error_message = 100016364;
      break;
    }
      
    if(empty($offer_title)) {
    $error_message = 100016363;
      break;
  }
 
    if(!class_exists( $promotion_classes[$promotion_type] ) ) {
      $error_message = $promotion_errors[$promotion_type];
      break;
    }
    
    $is_error = 0;
    break;
  }


  // update ad_html 

  if($is_error == 0) {

    $metadata = array( 'ad_id'		=> $promotion_template,
                       'html'			=> $promotion_html,
                       'delaystart'	=> $promotion_delaystart,
                       'reqapproval'	=> $promotion_reqapproval,
                       'duration'		=> $promotion_duration
                       );
    
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
								$promotion_type,
								'promote',
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
								userpointspender_comments_allowed = $offer_allow_comments
								WHERE userpointspender_id = $item_id");

	$result = 1;
  }

  // CONVERT HTML CHARACTERS BACK
  $offer_desc = str_replace("\r\n", "", html_entity_decode( $offer_desc ));

}

// CONVERT HTML CHARACTERS BACK
$promotion_html	= html_entity_decode( $promotion_html, ENT_QUOTES, 'UTF-8' );


// GET PROMOTION TEMPLATES
$rows = $database->database_query( "SELECT * FROM se_ads WHERE ad_name LIKE 'PROMOTION TEMPLATE%'" );
while($row = $database->database_fetch_assoc($rows)) {
  $promotion_templates[] = $row;
}

// PROMOTION TYPES
$promotion_types = array( array( 'promotiontype_id'		=> 100,
								 'promotiontype_name'	=> 'Profile',
								 'promotiontype_html'	=> "<div>
<div Xstyle='float:left'>
<a href='[userprofile]'>
<img src=[userphoto] width=[userphotowidth] border=0>
</a>
</div>
<div>[promotionmessage]</div>
</div>
"),
						  array( 'promotiontype_id'		=> 101,
								 'promotiontype_name'	=> 'Classified',
								 'promotiontype_html'	=> "<div>
<div Xstyle='float:left'>
<a href='[classified]'>
<img src=[classifiedphoto] width=[classifiedphotowidth] border=0>
</a>
</div>
<div>[promotionmessage]</div>
</div>
"),
                          
						  array( 'promotiontype_id'		=> 102,
								 'promotiontype_name'	=> 'Event',
								 'promotiontype_html'	=> "<div>
<div Xstyle='float:left'>
<a href='[event]'>
<img src=[eventphoto] width=[eventphotowidth] border=0>
</a>
</div>
<div>[promotionmessage]</div>
</div>
"),
                          
						  array( 'promotiontype_id'		=> 103,
								 'promotiontype_name'	=> 'Group',
								 'promotiontype_html'	=> "<div>
<div Xstyle='float:left'>
<a href='[group]'>
<img src=[groupphoto] width=[groupphotowidth] border=0>
</a>
</div>
<div>[promotionmessage]</div>
</div>
"),
                          
						  array( 'promotiontype_id'		=> 104,
								 'promotiontype_name'	=> 'Poll',
								 'promotiontype_html'	=> "<div>
<div Xstyle='float:left'>
<a href='[poll]'>
<img src=[userphoto] width=[userphotowidth] border=0>
</a>
</div>
<div>[promotionmessage]</div>
</div>
")
						  );
						 

$offer_levels = empty($offer_levels) ? array() : explode(",", $offer_levels);

// LOOP OVER USER LEVELS, TAKE SELECTED
$levels = $database->database_query("SELECT level_id, level_name, level_default FROM se_levels ORDER BY level_id");
while($level_info = $database->database_fetch_assoc($levels)) {
  $level_info['level_selected'] = in_array($level_info['level_id'], $offer_levels) || $item_id == 0 || empty($offer_levels) ? 1 : 0;
  $level_array[] = $level_info;
}

$offer_subnets = empty($offer_subnets) ? array() : explode(",", $offer_subnets);

// LOOP OVER USER SUBNETS, TAKE SELECTED
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

$smarty->assign('promotion_templates', $promotion_templates);
$smarty->assign('promotion_types', $promotion_types);

$smarty->assign('promotion_types_js', json_encode($promotion_types));

$smarty->assign('promotion_template', $promotion_template);
$smarty->assign('promotion_type', $promotion_type);
$smarty->assign('promotion_html', $promotion_html);
$smarty->assign('promotion_duration', $promotion_duration);
$smarty->assign('promotion_reqapproval', $promotion_reqapproval);
$smarty->assign('promotion_delaystart', $promotion_delaystart);

include "admin_footer.php";
?>