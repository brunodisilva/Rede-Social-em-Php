<?php

/* $Id: class_classified.php 16 2009-01-13 04:01:31Z john $ */


//  THIS CLASS CONTAINS classified ENTRY-RELATED METHODS 
//  METHODS IN THIS CLASS:
//
//    se_classified()
//
//    classified_total()
//    classified_list()
//
//    classified_post()
//    classified_delete()
//    classified_dir()
//    classified_photo()
//    classified_photo_upload()
//    classified_photo_delete()
//    classified_lastupdate()
//    classified_media_upload()
//    classified_media_space()
//    classified_media_total()
//    classified_media_list()
//    classified_media_delete()


defined('SE_PAGE') or exit();


class se_classified
{
	// INITIALIZE VARIABLES
	var $is_error;				            // DETERMINES WHETHER THERE IS AN ERROR OR NOT
	var $error_message;			          // CONTAINS RELEVANT ERROR MESSAGE

	var $user_id;				              // CONTAINS THE USER ID OF THE USER WHOSE CLASSIFIED WE ARE EDITING

	var $classified_exists;			      // DETERMINES WHETHER THE CLASSIFIED HAS BEEN SET AND EXISTS OR NOT

	var $classified_info;			        // CONTAINS THE CLASSIFIED INFO OF THE CLASSIFIED WE ARE EDITING
	var $classifiedvalue_info;	      // CONTAINS THE CLASSIFIED FIELD VALUE INFO
	var $classifiedowner_level_info;	// CONTAINS THE CLASSIFIED CREATOR'S LEVEL INFO

	var $url_string;		              // CONTAINS VARIOUS PARTIAL URL STRINGS (SITUATION DEPENDENT)
  
  
  
  
  
  
  
  
  //
	// THIS METHOD SETS INITIAL VARS
  //
	// INPUT:
  //    $user_id (OPTIONAL) REPRESENTING THE USER ID OF THE USER WHOSE classified WE ARE CONCERNED WITH
  //
	// OUTPUT: 
  //
  
	function se_classified($user_id=NULL, $classified_id=NULL)
  {
	  global $database, $user, $owner;
    
	  $this->user_id = $user_id;
	  $this->classified_exists = FALSE;
	  $this->is_member = FALSE;
    
	  if( $classified_id )
    {
      $sql = "SELECT * FROM se_classifieds WHERE classified_id='{$classified_id}' LIMIT 1";
      $resource = $database->database_query($sql);
      
	    if( $database->database_num_rows($resource) )
      {
	      $this->classified_exists = TRUE;
	      $this->classified_info = $database->database_fetch_assoc($resource);
        
        $sql = "SELECT * FROM se_classifiedvalues WHERE classifiedvalue_classified_id='{$classified_id}' LIMIT 1";
        $resource = $database->database_query($sql);
        
        if( $database->database_num_rows($resource) )
          $this->classifiedvalue_info = $database->database_fetch_assoc($resource);
        
	      // GET LEVEL INFO
	      if( $this->classified_info['classified_user_id']==$user->user_info['user_id'] )
	        $this->classifiedowner_level_info =& $user->level_info;
        elseif( $this->classified_info['classified_user_id']==$owner->user_info['user_id'] )
	        $this->classifiedowner_level_info =& $owner->level_info;
        
        if( !$this->classifiedowner_level_info )
        {
          $sql = "SELECT se_levels.* FROM se_users LEFT JOIN se_levels ON se_users.user_level_id=se_levels.level_id WHERE se_users.user_id='{$this->classified_info['classified_user_id']}' LIMIT 1";
          $resource = $database->database_query($sql);
          
          if( $database->database_num_rows($resource) )
            $this->classifiedowner_level_info = $database->database_fetch_assoc($resource);
	      }
	    }
	  }
	}
  
  // END se_classified() METHOD
  
  
  
  
  
  
  
  
	//
  // THIS METHOD RETURNS THE TOTAL NUMBER OF ENTRIES
	//
  // INPUT:
  //    $where                (OPTIONAL) REPRESENTING ADDITIONAL THINGS TO INCLUDE IN THE WHERE CLAUSE
	//	  $classified_details   (OPTIONAL) REPRESENTING WHETHER TO RETRIEVE THE VALUES FROM CLASSIFIEDVALUES TABLE AS WELL
  //
	// OUTPUT:
  //    AN INTEGER REPRESENTING THE NUMBER OF ENTRIES
  //
  
	function classified_total($where=NULL, $classified_details=FALSE)
  {
	  global $database;
    
	  // BEGIN ENTRY QUERY
	  $sql = "
      SELECT
        NULL
      FROM
        se_classifieds
    ";
    
	  // IF NO USER ID SPECIFIED, JOIN TO USER TABLE
	  if( !$this->user_id ) $sql .= "
      LEFT JOIN
        se_users
        ON se_classifieds.classified_user_id=se_users.user_id
    ";
    
	  // IF CLASSIFIED DETAILS
	  if( $classified_details ) $sql .= "
      LEFT JOIN
        se_classifiedvalues
        ON se_classifieds.classified_id=se_classifiedvalues.classifiedvalue_classified_id
    ";
    
	  // ADD WHERE IF NECESSARY
	  if( !empty($where) || $this->user_id ) $sql .= "
      WHERE
    ";
    
	  // ENSURE USER ID IS NOT EMPTY
	  if( $this->user_id ) $sql .= "
        se_classifieds.classified_user_id='{$this->user_id}'
    ";
    
	  // INSERT AND IF NECESSARY
	  if( $this->user_id && !empty($where) ) $sql .= " AND ";
    
	  // ADD WHERE CLAUSE, IF NECESSARY
	  if( !empty($where) ) $sql .= "
        $where
    ";
    
	  // GET AND RETURN TOTAL CLASSIFIED ENTRIES
	  $resource = $database->database_query($sql);
	  $classified_total = $database->database_num_rows($resource);
    
	  return $classified_total;
	}
  
  // END classifieds_total() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD RETURNS AN ARRAY OF CLASSIFIED ENTRIES
  //
	// INPUT:
  //    $start                REPRESENTING THE ENTRY TO START WITH
	//	  $limit                REPRESENTING THE NUMBER OF ENTRIES TO RETURN
	//	  $sort_by              (OPTIONAL) REPRESENTING THE ORDER BY CLAUSE
	//	  $where                (OPTIONAL) REPRESENTING ADDITIONAL THINGS TO INCLUDE IN THE WHERE CLAUSE
	//	  $classified_details   (OPTIONAL) REPRESENTING WHETHER TO RETRIEVE THE VALUES FROM CLASSIFIEDVALUES TABLE AS WELL
  //
	// OUTPUT:
  //    AN ARRAY OF CLASSIFIED ENTRIES
  //
  
	function classified_list($start, $limit, $sort_by="classified_date DESC", $where=NULL, $classified_details=FALSE)
  {
	  global $database, $user, $owner;
    
	  // BEGIN QUERY
	  $sql = "
      SELECT
        se_classifieds.*,
        main_category.classifiedcat_id AS main_category_id,
        main_category.classifiedcat_title AS main_category_title,
        parent_category.classifiedcat_id AS parent_category_id,
        parent_category.classifiedcat_title AS parent_category_title,
        se_classifieds.classified_totalcomments AS total_comments
    ";
    
	  // IF NO USER ID SPECIFIED, RETRIEVE USER INFORMATION
	  if( !$this->user_id ) $sql .= ",
        se_users.user_id,
        se_users.user_username,
        se_users.user_photo,
        se_users.user_fname,
        se_users.user_lname
    ";
    
	  // IF CLASSIFIED DETAILS
    if( $classified_details ) $sql .= ",
        se_classifiedvalues.*
    ";
    
	  // CONTINUE QUERY
	  $sql .= " 
      FROM
        se_classifieds
      LEFT JOIN
        se_classifiedcats AS main_category
        ON main_category.classifiedcat_id=se_classifieds.classified_classifiedcat_id
      LEFT JOIN
        se_classifiedcats AS parent_category
        ON parent_category.classifiedcat_id=main_category.classifiedcat_dependency
    ";
    
	  // IF NO USER ID SPECIFIED, JOIN TO USER TABLE
	  if( !$this->user_id ) $sql .= "
      LEFT JOIN
        se_users
        ON se_classifieds.classified_user_id=se_users.user_id
    ";
    
	  // IF CLASSIFIED DETAILS
	  if( $classified_details ) $sql .= "
      LEFT JOIN
        se_classifiedvalues
        ON se_classifieds.classified_id=se_classifiedvalues.classifiedvalue_classified_id
    ";
    
	  // ADD WHERE IF NECESSARY
	  if( !empty($where) || $this->user_id ) $sql .= "
      WHERE
    ";
    
	  // ENSURE USER ID IS NOT EMPTY
	  if( $this->user_id ) $sql .= "
        classified_user_id='{$this->user_id}'
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
        classified_id */
      ORDER BY
        $sort_by
      LIMIT
        $start, $limit
    ";
    
	  // RUN QUERY
	  $resource = $database->database_query($sql);
    
	  // GET CLASSIFIED ENTRIES INTO AN ARRAY
	  $classified_array = array();
	  while( $classified_info=$database->database_fetch_assoc($resource) )
    {
	    // CONVERT HTML CHARACTERS BACK
	    $classified_info['classified_body'] = str_replace("\r\n", "", html_entity_decode($classified_info['classified_body']));
      
	    // IF NO USER ID SPECIFIED, CREATE OBJECT FOR AUTHOR
	    if( !$this->user_id )
      {
	      $author = new se_user();
	      $author->user_exists = 1;
	      $author->user_info['user_id']       = $classified_info['user_id'];
	      $author->user_info['user_username'] = $classified_info['user_username'];
	      $author->user_info['user_photo']    = $classified_info['user_photo'];
	      $author->user_info['user_fname']    = $classified_info['user_fname'];
	      $author->user_info['user_lname']    = $classified_info['user_lname'];
	      $author->user_displayname();
      }
      
	    // OTHERWISE, SET AUTHOR TO OWNER/LOGGED-IN USER
	    elseif( $owner->user_exists && $owner->user_info['user_id']==$classified_info['classified_user_id'] )
      {
	      $author =& $owner;
	    }
      elseif( $user->user_exists && $user->user_info['user_id']==$classified_info['classified_user_id'] )
      {
	      $author =& $user;
	    }
      else
      {
        $author = new se_user(array($classified_info['classified_user_id']));
      }
      
	    // GET ENTRY COMMENT PRIVACY
      // FIND A WAY TO MAKE THIS WORK WITH THE AUTHOR
	    $allowed_to_comment = TRUE;
	    if( $owner->user_exists )
      {
	      $comment_level = $owner->user_privacy_max($user);
	      if( !($comment_level & $classified_info['classified_comments']) )
          $allowed_to_comment = FALSE;
	    }
      
      // PRELOAD CATEGORY TITLE
      if( $classified_info['main_category_title'] )
        SE_Language::_preload($classified_info['main_category_title']);
        
      if( $classified_info['parent_category_title'] )
        SE_Language::_preload($classified_info['parent_category_title']);
      
	    // CREATE OBJECT FOR CLASSIFIED
	    $classified = new se_classified($classified_info['user_id']);
	    $classified->classified_exists = TRUE;
	    $classified->classified_info = $classified_info;
      
	    // SET CLASSIFIED ARRAY
	    $classified_array[] = array
      (
        'classified'                      => &$classified,
        'classified_author'               => &$author,
        'total_comments'                  => $classified_info['total_comments'],
        'allowed_to_comment'              => $allowed_to_comment
      );
      
      unset($author, $classified);
	  }
    
	  // RETURN ARRAY
	  return $classified_array;
	}
  
  // END classifieds_list() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD POSTS/EDITS AN ENTRY
  //
	// INPUT:
  //    $classified_id                REPRESENTING THE ID OF THE CLASSIFIED ENTRY TO EDIT. IF NO ENTRY WITH THIS ID IS FOUND, A NEW ENTRY WILL BE ADDED
	//	  $classified_title             REPRESENTING THE TITLE OF THE CLASSIFIED ENTRY
	//	  $classified_body              REPRESENTING THE BODY OF THE CLASSIFIED ENTRY
	//	  $classified_classifiedcat_id  REPRESENTING THE ID OF THE SELECTED CLASSIFIED ENTRY CATEGORY
	//	  $classified_search            REPRESENTING WHETHER THE CLASSIFIED ENTRY SHOULD BE INCLUDED IN SEARCH RESULTS
	//	  $classified_privacy           REPRESENTING THE PRIVACY LEVEL OF THE ENTRY
	//	  $classified_comments          REPRESENTING WHO CAN COMMENT ON THE ENTRY
	//	  $classified_field_query       REPRESENTING THE PARTIAL QUERY TO SAVE IN THE CLASSIFIED'S VALUE TABLE
  //
	// OUTPUT:
  //
  
	function classified_post($classified_id=NULL, $classified_title, $classified_body, $classified_classifiedcat_id, $classified_search, $classified_privacy, $classified_comments, $classified_field_query)
  {
	  global $database, $user;
    
    // INIT VARS
	  $classified_date = time();
	  $classified_title = censor($classified_title);
	  $classified_body = censor(htmlspecialchars_decode($classified_body));
	  $classified_body = cleanHTML($classified_body, $user->level_info['level_classified_html']);
	  $classified_body = security($classified_body);
    
    
    // UPDATE TABLE ROW
    if( $classified_id )
    {
      $sql = "
        UPDATE
          se_classifieds
        SET
          classified_classifiedcat_id='{$classified_classifiedcat_id}',
          classified_dateupdated='{$classified_date}',
          classified_title='{$classified_title}',
          classified_body='{$classified_body}',
          classified_search='{$classified_search}',
          classified_privacy='{$classified_privacy}',
          classified_comments='{$classified_comments}'
        WHERE
          classified_id='{$classified_id}' &&
          classified_user_id='{$this->user_id}'
        LIMIT
          1
      ";
      
      $database->database_query($sql);
    }
    
    // ADD TABLE ROW
    else
    {
      $sql = "
        INSERT INTO se_classifieds (
          classified_user_id,
          classified_classifiedcat_id,
          classified_date,
          classified_dateupdated,
          classified_title,
          classified_body,
          classified_search,
          classified_privacy,
          classified_comments
        ) VALUES (
          '{$this->user_id}',
          '{$classified_classifiedcat_id}',
          '{$classified_date}',
          '{$classified_date}',
          '{$classified_title}',
          '{$classified_body}',
          '{$classified_search}',
          '{$classified_privacy}',
          '{$classified_comments}'
        )
      ";
      
      $database->database_query($sql);
      $classified_id = $database->database_insert_id();
      
      // ADD CLASSIFIED FIELD VALUE ROW
      $sql = "INSERT INTO se_classifiedvalues (classifiedvalue_classified_id) VALUES ('{$classified_id}')";
      $database->database_query($sql);
      
      // ADD CLASSIFIED ALBUM
      $sql = "
        INSERT INTO se_classifiedalbums (
          classifiedalbum_classified_id,
          classifiedalbum_datecreated,
          classifiedalbum_dateupdated,
          classifiedalbum_title,
          classifiedalbum_desc,
          classifiedalbum_search,
          classifiedalbum_privacy,
          classifiedalbum_comments
        ) VALUES (
          '{$classified_id}',
          '{$classified_date}',
          '{$classified_date}',
          '',
          '',
          '{$classified_search}',
          '{$classified_privacy}',
          '{$classified_comments}'
        )
      ";
      $database->database_query($sql);
    }
    
    // UPDATE CLASSIFIED FIELD VALUES
    if( !empty($classified_field_query) )
    {
      $sql = "UPDATE se_classifiedvalues SET {$classified_field_query} WHERE classifiedvalue_classified_id='{$classified_id}' LIMIT 1";
      $database->database_query($sql);
    }
    
    // CHECK AND ADD CLASSIFIED DIRECTORY
    $classified_directory = $this->classified_dir($classified_id);
    $classified_path_array = explode("/", $classified_directory);
    array_pop($classified_path_array);
    array_pop($classified_path_array);
    $subdir = implode("/", $classified_path_array)."/";
    
    if( !is_dir($subdir) )
    { 
      mkdir($subdir, 0777); 
      chmod($subdir, 0777); 
      $handle = fopen($subdir."index.php", 'x+');
      fclose($handle);
    }
    
    if( !is_dir($classified_directory) )
    {
      mkdir($classified_directory, 0777);
      chmod($classified_directory, 0777);
      $handle = fopen($classified_directory."/index.php", 'x+');
      fclose($handle);
    }
    
	  return $classified_id;
	}
  
  // END classified_post() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD DELETES CLASSIFIED ENTRIES
  //
	// INPUT:
  //    $classified_id  REPRESENTING THE ID OF THE ENTRY TO DELETE
  //
	// OUTPUT:
  //
  
	function classified_delete($classified_id=NULL)
  {
	  global $database;
    
    // IF EMPTY, TRY TO GET FROM OBJECT
	  if( !$classified_id && !$this->classified_exists )
      return FALSE;
    elseif( !$classified_id )
      $classified_id = $this->classified_info['classified_id'];
    
    // IF ARRAY
    if( is_array($classified_id) )
      return array_map(array(&$this, 'classified_delete'), $classified_id);
    
	  // DELETE CLASSIFIED ALBUM AND MEDIA
    $sql = "DELETE FROM se_classifiedalbums, se_classifiedmedia USING se_classifiedalbums LEFT JOIN se_classifiedmedia ON se_classifiedalbums.classifiedalbum_id=se_classifiedmedia.classifiedmedia_classifiedalbum_id WHERE se_classifiedalbums.classifiedalbum_classified_id='{$classified_id}'";
	  $database->database_query($sql);
    
	  // DELETE CLASSIFIED VALUES
	  $sql = "DELETE FROM se_classifiedvalues WHERE se_classifiedvalues.classifiedvalue_classified_id='{$classified_id}' LIMIT 1";
    $database->database_query($sql);
    
	  // DELETE CLASSIFIED ROW
	  $sql = "DELETE FROM se_classifieds WHERE se_classifieds.classified_id='{$classified_id}' LIMIT 1";
    $database->database_query($sql);
    
	  // DELETE CLASSIFIED COMMENTS
	  $sql = "DELETE FROM se_classifiedcomments WHERE se_classifiedcomments.classifiedcomment_classified_id='{$classified_id}'";
    $database->database_query($sql);
    
	  // DELETE CLASSIFIED'S FILES
	  if( is_dir($this->classified_dir($classified_id)) )
	    $dir = $this->classified_dir($classified_id);
	  else
	    $dir = ".".$this->classified_dir($classified_id);
    
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
  
  // END classified_delete() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD RETURNS THE PATH TO THE GIVEN CLASSIFIED'S DIRECTORY
  //
	// INPUT:
  //    $classified_id (OPTIONAL) REPRESENTING A CLASSIFIED'S CLASSIFIED
  //
	// OUTPUT:
  //    A STRING REPRESENTING THE RELATIVE PATH TO THE CLASSIFIED'S DIRECTORY
  //
  
	function classified_dir($classified_id=NULL)
  {
	  if( !$classified_id && $this->classified_exists )
      $classified_id = $this->classified_info['classified_id'];
    
	  $subdir = $classified_id+999-(($classified_id-1)%1000);
	  $classifieddir = "./uploads_classified/$subdir/$classified_id/";
	  return $classifieddir;
	}
  
  // END classified_dir() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD OUTPUTS THE PATH TO THE CLASSIFIED'S PHOTO OR THE GIVEN NOPHOTO IMAGE
  //
	// INPUT:
  //    $nophoto_image (OPTIONAL) REPRESENTING THE PATH TO AN IMAGE TO OUTPUT IF NO PHOTO EXISTS
  //
	// OUTPUT:
  //    A STRING CONTAINING THE PATH TO THE CLASSIFIED'S PHOTO
  //
  
	function classified_photo($nophoto_image=NULL, $thumb=FALSE)
  {
    if( empty($this->classified_info['classified_photo']) )
      return $nophoto_image;
    
	  $classified_dir = $this->classified_dir($this->classified_info['classified_id']);
	  $classified_photo = $classified_dir.$this->classified_info['classified_photo'];
    if( $thumb )
    {
      $classified_thumb = substr($classified_photo, 0, strrpos($classified_photo, "."))."_thumb".substr($classified_photo, strrpos($classified_photo, "."));
      if( file_exists($classified_thumb) )
        return $classified_thumb;
    }
    
    if( file_exists($classified_photo) )
      return $classified_photo;
    
    return $nophoto_image;
	}
  
  // END classified_photo() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD UPLOADS AN CLASSIFIED PHOTO ACCORDING TO SPECIFICATIONS AND RETURNS CLASSIFIED PHOTO
  //
	// INPUT:
  //    $photo_name REPRESENTING THE NAME OF THE FILE INPUT
  //
	// OUTPUT:
  //
  
	function classified_photo_upload($photo_name)
  {
	  global $database, $url;
    
	  // SET KEY VARIABLES
	  $file_maxsize = "4194304";
	  $file_exts = explode(",", str_replace(" ", "", strtolower($this->classifiedowner_level_info['level_classified_photo_exts'])));
	  $file_types = explode(",", str_replace(" ", "", strtolower("image/jpeg, image/jpg, image/jpe, image/pjpeg, image/pjpg, image/x-jpeg, x-jpg, image/gif, image/x-gif, image/png, image/x-png")));
	  $file_maxwidth = $this->classifiedowner_level_info['level_classified_photo_width'];
	  $file_maxheight = $this->classifiedowner_level_info['level_classified_photo_height'];
	  $photo_newname = "0_".rand(1000, 9999).".jpg";
	  $file_dest = $this->classified_dir($this->classified_info['classified_id']).$photo_newname;
	  $thumb_dest = substr($file_dest, 0, strrpos($file_dest, "."))."_thumb".substr($file_dest, strrpos($file_dest, "."));
    
	  $new_photo = new se_upload();
	  $new_photo->new_upload($photo_name, $file_maxsize, $file_exts, $file_types, $file_maxwidth, $file_maxheight);
    
	  // UPLOAD AND RESIZE PHOTO IF NO ERROR
	  if( !$new_photo->is_error )
    {
	    // DELETE OLD AVATAR IF EXISTS
	    $this->classified_photo_delete();
      
	    // UPLOAD THUMB
	    $new_photo->upload_thumb($thumb_dest, 200);
      
	    // CHECK IF IMAGE RESIZING IS AVAILABLE, OTHERWISE MOVE UPLOADED IMAGE
	    if( $new_photo->is_image )
	      $new_photo->upload_photo($file_dest);
	    else
	      $new_photo->upload_file($file_dest);
      
	    // UPDATE classified INFO WITH IMAGE IF STILL NO ERROR
	    if( !$new_photo->is_error )
      {
        $sql = "UPDATE se_classifieds SET classified_photo='{$photo_newname}' WHERE classified_id='{$this->classified_info['classified_id']}'";
	      $database->database_query($sql);
	      $this->classified_info['classified_photo'] = $photo_newname;
	    }
	  }
    
	  $this->is_error = $new_photo->is_error;
	  $this->error_message = $new_photo->error_message;
	}
  
  // END classified_photo_upload() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD DELETES A CLASSIFIED PHOTO
  //
	// INPUT: 
  //
	// OUTPUT: 
  //
  
	function classified_photo_delete()
  {
	  global $database;
    
	  $classified_photo = $this->classified_photo();
    
	  if( !empty($classified_photo) )
    {
	    $sql = "UPDATE se_classifieds SET classified_photo='' WHERE classified_id='{$this->classified_info['classified_id']}' LIMIT 1";
	    $database->database_query($sql);
	    $this->classified_info['classified_photo'] = "";
	    @unlink($classified_photo);
	  }
	}
  
  // END classified_photo_delete() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD UPDATES THE CLASSIFIED'S LAST UPDATE DATE
  //
	// INPUT: 
  //
	// OUTPUT: 
  //
  
	function classified_lastupdate()
  {
	  global $database;
    $sql = "UPDATE se_classifieds SET classified_dateupdated='".time()."' WHERE classified_id='{$this->classified_info['classified_id']}' LIMIT 1";
	  $database->database_query($sql);
	}
  
  // END classified_lastupdate() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD UPLOADS MEDIA TO A CLASSIFIED ALBUM
  //
	// INPUT:
  //    $file_name          REPRESENTING THE NAME OF THE FILE INPUT
	//	  $classifiedalbum_id REPRESENTING THE ID OF THE CLASSIFIED ALBUM TO UPLOAD THE MEDIA TO
	//	  $space_left         REPRESENTING THE AMOUNT OF SPACE LEFT
  //
	// OUTPUT:
  //
  
	function classified_media_upload($file_name, $classifiedalbum_id, &$space_left)
  {
	  global $class_classified, $database, $url;
    
	  // SET KEY VARIABLES
	  $file_maxsize   = $this->classifiedowner_level_info['level_classified_album_maxsize'];
	  $file_exts      = explode(",", str_replace(" ", "", strtolower($this->classifiedowner_level_info['level_classified_album_exts'])));
	  $file_types     = explode(",", str_replace(" ", "", strtolower($this->classifiedowner_level_info['level_classified_album_mimes'])));
	  $file_maxwidth  = $this->classifiedowner_level_info['level_classified_album_width'];
	  $file_maxheight = $this->classifiedowner_level_info['level_classified_album_height'];
    
	  $new_media = new se_upload();
	  $new_media->new_upload($file_name, $file_maxsize, $file_exts, $file_types, $file_maxwidth, $file_maxheight);
    
	  // UPLOAD AND RESIZE PHOTO IF NO ERROR
	  if( !$new_media->is_error )
    {
	    // INSERT ROW INTO MEDIA TABLE
      $sql = "
        INSERT INTO se_classifiedmedia
          (classifiedmedia_classifiedalbum_id, classifiedmedia_date)
        VALUES
          ('{$classifiedalbum_id}', '".time()."')
      ";
      
      $database->database_query($sql);
	    $classifiedmedia_id = $database->database_insert_id();
      
	    // CHECK IF IMAGE RESIZING IS AVAILABLE, OTHERWISE MOVE UPLOADED IMAGE
      $classified_dir = $this->classified_dir($this->classified_info['classified_id']);
	    if( $new_media->is_image )
      {
	      $file_dest  = "{$classified_dir}{$classifiedmedia_id}.jpg";
	      $thumb_dest = "{$classified_dir}{$classifiedmedia_id}_thumb.jpg";
        
	      // UPLOAD THUMB
	      $new_media->upload_thumb($thumb_dest, 200);
        
	      // UPLOAD FILE
	      $new_media->upload_photo($file_dest);
	      $file_ext = "jpg";
	      $file_filesize = filesize($file_dest);
	    }
      
      else
      {
	      $file_dest  = "{$classified_dir}{$classifiedmedia_id}.{$new_media->file_ext}";
	      $thumb_dest = "{$classified_dir}{$classifiedmedia_id}_thumb.jpg";
        
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
        $new_media->error_message = $class_classified[1].$_FILES[$file_name]['name']; // TODO LANG
      }
      elseif( $space_left!==FALSE )
      {
	      $space_left = $space_left - $file_filesize;
	    }
      
	    // DELETE FROM DATABASE IF ERROR
	    if( $new_media->is_error )
      {
        $sql = "DELETE FROM se_classifiedmedia WHERE classifiedmedia_id='{$classifiedmedia_id}' AND classifiedmedia_classifiedalbum_id='{$classifiedalbum_id}' LIMIT 1";
	      $database->database_query($sql);
	      @unlink($file_dest);
	    }
      
	    // UPDATE ROW IF NO ERROR
      else
      {
	      $sql = "UPDATE se_classifiedmedia SET classifiedmedia_ext='{$file_ext}', classifiedmedia_filesize='{$file_filesize}' WHERE classifiedmedia_id='{$classifiedmedia_id}' AND classifiedmedia_classifiedalbum_id='{$classifiedalbum_id}' LIMIT 1";
        $database->database_query($sql);
        
        // Update parent row
        $sql = "UPDATE se_classifiedalbums SET classifiedalbum_totalfiles=classifiedalbum_totalfiles+1, classifiedalbum_totalspace=classifiedalbum_totalspace+'{$file_filesize}' WHERE classifiedalbum_id='{$classifiedalbum_id}' LIMIT 1";
        $database->database_query($sql);
	    }
	  }
    
	  // RETURN FILE STATS
	  return array(
      'is_error'                  => $new_media->is_error,
			'error_message'             => $new_media->error_message,
			'classifiedmedia_id'        => $classifiedmedia_id,
			'classifiedmedia_ext'       => $file_ext,
			'classifiedmedia_filesize'  => $file_filesize
    );
	}
  
  // END classified_media_upload() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD RETURNS THE SPACE USED
  //
	// INPUT:
  //    $classifiedalbum_id (OPTIONAL) REPRESENTING THE ID OF THE ALBUM TO CALCULATE
  //
	// OUTPUT:
  //    AN INTEGER REPRESENTING THE SPACE USED
  //
  
	function classified_media_space($classifiedalbum_id=NULL)
  {
	  global $database;
    
    // NEW HANDLING METHOD
    if( !$classifiedalbum_id )
    {
      $sql = "
        SELECT
          se_classifiedalbums.classifiedalbum_totalspace AS total_space
        FROM
          se_classifiedalbums
        WHERE
          se_classifiedalbums.classifiedalbum_id='{$classifiedalbum_id}'
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
        SUM(se_classifiedmedia.classifiedmedia_filesize) AS total_space
    ";
    
	  // CONTINUE QUERY
	  $sql .= "
      FROM
        se_classifiedalbums
      LEFT JOIN
        se_classifiedmedia
        ON se_classifiedalbums.classifiedalbum_id=se_classifiedmedia.classifiedmedia_classifiedalbum_id
    ";
    
	  // ADD WHERE IF NECESSARY
	  if( $this->classified_exists || $classifiedalbum_id ) $sql .= "
      WHERE
    ";
    
	  // IF CLASSIFIED EXISTS, SPECIFY CLASSIFIED ID
	  if( $this->classified_exists ) $sql .= "
        se_classifiedalbums.classifiedalbum_classified_id='{$this->classified_info['classified_id']}'
    ";
    
	  // ADD AND IF NECESSARY
	  if( $this->classified_exists && $classifiedalbum_id )
      $sql .= " AND";
    
	  // SPECIFY ALBUM ID IF NECESSARY
	  if( $classifiedalbum_id ) $sql .= "
        se_classifiedalbums.classifiedalbum_id='{$classifiedalbum_id}'
    ";
    
	  // GET AND RETURN TOTAL SPACE USED
    $resource = $database->database_query($sql);
	  $space_info = $database->database_fetch_assoc($resource);
	  return $space_info['total_space'];
	}
  
  // END classified_media_space() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD RETURNS THE NUMBER OF CLASSIFIED MEDIA
  //
	// INPUT:
  //    $classifiedalbum_id (OPTIONAL) REPRESENTING THE ID OF THE claCLASSIFIEDssified ALBUM TO CALCULATE
  //
	// OUTPUT:
  //    AN INTEGER REPRESENTING THE NUMBER OF FILES
  //
  
	function classified_media_total($classifiedalbum_id=NULL)
  {
	  global $database;
    
    // NEW HANDLING METHOD
    if( !$classifiedalbum_id )
    {
      $sql = "
        SELECT
          se_classifiedalbums.classifiedalbum_totalfiles AS total_files
        FROM
          se_classifiedalbums
        WHERE
          se_classifiedalbums.classifiedalbum_id='{$classifiedalbum_id}'
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
        COUNT(se_classifiedmedia.classifiedmedia_id) AS total_files
    ";
    
	  // CONTINUE QUERY
	  $sql .= "
      FROM
        se_classifiedalbums
      LEFT JOIN
        se_classifiedmedia
        ON se_classifiedalbums.classifiedalbum_id=se_classifiedmedia.classifiedmedia_classifiedalbum_id
    ";
    
	  // ADD WHERE IF NECESSARY
	  if( $this->classified_exists || $classifiedalbum_id ) $sql .= "
      WHERE
    ";
    
	  // IF classified EXISTS, SPECIFY classified ID
	  if( $this->classified_exists ) $sql .= "
        se_classifiedalbums.classifiedalbum_classified_id='{$this->classified_info['classified_id']}'
    ";
    
	  // ADD AND IF NECESSARY
	  if( $this->classified_exists && $classifiedalbum_id )
      $sql .= " AND";
    
	  // SPECIFY ALBUM ID IF NECESSARY
	  if( $classifiedalbum_id ) $sql .= "
        se_classifiedalbums.classifiedalbum_id='{$classifiedalbum_id}'
    ";
    
	  // GET AND RETURN TOTAL FILES
    $resource = $database->database_query($sql);
	  $file_info = $database->database_fetch_assoc($resource);
	  return $file_info['total_files'];
	}
  
  // END classified_media_total() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD RETURNS AN ARRAY OF CLASSIFIED MEDIA
  //
	// INPUT:
  //    $start REPRESENTING THE classified MEDIA TO START WITH
	//	  $limit REPRESENTING THE NUMBER OF classified MEDIA TO RETURN
	//	  $sort_by (OPTIONAL) REPRESENTING THE ORDER BY CLAUSE
	//	  $where (OPTIONAL) REPRESENTING ADDITIONAL THINGS TO INCLUDE IN THE WHERE CLAUSE
  //
	// OUTPUT:
  //    AN ARRAY OF classified MEDIA
  //
  
	function classified_media_list($start, $limit, $sort_by = "classifiedmedia_id DESC", $where=NULL, $file_details=FALSE)
  {
	  global $database;
    
    if( !$start ) $start = '0';
    
	  // BEGIN QUERY
	  $sql = "
      SELECT
        se_classifiedmedia.*,
        se_classifiedalbums.classifiedalbum_id,
        se_classifiedalbums.classifiedalbum_classified_id,
        se_classifiedalbums.classifiedalbum_title
    ";
    
	  // CONTINUE QUERY
	  $sql .= "
      FROM
        se_classifiedmedia
      LEFT JOIN
        se_classifiedalbums
        ON se_classifiedalbums.classifiedalbum_id=se_classifiedmedia.classifiedmedia_classifiedalbum_id
    ";
    
	  // ADD WHERE IF NECESSARY
	  if( $this->classified_exists || !empty($where) ) $sql .= "
      WHERE
    ";
    
	  // IF classified EXISTS, SPECIFY classified ID
	  if( $this->classified_exists ) $sql .= "
        se_classifiedalbums.classifiedalbum_classified_id='{$this->classified_info['classified_id']}'
    ";
    
	  // ADD AND IF NECESSARY
	  if( $this->classified_exists && !empty($where) )
      $sql .= " AND";
    
	  // ADD ADDITIONAL WHERE CLAUSE
	  if( !empty($where) ) $sql .= "
        $where
    ";
    
	  // ADD GROUP BY, ORDER, AND LIMIT CLAUSE
	  $sql .= "
    /*
      GROUP BY
        classifiedmedia_id */
      ORDER BY
        $sort_by
      LIMIT
        $start, $limit
    ";
    
	  // RUN QUERY
    $resource = $database->database_query($sql);
    
	  // GET classified MEDIA INTO AN ARRAY
    $classified_dir = $this->classified_dir($this->classified_info['classified_id']);
	  $classifiedmedia_array = array();
	  while( $classifiedmedia_info=$database->database_fetch_assoc($resource) )
    {
      $classifiedmedia_info['classifiedmedia_desc'] = str_replace("<br />", "\r\n", $classifiedmedia_info['classifiedmedia_desc']);
      
      if( $file_details )
      {
        $mediasize = getimagesize($classified_dir.$classifiedmedia_info['classifiedmedia_id'].'.'.$classifiedmedia_info['classifiedmedia_ext']);
        $classifiedmedia_info['classifiedmedia_dir']  = $classified_dir;
        $classifiedmedia_info['classifiedmedia_width']  = $mediasize[0];
        $classifiedmedia_info['classifiedmedia_height'] = $mediasize[1];
        
      }
      
	    $classifiedmedia_array[] = $classifiedmedia_info;
	  }
    
	  // RETURN ARRAY
	  return $classifiedmedia_array;
	}
  
  // END classified_media_list() METHOD
  
  
  
  
  
  
  
  
  //
	// THIS METHOD DELETES SELECTED CLASSIFIED MEDIA
  //
	// INPUT:
  //    $start    REPRESENTING THE CLASSIFIED MEDIA TO START WITH
	//	  $limit    REPRESENTING THE NUMBER OF classified MEDIA TO RETURN
	//	  $sort_by  (OPTIONAL) REPRESENTING THE ORDER BY CLAUSE
	//	  $where    (OPTIONAL) REPRESENTING ADDITIONAL THINGS TO INCLUDE IN THE WHERE CLAUSE
  //
	// OUTPUT:
  //
  
	function classified_media_delete($start, $limit, $sort_by = "classifiedmedia_id DESC", $where = "")
  {
	  global $database, $url;
    
	  // BEGIN QUERY
	  $classifiedmedia_query = "SELECT se_classifiedmedia.*, se_classifiedalbums.classifiedalbum_id, se_classifiedalbums.classifiedalbum_classified_id, se_classifiedalbums.classifiedalbum_title";
    
	  // CONTINUE QUERY
	  $classifiedmedia_query .= " FROM se_classifiedmedia LEFT JOIN se_classifiedalbums ON se_classifiedalbums.classifiedalbum_id=se_classifiedmedia.classifiedmedia_classifiedalbum_id";
    
	  // ADD WHERE IF NECESSARY
	  if($this->classified_exists != 0 | $where != "") { $classifiedmedia_query .= " WHERE"; }
    
	  // IF classified EXISTS, SPECIFY classified ID
	  if($this->classified_exists != 0) { $classifiedmedia_query .= " se_classifiedalbums.classifiedalbum_classified_id='{$this->classified_info['classified_id']}'"; }
    
	  // ADD AND IF NECESSARY
	  if($this->classified_exists != 0 & $where != "") { $classifiedmedia_query .= " AND"; }
    
	  // ADD ADDITIONAL WHERE CLAUSE
	  if($where != "") { $classifiedmedia_query .= " $where"; }
    
	  // ADD GROUP BY, ORDER, AND LIMIT CLAUSE
	  $classifiedmedia_query .= " GROUP BY se_classifiedmedia.classifiedmedia_id ORDER BY $sort_by LIMIT $start, $limit";
    
	  // RUN QUERY
	  $classifiedmedia = $database->database_query($classifiedmedia_query);
    
	  // LOOP OVER classified MEDIA
	  $classifiedmedia_delete = "";
	  while($classifiedmedia_info = $database->database_fetch_assoc($classifiedmedia))
    {
	    $var = "delete_classifiedmedia_".$classifiedmedia_info['classifiedmedia_id']; 
	    if($classifiedmedia_delete != "") { $classifiedmedia_delete .= " OR "; }
	    $classifiedmedia_delete .= "classifiedmedia_id='$classifiedmedia_info[classifiedmedia_id]'";
	    $classifiedmedia_path = $this->classified_dir($this->classified_info['classified_id']).$classifiedmedia_info['classifiedmedia_id'].".".$classifiedmedia_info['classifiedmedia_ext'];
	    if(file_exists($classifiedmedia_path)) { @unlink($classifiedmedia_path); }
	    $thumb_path = $this->classified_dir($this->classified_info['classified_id']).$classifiedmedia_info['classifiedmedia_id']."_thumb.".$classifiedmedia_info['classifiedmedia_ext'];
	    if(file_exists($thumb_path)) { @unlink($thumb_path); }
      
      // Update parent row
      $sql = "UPDATE se_classifiedalbums SET classifiedalbum_totalfiles=classifiedalbum_totalfiles-1, classifiedalbum_totalspace=classifiedalbum_totalspace-'{$classifiedmedia_info['classifiedmedia_filesize']}' WHERE classifiedalbum_id='{$classifiedmedia_info['classifiedmedia_classifiedalbum_id']}' LIMIT 1";
      $database->database_query($sql);
	  }
    
	  // IF DELETE CLAUSE IS NOT EMPTY, DELETE classified MEDIA
	  if($classifiedmedia_delete != "") { $database->database_query("DELETE FROM se_classifiedmedia WHERE ($classifiedmedia_delete)"); }
	}
  
  // END classified_media_delete() METHOD
}

?>