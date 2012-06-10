<?php
include "header.php";

$per_page = 7;
$tab = $_GET['tab'];
$user_id = $_GET['id'];

$someuser = new se_user(array($user_id));
$all_friends = $someuser->user_friend_list($tab*$per_page, $per_page, 0, 1);

	echo "<div style='width:560px; _width:570px; margin:0 auto;'>";

foreach($all_friends as $friend){

	echo "<div style='float:left; width:80px; text-align:center'><a href='".$url->url_create('profile',$friend->user_info['user_username'])."'>";
	echo "<div style='width:60px; margin:0 auto'><img src='".$friend->user_photo('./images/nophoto_60.gif', true)."' class='photo' border='0' width='60' style='float:left' /></div>";
	$friend->name = $friend->user_displayname;
	if (strlen($friend->name)>=14)
		$friend->name = substr($friend->name, 0, 12)."...";
	echo $friend->name;
	echo "</a></div>";
}
	
	echo "</div>";
die;

?>