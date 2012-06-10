<?
$page = "admin_userpoints_viewusers";
include "admin_header.php";


$s = semods::getpost('s', "pcd");   // sort default by points count desc
$p = semods::getpost('p', 1);
$f_user = semods::getpost('f_user', "");
$f_email = semods::getpost('f_email', "");
$f_level = semods::getpost('f_level', "");
$f_subnet = semods::getpost('f_subnet', "");
$f_enabled = semods::getpost('f_enabled', "");
$task = semods::getpost('task', "main");
$user_id = semods::getpost('user_id', 0);

// GET USER IF ONE IS SPECIFIED
$user = new se_user(Array($user_id));

// SET USER SORT-BY VARIABLES FOR HEADING LINKS
$i = "id";   // USER_ID
$u = "u";    // USER_USERNAME
$em = "em";  // USER_EMAIL
$v = "v";    // USER_VERIFIED
$sd = "sd";  // USER_SIGNUPDATE
$pc = "pc";  // POINTS COUNT
$tp = "tp";  // TOTAL POINTS EARNED
//$sip = "sip";  // SIGNUP IP

// SET SORT VARIABLE FOR DATABASE QUERY
if($s == "i") {
  $sort = "user_id";
  $i = "id";
} elseif($s == "id") {
  $sort = "user_id DESC";
  $i = "i";
} elseif($s == "u") {
  $sort = "user_username";
  $u = "ud";
} elseif($s == "ud") {
  $sort = "user_username DESC";
  $u = "u";
} elseif($s == "em") {
  $sort = "user_email";
  $em = "emd";
} elseif($s == "emd") {
  $sort = "user_email DESC";
  $em = "em";
} elseif($s == "v") {
  $sort = "user_verified, user_email";
  $v = "vd";
} elseif($s == "vd") {
  $sort = "user_verified DESC, user_email";
  $v = "v";
} elseif($s == "sd") {
  $sort = "user_signupdate";
  $sd = "sdd";
} elseif($s == "sdd") {
  $sort = "user_signupdate DESC";
  $sd = "sd";
} elseif($s == "pc") {
  $sort = "userpoints_count";
  $pc = "pcd";
} elseif($s == "pcd") {
  $sort = "userpoints_count DESC";
  $pc = "pc";
} elseif($s == "tp") {
  $sort = "userpoints_totalearned";
  $tp = "tpd";
} elseif($s == "tpd") {
  $sort = "userpoints_totalearned DESC";
  $tp = "tp";
} else {
  $sort = "user_id DESC";
  $i = "i";
}


$sql_head = "SELECT U.*,
			   IF(ISNULL(P.userpoints_count), 0, P.userpoints_count) AS userpoints_count,
			   IF(ISNULL(P.userpoints_totalearned), 0, P.userpoints_totalearned) AS userpoints_totalearned";

$sql_body = "FROM se_users U
		LEFT JOIN se_semods_userpoints P ON U.user_id = P.userpoints_user_id";

$filters = array();
$f_user != ""    ? ($setting['setting_username'] ? $filters[] = "user_username LIKE '%$f_user%'" : $filters[] = "(user_fname LIKE '%$f_user%' OR user_lname LIKE '%$f_user%')" ) : 0;
$f_email != ""   ? $filters[] = "user_email LIKE '%$f_email%'" :0;
$f_level != ""   ? $filters[] = "user_level_id = '$f_level'" :0;
$f_subnet != ""  ? $filters[] = "user_subnet_id = '$f_subnet'" :0;
$f_enabled != "" ? $filters[] = "user_enabled = '$f_enabled'" :0;

!empty($filters)  ? $sql_body .= " WHERE " . implode( " AND ", $filters):0;

$sql_count = 'SELECT COUNT(*)' . ' ' . $sql_body;

$sql_users = $sql_head  . ' ' . $sql_body;


// GET TOTAL USERS
$total_users = semods::db_query_count( $sql_count );

// MAKE USER PAGES
$users_per_page = 100;
$page_vars = make_page($total_users, $users_per_page, $p);

$page_array = Array();
for($x=0;$x<=$page_vars[2]-1;$x++) {
  if($x+1 == $page_vars[1]) { $link = "1"; } else { $link = "0"; }
  $page_array[$x] = Array('page' => $x+1,
			  'link' => $link);
}

$sql_users .= " ORDER BY $sort LIMIT $page_vars[0], $users_per_page";




// LOOP OVER USER LEVELS
$levels = $database->database_query("SELECT level_id, level_name FROM se_levels ORDER BY level_name");
while($level_info = $database->database_fetch_assoc($levels)) {
  $level_array[$level_info[level_id]] = Array('level_id' => $level_info[level_id],
			'level_name' => $level_info[level_name]);
}


// LOOP OVER SUBNETWORKS
$subnets = $database->database_query("SELECT subnet_id, subnet_name FROM se_subnets ORDER BY subnet_name");
$subnet_array[0] = Array('subnet_id' => 0, 'subnet_name' => 152);
SE_Language::_preload(152);
while($subnet_info = $database->database_fetch_assoc($subnets)) {
  $subnet_array[$subnet_info[subnet_id]] = Array('subnet_id' => $subnet_info[subnet_id],
		       'subnet_name' => $subnet_info[subnet_name]);
  SE_Language::_preload( $subnet_info[subnet_name] );
}


// PULL USERS INTO AN ARRAY
$users = Array();
$user_count = 0;
$users_dbr = $database->database_query($sql_users);
while($user_info = $database->database_fetch_assoc($users_dbr)) {

  $user_info['user_level'] = $level_array[$user_info['user_level_id']]['level_name'];
  $user_info['user_subnet'] = $subnet_array[$user_info['user_subnet_id']]['subnet_name'];
  semods_utils::create_user_displayname_ex( $user_info );

  $users[] = $user_info;
}




// ASSIGN VARIABLES AND SHOW VIEW USERS PAGE
$smarty->assign('total_users', $total_users);
$smarty->assign('pages', $page_array);
$smarty->assign('users', $users);
$smarty->assign('i', $i);
$smarty->assign('u', $u);
$smarty->assign('em', $em);
$smarty->assign('v', $v);
$smarty->assign('sd', $sd);
$smarty->assign('pc', $pc);
$smarty->assign('tp', $tp);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('s', $s);
$smarty->assign('f_user', $f_user);
$smarty->assign('f_email', $f_email);
$smarty->assign('f_level', $f_level);
$smarty->assign('f_subnet', $f_subnet);
$smarty->assign('f_enabled', $f_enabled);
$smarty->assign('levels', array_values($level_array));
$smarty->assign('subnets', array_values($subnet_array));
include "admin_footer.php";
?>