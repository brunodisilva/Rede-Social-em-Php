<?
$page = "admin_userpoints_transactions";
include "admin_header.php";

$task = semods::getpost('task', "main");
$s = semods::getpost('s', "tid");                     // sort default by transactionid
$p = semods::getpost('p', 1);
$f_user = semods::getpost('f_user', "");
$f_title = semods::getpost('f_title', "");
$f_state = intval(semods::getpost('f_state', -1));    // transaction status


if($task == "confirm") {
  $transaction_id = intval(semods::getpost('transaction_id', 0));

  $uptransaction = new semods_uptransaction($transaction_id);
  $uptransaction->complete();

  semods::redirect("admin_userpoints_transactions.php?s=$s&p=$p&f_user=$f_user&f_title=$f_title&f_state=$f_state");
}


if($task == "cancel") {
  $transaction_id = intval(semods::getpost('transaction_id',0));

  $uptransaction = new semods_uptransaction($transaction_id);
  $uptransaction->cancel();

  semods::redirect("admin_userpoints_transactions.php?s=$s&p=$p&f_user=$f_user&f_title=$f_title&f_state=$f_state");
}



// SET USER SORT-BY VARIABLES FOR HEADING LINKS
$u = "u";     // USER_USERNAME
$d = "d";     // Transaction Date
$st = "st";   // State
$a = "a";     // amount

// SET SORT VARIABLE FOR DATABASE QUERY
if($s == "u") {
  $sort = "user_username";
  $u = "ud";
} elseif($s == "ud") {
  $sort = "user_username DESC";
  $u = "u";
} elseif($s == "d") {
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
  $u = "u";
}


$sql_head = "SELECT * ";

$sql_body = "FROM se_semods_uptransactions T
		LEFT JOIN se_users U ON T.uptransaction_user_id = U.user_id";

$filters = array();
$f_user != ""    ? ($setting['setting_username'] ? $filters[] = "user_username LIKE '%$f_user%'" : $filters[] = "(user_fname LIKE '%$f_user%' OR user_lname LIKE '%$f_user%')" ) : 0;
$f_title != ""    ? $filters[] = "uptransaction_text LIKE '%$f_title%'" :0;
$f_state != -1   ? $filters[] = "uptransaction_state = $f_state" :0;

!empty($filters)  ? $sql_body .= " WHERE " . implode( " AND ", $filters):0;

$sql_count = 'SELECT COUNT(*)' . ' ' . $sql_body;

$sql_items = $sql_head  . ' ' . $sql_body;


// GET TOTAL
$total_items = semods::db_query_count( $sql_count );

// MAKE PAGES
$items_per_page = 100;
$page_vars = make_page($total_items, $items_per_page, $p);

$page_array = Array();
for($x=0;$x<=$page_vars[2]-1;$x++) {
  if($x+1 == $page_vars[1]) { $link = "1"; } else { $link = "0"; }
  $page_array[$x] = Array('page' => $x+1,
						  'link' => $link);
}

$sql_items .= " ORDER BY $sort LIMIT $page_vars[0], $items_per_page";


$level_array = semods::load_userlevels();
$subnet_array = semods::load_subnets();


foreach($uptransaction_states as $key => $value) {
  $transaction_states[] = array( 'transactionstate_id'    =>  $key,
                                 'transactionstate_name'  =>  $value );
  SE_Language::_preload( $value );
}


// PULL ITEMS INTO AN ARRAY
$items = Array();
$rows = $database->database_query($sql_items);
while($row = $database->database_fetch_assoc($rows)) {

  $items[] = array( 'transaction_id'	 => $row['uptransaction_id'],
                    'transaction_date'   => $row['uptransaction_date'],
                    'transaction_desc'   => $row['uptransaction_text'],
                    'transaction_state' => $uptransaction_states[$row['uptransaction_state']],
                    'transaction_stateid' => $row['uptransaction_state'],
                    'transaction_amount' => $row['uptransaction_amount'],
                    'transaction_user_id' => $row['user_id'],
                    'transaction_username' => $row['user_username'],
                    'transaction_userdisplayname' => semods_utils::create_user_displayname_ex($row)
                  );

}


// ASSIGN VARIABLES AND SHOW VIEW USERS PAGE
$smarty->assign('total_items', $total_items);
$smarty->assign('pages', $page_array);
$smarty->assign('items', $items);

$smarty->assign('u', $u);
$smarty->assign('d', $d);
$smarty->assign('st', $st);
$smarty->assign('a', $a);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('s', $s);

$smarty->assign('f_user', $f_user);
$smarty->assign('f_title', $f_title);

$smarty->assign('f_status', $f_status);
$smarty->assign('f_state', $f_state);

$smarty->assign('transaction_states', $transaction_states);

include "admin_footer.php";
?>