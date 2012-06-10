<?php 
$page = "lateststatus";
include "header.php";

if(isset($_POST['n'])) { $n = $_POST['n']; } elseif(isset($_GET['n'])) { $n = $_GET['n']; }

// GET RECENT STATUS UPDATES
$statuses = $database->database_query("SELECT user_id, user_username, user_photo, user_fname, user_lname, user_status FROM se_users WHERE user_id<>".$user->user_info[user_id]." AND user_status<>'' ORDER BY user_status_date DESC LIMIT 10");
while($status = $database->database_fetch_assoc($statuses)) {
  $status_user = new se_user();
  $status_user->user_info[user_id] = $status[user_id];
  $status_user->user_info[user_username] = $status[user_username];
  $status_user->user_info[user_photo] = $status[user_photo];
  $status_user->user_info[user_fname] = $status[user_fname];
  $status_user->user_info[user_lname] = $status[user_lname];
  $status_user->user_info[user_status] = $status[user_status];
  $status_user->user_displayname();

  $statuses_array[] = $status_user;

}

$smarty->assign('statuses', $statuses_array);

include "footer.php";
?>