<?php
include "header.php";
if ($user->user_info[user_invisible] == 0)	$mode = 1;

if ($user->user_info[user_invisible] == 1)	$mode = 0;

if ($user->user_exists != 0){
$database->database_query("UPDATE se_users SET user_invisible='".$mode."' WHERE user_id='".$user->user_info[user_id]."'");

if ($mode == 1)  echo($url->url_base."/templates/img/offline.png"); 
if ($mode == 0)  echo($url->url_base."/templates/img/online.png");

}
?>