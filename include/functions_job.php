<?php

/* $Id: functions_job.php 7 2009-01-11 06:01:49Z john $ */


//
//  THIS FILE CONTAINS JOB-RELATED FUNCTIONS
//
//  FUNCTIONS IN THIS CLASS:
//
//    search_job()
//    deleteuser_job()
//    site_statistics_job()
//


defined('SE_PAGE') or exit();








//
// THIS FUNCTION IS RUN DURING THE SEARCH PROCESS TO SEARCH THROUGH job ENTRIES
//
// INPUT:
//
// OUTPUT: 
//

function search_job()
{
	global $database, $url, $results_per_page, $p, $search_text, $t, $search_objects, $results, $total_results;
  
  /*
	// GET JOB FIELDS
	$jobfields = $database->database_query("SELECT jobfield_id, jobfield_type, jobfield_options FROM se_jobfields WHERE jobfield_type<>'5'");
	$jobvalue_query = "se_jobs.job_title LIKE '%$search_text%' OR se_jobs.job_body LIKE '%$search_text%'";
  
	// LOOP OVER JOB FIELDS
	while($jobfield_info = $database->database_fetch_assoc($jobfields)) {
    
	  // TEXT FIELD OR TEXTAREA
	  if($jobfield_info[jobfield_type] == 1 | $jobfield_info[jobfield_type] == 2) {
	    if($jobvalue_query != "") { $jobvalue_query .= " OR "; }
	    $jobvalue_query .= "se_jobvalues.jobvalue_".$jobfield_info[jobfield_id]." LIKE '%$search_text%'";

	  // RADIO OR SELECT BOX
	  } elseif($jobfield_info[jobfield_type] == 3 | $jobfield_info[jobfield_type] == 4) {
	    // LOOP OVER FIELD OPTIONS
	    $options = explode("<~!~>", $jobfield_info[jobfield_options]);
	    for($i=0,$max=count($options);$i<$max;$i++) {
	      if(str_replace(" ", "", $options[$i]) != "") {
	        $option = explode("<!>", $options[$i]);
	        $option_id = $option[0];
	        $option_label = $option[1];
	        if(strpos($option_label, $search_text)) {
	          if($jobvalue_query != "") { $jobvalue_query .= " OR "; }
	          $jobvalue_query .= "se_jobvalues.jobvalue_".$jobfield_info[jobfield_id]."='$option_id'";
	        }
	      }
	    }
	  }
	}
  */
  
  /*
  $field = new se_field("job");
  $text_columns = $field->field_index(TRUE);
  
  if( !is_array($text_columns) )
    $text_columns = array();
  */
  
	// CONSTRUCT QUERY
  $sql = "
    SELECT
      se_jobs.job_id,
      se_jobs.job_title,
      se_jobs.job_body,
      se_jobs.job_photo,
      se_users.user_id,
      se_users.user_username,
      se_users.user_photo,
      se_users.user_fname,
      se_users.user_lname
    FROM
      se_jobs
    LEFT JOIN
      se_users
      ON se_jobs.job_user_id=se_users.user_id
    LEFT JOIN
      se_levels
      ON se_users.user_level_id=se_levels.level_id
    LEFT JOIN
      se_jobvalues
      ON se_jobs.job_id=se_jobvalues.jobvalue_job_id
    WHERE
      (se_jobs.job_search=1 || se_levels.level_job_search=0)
  ";
  
  /*
  $sql .= " && (MATCH (`job_title`, `job_body`) AGAINST ('{$search_text}' IN BOOLEAN MODE)";
  
  if( !empty($text_columns) )
    $sql .= " || MATCH (`".join("`, `", $text_columns)."`) AGAINST ('{$search_text}' IN BOOLEAN MODE)";
  
  $sql .= ")";
  */
  
  $text_columns[] = 'job_title';
  $text_columns[] = 'job_body';
  $sql .= " && MATCH (`".join("`, `", $text_columns)."`) AGAINST ('{$search_text}' IN BOOLEAN MODE)";
  
  
	// GET TOTAL ENTRIES
  $sql2 = $sql . " LIMIT 201";
  $resource = $database->database_query($sql2) or die($database->database_error()." <b>SQL was: </b>{$sql2}");
	$total_entries = $database->database_num_rows($resource);

	// IF NOT TOTAL ONLY
	if( $t=="job" )
  {
	  // MAKE JOB PAGES
	  $start = ($p - 1) * $results_per_page;
	  $limit = $results_per_page+1;
    
	  // SEARCH JOBS
    $sql3 = $sql . " ORDER BY job_id DESC LIMIT {$start}, {$limit}";
    $resource = $database->database_query($sql3) or die($database->database_error()." <b>SQL was: </b>{$sql3}");
    
	  while( $job_info=$database->database_fetch_assoc($resource) )
    {
	    // CREATE AN OBJECT FOR AUTHOR
	    $profile = new se_user();
	    $profile->user_info['user_id']        = $job_info['user_id'];
	    $profile->user_info['user_username']  = $job_info['user_username'];
	    $profile->user_info['user_photo']     = $job_info['user_photo'];
	    $profile->user_info['user_fname']     = $job_info['user_fname'];
	    $profile->user_info['user_lname']     = $job_info['user_lname'];
	    $profile->user_displayname();
      
	    // IF EMPTY TITLE
	    if( !trim($job_info['job_title']) )
        $job_info['job_title'] = SE_Language::get(589);
      
      $job_info['job_body'] = cleanHTML($job_info['job_body'], '');
      
	    // IF BODY IS LONG
	    if( strlen($job_info['job_body'])>150 )
        $job_info['job_body'] = substr($job_info['job_body'], 0, 147)."...";
      
	    // SET THUMBNAIL, IF AVAILABLE
      $thumb_path = NULL;
      if( !empty($job_info['job_photo']) )
      {
        $job_dir = se_job::job_dir($job_info['job_id']);
        $job_photo = $job_info['job_photo'];
        $job_thumb = substr($job_photo, 0, strrpos($job_photo, "."))."_thumb".substr($job_photo, strrpos($job_photo, "."));
        
        if( file_exists($job_dir.$job_thumb) )
          $thumb_path = $job_dir.$job_thumb;
        elseif( file_exists($job_dir.$job_photo) )
          $thumb_path = $job_dir.$job_photo;
      }
      
      if( !$thumb_path )
        $thumb_path = "./images/icons/file_big.gif";
      
      
      $result_url = $url->url_create('job', $job_info['user_username'], $job_info['job_id']);
      $result_name = 6400137;
      $result_desc = 6400138;
      
      
	    $results[] = array(
        'result_url'    => $result_url,
				'result_icon'   => $thumb_path,
				'result_name'   => $result_name,
				'result_name_1' => $job_info['job_title'],
				'result_desc'   => $result_desc,
				'result_desc_1' => $url->url_create('profile', $job_info['user_username']),
				'result_desc_2' => $profile->user_displayname,
				'result_desc_3' => $job_info['job_body']
      );
      
      unset($profile);
	  }
    
	  // SET TOTAL RESULTS
	  $total_results = $total_entries;
	}

	// SET ARRAY VALUES
	SE_Language::_preload_multi(6400137, 6400138, 6400139);
	if( $total_entries>200 )
    $total_entries = "200+";
  
	$search_objects[] = array(
    'search_type'   => 'job',
    'search_lang'   => 6400139,
    'search_total'  => $total_entries
  );
}

// END search_job() FUNCTION








//
// THIS FUNCTION IS RUN WHEN A USER IS DELETED
//
// INPUT:
//    $user_id REPRESENTING THE USER ID OF THE USER BEING DELETED
//
// OUTPUT: 
//

function deleteuser_job($user_id)
{
	global $database;

	// DELETE JOB ENTRIES AND COMMENTS AND VALUES
	$database->database_query("DELETE se_jobs.*, se_jobcomments.*, se_jobvalues.* FROM se_jobs LEFT JOIN se_jobcomments ON se_jobcomments.jobcomment_job_id=se_jobs.job_id LEFT JOIN se_jobvalues ON se_jobvalues.jobvalue_job_id=se_jobs.job_id WHERE se_jobs.job_user_id='{$user_id}'");

	// DELETE COMMENTS POSTED BY USER
	$database->database_query("DELETE FROM se_jobcomments WHERE jobcomment_authoruser_id='{$user_id}'");

	// DELETE STYLE
	$database->database_query("DELETE FROM se_jobstyles WHERE jobstyle_user_id='{$user_id}'");
}

// END deleteuser_job() FUNCTION









// THIS FUNCTION IS RUN WHEN GENERATING SITE STATISTICS
// INPUT: 
// OUTPUT: 
function site_statistics_job(&$args)
{
  global $database;
  
  $statistics =& $args['statistics'];
  
  // NOTE: CACHING WILL BE HANDLED BY THE FUNCTION THAT CALLS THIS
  
  $total = $database->database_fetch_assoc($database->database_query("SELECT COUNT(job_id) AS total FROM se_jobs"));
  $statistics['jobs'] = array(
    'title' => 6400145,
    'stat'  => (int) ( isset($total['total']) ? $total['total'] : 0 )
  );
  
  /*
  $total = $database->database_fetch_assoc($database->database_query("SELECT COUNT(jobcomment_id) AS total FROM se_jobcomments"));
  $statistics['jobcomments'] = array(
    'title' => 6400146,
    'stat'  => (int) ( isset($total['total']) ? $total['total'] : 0 )
  );
  
  $total = $database->database_fetch_assoc($database->database_query("SELECT COUNT(jobmedia_id) AS total FROM se_jobmedia"));
  $statistics['jobmedia'] = array(
    'title' => 6400147,
    'stat'  => (int) ( isset($total['total']) ? $total['total'] : 0 )
  );
  */
}

// END site_statistics_job() FUNCTION

?>