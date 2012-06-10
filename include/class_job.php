<?php

/* $Id: class_job.php 16 2009-01-13 04:01:31Z john $ */


//  THIS CLASS CONTAINS job ENTRY-RELATED METHODS 
//  METHODS IN THIS CLASS:
//
//    se_job()
//
//    job_total()
//    job_list()
//
//    job_post()
//    job_delete()
//    job_dir()
//    job_photo()
//    job_photo_upload()
//    job_photo_delete()
//    job_lastupdate()
//    job_media_upload()
//    job_media_space()
//    job_media_total()
//    job_media_list()
//    job_media_delete()


defined('SE_PAGE') or exit();


class se_job
{
	// INITIALIZE VARIABLES
	var $is_error;				            // DETERMINES WHETHER THERE IS AN ERROR OR NOT
	var $error_message;			          // CONTAINS RELEVANT ERROR MESSAGE

	var $user_id;				              // CONTAINS THE USER ID OF THE USER WHOSE JOB WE ARE EDITING

	var $job_exists;			      // DETERMINES WHETHER THE JOB HAS BEEN SET AND EXISTS OR NOT

	var $job_info;			        // CONTAINS THE JOB INFO OF THE JOB WE ARE EDITING
	var $jobvalue_info;	      // CONTAINS THE JOB FIELD VALUE INFO
	var $jobowner_level_info;	// CONTAINS THE JOB CREATOR'S LEVEL INFO

	var $url_string;		              // CONTAINS VARIOUS PARTIAL URL STRINGS (SITUATION DEPENDENT)
  
  
  
  
  
  
  
  
  //
	// THIS METHOD SETS INITIAL VARS
  //
	// INPUT:
  //    $user_id (OPTIONAL) REPRESENTING THE USER ID OF THE USER WHOSE job WE ARE CONCERNED WITH
  //
	// OUTPUT: 
  //
  
	function se_job($user_id=NULL, $job_id=NULL)
  {
	  global $database, $user, $owner;
    
	  $this->user_id = $user_id;
	  $this->job_exists = FALSE;
	  $this->is_member = FALSE;
    
	  if( $job_id )
    {
      $sql = "SELECT * FROM se_jobs WHERE job_id='{$job_id}' LIMIT 1";
      $resource = $database->database_query($sql);
      
	    if( $database->database_num_rows($resource) )
      {
	      $this->job_exists = TRUE;
	      $this->job_info = $database->database_fetch_assoc($resource);
        
        $sql = "SELECT * FROM se_jobvalues WHERE jobvalue_job_id='{$job_id}' LIMIT 1";
        $resource = $database->database_query($sql);
        
        if( $database->database_num_rows($resource) )
          $this->jobvalue_info = $database->database_fetch_assoc($resource);
        
	      // GET LEVEL INFO
	      if( $this->job_info['job_user_id']==$user->user_info['user_id'] )
	        $this->jobowner_level_info =& $user->level_info;
        elseif( $this->job_info['job_user_id']==$owner->user_info['user_id'] )
	        $this->jobowner_level_info =& $owner->level_info;
        
        if( !$this->jobowner_level_info )
        {
          $sql = "SELECT se_levels.* FROM se_users LEFT JOIN se_levels ON se_users.user_level_id=se_levels.level_id WHERE se_users.user_id='{$this->job_info['job_user_id']}' LIMIT 1";
          $resource = $database->database_query($sql);
          
          if( $database->database_num_rows($resource) )
            $this->jobowner_level_info = $database->database_fetch_assoc($resource);
	      }
	    }
	  }
	}
  
  // END se_job() METHOD
  
  
  
  
  
  
  
  
	//
  // THIS METHOD RETURNS THE TOTAL NUMBER OF ENTRIES
	//
  // INPUT:
  //    $where                (OPTIONAL) REPRESENTING ADDITIONAL THINGS TO INCLUDE IN THE WHERE CLAUSE
	//	  $job_details   (OPTIONAL) REPRESENTING WHETHER TO RETRIEVE THE VALUES FROM JOBVALUES TABLE AS WELL
  //
	// OUTPUT:
  //    AN INTEGER REPRESENTING THE NUMBER OF ENTRIES
  //
  
	function job_total($where=NULL, $job_details=FALSE)
  {
	  global $database;
    
	  // BEGIN ENTRY QUERY
	  $sql = "
      SELECT
        NULL
      FROM
        se_jobs
    ";
    
	  // IF NO USER ID SPECIFIED, JOIN TO USER TABLE
	  if( !$this->user_id ) $sql .= "
      LEFT JOIN
        se_users
        ON se_jobs.job_user_id=se_users.user_id
    ";
    
	  // IF JOB DETAILS
	  if( $job_details ) $sql .= "
      LEFT JOIN
        se_jobvalues
        ON se_jobs.job_id=se_jobvalues.jobvalue_job_id
    ";
    
	  // ADD WHERE IF NECESSARY
	  if( !empty($where) || $this->user_id ) $sql .= "
      WHERE
    ";
    
	  // ENSURE USER ID IS NOT EMPTY
	  if( $this->user_id ) $sql .= "
        se_jobs.job_user_id='{$this->user_id}'
    ";
    
	  // INSERT AND IF NECESSARY
	  if( $this->user_id && !empty($where) ) $sql .= " AND ";
    
	  // ADD WHERE CLAUSE, IF NECESSARY
	  if( !empty($where) ) $sql .= "
        $where
    ";
    
	  // GET AND RETURN TOTAL JOB ENTRIES
	  $resource = $database->database_query($sql);
	  $job_total = $database->database_num_rows($resource);
    
	  return $job_total;
	}
  
  // END jobs_total() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD RETURNS AN ARRAY OF JOB ENTRIES
  //
	// INPUT:
  //    $start                REPRESENTING THE ENTRY TO START WITH
	//	  $limit                REPRESENTING THE NUMBER OF ENTRIES TO RETURN
	//	  $sort_by              (OPTIONAL) REPRESENTING THE ORDER BY CLAUSE
	//	  $where                (OPTIONAL) REPRESENTING ADDITIONAL THINGS TO INCLUDE IN THE WHERE CLAUSE
	//	  $job_details   (OPTIONAL) REPRESENTING WHETHER TO RETRIEVE THE VALUES FROM JOBVALUES TABLE AS WELL
  //
	// OUTPUT:
  //    AN ARRAY OF JOB ENTRIES
  //
  
	function job_list($start, $limit, $sort_by="job_date DESC", $where=NULL, $job_details=FALSE)
  {
	  global $database, $user, $owner;
    
	  // BEGIN QUERY
	  $sql = "
      SELECT
        se_jobs.*,
        main_category.jobcat_id AS main_category_id,
        main_category.jobcat_title AS main_category_title,
        parent_category.jobcat_id AS parent_category_id,
        parent_category.jobcat_title AS parent_category_title,
        se_jobs.job_totalcomments AS total_comments
    ";
    
	  // IF NO USER ID SPECIFIED, RETRIEVE USER INFORMATION
	  if( !$this->user_id ) $sql .= ",
        se_users.user_id,
        se_users.user_username,
        se_users.user_photo,
        se_users.user_fname,
        se_users.user_lname
    ";
    
	  // IF JOB DETAILS
    if( $job_details ) $sql .= ",
        se_jobvalues.*
    ";
    
	  // CONTINUE QUERY
	  $sql .= " 
      FROM
        se_jobs
      LEFT JOIN
        se_jobcats AS main_category
        ON main_category.jobcat_id=se_jobs.job_jobcat_id
      LEFT JOIN
        se_jobcats AS parent_category
        ON parent_category.jobcat_id=main_category.jobcat_dependency
    ";
    
	  // IF NO USER ID SPECIFIED, JOIN TO USER TABLE
	  if( !$this->user_id ) $sql .= "
      LEFT JOIN
        se_users
        ON se_jobs.job_user_id=se_users.user_id
    ";
    
	  // IF JOB DETAILS
	  if( $job_details ) $sql .= "
      LEFT JOIN
        se_jobvalues
        ON se_jobs.job_id=se_jobvalues.jobvalue_job_id
    ";
    
	  // ADD WHERE IF NECESSARY
	  if( !empty($where) || $this->user_id ) $sql .= "
      WHERE
    ";
    
	  // ENSURE USER ID IS NOT EMPTY
	  if( $this->user_id ) $sql .= "
        job_user_id='{$this->user_id}'
    ";
    
	  // INSERT AND IF NECESSARY
	  if( $this->user_id && !empty($where) )
      $sql .= " AND";
    
	  // ADD WHERE CLAUSE, IF NECESSARY
	  if( !empty($where) ) $sql .= "
      $where
    ";
    
	  // ADD GROUP BY, ORDER, AND LIMIT CLAUSE
	  $sql .= "
    /*
      GROUP BY
        job_id */
      ORDER BY
        $sort_by
      LIMIT
        $start, $limit
    ";
    
	  // RUN QUERY
	  $resource = $database->database_query($sql);
    
	  // GET JOB ENTRIES INTO AN ARRAY
	  $job_array = array();
	  while( $job_info=$database->database_fetch_assoc($resource) )
    {
	    // CONVERT HTML CHARACTERS BACK
	    $job_info['job_body'] = str_replace("\r\n", "", html_entity_decode($job_info['job_body']));
      
	    // IF NO USER ID SPECIFIED, CREATE OBJECT FOR AUTHOR
	    if( !$this->user_id )
      {
	      $author = new se_user();
	      $author->user_exists = 1;
	      $author->user_info['user_id']       = $job_info['user_id'];
	      $author->user_info['user_username'] = $job_info['user_username'];
	      $author->user_info['user_photo']    = $job_info['user_photo'];
	      $author->user_info['user_fname']    = $job_info['user_fname'];
	      $author->user_info['user_lname']    = $job_info['user_lname'];
	      $author->user_displayname();
      }
      
	    // OTHERWISE, SET AUTHOR TO OWNER/LOGGED-IN USER
	    elseif( $owner->user_exists && $owner->user_info['user_id']==$job_info['job_user_id'] )
      {
	      $author =& $owner;
	    }
      elseif( $user->user_exists && $user->user_info['user_id']==$job_info['job_user_id'] )
      {
	      $author =& $user;
	    }
      else
      {
        $author = new se_user(array($job_info['job_user_id']));
      }
      
	    // GET ENTRY COMMENT PRIVACY
      // FIND A WAY TO MAKE THIS WORK WITH THE AUTHOR
	    $allowed_to_comment = TRUE;
	    if( $owner->user_exists )
      {
	      $comment_level = $owner->user_privacy_max($user);
	      if( !($comment_level & $job_info['job_comments']) )
          $allowed_to_comment = FALSE;
	    }
      
      // PRELOAD CATEGORY TITLE
      if( $job_info['main_category_title'] )
        SE_Language::_preload($job_info['main_category_title']);
        
      if( $job_info['parent_category_title'] )
        SE_Language::_preload($job_info['parent_category_title']);
      
	    // CREATE OBJECT FOR JOB
	    $job = new se_job($job_info['user_id']);
	    $job->job_exists = TRUE;
	    $job->job_info = $job_info;
      
	    // SET JOB ARRAY
	    $job_array[] = array
      (
        'job'                      => &$job,
        'job_author'               => &$author,
        'total_comments'                  => $job_info['total_comments'],
        'allowed_to_comment'              => $allowed_to_comment
      );
      
      unset($author, $job);
	  }
    
	  // RETURN ARRAY
	  return $job_array;
	}
  
  // END jobs_list() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD POSTS/EDITS AN ENTRY
  //
	// INPUT:
  //    $job_id                REPRESENTING THE ID OF THE JOB ENTRY TO EDIT. IF NO ENTRY WITH THIS ID IS FOUND, A NEW ENTRY WILL BE ADDED
	//	  $job_title             REPRESENTING THE TITLE OF THE JOB ENTRY
	//	  $job_body              REPRESENTING THE BODY OF THE JOB ENTRY
	//	  $job_jobcat_id  REPRESENTING THE ID OF THE SELECTED JOB ENTRY CATEGORY
	//	  $job_search            REPRESENTING WHETHER THE JOB ENTRY SHOULD BE INCLUDED IN SEARCH RESULTS
	//	  $job_privacy           REPRESENTING THE PRIVACY LEVEL OF THE ENTRY
	//	  $job_comments          REPRESENTING WHO CAN COMMENT ON THE ENTRY
	//	  $job_field_query       REPRESENTING THE PARTIAL QUERY TO SAVE IN THE JOB'S VALUE TABLE
  //
	// OUTPUT:
  //
  
	function job_post($job_id=NULL, $job_title, $job_body, $job_jobcat_id, $job_search, $job_privacy, $job_comments, $job_field_query)
  {
	  global $database, $user;
    
    // INIT VARS
	  $job_date = time();
	  $job_title = censor($job_title);
	  $job_body = censor(htmlspecialchars_decode($job_body));
	  $job_body = cleanHTML($job_body, $user->level_info['level_job_html']);
	  $job_body = security($job_body);
    
    
    // UPDATE TABLE ROW
    if( $job_id )
    {
      $sql = "
        UPDATE
          se_jobs
        SET
          job_jobcat_id='{$job_jobcat_id}',
          job_dateupdated='{$job_date}',
          job_title='{$job_title}',
          job_body='{$job_body}',
          job_search='{$job_search}',
          job_privacy='{$job_privacy}',
          job_comments='{$job_comments}'
        WHERE
          job_id='{$job_id}' &&
          job_user_id='{$this->user_id}'
        LIMIT
          1
      ";
      
      $database->database_query($sql);
    }
    
    // ADD TABLE ROW
    else
    {
      $sql = "
        INSERT INTO se_jobs (
          job_user_id,
          job_jobcat_id,
          job_date,
          job_dateupdated,
          job_title,
          job_body,
          job_search,
          job_privacy,
          job_comments
        ) VALUES (
          '{$this->user_id}',
          '{$job_jobcat_id}',
          '{$job_date}',
          '{$job_date}',
          '{$job_title}',
          '{$job_body}',
          '{$job_search}',
          '{$job_privacy}',
          '{$job_comments}'
        )
      ";
      
      $database->database_query($sql);
      $job_id = $database->database_insert_id();
      
      // ADD JOB FIELD VALUE ROW
      $sql = "INSERT INTO se_jobvalues (jobvalue_job_id) VALUES ('{$job_id}')";
      $database->database_query($sql);
      
      // ADD JOB ALBUM
      $sql = "
        INSERT INTO se_jobalbums (
          jobalbum_job_id,
          jobalbum_datecreated,
          jobalbum_dateupdated,
          jobalbum_title,
          jobalbum_desc,
          jobalbum_search,
          jobalbum_privacy,
          jobalbum_comments
        ) VALUES (
          '{$job_id}',
          '{$job_date}',
          '{$job_date}',
          '',
          '',
          '{$job_search}',
          '{$job_privacy}',
          '{$job_comments}'
        )
      ";
      $database->database_query($sql);
    }
    
    // UPDATE JOB FIELD VALUES
    if( !empty($job_field_query) )
    {
      $sql = "UPDATE se_jobvalues SET {$job_field_query} WHERE jobvalue_job_id='{$job_id}' LIMIT 1";
      $database->database_query($sql);
    }
    
    // CHECK AND ADD JOB DIRECTORY
    $job_directory = $this->job_dir($job_id);
    $job_path_array = explode("/", $job_directory);
    array_pop($job_path_array);
    array_pop($job_path_array);
    $subdir = implode("/", $job_path_array)."/";
    
    if( !is_dir($subdir) )
    { 
      mkdir($subdir, 0777); 
      chmod($subdir, 0777); 
      $handle = fopen($subdir."index.php", 'x+');
      fclose($handle);
    }
    
    if( !is_dir($job_directory) )
    {
      mkdir($job_directory, 0777);
      chmod($job_directory, 0777);
      $handle = fopen($job_directory."/index.php", 'x+');
      fclose($handle);
    }
    
	  return $job_id;
	}
  
  // END job_post() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD DELETES JOB ENTRIES
  //
	// INPUT:
  //    $job_id  REPRESENTING THE ID OF THE ENTRY TO DELETE
  //
	// OUTPUT:
  //
  
	function job_delete($job_id=NULL)
  {
	  global $database;
    
    // IF EMPTY, TRY TO GET FROM OBJECT
	  if( !$job_id && !$this->job_exists )
      return FALSE;
    elseif( !$job_id )
      $job_id = $this->job_info['job_id'];
    
    // IF ARRAY
    if( is_array($job_id) )
      return array_map(array(&$this, 'job_delete'), $job_id);
    
	  // DELETE JOB ALBUM AND MEDIA
    $sql = "DELETE FROM se_jobalbums, se_jobmedia USING se_jobalbums LEFT JOIN se_jobmedia ON se_jobalbums.jobalbum_id=se_jobmedia.jobmedia_jobalbum_id WHERE se_jobalbums.jobalbum_job_id='{$job_id}'";
	  $database->database_query($sql);
    
	  // DELETE JOB VALUES
	  $sql = "DELETE FROM se_jobvalues WHERE se_jobvalues.jobvalue_job_id='{$job_id}' LIMIT 1";
    $database->database_query($sql);
    
	  // DELETE JOB ROW
	  $sql = "DELETE FROM se_jobs WHERE se_jobs.job_id='{$job_id}' LIMIT 1";
    $database->database_query($sql);
    
	  // DELETE JOB COMMENTS
	  $sql = "DELETE FROM se_jobcomments WHERE se_jobcomments.jobcomment_job_id='{$job_id}'";
    $database->database_query($sql);
    
	  // DELETE JOB'S FILES
	  if( is_dir($this->job_dir($job_id)) )
	    $dir = $this->job_dir($job_id);
	  else
	    $dir = ".".$this->job_dir($job_id);
    
	  if( $dh = @opendir($dir) )
    {
	    while( ($file = @readdir($dh))!==FALSE )
	      if($file != "." & $file != "..")
	        @unlink($dir.$file);
      
	    @closedir($dh);
	  }
	  @rmdir($dir);
    
    return TRUE;
	}
  
  // END job_delete() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD RETURNS THE PATH TO THE GIVEN JOB'S DIRECTORY
  //
	// INPUT:
  //    $job_id (OPTIONAL) REPRESENTING A JOB'S JOB
  //
	// OUTPUT:
  //    A STRING REPRESENTING THE RELATIVE PATH TO THE JOB'S DIRECTORY
  //
  
	function job_dir($job_id=NULL)
  {
	  if( !$job_id && $this->job_exists )
      $job_id = $this->job_info['job_id'];
    
	  $subdir = $job_id+999-(($job_id-1)%1000);
	  $jobdir = "./uploads_job/$subdir/$job_id/";
	  return $jobdir;
	}
  
  // END job_dir() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD OUTPUTS THE PATH TO THE JOB'S PHOTO OR THE GIVEN NOPHOTO IMAGE
  //
	// INPUT:
  //    $nophoto_image (OPTIONAL) REPRESENTING THE PATH TO AN IMAGE TO OUTPUT IF NO PHOTO EXISTS
  //
	// OUTPUT:
  //    A STRING CONTAINING THE PATH TO THE JOB'S PHOTO
  //
  
	function job_photo($nophoto_image=NULL, $thumb=FALSE)
  {
    if( empty($this->job_info['job_photo']) )
      return $nophoto_image;
    
	  $job_dir = $this->job_dir($this->job_info['job_id']);
	  $job_photo = $job_dir.$this->job_info['job_photo'];
    if( $thumb )
    {
      $job_thumb = substr($job_photo, 0, strrpos($job_photo, "."))."_thumb".substr($job_photo, strrpos($job_photo, "."));
      if( file_exists($job_thumb) )
        return $job_thumb;
    }
    
    if( file_exists($job_photo) )
      return $job_photo;
    
    return $nophoto_image;
	}
  
  // END job_photo() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD UPLOADS AN JOB PHOTO ACCORDING TO SPECIFICATIONS AND RETURNS JOB PHOTO
  //
	// INPUT:
  //    $photo_name REPRESENTING THE NAME OF THE FILE INPUT
  //
	// OUTPUT:
  //
  
	function job_photo_upload($photo_name)
  {
	  global $database, $url;
    
	  // SET KEY VARIABLES
	  $file_maxsize = "4194304";
	  $file_exts = explode(",", str_replace(" ", "", strtolower($this->jobowner_level_info['level_job_photo_exts'])));
	  $file_types = explode(",", str_replace(" ", "", strtolower("image/jpeg, image/jpg, image/jpe, image/pjpeg, image/pjpg, image/x-jpeg, x-jpg, image/gif, image/x-gif, image/png, image/x-png")));
	  $file_maxwidth = $this->jobowner_level_info['level_job_photo_width'];
	  $file_maxheight = $this->jobowner_level_info['level_job_photo_height'];
	  $photo_newname = "0_".rand(1000, 9999).".jpg";
	  $file_dest = $this->job_dir($this->job_info['job_id']).$photo_newname;
	  $thumb_dest = substr($file_dest, 0, strrpos($file_dest, "."))."_thumb".substr($file_dest, strrpos($file_dest, "."));
    
	  $new_photo = new se_upload();
	  $new_photo->new_upload($photo_name, $file_maxsize, $file_exts, $file_types, $file_maxwidth, $file_maxheight);
    
	  // UPLOAD AND RESIZE PHOTO IF NO ERROR
	  if( !$new_photo->is_error )
    {
	    // DELETE OLD AVATAR IF EXISTS
	    $this->job_photo_delete();
      
	    // UPLOAD THUMB
	    $new_photo->upload_thumb($thumb_dest, 200);
      
	    // CHECK IF IMAGE RESIZING IS AVAILABLE, OTHERWISE MOVE UPLOADED IMAGE
	    if( $new_photo->is_image )
	      $new_photo->upload_photo($file_dest);
	    else
	      $new_photo->upload_file($file_dest);
      
	    // UPDATE job INFO WITH IMAGE IF STILL NO ERROR
	    if( !$new_photo->is_error )
      {
        $sql = "UPDATE se_jobs SET job_photo='{$photo_newname}' WHERE job_id='{$this->job_info['job_id']}'";
	      $database->database_query($sql);
	      $this->job_info['job_photo'] = $photo_newname;
	    }
	  }
    
	  $this->is_error = $new_photo->is_error;
	  $this->error_message = $new_photo->error_message;
	}
  
  // END job_photo_upload() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD DELETES A JOB PHOTO
  //
	// INPUT: 
  //
	// OUTPUT: 
  //
  
	function job_photo_delete()
  {
	  global $database;
    
	  $job_photo = $this->job_photo();
    
	  if( !empty($job_photo) )
    {
	    $sql = "UPDATE se_jobs SET job_photo='' WHERE job_id='{$this->job_info['job_id']}' LIMIT 1";
	    $database->database_query($sql);
	    $this->job_info['job_photo'] = "";
	    @unlink($job_photo);
	  }
	}
  
  // END job_photo_delete() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD UPDATES THE JOB'S LAST UPDATE DATE
  //
	// INPUT: 
  //
	// OUTPUT: 
  //
  
	function job_lastupdate()
  {
	  global $database;
    $sql = "UPDATE se_jobs SET job_dateupdated='".time()."' WHERE job_id='{$this->job_info['job_id']}' LIMIT 1";
	  $database->database_query($sql);
	}
  
  // END job_lastupdate() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD UPLOADS MEDIA TO A JOB ALBUM
  //
	// INPUT:
  //    $file_name          REPRESENTING THE NAME OF THE FILE INPUT
	//	  $jobalbum_id REPRESENTING THE ID OF THE JOB ALBUM TO UPLOAD THE MEDIA TO
	//	  $space_left         REPRESENTING THE AMOUNT OF SPACE LEFT
  //
	// OUTPUT:
  //
  
	function job_media_upload($file_name, $jobalbum_id, &$space_left)
  {
	  global $class_job, $database, $url;
    
	  // SET KEY VARIABLES
	  $file_maxsize   = $this->jobowner_level_info['level_job_album_maxsize'];
	  $file_exts      = explode(",", str_replace(" ", "", strtolower($this->jobowner_level_info['level_job_album_exts'])));
	  $file_types     = explode(",", str_replace(" ", "", strtolower($this->jobowner_level_info['level_job_album_mimes'])));
	  $file_maxwidth  = $this->jobowner_level_info['level_job_album_width'];
	  $file_maxheight = $this->jobowner_level_info['level_job_album_height'];
    
	  $new_media = new se_upload();
	  $new_media->new_upload($file_name, $file_maxsize, $file_exts, $file_types, $file_maxwidth, $file_maxheight);
    
	  // UPLOAD AND RESIZE PHOTO IF NO ERROR
	  if( !$new_media->is_error )
    {
	    // INSERT ROW INTO MEDIA TABLE
      $sql = "
        INSERT INTO se_jobmedia
          (jobmedia_jobalbum_id, jobmedia_date)
        VALUES
          ('{$jobalbum_id}', '".time()."')
      ";
      
      $database->database_query($sql);
	    $jobmedia_id = $database->database_insert_id();
      
	    // CHECK IF IMAGE RESIZING IS AVAILABLE, OTHERWISE MOVE UPLOADED IMAGE
      $job_dir = $this->job_dir($this->job_info['job_id']);
	    if( $new_media->is_image )
      {
	      $file_dest  = "{$job_dir}{$jobmedia_id}.jpg";
	      $thumb_dest = "{$job_dir}{$jobmedia_id}_thumb.jpg";
        
	      // UPLOAD THUMB
	      $new_media->upload_thumb($thumb_dest, 200);
        
	      // UPLOAD FILE
	      $new_media->upload_photo($file_dest);
	      $file_ext = "jpg";
	      $file_filesize = filesize($file_dest);
	    }
      
      else
      {
	      $file_dest  = "{$job_dir}{$jobmedia_id}.{$new_media->file_ext}";
	      $thumb_dest = "{$job_dir}{$jobmedia_id}_thumb.jpg";
        
	      if( $new_media->file_ext=='gif' )
	        $new_media->upload_thumb($thumb_dest, 200);
        
	      $new_media->upload_file($file_dest);
	      $file_ext = $new_media->file_ext;
	      $file_filesize = filesize($file_dest);
	    }
      
      // CHECK SPACE LEFT
      if( $space_left!==FALSE && $file_filesize > $space_left)
      {
        $new_media->is_error = 1;
        $new_media->error_message = $class_job[1].$_FILES[$file_name]['name']; // TODO LANG
      }
      elseif( $space_left!==FALSE )
      {
	      $space_left = $space_left - $file_filesize;
	    }
      
	    // DELETE FROM DATABASE IF ERROR
	    if( $new_media->is_error )
      {
        $sql = "DELETE FROM se_jobmedia WHERE jobmedia_id='{$jobmedia_id}' AND jobmedia_jobalbum_id='{$jobalbum_id}' LIMIT 1";
	      $database->database_query($sql);
	      @unlink($file_dest);
	    }
      
	    // UPDATE ROW IF NO ERROR
      else
      {
	      $sql = "UPDATE se_jobmedia SET jobmedia_ext='{$file_ext}', jobmedia_filesize='{$file_filesize}' WHERE jobmedia_id='{$jobmedia_id}' AND jobmedia_jobalbum_id='{$jobalbum_id}' LIMIT 1";
        $database->database_query($sql);
        
        // Update parent row
        $sql = "UPDATE se_jobalbums SET jobalbum_totalfiles=jobalbum_totalfiles+1, jobalbum_totalspace=jobalbum_totalspace+'{$file_filesize}' WHERE jobalbum_id='{$jobalbum_id}' LIMIT 1";
        $database->database_query($sql);
	    }
	  }
    
	  // RETURN FILE STATS
	  return array(
      'is_error'                  => $new_media->is_error,
			'error_message'             => $new_media->error_message,
			'jobmedia_id'        => $jobmedia_id,
			'jobmedia_ext'       => $file_ext,
			'jobmedia_filesize'  => $file_filesize
    );
	}
  
  // END job_media_upload() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD RETURNS THE SPACE USED
  //
	// INPUT:
  //    $jobalbum_id (OPTIONAL) REPRESENTING THE ID OF THE ALBUM TO CALCULATE
  //
	// OUTPUT:
  //    AN INTEGER REPRESENTING THE SPACE USED
  //
  
	function job_media_space($jobalbum_id=NULL)
  {
	  global $database;
    
    // NEW HANDLING METHOD
    if( !$jobalbum_id )
    {
      $sql = "
        SELECT
          se_jobalbums.jobalbum_totalspace AS total_space
        FROM
          se_jobalbums
        WHERE
          se_jobalbums.jobalbum_id='{$jobalbum_id}'
        LIMIT
          1
      ";
      
      $resource = $database->database_query($sql);
      
      if( $resource )
      {
        $space_info = $database->database_fetch_assoc($resource);
        return $space_info['total_space'];
      }
    }
    
    // OLD HANDLING METHOD - BACKWARDS COMPATIBILITY
    
	  // BEGIN QUERY
	  $sql = "
      SELECT
        SUM(se_jobmedia.jobmedia_filesize) AS total_space
    ";
    
	  // CONTINUE QUERY
	  $sql .= "
      FROM
        se_jobalbums
      LEFT JOIN
        se_jobmedia
        ON se_jobalbums.jobalbum_id=se_jobmedia.jobmedia_jobalbum_id
    ";
    
	  // ADD WHERE IF NECESSARY
	  if( $this->job_exists || $jobalbum_id ) $sql .= "
      WHERE
    ";
    
	  // IF JOB EXISTS, SPECIFY JOB ID
	  if( $this->job_exists ) $sql .= "
        se_jobalbums.jobalbum_job_id='{$this->job_info['job_id']}'
    ";
    
	  // ADD AND IF NECESSARY
	  if( $this->job_exists && $jobalbum_id )
      $sql .= " AND";
    
	  // SPECIFY ALBUM ID IF NECESSARY
	  if( $jobalbum_id ) $sql .= "
        se_jobalbums.jobalbum_id='{$jobalbum_id}'
    ";
    
	  // GET AND RETURN TOTAL SPACE USED
    $resource = $database->database_query($sql);
	  $space_info = $database->database_fetch_assoc($resource);
	  return $space_info['total_space'];
	}
  
  // END job_media_space() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD RETURNS THE NUMBER OF JOB MEDIA
  //
	// INPUT:
  //    $jobalbum_id (OPTIONAL) REPRESENTING THE ID OF THE claJOBssified ALBUM TO CALCULATE
  //
	// OUTPUT:
  //    AN INTEGER REPRESENTING THE NUMBER OF FILES
  //
  
	function job_media_total($jobalbum_id=NULL)
  {
	  global $database;
    
    // NEW HANDLING METHOD
    if( !$jobalbum_id )
    {
      $sql = "
        SELECT
          se_jobalbums.jobalbum_totalfiles AS total_files
        FROM
          se_jobalbums
        WHERE
          se_jobalbums.jobalbum_id='{$jobalbum_id}'
        LIMIT
          1
      ";
      
      $resource = $database->database_query($sql);
      
      if( $resource )
      {
        $file_info = $database->database_fetch_assoc($resource);
        return $file_info['total_files'];
      }
    }
    
    // OLD HANDLING METHOD - BACKWARDS COMPATIBILITY
    
	  // BEGIN QUERY
	  $sql = "
      SELECT
        COUNT(se_jobmedia.jobmedia_id) AS total_files
    ";
    
	  // CONTINUE QUERY
	  $sql .= "
      FROM
        se_jobalbums
      LEFT JOIN
        se_jobmedia
        ON se_jobalbums.jobalbum_id=se_jobmedia.jobmedia_jobalbum_id
    ";
    
	  // ADD WHERE IF NECESSARY
	  if( $this->job_exists || $jobalbum_id ) $sql .= "
      WHERE
    ";
    
	  // IF job EXISTS, SPECIFY job ID
	  if( $this->job_exists ) $sql .= "
        se_jobalbums.jobalbum_job_id='{$this->job_info['job_id']}'
    ";
    
	  // ADD AND IF NECESSARY
	  if( $this->job_exists && $jobalbum_id )
      $sql .= " AND";
    
	  // SPECIFY ALBUM ID IF NECESSARY
	  if( $jobalbum_id ) $sql .= "
        se_jobalbums.jobalbum_id='{$jobalbum_id}'
    ";
    
	  // GET AND RETURN TOTAL FILES
    $resource = $database->database_query($sql);
	  $file_info = $database->database_fetch_assoc($resource);
	  return $file_info['total_files'];
	}
  
  // END job_media_total() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD RETURNS AN ARRAY OF JOB MEDIA
  //
	// INPUT:
  //    $start REPRESENTING THE job MEDIA TO START WITH
	//	  $limit REPRESENTING THE NUMBER OF job MEDIA TO RETURN
	//	  $sort_by (OPTIONAL) REPRESENTING THE ORDER BY CLAUSE
	//	  $where (OPTIONAL) REPRESENTING ADDITIONAL THINGS TO INCLUDE IN THE WHERE CLAUSE
  //
	// OUTPUT:
  //    AN ARRAY OF job MEDIA
  //
  
	function job_media_list($start, $limit, $sort_by = "jobmedia_id DESC", $where=NULL, $file_details=FALSE)
  {
	  global $database;
    
    if( !$start ) $start = '0';
    
	  // BEGIN QUERY
	  $sql = "
      SELECT
        se_jobmedia.*,
        se_jobalbums.jobalbum_id,
        se_jobalbums.jobalbum_job_id,
        se_jobalbums.jobalbum_title
    ";
    
	  // CONTINUE QUERY
	  $sql .= "
      FROM
        se_jobmedia
      LEFT JOIN
        se_jobalbums
        ON se_jobalbums.jobalbum_id=se_jobmedia.jobmedia_jobalbum_id
    ";
    
	  // ADD WHERE IF NECESSARY
	  if( $this->job_exists || !empty($where) ) $sql .= "
      WHERE
    ";
    
	  // IF job EXISTS, SPECIFY job ID
	  if( $this->job_exists ) $sql .= "
        se_jobalbums.jobalbum_job_id='{$this->job_info['job_id']}'
    ";
    
	  // ADD AND IF NECESSARY
	  if( $this->job_exists && !empty($where) )
      $sql .= " AND";
    
	  // ADD ADDITIONAL WHERE CLAUSE
	  if( !empty($where) ) $sql .= "
        $where
    ";
    
	  // ADD GROUP BY, ORDER, AND LIMIT CLAUSE
	  $sql .= "
    /*
      GROUP BY
        jobmedia_id */
      ORDER BY
        $sort_by
      LIMIT
        $start, $limit
    ";
    
	  // RUN QUERY
    $resource = $database->database_query($sql);
    
	  // GET job MEDIA INTO AN ARRAY
    $job_dir = $this->job_dir($this->job_info['job_id']);
	  $jobmedia_array = array();
	  while( $jobmedia_info=$database->database_fetch_assoc($resource) )
    {
      $jobmedia_info['jobmedia_desc'] = str_replace("<br />", "\r\n", $jobmedia_info['jobmedia_desc']);
      
      if( $file_details )
      {
        $mediasize = getimagesize($job_dir.$jobmedia_info['jobmedia_id'].'.'.$jobmedia_info['jobmedia_ext']);
        $jobmedia_info['jobmedia_dir']  = $job_dir;
        $jobmedia_info['jobmedia_width']  = $mediasize[0];
        $jobmedia_info['jobmedia_height'] = $mediasize[1];
        
      }
      
	    $jobmedia_array[] = $jobmedia_info;
	  }
    
	  // RETURN ARRAY
	  return $jobmedia_array;
	}
  
  // END job_media_list() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD DELETES SELECTED JOB MEDIA
  //
	// INPUT:
  //    $start    REPRESENTING THE JOB MEDIA TO START WITH
	//	  $limit    REPRESENTING THE NUMBER OF job MEDIA TO RETURN
	//	  $sort_by  (OPTIONAL) REPRESENTING THE ORDER BY CLAUSE
	//	  $where    (OPTIONAL) REPRESENTING ADDITIONAL THINGS TO INCLUDE IN THE WHERE CLAUSE
  //
	// OUTPUT:
  //
  
	function job_media_delete($start, $limit, $sort_by = "jobmedia_id DESC", $where = "")
  {
	  global $database, $url;
    
	  // BEGIN QUERY
	  $jobmedia_query = "SELECT se_jobmedia.*, se_jobalbums.jobalbum_id, se_jobalbums.jobalbum_job_id, se_jobalbums.jobalbum_title";
    
	  // CONTINUE QUERY
	  $jobmedia_query .= " FROM se_jobmedia LEFT JOIN se_jobalbums ON se_jobalbums.jobalbum_id=se_jobmedia.jobmedia_jobalbum_id";
    
	  // ADD WHERE IF NECESSARY
	  if($this->job_exists != 0 | $where != "") { $jobmedia_query .= " WHERE"; }
    
	  // IF job EXISTS, SPECIFY job ID
	  if($this->job_exists != 0) { $jobmedia_query .= " se_jobalbums.jobalbum_job_id='{$this->job_info['job_id']}'"; }
    
	  // ADD AND IF NECESSARY
	  if($this->job_exists != 0 & $where != "") { $jobmedia_query .= " AND"; }
    
	  // ADD ADDITIONAL WHERE CLAUSE
	  if($where != "") { $jobmedia_query .= " $where"; }
    
	  // ADD GROUP BY, ORDER, AND LIMIT CLAUSE
	  $jobmedia_query .= " GROUP BY se_jobmedia.jobmedia_id ORDER BY $sort_by LIMIT $start, $limit";
    
	  // RUN QUERY
	  $jobmedia = $database->database_query($jobmedia_query);
    
	  // LOOP OVER job MEDIA
	  $jobmedia_delete = "";
	  while($jobmedia_info = $database->database_fetch_assoc($jobmedia))
    {
	    $var = "delete_jobmedia_".$jobmedia_info['jobmedia_id']; 
	    if($jobmedia_delete != "") { $jobmedia_delete .= " OR "; }
	    $jobmedia_delete .= "jobmedia_id='$jobmedia_info[jobmedia_id]'";
	    $jobmedia_path = $this->job_dir($this->job_info['job_id']).$jobmedia_info['jobmedia_id'].".".$jobmedia_info['jobmedia_ext'];
	    if(file_exists($jobmedia_path)) { @unlink($jobmedia_path); }
	    $thumb_path = $this->job_dir($this->job_info['job_id']).$jobmedia_info['jobmedia_id']."_thumb.".$jobmedia_info['jobmedia_ext'];
	    if(file_exists($thumb_path)) { @unlink($thumb_path); }
      
      // Update parent row
      $sql = "UPDATE se_jobalbums SET jobalbum_totalfiles=jobalbum_totalfiles-1, jobalbum_totalspace=jobalbum_totalspace-'{$jobmedia_info['jobmedia_filesize']}' WHERE jobalbum_id='{$jobmedia_info['jobmedia_jobalbum_id']}' LIMIT 1";
      $database->database_query($sql);
	  }
    
	  // IF DELETE CLAUSE IS NOT EMPTY, DELETE job MEDIA
	  if($jobmedia_delete != "") { $database->database_query("DELETE FROM se_jobmedia WHERE ($jobmedia_delete)"); }
	}
  
  // END job_media_delete() METHOD
}

?>