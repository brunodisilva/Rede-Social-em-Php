<?php
$page = "popular_people";
include "header.php";




// GET RECENT POPULAR USERS (MOST FRIENDS)
$friends_query = $database->database_query("SELECT count(se_friends.friend_user_id2) AS num_friends, se_users.user_id, se_users.user_username, se_users.user_fname, se_users.user_lname, se_users.user_photo FROM se_friends LEFT JOIN se_users ON se_friends.friend_user_id1=se_users.user_id WHERE se_friends.friend_status='1' AND se_users.user_search='1' GROUP BY se_users.user_id ORDER BY num_friends DESC LIMIT 20");
$friend_array = Array();
while($friend = $database->database_fetch_assoc($friends_query)) {

  $friend_user = new se_user();
  $friend_user->user_info[user_id] = $friend[user_id];
  $friend_user->user_info[user_username] = $friend[user_username];
  $friend_user->user_info[user_photo] = $friend[user_photo];
  $friend_user->user_info[user_fname] = $friend[user_fname];
  $friend_user->user_info[user_lname] = $friend[user_lname];
  $friend_user->user_displayname();

  $friend_array[] = Array('friend' => $friend_user,
		       'total_friends' => $friend[num_friends]);
}











// GET TOTALS
$total_members = $database->database_fetch_assoc($database->database_query("SELECT count(*) AS total_members FROM se_users"));
$total_friends = $database->database_fetch_assoc($database->database_query("SELECT count(*) AS total_friends FROM se_friends WHERE friend_status='1'"));



// ASSIGN SMARTY VARIABLES AND INCLUDE FOOTER
$smarty->assign('prev_email', $prev_email);
$smarty->assign('signups', $signup_array);
$smarty->assign('friends', $friend_array);
$smarty->assign('logins', $login_array);
$smarty->assign('news', $news_array);
$smarty->assign('total_members', $total_members[total_members]);
$smarty->assign('total_friends', $total_friends[total_friends]);
$smarty->assign('total_comments', $total_comments);
$smarty->assign('ip', $_SERVER['REMOTE_ADDR']);
$smarty->assign('online_users', online_users());
$smarty->assign('actions', $actions->actions_display(0, $setting[setting_actions_actionsperuser]));
include "footer.php";
?>