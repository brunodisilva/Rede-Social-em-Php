<?php

include_once "class_radcodes.php";

class rc_commented extends rc_model
{
  var $table = 'se_profilecomments';
  var $pk = 'profilecomment_id';
	
	function count_users_commented($author_id)
	{
    $query = "SELECT DISTINCT profilecomment_user_id FROM {$this->table} WHERE profilecomment_authoruser_id='$author_id' AND profilecomment_user_id <> '$author_id'";
    $total = $this->db->database_num_rows($this->db->database_query($query));

    return $total;
	}
	
	function get_commented_users($author_id, $start, $limit)
	{
    $users = Array();
    $comment_query = "SELECT DISTINCT se_users.user_id, se_users.user_username, se_users.user_photo FROM {$this->table}
      JOIN se_users ON {$this->table}.profilecomment_user_id=se_users.user_id
      WHERE profilecomment_authoruser_id='$author_id'
      AND profilecomment_user_id <> '$author_id'
      ORDER BY {$this->table}.profilecomment_date DESC LIMIT $start, $limit";
      
    $res = $this->db->database_query($comment_query);
    while($row = $this->db->database_fetch_assoc($res)) {

    	$user = new se_user();
      $user->user_info[user_id] = $row[user_id];
      $user->user_info[user_username] = $row[user_username];
      $user->user_info[user_photo] = $row[user_photo];
      
      $users[$row['user_id']] = $user;
    }

    $comments = $this->get_comments_of_users($author_id, array_keys($users));
    foreach ($users as $k=>$user) {
    	if (isset($comments[$user->user_info[user_id]])) {
    		$users[$k]->comments = $comments[$user->user_info[user_id]];
    	}
    	else {
    		$users[$k]->comments = array();
    	}
    	
    }
    
    return $users;
	}
	
	
	function get_comments_of_users($author_id, $user_ids)
	{
	  $comments = array();
    $comment_query = "SELECT {$this->table}.* FROM {$this->table}
      JOIN se_users ON {$this->table}.profilecomment_user_id=se_users.user_id
      WHERE profilecomment_authoruser_id='$author_id'
      AND profilecomment_user_id IN ('" . join("','", $user_ids) . "')
      ORDER BY {$this->table}.profilecomment_date DESC";
      
    $res = $this->db->database_query($comment_query);
    while($row = $this->db->database_fetch_assoc($res)) {

    	$comment = array(
    	  'comment_id' => $row['profilecomment_id'],
    	  'comment_date' => $row['profilecomment_date'],
    	  'comment_body' => $row['profilecomment_body'],
    	);
    	
      $comments[$row['profilecomment_user_id']][] = $comment;
    }
    
    return $comments;
	}
	
	
}
