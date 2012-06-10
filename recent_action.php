<?php
$page = "recent_action";
include "header.php";


// START CLASSIFIEDS
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }
if(isset($_POST['s'])) { $s = $_POST['s']; } elseif(isset($_GET['s'])) { $s = $_GET['s']; } else { $s = "classified_datecreated DESC"; }
if(isset($_POST['v'])) { $v = $_POST['v']; } elseif(isset($_GET['v'])) { $v = $_GET['v']; } else { $v = 0; }
if(isset($_POST['classifiedcat_id'])) { $classifiedcat_id = $_POST['classifiedcat_id']; } elseif(isset($_GET['classifiedcat_id'])) { $classifiedcat_id = $_GET['classifiedcat_id']; } else { $classifiedcat_id = 0; }
if(isset($_POST['classified_search'])) { $classified_search = $_POST['classified_search']; } elseif(isset($_GET['classified_search'])) { $classified_search = $_GET['classified_search']; } else { $classified_search = NULL; }

// ENSURE SORT/VIEW ARE VALID
if($s != "classified_date DESC" && $s != "classified_dateupdated DESC" && $s != "classified_views DESC" && $s != "total_comments DESC") { $s = "classified_date DESC"; }
if($v != "0" && $v != "1") { $v = 0; }





// SPECIFIC CLASSIFIED CATEGORY
if( $classifiedcat_id )
{
  $sql = "SELECT classifiedcat_id, classifiedcat_title, classifiedcat_dependency FROM se_classifiedcats WHERE classifiedcat_id={$classifiedcat_id} LIMIT 1";
  $resource = $database->database_query($sql) or die("<b>Error: </b>".$database->database_error()."<br /><b>File: </b>".__FILE__."<br /><b>Line: </b>".__LINE__."<br /><b>Query: </b>".$sql);
  
  if( $database->database_num_rows($resource) )
  {
    $classifiedcat = $database->database_fetch_assoc($resource);
    
    if( !$classifiedcat[classifiedcat_dependency] )
    {
      $cat_ids[] = $classifiedcat['classifiedcat_id'];
      $depcats = $database->database_query("SELECT classifiedcat_id FROM se_classifiedcats WHERE classifiedcat_id={$classifiedcat[classifiedcat_id]} OR classifiedcat_dependency={$classifiedcat[classifiedcat_id]}");
      while($depcat_info = $database->database_fetch_assoc($depcats)) { $cat_ids[] = $depcat_info[classifiedcat_id]; }
      $where .= " AND se_classifieds.classified_classifiedcat_id IN('".implode("', '", $cat_ids)."')";
    }
    else
    {
      $where .= " AND se_classifieds.classified_classifiedcat_id={$classifiedcat[classifiedcat_id]}";
      $classifiedsubcat = $classifiedcat;
      $classifiedcat = $database->database_fetch_assoc($database->database_query("SELECT classifiedcat_id, classifiedcat_title FROM se_classifiedcats WHERE classifiedcat_id={$classifiedcat[classifiedcat_dependency]}"));
    }
  }
}


// GET CATS
$field = new se_field("classified");
$field->cat_list(0, 0, 0, "", "", "");
$cat_menu_array = $field->cats;

//$field->cat_list(0, 0, 1, "classifiedcat_id='{$classifiedcat['classifiedcat_id']}'", "", "");
$field->field_list(0, 0, 1, "classifiedfield_classifiedcat_id='{$classifiedcat['classifiedcat_id']}' && classifiedfield_search<>'0'");


// BEGIN CONSTRUCTING SEARCH QUERY
//echo $field->field_query;
if( $field->field_query )
  $where .= " && ".$field->field_query;

if( !empty($classified_search) )
{
  $where .= " && MATCH(classified_title, classified_body) AGAINST ('{$classified_search}' IN BOOLEAN MODE) ";
}


// CREATE CLASSIFIED OBJECT, GET TOTAL CLASSIFIEDS, MAKE ENTRY PAGES, GET CLASSIFIED ARRAY
$classified = new se_classified();

$total_classifieds = $classified->classified_total($where, TRUE);
$classifieds_per_page = 3;
$page_vars = make_page($total_classifieds, $classifieds_per_page, $p);

$classified_array = $classified->classified_list($page_vars[0], $classifieds_per_page, $s, $where, TRUE);




// ASSIGN SMARTY VARIABLES AND DISPLAY CLASSIFIEDS PAGE
$smarty->assign('classifiedcat_id', $classifiedcat_id);
$smarty->assign('classifiedcat', $classifiedcat);
$smarty->assign('classifiedsubcat', $classifiedsubcat);
$smarty->assign('classified_search', $classified_search);

$smarty->assign_by_ref('cats_menu', $cat_menu_array);
$smarty->assign_by_ref('cats', $field->cats);
$smarty->assign_by_ref('fields', $field->fields);
$smarty->assign_by_ref('url_string', $field->url_string);

$smarty->assign('classifieds', $classified_array);
$smarty->assign('total_classifieds', $total_classifieds);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($classified_array));
$smarty->assign('s', $s);
$smarty->assign('v', $v);
// END CLASSIFIEDS PAGE


// RECENT GROUPS
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }
if(isset($_POST['s'])) { $s = $_POST['s']; } elseif(isset($_GET['s'])) { $s = $_GET['s']; } else { $s = "group_datecreated DESC"; }
if(isset($_POST['v'])) { $v = $_POST['v']; } elseif(isset($_GET['v'])) { $v = $_GET['v']; } else { $v = 0; }
if(isset($_POST['c'])) { $c = $_POST['c']; } elseif(isset($_GET['c'])) { $c = $_GET['c']; } else { $c = -1; }
if(isset($_POST['group_search'])) { $group_search = $_POST['group_search']; } elseif(isset($_GET['c'])) { $group_search = $_GET['group_search']; } else { $c = NULL; }
// ENSURE SORT/VIEW ARE VALID
if($s != "group_datecreated DESC" && $s != "group_views DESC" && $s != "total_comments DESC") { $s = "group_datecreated DESC"; }
if($v != "0" && $v != "1") { $v = 0; }
// SET WHERE CLAUSE
$where = "CASE
        WHEN se_groups.group_user_id='{$user->user_info[user_id]}'
          THEN TRUE
        WHEN ((se_groups.group_privacy & @SE_PRIVACY_REGISTERED) AND '{$user->user_exists}'<>0)
          THEN TRUE
        WHEN ((se_groups.group_privacy & @SE_PRIVACY_ANONYMOUS) AND '{$user->user_exists}'=0)
          THEN TRUE
        WHEN ((se_groups.group_privacy & @SE_PRIVACY_FRIEND) AND (SELECT TRUE FROM se_friends WHERE friend_user_id1=se_groups.group_user_id AND friend_user_id2='{$user->user_info[user_id]}' AND friend_status='1' LIMIT 1))
          THEN TRUE
        WHEN ((se_groups.group_privacy & @SE_PRIVACY_SUBNET) AND '{$user->user_exists}'<>0 AND (SELECT TRUE FROM se_users WHERE user_id=se_groups.group_user_id AND user_subnet_id='{$user->user_info[user_subnet_id]}' LIMIT 1))
          THEN TRUE
        WHEN ((se_groups.group_privacy & @SE_PRIVACY_FRIEND2) AND (SELECT TRUE FROM se_friends AS friends_primary LEFT JOIN se_users ON friends_primary.friend_user_id1=se_users.user_id LEFT JOIN se_friends AS friends_secondary ON friends_primary.friend_user_id2=friends_secondary.friend_user_id1 WHERE friends_primary.friend_user_id1=se_groups.group_user_id AND friends_secondary.friend_user_id2='{$user->user_info[user_id]}' AND se_users.user_subnet_id='{$user->user_info[user_subnet_id]}' LIMIT 1))
          THEN TRUE
        ELSE FALSE
    END";
// ONLY MY FRIENDS' BLOGS
if( $v=="1" && $user->user_exists )
{
  // SET WHERE CLAUSE
  $where .= " AND (SELECT TRUE FROM se_friends WHERE friend_user_id1={$user->user_info[user_id]} AND friend_user_id2=se_groups.group_user_id AND friend_status=1)";
}
// CATEGORIES
if( isset($c) && $c!=-1 )
{
  if( $c==0 ) $c = '0';
  $where .= " AND group_group_groupcat_id='$c'";
}
// CREATE blog OBJECT
$group = new se_group($user->user_info[user_id]);
// GET TOTAL blogs
$total_groups = $group->group_total($where);
// MAKE ENTRY PAGES
$groups_per_page = 1;
$page_vars = make_page($total_groups, $groups_per_page, $p);
// GET blog ARRAY
$group_array = $group->group_list($page_vars[0], $groups_per_page, $s, $where);
// ASSIGN SMARTY VARIABLES AND DISPLAY blogs PAGE
$smarty->assign('group', $group);
$smarty->assign('groupowner_info', $groupowner_info);
$smarty->assign('group_category', $group_category);
$smarty->assign('comments', $comments);
$smarty->assign('total_comments', $total_comments);
$smarty->assign('members', $members);
$smarty->assign('total_members', $total_members);
$smarty->assign('is_group_private', $is_group_private);
$smarty->assign('allowed_to_comment', $allowed_to_comment);
$smarty->assign('fields', $group->group_fields);
$smarty->assign('files', $file_array);
$smarty->assign('total_files', $total_files);
$smarty->assign('topics', $topics);
$smarty->assign('total_topics', $total_topics);
$smarty->assign('allowed_to_discuss', $allowed_to_discuss);
$smarty->assign('total_groups', $total_groups);
$smarty->assign_by_ref('groups', $group_array);
$smarty->assign_by_ref('groupcats', $groupcats_array);
$smarty->assign('group_search', $group_search);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($group_array));
$smarty->assign('s', $s);
$smarty->assign('v', $v);
$smarty->assign('c', $c);
// RECENT GROUPS END

// DISPLAY ERROR PAGE IF USER IS NOT LOGGED IN AND ADMIN SETTING REQUIRES REGISTRATION
if($user->user_exists == 0 && $setting[setting_permission_portal] == 0) {
  $page = "error";
  $smarty->assign('error_header', 639);
  $smarty->assign('error_message', 656);
  $smarty->assign('error_submit', 641);
  include "footer.php";
}

// DETERMINE IF USER IS ONLINE
$online_users_array = online_users();
if(in_array($owner->user_info[user_username], $online_users_array[2])) { $is_online = 1; } else { $is_online = 0; }


// GET UPCOMING BIRTHDAYS, START BY CHECKING FOR BIRTHDAY PROFILE FIELDS
$birthday_array = Array();
$birthday_fields = $database->database_query("SELECT profilefield_id, t2.profilecat_id FROM se_profilefields LEFT JOIN se_profilecats AS t1 ON se_profilefields.profilefield_profilecat_id=t1.profilecat_id LEFT JOIN se_profilecats AS t2 ON t1.profilecat_dependency=t2.profilecat_id WHERE profilefield_special='1'");
if($database->database_num_rows($birthday_fields) > 0) {

  // CONSTRUCT QUERY
  $birthdays_upcoming_query = "SELECT se_users.user_id, 
					se_users.user_username, 
					se_users.user_fname, 
					se_users.user_lname,
					CASE ";
  while($birthday_field = $database->database_fetch_assoc($birthday_fields)) {
    $birthdays_upcoming_query .= " WHEN se_users.user_profilecat_id=$birthday_field[profilecat_id] THEN DATE_FORMAT(CONCAT(YEAR(CURDATE()), \"-\", MONTH(se_profilevalues.profilevalue_$birthday_field[profilefield_id]), \"-\", DAY(se_profilevalues.profilevalue_$birthday_field[profilefield_id])), '%Y-%m-%d')";
    $birthdays_upcoming_where[] = "(se_users.user_profilecat_id=$birthday_field[profilecat_id] AND DAY(se_profilevalues.profilevalue_$birthday_field[profilefield_id])<>'0' AND MONTH(se_profilevalues.profilevalue_$birthday_field[profilefield_id])<>'0' AND CURDATE() <= DATE_FORMAT(CONCAT(YEAR(CURDATE()), \"-\", MONTH(se_profilevalues.profilevalue_$birthday_field[profilefield_id]), \"-\", DAY(se_profilevalues.profilevalue_$birthday_field[profilefield_id])), '%Y-%m-%d') AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) >= DATE_FORMAT(CONCAT(YEAR(CURDATE()), \"-\", MONTH(se_profilevalues.profilevalue_$birthday_field[profilefield_id]), \"-\", DAY(se_profilevalues.profilevalue_$birthday_field[profilefield_id])), '%Y-%m-%d'))";
  }
  $birthdays_upcoming_query .= " ELSE '0000-00-00' END AS birthday FROM se_friends LEFT JOIN se_users ON friend_user_id2=se_users.user_id LEFT JOIN se_profilevalues ON se_users.user_id=se_profilevalues.profilevalue_user_id WHERE friend_user_id1=".$user->user_info[user_id]." AND (".implode(" OR ", $birthdays_upcoming_where).") ORDER BY birthday";

  $birthdays = $database->database_query($birthdays_upcoming_query);
  while($birthday = $database->database_fetch_assoc($birthdays)) {
    $fullname = $birthday[user_fname].((trim($birthday[user_fname]) != "" && trim($birthday[user_lname]) != "") ? " " : "").$birthday[user_lname];
    $user_displayname_short = (trim($birthday[user_fname]) != "") ? $birthday[user_fname] : $birthday[user_username];
    $user_displayname = (trim($fullname) != "") ? $fullname : $birthday[user_username];

    // SET BIRTHDAY
    $birthday_date = mktime(0, 0, 0, substr($birthday[birthday], 5, 2), substr($birthday[birthday], 8, 2), 1990);

    $birthday_array[] = Array('birthday_user_id' => $birthday[profilevalue_user_id],
			    'birthday_user_username' => $birthday[user_username],
			    'birthday_user_displayname' => $user_displayname,
			    'birthday_date' => $birthday_date);
  }
}


// GET LIST OF USERS CURRENTLY CHATTING (ONLY GET USERS THAT HAVE UPDATED WITHIN THE LAST 15 SECONDS)
$updatetime = time() - 15;
$chatusers = $database->database_query("SELECT chatuser_id, chatuser_user_username FROM se_chatusers WHERE chatuser_lastupdate>'$updatetime' ORDER BY chatuser_id DESC");
$chatusers_array = Array();
while($chatuser = $database->database_fetch_assoc($chatusers)) {
$chatusers_array[] = Array('chatuser_id' => $chatuser[chatuser_id],
'chatuser_user_username' => $chatuser[chatuser_user_username]);
} 

// IF PREVIOUSLY LOGGED IN EMAIL COOKIE AVAILABLE, SET IT
if(isset($_COOKIE['prev_email'])) { $prev_email = $_COOKIE['prev_email']; } else { $prev_email = ""; }

// GET RECENT SIGNUPS
$signups_query = $database->database_query("SELECT user_id, user_username, user_fname, user_lname, user_photo FROM se_users WHERE user_verified='1' AND user_enabled='1' AND user_search='1' AND user_photo<>'' ORDER BY user_signupdate DESC LIMIT 20");
$signup_array = Array();
while($signup = $database->database_fetch_assoc($signups_query)) {

  $signup_user = new se_user();
  $signup_user->user_info[user_id] = $signup[user_id];
  $signup_user->user_info[user_username] = $signup[user_username];
  $signup_user->user_info[user_photo] = $signup[user_photo];
  $signup_user->user_info[user_fname] = $signup[user_fname];
  $signup_user->user_info[user_lname] = $signup[user_lname];
  $signup_user->user_displayname();

  $signup_array[] = $signup_user;
}


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




// GET RECENT LOGINS
$logins_query = $database->database_query("SELECT user_id, user_username, user_fname, user_lname, user_photo FROM se_users WHERE user_photo<>'' AND user_search='1' ORDER BY user_lastlogindate DESC LIMIT 34");
$login_array = Array();
while($login = $database->database_fetch_assoc($logins_query)) {

  $login_user = new se_user();
  $login_user->user_info[user_id] = $login[user_id];
  $login_user->user_info[user_username] = $login[user_username];
  $login_user->user_info[user_photo] = $login[user_photo];
  $login_user->user_info[user_fname] = $login[user_fname];
  $login_user->user_info[user_lname] = $login[user_lname];
  $login_user->user_displayname();

  $login_array[] = $login_user;
}



// GET NEWS ITEMS
$news = $database->database_query("SELECT * FROM se_announcements ORDER BY announcement_order DESC LIMIT 20");
$news_array = Array();
while($item = $database->database_fetch_assoc($news)) {

  // CONVERT SUBJECT/BODY BACK TO HTML
  $item['announcement_body'] = htmlspecialchars_decode($item['announcement_body'], ENT_QUOTES);
  $item['announcement_subject'] = htmlspecialchars_decode($item['announcement_subject'], ENT_QUOTES);
  $news_array[] = $item;

}
// GET RECENT STATUS UPDATES
$statuses = $database->database_query("SELECT user_id, user_username, user_photo, user_fname, user_lname, user_status FROM se_users WHERE user_subnet_id='$n' AND user_photo<>'' AND user_id<>".$user->user_info[user_id]." AND user_status<>'' ORDER BY user_status_date DESC LIMIT 10");
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



// GET TOTALS
$total_members = $database->database_fetch_assoc($database->database_query("SELECT count(*) AS total_members FROM se_users"));
$total_friends = $database->database_fetch_assoc($database->database_query("SELECT count(*) AS total_friends FROM se_friends WHERE friend_status='1'"));

// LOOP THROUGH COMMENT TABLES TO GET TOTAL COMMENTS
$total_comments = 0;
$comment_tables = $database->database_query("SHOW TABLES FROM `$database_name` LIKE 'se_%comments'");
while($table_info = $database->database_fetch_array($comment_tables)) {
  $comment_type = strrev(substr(strrev(substr($table_info[0], 3)), 8));
  $table_comments = $database->database_fetch_assoc($database->database_query("SELECT count(*) AS total_comments FROM se_".$comment_type."comments"));
  $total_comments += $table_comments[total_comments];
}

// GET LATEST FORUM POSTS/TOPICS
$view_posts_limit = 5; // define how many items should be displayed
include_once(getcwd().'/include/forum/api/impl/home.php');

// RECENT ALBUM
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }
if(isset($_POST['s'])) { $s = $_POST['s']; } elseif(isset($_GET['s'])) { $s = $_GET['s']; } else { $s = "album_datecreated DESC"; }
if(isset($_POST['v'])) { $v = $_POST['v']; } elseif(isset($_GET['v'])) { $v = $_GET['v']; } else { $v = 0; }
// ENSURE SORT/VIEW ARE VALID
if($s != "album_datecreated DESC" && $s != "album_dateupdated DESC") { $s = "album_dateupdated DESC"; }
if($v != "0" && $v != "1") { $v = 0; }
// SET WHERE CLAUSE
$where = "CASE
        WHEN se_albums.album_user_id={$user->user_info[user_id]}
          THEN TRUE
        WHEN ((se_albums.album_privacy & @SE_PRIVACY_REGISTERED) AND {$user->user_exists}<>0)
          THEN TRUE
        WHEN ((se_albums.album_privacy & @SE_PRIVACY_ANONYMOUS) AND {$user->user_exists}=0)
          THEN TRUE
        WHEN ((se_albums.album_privacy & @SE_PRIVACY_FRIEND) AND (SELECT TRUE FROM se_friends WHERE friend_user_id1=se_albums.album_user_id AND friend_user_id2={$user->user_info[user_id]} AND friend_status='1' LIMIT 1))
          THEN TRUE
        WHEN ((se_albums.album_privacy & @SE_PRIVACY_SUBNET) AND {$user->user_exists}<>0 AND (SELECT TRUE FROM se_users WHERE user_id=se_albums.album_user_id AND user_subnet_id={$user->user_info[user_subnet_id]} LIMIT 1))
          THEN TRUE
        WHEN ((se_albums.album_privacy & @SE_PRIVACY_FRIEND2) AND (SELECT TRUE FROM se_friends AS friends_primary LEFT JOIN se_users ON friends_primary.friend_user_id1=se_users.user_id LEFT JOIN se_friends AS friends_secondary ON friends_primary.friend_user_id2=friends_secondary.friend_user_id1 WHERE friends_primary.friend_user_id1=se_albums.album_user_id AND friends_secondary.friend_user_id2={$user->user_info[user_id]} AND se_users.user_subnet_id={$user->user_info[user_subnet_id]} LIMIT 1))
          THEN TRUE
        ELSE FALSE
    END";
// ONLY MY FRIENDS' ALBUMS
if($v == "1" && $user->user_exists) {
  // SET WHERE CLAUSE
  $where .= " AND (SELECT TRUE FROM se_friends WHERE friend_user_id1={$user->user_info[user_id]} AND friend_user_id2=se_albums.album_user_id AND friend_status=1)";

}
// CREATE ALBUM OBJECT
$album = new se_album();
// GET TOTAL ALBUMS
$total_albums = $album->album_total($where);
// MAKE ENTRY PAGES
$albums_per_page = 12;
$page_vars = make_page($total_albums, $albums_per_page, $p);
// GET ALBUM ARRAY
$album_array = $album->album_list($page_vars[0], $albums_per_page, $s, $where);
// ASSIGN SMARTY VARIABLES AND DISPLAY ALBUMS PAGE
$smarty->assign('albums', $album_array);
$smarty->assign('total_albums', $total_albums);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($album_array));
$smarty->assign('s', $s);
$smarty->assign('v', $v);

// RECENT BLOGS
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }
if(isset($_POST['s'])) { $s = $_POST['s']; } elseif(isset($_GET['s'])) { $s = $_GET['s']; } else { $s = "blogentry_date DESC"; }
if(isset($_POST['v'])) { $v = $_POST['v']; } elseif(isset($_GET['v'])) { $v = $_GET['v']; } else { $v = 0; }
if(isset($_POST['c'])) { $c = $_POST['c']; } elseif(isset($_GET['c'])) { $c = $_GET['c']; } else { $c = -1; }
if(isset($_POST['blog_search'])) { $blog_search = $_POST['blog_search']; } elseif(isset($_GET['c'])) { $blog_search = $_GET['blog_search']; } else { $c = NULL; }
// ENSURE SORT/VIEW ARE VALID
if($s != "blogentry_date DESC" && $s != "blogentry_views DESC" && $s != "total_comments DESC") { $s = "blogentry_date DESC"; }
if($v != "0" && $v != "1") { $v = 0; }
// SET WHERE CLAUSE
$where = "CASE
        WHEN se_blogentries.blogentry_user_id='{$user->user_info[user_id]}'
          THEN TRUE
        WHEN ((se_blogentries.blogentry_privacy & @SE_PRIVACY_REGISTERED) AND '{$user->user_exists}'<>0)
          THEN TRUE
        WHEN ((se_blogentries.blogentry_privacy & @SE_PRIVACY_ANONYMOUS) AND '{$user->user_exists}'=0)
          THEN TRUE
        WHEN ((se_blogentries.blogentry_privacy & @SE_PRIVACY_FRIEND) AND (SELECT TRUE FROM se_friends WHERE friend_user_id1=se_blogentries.blogentry_user_id AND friend_user_id2='{$user->user_info[user_id]}' AND friend_status='1' LIMIT 1))
          THEN TRUE
        WHEN ((se_blogentries.blogentry_privacy & @SE_PRIVACY_SUBNET) AND '{$user->user_exists}'<>0 AND (SELECT TRUE FROM se_users WHERE user_id=se_blogentries.blogentry_user_id AND user_subnet_id='{$user->user_info[user_subnet_id]}' LIMIT 1))
          THEN TRUE
        WHEN ((se_blogentries.blogentry_privacy & @SE_PRIVACY_FRIEND2) AND (SELECT TRUE FROM se_friends AS friends_primary LEFT JOIN se_users ON friends_primary.friend_user_id1=se_users.user_id LEFT JOIN se_friends AS friends_secondary ON friends_primary.friend_user_id2=friends_secondary.friend_user_id1 WHERE friends_primary.friend_user_id1=se_blogentries.blogentry_user_id AND friends_secondary.friend_user_id2='{$user->user_info[user_id]}' AND se_users.user_subnet_id='{$user->user_info[user_subnet_id]}' LIMIT 1))
          THEN TRUE
        ELSE FALSE
    END";
// ONLY MY FRIENDS' BLOGS
if( $v=="1" && $user->user_exists )
{
  // SET WHERE CLAUSE
  $where .= " AND (SELECT TRUE FROM se_friends WHERE friend_user_id1={$user->user_info[user_id]} AND friend_user_id2=se_blogentries.blogentry_user_id AND friend_status=1)";
}
// CATEGORIES
if( isset($c) && $c!=-1 )
{
  if( $c==0 ) $c = '0';
  $where .= " AND blogentry_blogentrycat_id='$c'";
}
// CREATE blog OBJECT
$blog = new se_blog();
// GET TOTAL blogs
$total_blogentries = $blog->blog_entries_total($where);
// MAKE ENTRY PAGES
$blogentries_per_page = 4;
$page_vars = make_page($total_blogentries, $blogentries_per_page, $p);
// GET blog ARRAY
$blogentry_array = $blog->blog_entries_list($page_vars[0], $blogentries_per_page, $s, $where);
// ASSIGN SMARTY VARIABLES AND DISPLAY blogs PAGE
$smarty->assign('total_blogentries', $total_blogentries);
$smarty->assign_by_ref('blogentries', $blogentry_array);
$smarty->assign_by_ref('blogentrycats', $blogentrycats_array);
$smarty->assign('blog_search', $blog_search);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($blogentry_array));
$smarty->assign('s', $s);
$smarty->assign('v', $v);
$smarty->assign('c', $c);

// RECENT BLOGS
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }
if(isset($_POST['s'])) { $s = $_POST['s']; } elseif(isset($_GET['s'])) { $s = $_GET['s']; } else { $s = "blogentry_date DESC"; }
if(isset($_POST['v'])) { $v = $_POST['v']; } elseif(isset($_GET['v'])) { $v = $_GET['v']; } else { $v = 0; }
if(isset($_POST['c'])) { $c = $_POST['c']; } elseif(isset($_GET['c'])) { $c = $_GET['c']; } else { $c = -1; }
if(isset($_POST['blog_search'])) { $blog_search = $_POST['blog_search']; } elseif(isset($_GET['c'])) { $blog_search = $_GET['blog_search']; } else { $c = NULL; }
// ENSURE SORT/VIEW ARE VALID
if($s != "blogentry_date DESC" && $s != "blogentry_views DESC" && $s != "total_comments DESC") { $s = "blogentry_date DESC"; }
if($v != "0" && $v != "1") { $v = 0; }
// SET WHERE CLAUSE
$where = "CASE
        WHEN se_blogentries.blogentry_user_id='{$user->user_info[user_id]}'
          THEN TRUE
        WHEN ((se_blogentries.blogentry_privacy & @SE_PRIVACY_REGISTERED) AND '{$user->user_exists}'<>0)
          THEN TRUE
        WHEN ((se_blogentries.blogentry_privacy & @SE_PRIVACY_ANONYMOUS) AND '{$user->user_exists}'=0)
          THEN TRUE
        WHEN ((se_blogentries.blogentry_privacy & @SE_PRIVACY_FRIEND) AND (SELECT TRUE FROM se_friends WHERE friend_user_id1=se_blogentries.blogentry_user_id AND friend_user_id2='{$user->user_info[user_id]}' AND friend_status='1' LIMIT 1))
          THEN TRUE
        WHEN ((se_blogentries.blogentry_privacy & @SE_PRIVACY_SUBNET) AND '{$user->user_exists}'<>0 AND (SELECT TRUE FROM se_users WHERE user_id=se_blogentries.blogentry_user_id AND user_subnet_id='{$user->user_info[user_subnet_id]}' LIMIT 1))
          THEN TRUE
        WHEN ((se_blogentries.blogentry_privacy & @SE_PRIVACY_FRIEND2) AND (SELECT TRUE FROM se_friends AS friends_primary LEFT JOIN se_users ON friends_primary.friend_user_id1=se_users.user_id LEFT JOIN se_friends AS friends_secondary ON friends_primary.friend_user_id2=friends_secondary.friend_user_id1 WHERE friends_primary.friend_user_id1=se_blogentries.blogentry_user_id AND friends_secondary.friend_user_id2='{$user->user_info[user_id]}' AND se_users.user_subnet_id='{$user->user_info[user_subnet_id]}' LIMIT 1))
          THEN TRUE
        ELSE FALSE
    END";
// ONLY MY FRIENDS' BLOGS
if( $v=="1" && $user->user_exists )
{
  // SET WHERE CLAUSE
  $where .= " AND (SELECT TRUE FROM se_friends WHERE friend_user_id1={$user->user_info[user_id]} AND friend_user_id2=se_blogentries.blogentry_user_id AND friend_status=1)";
}
// CATEGORIES
if( isset($c) && $c!=-1 )
{
  if( $c==0 ) $c = '0';
  $where .= " AND blogentry_blogentrycat_id='$c'";
}
// CREATE blog OBJECT
$blog = new se_blog();
// GET TOTAL blogs
$total_blogentries = $blog->blog_entries_total($where);
// MAKE ENTRY PAGES
$blogentries_per_page = 4;
$page_vars = make_page($total_blogentries, $blogentries_per_page, $p);
// GET blog ARRAY
$blogentry_array = $blog->blog_entries_list($page_vars[0], $blogentries_per_page, $s, $where);
  foreach( $blogentry_array as $blogentryindex=>$blogentryitem )
  {
    $blogentry_array[$blogentryindex]['blogentry_body'] = preg_replace('/<.*?>/', ' ', substr($blogentry_array[$blogentryindex]['blogentry_body'], 0, 300));
    $blogentry_array[$blogentryindex]['blogentry_body'] = preg_replace('/<[^>]+?$/', ' ', substr($blogentry_array[$blogentryindex]['blogentry_body'], 0, 300));

}

// GET LATEST SKINS
$sample_css = Array();
$styles = $database->database_query("SELECT * FROM se_stylesamples WHERE stylesample_type='profile'");
while ($sample_info = $database->database_fetch_assoc($styles)) {
  $sample_css[] = $sample_info;
  $sample[$sample_info[stylesample_id]] = $sample_info[stylesample_css];
}



// ASSIGN SMARTY VARIABLES AND DISPLAY blogs PAGE
$smarty->assign('total_blogentries', $total_blogentries);
$smarty->assign_by_ref('blogentries', $blogentry_array);
$smarty->assign_by_ref('blogentrycats', $blogentrycats_array);
$smarty->assign('blog_search', $blog_search);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($blogentry_array));
$smarty->assign('s', $s);
$smarty->assign('v', $v);
$smarty->assign('c', $c);

//FRIENDS OF FRIENDS PLUGIN 
include('right_side_fof.php');

// ASSIGN SMARTY VARIABLES AND INCLUDE FOOTER
$smarty->assign('sample_css', $sample_css);
$smarty->assign('is_online', $is_online);
$smarty->assign('actions', $actions->actions_display(0, $setting[setting_actions_actionsonprofile], "se_actions.action_user_id='".$owner->user_info[user_id]."'"));
$smarty->assign('p_percent', $profile_percent);
$smarty->assign('chatusers', $chatusers_array); 
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
$smarty->assign('birthdays', $birthday_array);
$smarty->assign('statuses', $statuses_array);
$smarty->assign('actions', $actions->actions_display(0, $setting[setting_actions_actionsperuser]));

// START - ASSIGN ALBUM VARIABLES
$smarty->assign('albums', $album_array);
$smarty->assign('total_albums', $total_albums);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0] + 1);
$smarty->assign('p_end', $page_vars[0] + count($album_array));
$smarty->assign('s', $s);
$smarty->assign('v', $v);
// END - ASSIGN ALBUM VARIABLES

include "footer.php";
?>