<?php

/* $Id: blog_ajax.php 53 2009-02-06 04:55:08Z john $ */

ob_start();
$page = "blog_ajax";
include "header.php";

/*
// DEBUG
if( $fh = fopen('./logs/tb/'.time().'.txt', 'w') )
{
  fwrite( $fh, print_r($_GET, TRUE)."\n\n".print_r($_POST, TRUE)."\n\n".print_r($_COOKIE, TRUE) );
  fclose( $fh );
}
*/

// PROCESS INPUT
$task         = ( !empty($_POST['task'])          ? $_POST['task']          : ( !empty($_GET['task'])         ? $_GET['task']         : NULL ) );
$blogentry_id = ( !empty($_POST['blogentry_id'])  ? $_POST['blogentry_id']  : ( !empty($_GET['blogentry_id']) ? $_GET['blogentry_id'] : NULL ) );


// TRACKBACK COMPATIBILITY
if( empty($_POST['e_id']) && !empty($blogentry_id) )
  $_POST['e_id'] = $blogentry_id;


// CREATE BLOG OBJECT
$blog = new se_blog();




// TRACKBACKS
if( $task=="trackback" )
{
  // Redirect if no data 
  if( !empty($blogentry_id) && empty($_POST['url']) && empty($_GET['url']) )
  {
    $blogentry_info = $blog->blog_entry_info($blogentry_id);
    ob_end_clean();
    header('Location: ' . $url->url_create('blog_entry', $blogentry_info['user_username'], $blogentry_id));
    exit();
  }
  
  // OUTPUT
  ob_end_clean();
  
  echo $blog->blog_trackback_receive();
  exit();
}



// DELETE
elseif( $task=="deleteblog" )
{
  // OUTPUT
  ob_end_clean();
  
  if( !$is_error && $blog->blog_entry_delete($blogentry_id) )
    echo '{"result":"success"}';
  else
    echo '{"result":"failure"}';
  
  exit();
}



// PREVIEW
elseif( $task=="previewblog" )
{
  $page = "blog";
  
  $owner =& $user;
  $blog->user_id = $user->user_info['user_id'];
  
  $blogentry_title            = $_POST['blogentry_title'];
  $blogentry_body             = $_POST['blogentry_body'];
  $blogentry_blogentrycat_id  = $_POST['blogentry_blogentrycat_id'];
  
  $blogentry_body = str_replace("\r\n", "", htmlspecialchars_decode($blogentry_body));
  
  // GET CUSTOM BLOG STYLE IF ALLOWED
  if( $user->level_info['level_blog_style'] )
  {
    $blogstyle_info = $database->database_fetch_assoc($database->database_query("SELECT blogstyle_css FROM se_blogstyles WHERE blogstyle_user_id='{$owner->user_info['user_id']}' LIMIT 1"));
    $global_css = $blogstyle_info['blogstyle_css'];
  }

  // GET ARCHIVE AND CATEGORIES
  $archive_list = $blog->blog_archive_generate();
  $category_list = $blog->blog_categories_generate();
  
  // ASSIGN VARIABLES AND DISPLAY BLOG PAGE
  $smarty->assign('total_blogentries', 1);
  $smarty->assign('entries', array(array(
    'blogentry_id'              => $blogentry_id,
    'blogentry_title'           => $blogentry_title,
    'blogentry_body'            => $blogentry_body,
    'blogentry_blogentrycat_id' => $blogentry_blogentrycat_id
  )));
  
  $smarty->assign_by_ref('archive_list', $archive_list);
  $smarty->assign_by_ref('category_list', $category_list);
  $smarty->assign('p', 1);
  $smarty->assign('maxpage', 1);
  $smarty->assign('p_start', 1);
  $smarty->assign('p_end', 1);
  
  ob_end_clean();
  
  include "footer.php";
  exit();
}



// SUBSCRIBE
elseif( $task=="subscribeblog" )
{
  // OUTPUT
  ob_end_clean();
  
  if( !$is_error && $blog->blog_subscription_create($owner->user_info['user_id']) )
    echo '{"result":"success"}';
  else
    echo '{"result":"failure"}';
  
  exit();
}



// UNSUBSCRIBE
elseif( $task=="unsubscribeblog" )
{
  // OUTPUT
  ob_end_clean();
  
  if( !$is_error && $blog->blog_subscription_delete($owner->user_info['user_id']) )
    echo '{"result":"success"}';
  else
    echo '{"result":"failure"}';
  
  exit();
}


/*
else
{
  ob_end_clean();
  
  echo "<html><body><form method='post'>";
  
  echo "<input type='text' name='e_id' value='15' />EID<br /><br />";
  echo "<input type='text' name='url' value='TestBlogURL' />url<br /><br />";
  echo "<input type='text' name='title' value='TestBlogTitle' />title<br /><br />";
  echo "<input type='text' name='excerpt' value='TestBlogExcerpt' />excerpt<br /><br />";
  echo "<input type='text' name='blog_name' value='TestBlogName' />blog_name<br /><br />";
  
  echo "<input type='hidden' name='task' value='trackback' /><br /><br />";
  echo "<input type='submit' value='Submit' /><br /><br />";
  
  echo "</form></body></html>";
}
*/

?>