<?
$page = "user_points_shop_item";
include "header.php";

// ENSURE POINTS ARE ENABLED FOR THIS USER
if(($user->level_info['level_userpoints_allow'] == 0) || ($user->user_info['user_userpoints_allowed'] == 0)){ header("Location: user_home.php"); exit(); }

$task = semods::getpost('task', 'main');
$upspender_id = intval(semods::getpost('shopitem_id',0));

$upspender = new semods_upspender( $upspender_id );

if($upspender->upspender_exists == 0) {
  header("Location: user_points_shop.php");
  exit();
}



if($task == "dobuy") {
  
  if(!$upspender->transact( $user ) ) {
	$is_error = 1;
	$error_message = $upspender->err_msg;	
  } else {
	$transaction_message = !empty($upspender->transaction_message) ? $upspender->transaction_message :  100016661;
    $transaction_message = semods::get_language_text( $transaction_message );
	header("Location: user_points_transactions.php?success=1&success_message=" . urlencode($transaction_message) );
	exit();
  }
  
}



// UPDATE ENTRY VIEWS
$database->database_query( "UPDATE se_semods_userpointspender SET userpointspender_views = userpointspender_views + 1 WHERE userpointspender_id = " . $upspender_id );

$allowed_to_comment = $upspender->upspender_info['userpointspender_comments_allowed'];

// IF A COMMENT IS BEING POSTED
// IS THIS A BUG WITH ONE "&" ? 
if(($task == "dopost") && ($allowed_to_comment != 0)) {

  $comment_date = time();
  $comment_body = $_POST['comment_body'];

  // RETRIEVE AND CHECK SECURITY CODE IF NECESSARY
  if($setting['setting_comment_code'] != 0) {
    session_start();
    $code = $_SESSION['code'];
    if($code == "") { $code = randomcode(); }
    $comment_secure = $_POST['comment_secure'];

    if($comment_secure != $code) { $is_error = 1; }
  }

  // MAKE SURE COMMENT BODY IS NOT EMPTY
  $comment_body = censor(str_replace("\r\n", "<br>", $comment_body));
  $comment_body = preg_replace('/(<br>){3,}/is', '<br><br>', $comment_body);
  $comment_body = ChopText($comment_body);
  if(str_replace(" ", "", $comment_body) == "") { $is_error = 1; $comment_body = ""; }

  // ADD COMMENT IF NO ERROR
  if($is_error == 0) {
    $database->database_query("INSERT INTO se_userpointspendercomments (userpointspendercomment_userpointspender_id, userpointspendercomment_authoruser_id, userpointspendercomment_date, userpointspendercomment_body) VALUES ('".$upspender_id."', '".$user->user_info['user_id']."', '$comment_date', '$comment_body')");

    // Update comments counter
    $database->database_query( "UPDATE se_semods_userpointspender SET userpointspender_comments = userpointspender_comments + 1 WHERE userpointspender_id = " . $upspender_id );

    // INSERT ACTION IF USER EXISTS
    if($user->user_exists != 0) {
      $commenter = $user->user_info['user_username'];
      $comment_body_encoded = $comment_body;
      if(strlen($comment_body_encoded) > 250) { 
        $comment_body_encoded = substr($comment_body_encoded, 0, 240);
        $comment_body_encoded .= "...";
      }
      $comment_body_encoded = htmlspecialchars(str_replace("<br>", " ", $comment_body_encoded));
      
      $upspender_title = choptext( $upspender->upspender_info['userpointspender_title'] ) . "...";
      $actions->actions_add($user,
                            "upspendercomment",
                            array(  $user->user_info['user_username'],
                                    $user->user_displayname,
                                    $upspender->upspender_info['userpointspender_id'],
                                    $upspender_title,
                                    $comment_body_encoded,
                                    $upspender->upspender_info['userpointspender_id'],
                                    ""  // $object_title
                                    ),
                            array(),
                            0,
                            false,
                            "",
                            0,
                            63
                            );

    }
    //else {
    //  $commenter = "Anonymous";
    //}

  }

  echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><script type=\"text/javascript\">";
  echo "window.parent.addComment('$is_error', '$comment_body', '$comment_date');";
  echo "</script></head><body></body></html>";
  exit();
}


if($task != "buy") {

  
  
  // GET COMMENTS
  $comment = new se_comment( 'userpointspender', 'userpointspender_id', $upspender_id );
  $total_comments = $comment->comment_total();
  $comments = $comment->comment_list(0, $total_comments);


}



// ASSIGN VARIABLES AND INCLUDE FOOTER

$smarty->assign('upspender', $upspender);

$smarty->assign('comments', $comments);
$smarty->assign('total_comments', $total_comments);
$smarty->assign('allowed_to_comment', $allowed_to_comment);

$smarty->assign('error_message', $error_message);
$smarty->assign('is_error', $is_error);

include "footer.php";
?>