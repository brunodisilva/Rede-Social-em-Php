<?
$page = "user_points_offers_item";
include "header.php";

// ENSURE POINTS ARE ENABLED FOR THIS USER
if(($user->level_info['level_userpoints_allow'] == 0) || ($user->user_info['user_userpoints_allowed'] == 0)){ header("Location: user_home.php"); exit(); }

$task = semods::getpost('task', 'main');
$item_id = intval(semods::getpost('item_id',0));

$upearner = new semods_upearner( $item_id );

if($upearner->upearner_exists == 0) {
  header("Location: user_points_offers.php");
  exit();
}


if($task == "dobuy") {
  
  if(!$upearner->transact( $user ) ) {
	$is_error = 1;
	$error_message = $upearner->err_msg;
  } else {
	$transaction_message = !empty($upearner->transaction_message) ? $upearner->transaction_message :  semods::get_language_text( 100016678 );
	header("Location: user_points_transactions.php?success=1&success_message=" . urlencode($transaction_message) );
	exit();
  }
  
}



// UPDATE ENTRY VIEWS
$database->database_query( "UPDATE se_semods_userpointearner SET userpointearner_views = userpointearner_views + 1 WHERE userpointearner_id = " . $item_id );

$allowed_to_comment = $upearner->upearner_info['userpointearner_comments_allowed'];

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
    $database->database_query("INSERT INTO se_userpointearnercomments (userpointearnercomment_userpointearner_id, userpointearnercomment_authoruser_id, userpointearnercomment_date, userpointearnercomment_body) VALUES ('".$item_id."', '".$user->user_info['user_id']."', '$comment_date', '$comment_body')");

    // Update comments counter
    $database->database_query( "UPDATE se_semods_userpointearner SET userpointearner_comments = userpointearner_comments + 1 WHERE userpointearner_id = " . $item_id );

    // INSERT ACTION IF USER EXISTS
    if($user->user_exists != 0) {
      $commenter = $user->user_info['user_username'];
      $comment_body_encoded = $comment_body;
      if(strlen($comment_body_encoded) > 250) { 
        $comment_body_encoded = substr($comment_body_encoded, 0, 240);
        $comment_body_encoded .= "...";
      }
      $comment_body_encoded = htmlspecialchars(str_replace("<br>", " ", $comment_body_encoded));
      
      $upearner_title = choptext( $upearner->upearner_info['userpointearner_title'] ) . "...";
      $actions->actions_add($user,
                            "upearnercomment",
                            array(  $user->user_info['user_username'],
                                    $user->user_displayname,
                                    $upearner->upearner_info['userpointearner_id'],
                                    $upearner_title,
                                    $comment_body_encoded,
                                    $upearner->upearner_info['userpointearner_id'],
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
    // TBD: if shown to public
    // else {
      //$commenter = "Anonymous";
    //}

  }

  echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><script type=\"text/javascript\">";
  echo "window.parent.addComment('$is_error', '$comment_body', '$comment_date');";
  echo "</script></head><body></body></html>";
  exit();
}


if($task != "buy") {

  
  // GET COMMENTS
  $comment = new se_comment( 'userpointearner', 'userpointearner_id', $item_id );
  $total_comments = $comment->comment_total();
  $comments = $comment->comment_list(0, $total_comments);


}



// ASSIGN VARIABLES AND INCLUDE FOOTER

$smarty->assign('upearner', $upearner);

$smarty->assign('comments', $comments);
$smarty->assign('total_comments', $total_comments);
$smarty->assign('allowed_to_comment', $allowed_to_comment);

$smarty->assign('error_message', $error_message);
$smarty->assign('is_error', $is_error);


include "footer.php";
?>