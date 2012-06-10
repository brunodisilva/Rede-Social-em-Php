<?php

/* $Id: browse_classifieds.php 16 2009-01-13 04:01:31Z john $ */

$page = "browse_classifieds";
include "header.php";

// DISPLAY ERROR PAGE IF USER IS NOT LOGGED IN AND ADMIN SETTING REQUIRES REGISTRATION
if( !$user->user_exists && !$setting['setting_permission_classified'] )
{
  $page = "error";
  $smarty->assign('error_header', 639);
  $smarty->assign('error_message', 656);
  $smarty->assign('error_submit', 641);
  include "footer.php";
}


// PARSE GET/POST
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }
if(isset($_POST['s'])) { $s = $_POST['s']; } elseif(isset($_GET['s'])) { $s = $_GET['s']; } else { $s = "classified_datecreated DESC"; }
if(isset($_POST['v'])) { $v = $_POST['v']; } elseif(isset($_GET['v'])) { $v = $_GET['v']; } else { $v = 0; }
if(isset($_POST['classifiedcat_id'])) { $classifiedcat_id = $_POST['classifiedcat_id']; } elseif(isset($_GET['classifiedcat_id'])) { $classifiedcat_id = $_GET['classifiedcat_id']; } else { $classifiedcat_id = 0; }
if(isset($_POST['classified_search'])) { $classified_search = $_POST['classified_search']; } elseif(isset($_GET['classified_search'])) { $classified_search = $_GET['classified_search']; } else { $classified_search = NULL; }

// ENSURE SORT/VIEW ARE VALID
if($s != "classified_date DESC" && $s != "classified_dateupdated DESC" && $s != "classified_views DESC" && $s != "total_comments DESC") { $s = "classified_date DESC"; }
if($v != "0" && $v != "1") { $v = 0; }


// SET WHERE CLAUSE
$where = "CASE
	    WHEN se_classifieds.classified_user_id='{$user->user_info['user_id']}'
	      THEN TRUE
	    WHEN ((se_classifieds.classified_privacy & @SE_PRIVACY_REGISTERED) AND '{$user->user_exists}'<>0)
	      THEN TRUE
	    WHEN ((se_classifieds.classified_privacy & @SE_PRIVACY_ANONYMOUS) AND '{$user->user_exists}'=0)
	      THEN TRUE
	    WHEN ((se_classifieds.classified_privacy & @SE_PRIVACY_FRIEND) AND '{$user->user_exists}'<>0 AND (SELECT TRUE FROM se_friends WHERE friend_user_id1=se_classifieds.classified_user_id AND friend_user_id2='{$user->user_info['user_id']}' AND friend_status='1' LIMIT 1))
	      THEN TRUE
	    WHEN ((se_classifieds.classified_privacy & @SE_PRIVACY_SUBNET) AND '{$user->user_exists}'<>0 AND (SELECT TRUE FROM se_users WHERE user_id=se_classifieds.classified_user_id AND user_subnet_id='{$user->user_info['user_subnet_id']}' LIMIT 1))
	      THEN TRUE
	    WHEN ((se_classifieds.classified_privacy & @SE_PRIVACY_FRIEND2) AND '{$user->user_exists}'<>0 AND (SELECT TRUE FROM se_friends AS friends_primary LEFT JOIN se_users ON friends_primary.friend_user_id1=se_users.user_id LEFT JOIN se_friends AS friends_secondary ON friends_primary.friend_user_id2=friends_secondary.friend_user_id1 WHERE friends_primary.friend_user_id1=se_classifieds.classified_user_id AND friends_secondary.friend_user_id2='{$user->user_info['user_id']}' AND se_users.user_subnet_id='{$user->user_info['user_subnet_id']}' LIMIT 1))
	      THEN TRUE
	    ELSE FALSE
	END";



// ONLY MY FRIENDS' CLASSIFIEDS
if( $v=="1" && $user->user_exists )
{
  // SET WHERE CLAUSE
  $where .= " AND (SELECT TRUE FROM se_friends WHERE friend_user_id1='{$user->user_info['user_id']}' AND friend_user_id2=se_classifieds.classified_user_id AND friend_status=1)";
}



// SPECIFIC CLASSIFIED CATEGORY
if( $classifiedcat_id )
{
  $sql = "SELECT classifiedcat_id, classifiedcat_title, classifiedcat_dependency FROM se_classifiedcats WHERE classifiedcat_id={$classifiedcat_id} LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( $database->database_num_rows($resource) )
  {
    $classifiedcat = $database->database_fetch_assoc($resource);
    
    if( !$classifiedcat['classifiedcat_dependency'] )
    {
      $cat_ids[] = $classifiedcat['classifiedcat_id'];
      $depcats = $database->database_query("SELECT classifiedcat_id FROM se_classifiedcats WHERE classifiedcat_id='{$classifiedcat['classifiedcat_id']}' OR classifiedcat_dependency='{$classifiedcat['classifiedcat_id']}'");
      while($depcat_info = $database->database_fetch_assoc($depcats)) { $cat_ids[] = $depcat_info['classifiedcat_id']; }
      $where .= " AND se_classifieds.classified_classifiedcat_id IN('".implode("', '", $cat_ids)."')";
    }
    else
    {
      $where .= " AND se_classifieds.classified_classifiedcat_id='{$classifiedcat['classifiedcat_id']}'";
      $classifiedsubcat = $classifiedcat;
      $classifiedcat = $database->database_fetch_assoc($database->database_query("SELECT classifiedcat_id, classifiedcat_title FROM se_classifiedcats WHERE classifiedcat_id='{$classifiedcat['classifiedcat_dependency']}'"));
    }
  }
}


// GET CATS
$field = new se_field("classified");
$field->cat_list(0, 0, 0, "", "", "");
$cat_menu_array = $field->cats;

//$field->cat_list(0, 0, 1, "classifiedcat_id='{$classifiedcat['classifiedcat_id']}'", "", "");
$field->field_list(0, 0, 1, "classifiedfield_classifiedcat_id='{$classifiedcat['classifiedcat_id']}' && classifiedfield_search<>'0'");


// BEGIN CONSTRUCTING SEARCH QUERY
//echo $field->field_query;
if( $field->field_query )
  $where .= " && ".$field->field_query;

if( !empty($classified_search) )
{
  $where .= " && MATCH(classified_title, classified_body) AGAINST ('{$classified_search}' IN BOOLEAN MODE) ";
}


// CREATE CLASSIFIED OBJECT, GET TOTAL CLASSIFIEDS, MAKE ENTRY PAGES, GET CLASSIFIED ARRAY
$classified = new se_classified();

$total_classifieds = $classified->classified_total($where, TRUE);
$classifieds_per_page = 10;
$page_vars = make_page($total_classifieds, $classifieds_per_page, $p);

$classified_array = $classified->classified_list($page_vars[0], $classifieds_per_page, $s, $where, TRUE);




// ASSIGN SMARTY VARIABLES AND DISPLAY CLASSIFIEDS PAGE
$smarty->assign('classifiedcat_id', $classifiedcat_id);
$smarty->assign('classifiedcat', $classifiedcat);
$smarty->assign('classifiedsubcat', $classifiedsubcat);
$smarty->assign('classified_search', $classified_search);

$smarty->assign_by_ref('cats_menu', $cat_menu_array);
$smarty->assign_by_ref('cats', $field->cats);
$smarty->assign_by_ref('fields', $field->fields);
$smarty->assign_by_ref('url_string', $field->url_string);

$smarty->assign('classifieds', $classified_array);
$smarty->assign('total_classifieds', $total_classifieds);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($classified_array));
$smarty->assign('s', $s);
$smarty->assign('v', $v);
include "footer.php";
?>