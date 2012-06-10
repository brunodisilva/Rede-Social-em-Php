<?
$page = "admin_userpoints_offercomments";
include "admin_header.php";
include_once "../include/class_comment.php";

$task = semods::getpost('task', "main");
$item_id = semods::getpost('item_id', 0);
$p = semods::getpost('p', 1);


// CREATE PROFILE COMMENT OBJECT
$comments_per_page = 50;
$comment = new se_comment( 'userpointearner', 'userpointearner_id', $item_id );

// DELETE NECESSARY COMMENTS
$start = ($p - 1) * $comments_per_page;
if($task == "delete") {
  $comment->comment_delete_selected($start, $comments_per_page);

  // Recount total comments
  $database->database_query( "UPDATE se_semods_userpointearner SET userpointearner_comments = ( SELECT COUNT(*) FROM se_userpointearnercomments WHERE userpointearnercomment_userpointearner_id = $item_id ) WHERE userpointearner_id = $item_id" );

  semods::redirect( "admin_userpoints_offercomments.php?item_id=$item_id" );
}


// GET TOTAL COMMENTS
$total_comments = $comment->comment_total();

// MAKE COMMENT PAGES
$page_vars = make_page($total_comments, $comments_per_page, $p);

// GET COMMENT ARRAY
//$comments = $comment->comment_list($page_vars[0], $comments_per_page);

$start = $page_vars[0];
$limit = $comments_per_page;
$comment_array = Array();
$comment_query = "SELECT se_userpointearnercomments.*, se_users.* FROM se_userpointearnercomments LEFT JOIN se_users ON se_userpointearnercomments.userpointearnercomment_authoruser_id=se_users.user_id WHERE userpointearnercomment_userpointearner_id='$item_id' ORDER BY userpointearnercomment_id DESC LIMIT $start, $limit";
$comments = $database->database_query($comment_query);
while($comment_info = $database->database_fetch_assoc($comments)) {

  // CREATE AN OBJECT FOR AUTHOR
  $author = new se_user();
  $author->user_info[user_id] = $comment_info[user_id];
  $author->user_info[user_username] = $comment_info[user_username];
  $author->user_info[user_photo] = $comment_info[user_photo];
  $author->user_info[user_fname] = $comment_info[user_fname];
  $author->user_info[user_lname] = $comment_info[user_lname];
  $author->user_info[user_displayname] = $comment_info[user_displayname];
  $author->user_displayname();

  // SET COMMENT ARRAY
  $comment_array[] = array( 'comment_id'      => $comment_info['userpointearnercomment_id'],
                            'comment_author'  => $author,
                            'comment_date'    => $comment_info['userpointearnercomment_date'],
                            'comment_body'    => $comment_info['userpointearnercomment_body']
                            );
}
$comments = $comment_array;

if(!isset($misc))
$misc = new se_misc();

// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('comments', $comments);
$smarty->assign('total_comments', $total_comments);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($comments));

$smarty->assign('item_id', $item_id);
$smarty->assign('misc', $misc);

include "admin_footer.php";
?>