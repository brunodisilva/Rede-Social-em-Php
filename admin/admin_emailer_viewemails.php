<?
$page = "admin_emailer_viewemails";
include "admin_header.php";

$task = semods::getpost('task');
$s = semods::getpost('s');
$row_id = semods::getpost('row_id');

$p = semods::getpost('p', 1);
$f_email = semods::getpost('f_email', "");
$f_type = intval(semods::getpost('f_type', -1));  


// DELETE QUEUED EMAIL
if($task == "delete") {

  semods::db_exec("DELETE FROM se_semods_email_queue WHERE id = $row_id");

  // todo: add filters
  header("Location: admin_emailer_viewemails.php?f_email=$f_email&$f_type=$f_type&p=$p&s=$s");
  exit;
}




// SET SORT-BY VARIABLES FOR HEADING LINKS
$i = "id";   // ID
$e = "e";    // EMAIL

// SET SORT VARIABLE FOR DATABASE QUERY
if($s == "i") {
  $sort = "id";
  $i = "id";
} elseif($s == "id") {
  $sort = "id DESC";
  $i = "i";
} elseif($s == "e") {
  $sort = "to_email";
  $e = "ed";
} elseif($s == "ed") {
  $sort = "to_email DESC";
  $e = "e";
} else {
  $sort = "id DESC";
  $i = "i";
}


$sql_head = "SELECT * ";

$sql_body = "FROM se_semods_email_queue";

$filters = array();
$f_email != ""   ? $filters[] = "to_email LIKE '%$f_email%'" :0;
$f_type != -1    ? $filters[] = "type = $f_type" :0;

!empty($filters)  ? $sql_body .= " WHERE " . implode( " AND ", $filters):0;

$items_per_page = 50;

// GET TOTAL ITEMS
$sql_count = "SELECT COUNT(*) " . $sql_body;
$total_items = semods::db_query_count( $sql_count );

// MAKE PAGES
$page_vars = make_page($total_items, $items_per_page, $p);

$page_array = Array();
for($x=0;$x<=$page_vars[2]-1;$x++) {
  if($x+1 == $page_vars[1]) { $link = "1"; } else { $link = "0"; }
  $page_array[$x] = Array('page' => $x+1,
                          'link' => $link);
}

$sql = $sql_head . $sql_body . " ORDER BY $sort LIMIT {$page_vars[0]}, $items_per_page";


// GET ITEMS FOR MAIN LIST
$rows = $database->database_query( $sql );
while($row = $database->database_fetch_assoc($rows)) {

  // status text
  $row['typetext'] = $queued_email_types[ $row['type'] ];

  $items[] = $row;

}

foreach($queued_email_types as $key => $value) {
  $queued_email_types_array[] = array(  'type_id'   => $key,
                                        'type_name' => $value );
}


// ASSIGN VARIABLES AND SHOW ADMIN ADS PAGE
$smarty->assign('s', $s);
$smarty->assign('i', $i);
$smarty->assign('e', $e);
$smarty->assign('pages', $page_array);

$smarty->assign('queued_email_types', $queued_email_types_array);

$smarty->assign('total_items', $total_items);
$smarty->assign('items', $items);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($items));

$smarty->assign('f_email', $f_email);
$smarty->assign('f_type', $f_type);

include 'admin_footer.php';
?>