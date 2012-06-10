<?php
include "header.php";

function truncate($string, $length = 80, $etc = '...',
								  $break_words = false, $middle = false)
{
	if ($length == 0)
		return '';

	if (strlen($string) > $length) {
		$length -= strlen($etc);
		if (!$break_words && !$middle) {
			$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
		}
		if(!$middle) {
			return substr($string, 0, $length).$etc;
		} else {
			return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
		}
	} else {
		return $string;
	}
}


if ( (isset($_GET['user_id'])) and ($_GET['user_id']!=null) ){
	$removed_id = $_GET['user_id'];
	$database->database_query("
	INSERT INTO  `se_fof_removed` (`id`,`user_id`,`removed_id`)
		VALUES ( NULL, '".$user->user_info['user_id']."', '".$removed_id."');
	");

}


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

$output = "";

if (count($p_friends)==0)
	$output = "<div style='margin:5px'>
			Try to add someone to friends to see more suggestions	
		</div>";

foreach ($p_friends as $friend){
	$output .= "<div style='float:left; width:215px; padding:5px'>
				<div style='float:left; padding-right:3px'>
				<a href='".$url->url_create('profile',$friend->user_info['user_username'])."' title='".$friend->user_info['user_username']."'>
					<img src='".$friend->user_photo('./images/nophoto.gif', true)."' width='60' height='60' border='0' />
				</a>
				</div>
				<div>
				<b>".truncate($friend->user_displayname, 20)."</b><br/>
				<a href=\"".htmlentities("javascript:TB_show('Add to My Friends', 'user_friends_manage.php?user=".$friend->user_info['user_username']."&TB_iframe=true&height=300\&width=450', '', './images/trans.gif');")."\">Add to Friends</a><br/>
				<a href=\"".htmlentities("javascript:TB_show('Compose New Message', 'user_messages_new.php?to_user=".$friend->user_displayname."&to_id=".$friend->user_info['user_username']."&TB_iframe=true&height=400&width=450', '', './images/trans.gif');")."\">Send Message</a><br/>
				<a href=\"javascript:updateContainerFof(".$friend->user_info['user_id'].");\">Remove</a>
				</div>
			</div>
				";
}
	echo $output;
?>