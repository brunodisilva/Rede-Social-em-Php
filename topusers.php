<?
$page = "topusers";
include "header.php";

if(semods::get_setting('userpoints_enable_topusers') == 0) {
  semods::redirect("home.php");
}

// MAXIMUM TOP USERS TO DISPLAY
$max_top_users = 10;


/*
 // This one takes into account if userpoints are enabled for user, just extra data that would better not to get pulled (performance)
$query = "SELECT *
          FROM se_semods_userpoints UP
          JOIN se_users U
            ON UP.userpoints_user_id = U.user_id
          JOIN se_levels L
            ON U.user_level_id = L.level_id
          WHERE UP.userpoints_totalearned  != 0 AND U.user_userpoints_allowed = 1 AND L.level_userpoints_allow = 1
          ORDER BY UP.userpoints_totalearned DESC";
 */


$query = "SELECT UP.*,
                 U.*,
                 V.profileview_views
          FROM se_semods_userpoints UP
          JOIN se_users U
            ON UP.userpoints_user_id = U.user_id
          LEFT JOIN se_profileviews V
            ON V.profileview_user_id = UP.userpoints_user_id
          WHERE UP.userpoints_totalearned  != 0
          ORDER BY UP.userpoints_totalearned DESC";

$query .= " LIMIT $max_top_users";

$rows = $database->database_query( $query );

// GET THEM INTO AN ARRAY
$items = Array();
$dummy_user = new se_user();
$rank = 1;
while($row = $database->database_fetch_assoc($rows)) {

  $dummy_user->user_info['user_id'] = $row['user_id'];
  $dummy_user->user_info['user_photo'] = $row['user_photo'];
  $dummy_user->user_info['user_username'] = $row['user_username'];
  $dummy_user->user_info['user_lname'] = $row['user_lname'];
  $dummy_user->user_info['user_fname'] = $row['user_fname'];
  $dummy_user->user_info['user_displayname'] = $row['user_displayname'];
  $dummy_user->user_displayname();

  $row['user_photo'] = $dummy_user->user_photo('./images/nophoto.gif');
  $row['user_displayname'] = $dummy_user->user_displayname;
  $row['profileview_views'] = intval($row['profileview_views']);
  $row['userpoints_rank'] = $rank++;

  $items[] = $row;
}


// ASSIGN VARIABLES
$smarty->assign('items', $items);
include "footer.php";
?>