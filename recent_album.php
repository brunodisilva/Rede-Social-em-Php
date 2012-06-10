<?php
$page = "recent_album";
include "header.php";

// RECENT PICTURES
function crank_type($n) {
  return ($n%10==1 && $n%100!=11 ? 0 : ($n%10>=2 && $n%10<=4 && ($n%100<10 || $n%100>=20) ? 1 : 2));
}

$result = 0;
$per_page = 10;
$temp_date = time()+(0*60) - (3600*24);
$comm = array('test', 'test', 'test');

if ($do == "new"){
$wher = "se_media.media_date > '$temp_date' AND ";
$wher_count = "WHERE media_date > '$temp_date'";
$link_files = "photo.php?do=new&";
$title = "test";
}else{
$wher = "";
$wher_count = "";
$link_files = "photo.php?";
$title = "test";
}

$file_info = $database->database_fetch_assoc($database->database_query("SELECT count(*) AS total_files FROM se_media $wher_count"));
$total_files = $file_info[total_files];
$page_vars = make_page($total_files, $per_page, $p);
$media = $database->database_query("SELECT se_media.*,se_albums.album_id,se_users.user_id,se_users.user_username FROM se_media LEFT JOIN se_albums ON se_albums.album_id=se_media.media_album_id LEFT JOIN se_users ON se_users.user_id=se_albums.album_user_id LEFT JOIN se_levels ON se_users.user_level_id=se_levels.level_id WHERE $wher se_albums.album_search='1' OR se_levels.level_album_search='0' ORDER BY media_date DESC LIMIT $page_vars[0], $per_page");
$media_array = Array();

while($media_info = $database->database_fetch_assoc($media)) {

$mediacomment_info = $database->database_fetch_assoc($database->database_query("SELECT count(mediacomment_id) AS total_comments FROM se_mediacomments WHERE mediacomment_media_id='".$media_info[media_id]."'"));
$total_mediacomment = $mediacomment_info[total_comments];

$url = new se_url();
$media_path = $url->url_userdir($media_info['user_id']).$media_info['media_id'].'_thumb.'.$media_info['media_ext'];

if( !in_array($media_info[media_ext], array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'tif')) ) continue;

$file_array[] = Array('media_id' => $media_info[media_id],
                    'media_album_id' => $media_info[album_id],
                    'media_path' => $media_path,
                    'media_username' => $media_info[user_username],
                    'media_title' => $media_info[media_title],
                    'media_desc' => str_replace("<br>", "\r\n", $media_info[media_desc]),
                    'media_ext' => $media_info[media_ext],
                    'media_views' => $media_info[media_views],
                    'media_comtype' => $comm[crank_type($total_mediacomment)],
          'media_comment' => $total_mediacomment); }

$smarty->assign('files', $file_array);
$smarty->assign('title', $title);
$smarty->assign('link_files', $link_files);
$smarty->assign('total_files', $total_files);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($file_array));
include "footer.php";
?>