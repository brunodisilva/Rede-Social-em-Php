<?
$page = "user_points_shop";
include "header.php";
include "./include/class_semods_tagcloud.php";

// ENSURE POINTS ARE ENABLED FOR THIS USER
if(($user->level_info['level_userpoints_allow'] == 0) || ($user->user_info['user_userpoints_allowed'] == 0)){ header("Location: user_home.php"); exit(); }

// ENSURE SHOP ALLOWED
if( !semods::get_setting('userpoints_enable_shop') ){ header("Location: user_home.php"); exit(); }

$p = semods::getpost('p',1);
$search = semods::getpost('search');
$tag =  semods::getpost('tag');

$items_per_page = 10;

$filters = array();
$search != ""    ? $filters[] = "userpointspender_title LIKE '%$search%'" :0;
$tag != ""   ? $filters[] = "userpointspender_tags LIKE '%$tag%'" :0;

!empty($filters)  ? $sql_body .= " WHERE " . implode( " AND ", $filters):0;


/* COUNT QUERY */

$sql = "SELECT COUNT(*)
        FROM se_semods_userpointspender
        WHERE
          userpointspender_enabled != 0 AND userpointspender_type >= 100 AND
          (FIND_IN_SET({$user->user_info['user_level_id']},userpointspender_levels) OR userpointspender_levels='') AND
          (FIND_IN_SET({$user->user_info['user_subnet_id']},userpointspender_subnets) OR userpointspender_subnets='')";

if($search)
  $sql .= " AND userpointspender_title LIKE '%$search%'";
else if($tag)
  $sql .= " AND userpointspender_tags LIKE '%$tag%'";

$total_items = semods::db_query_count( $sql );

$page_vars = make_page($total_items, $items_per_page, $p);


/* ACTUAL QUERY */

$sql = "SELECT *
        FROM se_semods_userpointspender S
        JOIN se_semods_userpointspendertypes T ON S.userpointspender_type = T.userpointspendertype_type
        WHERE
          userpointspender_enabled != 0 AND userpointspender_type >= 100 AND
          (FIND_IN_SET({$user->user_info['user_level_id']},userpointspender_levels) OR userpointspender_levels='') AND
          (FIND_IN_SET({$user->user_info['user_subnet_id']},userpointspender_subnets) OR userpointspender_subnets='')";

if($search)
  $sql .= " AND userpointspender_title LIKE '%$search%'";
else if($tag)
  $sql .= " AND userpointspender_tags LIKE '%$tag%'";

$sql .= " LIMIT {$page_vars[0]}, $items_per_page";


$upspender = new semods_upspender(0);
$upspender->spender_exists = 1;
$items = array();
$rows = $database->database_query( $sql );
while($row = $database->database_fetch_assoc( $rows )) {
  $upspender->upspender_info['userpointspender_id'] = $row['userpointspender_id'];
  $upspender->upspender_info['userpointspender_photo'] = $row['userpointspender_photo'];
  $row['userpointspender_photo'] = $upspender->public_photo( './images/nophoto.gif' );

  // decode html
  $row['userpointspender_body'] = html_entity_decode( str_replace("\r\n", "", $row['userpointspender_body']) , ENT_QUOTES, 'UTF-8' );

  $items[] = $row;
}

$tags = semods::db_query_count("SELECT GROUP_CONCAT(userpointspender_tags) FROM se_semods_userpointspender WHERE userpointspender_enabled = 1");
$tagcloud = new semods_tagcloud( $tags );



// ASSIGN VARIABLES AND INCLUDE FOOTER

$smarty->assign('search', $search);
$smarty->assign('tag', $tag);
$smarty->assign('tagcloud', $tagcloud->to_html('user_points_shop.php'));
$smarty->assign('items', $items);
$smarty->assign('total_items', $total_items);

$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($items));
$smarty->assign('semods_settings', semods::get_settings());
include "footer.php";
?>