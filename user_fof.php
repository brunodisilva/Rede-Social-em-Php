<?php

$page = "user_fof";
include "header.php";

$fof_result = $database->database_query("
	SELECT DISTINCT `se_friends`.friend_user_id2, se_users.user_username, se_users.user_id, se_users.user_photo, se_users.user_fname, se_users.user_lname
	FROM `se_friends`
	INNER JOIN `se_users` ON `se_friends`.friend_user_id2=`se_users`.user_id
	WHERE se_friends.friend_user_id1 IN 
		(SELECT se_friends.friend_user_id2
		 FROM `se_friends`
		 WHERE se_friends.friend_user_id1='".$user->user_info['user_id']."'  AND se_friends.friend_status='1')
	AND `se_friends`.friend_user_id2 NOT IN
		(SELECT se_friends.friend_user_id2
		FROM `se_friends`
		WHERE se_friends.friend_user_id1='".$user->user_info['user_id']."'
		)
	AND NOT(se_friends.friend_user_id2='".$user->user_info['user_id']."') AND se_friends.friend_status='1'
	AND se_friends.friend_user_id2 NOT IN 
		(SELECT `se_fof_removed`.removed_id
		 FROM `se_fof_removed`
		 WHERE `se_fof_removed`.user_id='".$user->user_info['user_id']."')
	ORDER BY  `se_users`.`user_signupdate` DESC 
	LIMIT 0, 9
	");
			

$p_friends = Array();
while($p_friend = $database->database_fetch_assoc($fof_result)) {
	$possible_friend = new se_user();
	$possible_friend->user_info['user_id'] = $p_friend['user_id'];
    $possible_friend->user_info['user_username'] = $p_friend['user_username'];
    $possible_friend->user_info['user_photo'] = $p_friend['user_photo'];
    $possible_friend->user_info['user_fname'] = $p_friend['user_fname'];
    $possible_friend->user_info['user_lname'] = $p_friend['user_lname'];
    $possible_friend->user_displayname();
	$p_friends[] = $possible_friend;
}


$smarty->assign('p_friends', $p_friends);

include "footer.php";
?>