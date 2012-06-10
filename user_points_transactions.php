<?
$page = "user_points_transactions";
include "header.php";

// ENSURE POINTS ARE ENABLED FOR THIS USER
if(($user->level_info['level_userpoints_allow'] == 0) || ($user->user_info['user_userpoints_allowed'] == 0)){ header("Location: user_home.php"); exit(); }

$p = semods::getpost('p', 1);
$s = semods::getpost('s', 'dd');
$search = semods::getpost('search');

$success_message = semods::getpost('success_message');
$error_message = semods::getpost('error_message');


// SET ENTRY SORT-BY VARIABLES FOR HEADING LINKS
$d = "d";       // Date
$st = "st";     // Status
$a = "a";       // Amount

// SET SORT VARIABLE FOR DATABASE QUERY
if($s == "d") {
  $sort = "uptransaction_date";
  $d = "dd";
} elseif($s == "dd") {
  $sort = "uptransaction_date DESC";
  $d = "d";
} elseif($s == "st") {
  $sort = "uptransaction_state";
  $st = "std";
} elseif($s == "std") {
  $sort = "uptransaction_state DESC";
  $st = "st";
} elseif($s == "a") {
  $sort = "uptransaction_amount";
  $a = "ad";
} elseif($s == "ad") {
  $sort = "uptransaction_amount DESC";
  $a = "a";
} else {
  $sort = "uptransaction_id DESC";
  $d = "d";
}

$sql_head = "SELECT * ";

$sql_body = "FROM se_semods_uptransactions";

$filters = array();
$filters[] = "uptransaction_user_id = {$user->user_info['user_id']}";
$search  != ""   ? $filters[] = "uptransaction_text LIKE '%$search%'" :0;

!empty($filters)  ? $sql_body .= " WHERE " . implode( " AND ", $filters):0;


$items_per_page = 10;


// GET TOTAL ITEMS
$sql_count = "SELECT COUNT(*) " . $sql_body;
$total_items = semods::db_query_count( $sql_count );


// MAKE PAGES
$page_vars = make_page($total_items, $items_per_page, $p);

$sql = $sql_head . $sql_body . " ORDER BY $sort LIMIT {$page_vars[0]}, $items_per_page";


$rows = $database->database_query( $sql );
while($row = $database->database_fetch_assoc($rows) ) {

  $items[] = array(
                    'transaction_date'   => $row['uptransaction_date'],
                    'transaction_desc'   => $row['uptransaction_text'],
                    'transaction_status' => $uptransaction_states[$row['uptransaction_state']],
                    'transaction_amount' => $row['uptransaction_amount']
                  );
  
  SE_Language::_preload( $uptransaction_states[$row['uptransaction_state']] );
  
}

// ASSIGN VARIABLES AND SHOW VIEW ENTRIES PAGE

$smarty->assign('success_message', $success_message);
$smarty->assign('error_message', $error_message);

$smarty->assign('s', $s);
$smarty->assign('d', $d);
$smarty->assign('st', $st);
$smarty->assign('a', $a);
$smarty->assign('search', $search);
$smarty->assign('total_items', $total_items);
$smarty->assign('items', $items);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($items));
$smarty->assign('semods_settings', semods::get_settings());
include "footer.php";
?>