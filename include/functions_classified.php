<?php

/* $Id: functions_classified.php 7 2009-01-11 06:01:49Z john $ */


//
//  THIS FILE CONTAINS CLASSIFIED-RELATED FUNCTIONS
//
//  FUNCTIONS IN THIS CLASS:
//
//    search_classified()
//    deleteuser_classified()
//    site_statistics_classified()
//


defined('SE_PAGE') or exit();








//
// THIS FUNCTION IS RUN DURING THE SEARCH PROCESS TO SEARCH THROUGH classified ENTRIES
//
// INPUT:
//
// OUTPUT: 
//

function search_classified()
{
	global $database, $url, $results_per_page, $p, $search_text, $t, $search_objects, $results, $total_results;
  
  /*
	// GET CLASSIFIED FIELDS
	$classifiedfields = $database->database_query("SELECT classifiedfield_id, classifiedfield_type, classifiedfield_options FROM se_classifiedfields WHERE classifiedfield_type<>'5'");
	$classifiedvalue_query = "se_classifieds.classified_title LIKE '%$search_text%' OR se_classifieds.classified_body LIKE '%$search_text%'";
  
	// LOOP OVER CLASSIFIED FIELDS
	while($classifiedfield_info = $database->database_fetch_assoc($classifiedfields)) {
    
	  // TEXT FIELD OR TEXTAREA
	  if($classifiedfield_info[classifiedfield_type] == 1 | $classifiedfield_info[classifiedfield_type] == 2) {
	    if($classifiedvalue_query != "") { $classifiedvalue_query .= " OR "; }
	    $classifiedvalue_query .= "se_classifiedvalues.classifiedvalue_".$classifiedfield_info[classifiedfield_id]." LIKE '%$search_text%'";

	  // RADIO OR SELECT BOX
	  } elseif($classifiedfield_info[classifiedfield_type] == 3 | $classifiedfield_info[classifiedfield_type] == 4) {
	    // LOOP OVER FIELD OPTIONS
	    $options = explode("<~!~>", $classifiedfield_info[classifiedfield_options]);
	    for($i=0,$max=count($options);$i<$max;$i++) {
	      if(str_replace(" ", "", $options[$i]) != "") {
	        $option = explode("<!>", $options[$i]);
	        $option_id = $option[0];
	        $option_label = $option[1];
	        if(strpos($option_label, $search_text)) {
	          if($classifiedvalue_query != "") { $classifiedvalue_query .= " OR "; }
	          $classifiedvalue_query .= "se_classifiedvalues.classifiedvalue_".$classifiedfield_info[classifiedfield_id]."='$option_id'";
	        }
	      }
	    }
	  }
	}
  */
  
  /*
  $field = new se_field("classified");
  $text_columns = $field->field_index(TRUE);
  
  if( !is_array($text_columns) )
    $text_columns = array();
  */
  
	// CONSTRUCT QUERY
  $sql = "
    SELECT
      se_classifieds.classified_id,
      se_classifieds.classified_title,
      se_classifieds.classified_body,
      se_classifieds.classified_photo,
      se_users.user_id,
      se_users.user_username,
      se_users.user_photo,
      se_users.user_fname,
      se_users.user_lname
    FROM
      se_classifieds
    LEFT JOIN
      se_users
      ON se_classifieds.classified_user_id=se_users.user_id
    LEFT JOIN
      se_levels
      ON se_users.user_level_id=se_levels.level_id
    LEFT JOIN
      se_classifiedvalues
      ON se_classifieds.classified_id=se_classifiedvalues.classifiedvalue_classified_id
    WHERE
      (se_classifieds.classified_search=1 || se_levels.level_classified_search=0)
  ";
  
  /*
  $sql .= " && (MATCH (`classified_title`, `classified_body`) AGAINST ('{$search_text}' IN BOOLEAN MODE)";
  
  if( !empty($text_columns) )
    $sql .= " || MATCH (`".join("`, `", $text_columns)."`) AGAINST ('{$search_text}' IN BOOLEAN MODE)";
  
  $sql .= ")";
  */
  
  $text_columns[] = 'classified_title';
  $text_columns[] = 'classified_body';
  $sql .= " && MATCH (`".join("`, `", $text_columns)."`) AGAINST ('{$search_text}' IN BOOLEAN MODE)";
  
  
	// GET TOTAL ENTRIES
  $sql2 = $sql . " LIMIT 201";
  $resource = $database->database_query($sql2) or die($database->database_error()." <b>SQL was: </b>{$sql2}");
	$total_entries = $database->database_num_rows($resource);

	// IF NOT TOTAL ONLY
	if( $t=="classified" )
  {
	  // MAKE CLASSIFIED PAGES
	  $start = ($p - 1) * $results_per_page;
	  $limit = $results_per_page+1;
    
	  // SEARCH CLASSIFIEDS
    $sql3 = $sql . " ORDER BY classified_id DESC LIMIT {$start}, {$limit}";
    $resource = $database->database_query($sql3) or die($database->database_error()." <b>SQL was: </b>{$sql3}");
    
	  while( $classified_info=$database->database_fetch_assoc($resource) )
    {
	    // CREATE AN OBJECT FOR AUTHOR
	    $profile = new se_user();
	    $profile->user_info['user_id']        = $classified_info['user_id'];
	    $profile->user_info['user_username']  = $classified_info['user_username'];
	    $profile->user_info['user_photo']     = $classified_info['user_photo'];
	    $profile->user_info['user_fname']     = $classified_info['user_fname'];
	    $profile->user_info['user_lname']     = $classified_info['user_lname'];
	    $profile->user_displayname();
      
	    // IF EMPTY TITLE
	    if( !trim($classified_info['classified_title']) )
        $classified_info['classified_title'] = SE_Language::get(589);
      
      $classified_info['classified_body'] = cleanHTML($classified_info['classified_body'], '');
      
	    // IF BODY IS LONG
	    if( strlen($classified_info['classified_body'])>150 )
        $classified_info['classified_body'] = substr($classified_info['classified_body'], 0, 147)."...";
      
	    // SET THUMBNAIL, IF AVAILABLE
      $thumb_path = NULL;
      if( !empty($classified_info['classified_photo']) )
      {
        $classified_dir = se_classified::classified_dir($classified_info['classified_id']);
        $classified_photo = $classified_info['classified_photo'];
        $classified_thumb = substr($classified_photo, 0, strrpos($classified_photo, "."))."_thumb".substr($classified_photo, strrpos($classified_photo, "."));
        
        if( file_exists($classified_dir.$classified_thumb) )
          $thumb_path = $classified_dir.$classified_thumb;
        elseif( file_exists($classified_dir.$classified_photo) )
          $thumb_path = $classified_dir.$classified_photo;
      }
      
      if( !$thumb_path )
        $thumb_path = "./images/icons/file_big.gif";
      
      
      $result_url = $url->url_create('classified', $classified_info['user_username'], $classified_info['classified_id']);
      $result_name = 4500137;
      $result_desc = 4500138;
      
      
	    $results[] = array(
        'result_url'    => $result_url,
				'result_icon'   => $thumb_path,
				'result_name'   => $result_name,
				'result_name_1' => $classified_info['classified_title'],
				'result_desc'   => $result_desc,
				'result_desc_1' => $url->url_create('profile', $classified_info['user_username']),
				'result_desc_2' => $profile->user_displayname,
				'result_desc_3' => $classified_info['classified_body']
      );
      
      unset($profile);
	  }
    
	  // SET TOTAL RESULTS
	  $total_results = $total_entries;
	}

	// SET ARRAY VALUES
	SE_Language::_preload_multi(4500137, 4500138, 4500139);
	if( $total_entries>200 )
    $total_entries = "200+";
  
	$search_objects[] = array(
    'search_type'   => 'classified',
    'search_lang'   => 4500139,
    'search_total'  => $total_entries
  );
}

// END search_classified() FUNCTION








//
// THIS FUNCTION IS RUN WHEN A USER IS DELETED
//
// INPUT:
//    $user_id REPRESENTING THE USER ID OF THE USER BEING DELETED
//
// OUTPUT: 
//

function deleteuser_classified($user_id)
{
	global $database;

	// DELETE CLASSIFIED ENTRIES AND COMMENTS AND VALUES
	$database->database_query("DELETE se_classifieds.*, se_classifiedcomments.*, se_classifiedvalues.* FROM se_classifieds LEFT JOIN se_classifiedcomments ON se_classifiedcomments.classifiedcomment_classified_id=se_classifieds.classified_id LEFT JOIN se_classifiedvalues ON se_classifiedvalues.classifiedvalue_classified_id=se_classifieds.classified_id WHERE se_classifieds.classified_user_id='{$user_id}'");

	// DELETE COMMENTS POSTED BY USER
	$database->database_query("DELETE FROM se_classifiedcomments WHERE classifiedcomment_authoruser_id='{$user_id}'");

	// DELETE STYLE
	$database->database_query("DELETE FROM se_classifiedstyles WHERE classifiedstyle_user_id='{$user_id}'");
}

// END deleteuser_classified() FUNCTION









// THIS FUNCTION IS RUN WHEN GENERATING SITE STATISTICS
// INPUT: 
// OUTPUT: 
function site_statistics_classified(&$args)
{
  global $database;
  
  $statistics =& $args['statistics'];
  
  // NOTE: CACHING WILL BE HANDLED BY THE FUNCTION THAT CALLS THIS
  
  $total = $database->database_fetch_assoc($database->database_query("SELECT COUNT(classified_id) AS total FROM se_classifieds"));
  $statistics['classifieds'] = array(
    'title' => 4500145,
    'stat'  => (int) ( isset($total['total']) ? $total['total'] : 0 )
  );
  
  /*
  $total = $database->database_fetch_assoc($database->database_query("SELECT COUNT(classifiedcomment_id) AS total FROM se_classifiedcomments"));
  $statistics['classifiedcomments'] = array(
    'title' => 4500146,
    'stat'  => (int) ( isset($total['total']) ? $total['total'] : 0 )
  );
  
  $total = $database->database_fetch_assoc($database->database_query("SELECT COUNT(classifiedmedia_id) AS total FROM se_classifiedmedia"));
  $statistics['classifiedmedia'] = array(
    'title' => 4500147,
    'stat'  => (int) ( isset($total['total']) ? $total['total'] : 0 )
  );
  */
}

// END site_statistics_classified() FUNCTION

?>