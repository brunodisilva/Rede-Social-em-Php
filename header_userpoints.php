<?php

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
if(!defined('SE_PAGE')) { exit(); }

// INCLUDE SEMODS CLASS FILE
include_once "./include/class_semods.php";
include_once "./include/class_semods_utils.php";

// INCLUDE FUNCTIONS FILE
include_once "./include/functions_userpoints.php";

// INCLUDE CLASS FILE
include_once "./include/class_userpoints.php";
include_once "./include/class_semods_actionsex.php";



/*** NOTE: ONLY ONE OF THE BELOW TOP BAR LINKS CAN BE SHOWN, NOT BOTH ***/


/*** SHOW "POINTS SHOP" LINK ON TOP BAR - BEGIN ***/

/*

// PRELOAD LANGUAGE
SE_Language::_preload(100016012);

// SET MAIN MENU VARS
$plugin_vars['menu_main'] = array( 'file'  => 'user_points_shop.php',
                                   'title' => 100016012
                                   );

*/

/*** SHOW POINTS SHOP LINK ON TOP BAR - END ***/



/*** SHOW "TOP USERS" LINK ON TOP BAR - BEGIN ***/

if(semods::get_setting('userpoints_enable_topusers')) {

  // PRELOAD LANGUAGE
  SE_Language::_preload(100016013);

  // SET MAIN MENU VARS
  $plugin_vars['menu_main'] = array( 'file'  => 'topusers.php',
                                     'title' => 100016013
                                     );

}

/*** SHOW "TOP USERS" LINK ON TOP BAR - END ***/





// hook actions
$actions = new se_actionsex();


$userpoints_user_enabled = $user->user_exists && $user->level_info['level_userpoints_allow'] && $user->user_info['user_userpoints_allowed'];
$smarty->assign('userpoints_user_enabled', $userpoints_user_enabled);


// USER (VIEWER) ORIENTED
if($userpoints_user_enabled) {


  // PRELOAD LANGUAGE
  SE_Language::_preload( 100016011 );

  // SET USER MENU VARS
  $plugin_vars['menu_user'] = array( 'file' => 'user_vault.php',
                                     'icon' => 'userpoints16.png',
                                     'title' => 100016011
                                     );

  $plugin_vars['menu_userhome'] = array( 'file' => 'user_home_userpoints.tpl' );


  switch($page) {


    /* ACTION POINTS */


  // ad click
  case "ad":
    $ad_id_ = semods::get('ad_id',0);
      if( $ad_id_ ) {
      userpoints_update_points( $user->user_info['user_id'], "adclick" );
    }

    break;




  // CODE FOR USER HOME PAGE
  case "user_home":

    $points_all = userpoints_get_all( $user->user_info['user_id'] );
    if($points_all) {
      $user_points = $points_all['userpoints_count'];
      $user_points_totalearned = $points_all['userpoints_totalearned'];
    } else {
      $user_points = 0;
      $user_points_totalearned = 0;
    }

    $userpoints_enable_topusers = semods::get_setting('userpoints_enable_topusers');
    if( $userpoints_enable_topusers != 0) {
      $user_rank = userpoints_get_rank($user->user_info['user_id']);
      $smarty->assign('user_rank', $user_rank);
    }

    $smarty->assign('userpoints_enable_pointrank', semods::get_setting('userpoints_enable_pointrank') );
    $smarty->assign('userpoints_enable_topusers', $userpoints_enable_topusers);
    $smarty->assign('user_points', $user_points);
    $smarty->assign('user_points_totalearned', $user_points_totalearned);

	break;


  }



  /* CHARGING */

  if(file_exists("header_userpoints_charging.php"))
    include_once "header_userpoints_charging.php";


            }



// USER (OWNER) ORIENTED - PROFILE PAGE
if( $owner->level_info['level_userpoints_allow'] && $owner->user_info['user_userpoints_allowed'] && $page=="profile" ) {

  $points_all = userpoints_get_all( $owner->user_info['user_id'] );
  if($points_all) {
    $user_points = $points_all['userpoints_count'];
    $user_points_totalearned = $points_all['userpoints_totalearned'];
  } else {
    $user_points = 0;
    $user_points_totalearned = 0;
          }

  $userpoints_enable_topusers = semods::get_setting('userpoints_enable_topusers');
  if( $userpoints_enable_topusers != 0) {
    $user_rank = userpoints_get_rank($owner->user_info['user_id']);
    $smarty->assign('user_rank', $user_rank);
    }

  $smarty->assign('userpoints_enable_pointrank', semods::get_setting('userpoints_enable_pointrank') );
  $smarty->assign('userpoints_enable_topusers', $userpoints_enable_topusers);
  $smarty->assign('user_points', $user_points);
  $smarty->assign('user_points_totalearned', $user_points_totalearned);

  // SET PROFILE MENU VARS
  $plugin_vars['menu_profile_side'] = NULL;
  $plugin_vars['menu_profile_tab'] = array('file'=> 'profile_userpoints.tpl', 'title' => 2500005);


}




// GLOBAL POINTS
switch($page) {


  /* ACTION POINTS */


  // Code for adding signup referrer points, Part I (Part II is in footer_userpoints.php)
  case "signup":
    SE_Hook::register("se_signup_success", "userpoints_hook_signup_success", 100);
    break;


  // CODE FOR SIGNUP VERIFICATION PAGE - Add signup referrer points
  case "signup_verify":
    SE_Hook::register("se_signup_verify", "userpoints_hook_footer_signup_verify");
    break;


  // CODE FOR PROFILE PAGE
  case "profile":

    if( $owner->level_info['level_userpoints_allow'] && $owner->user_info['user_userpoints_allowed'] ) {

      $points_all = userpoints_get_all( $owner->user_info['user_id'] );
      if($points_all) {
        $user_points = $points_all['userpoints_count'];
        $user_points_totalearned = $points_all['userpoints_totalearned'];
      } else {
        $user_points = 0;
        $user_points_totalearned = 0;
        }

      $userpoints_enable_topusers = semods::get_setting('userpoints_enable_topusers');
      if( $userpoints_enable_topusers != 0) {
        $user_rank = userpoints_get_rank($owner->user_info['user_id']);
        $smarty->assign('user_rank', $user_rank);
      }

      $smarty->assign('userpoints_enable_pointrank', semods::get_setting('userpoints_enable_pointrank') );
      $smarty->assign('userpoints_enable_topusers', $userpoints_enable_topusers);
      $smarty->assign('user_points', $user_points);
      $smarty->assign('user_points_totalearned', $user_points_totalearned);

      // SET PROFILE MENU VARS
      //$plugin_vars['menu_profile_side'] = NULL;
      //$plugin_vars['menu_profile_tab'] = array('file'=> 'profile_userpoints.tpl', 'title' => $profile600 );
      $plugin_vars['menu_profile_side'] = array('file'=> 'profile_userpoints.tpl', 'title' => $profile600 );
      $plugin_vars['menu_profile_tab'] = null;

    }

    break;





  // CODE FOR HOME PAGE
  case "home":

    $userpoints_enable_topusers = semods::get_setting('userpoints_enable_topusers');

      if($userpoints_enable_topusers != 0) {

        $sql = "SELECT P.userpoints_totalearned,
                       U.*
                FROM
                  se_semods_userpoints P
                  JOIN se_users U
                    ON P.userpoints_user_id = U.user_id
                ORDER BY userpoints_totalearned DESC
                LIMIT 5";

        $rows = $database->database_query( $sql );
        $dummy_user = new se_user();
        $dummy_user->user_exists = 0;
        while($row = $database->database_fetch_assoc($rows)) {
          $dummy_user->user_info['user_id'] = $row['user_id'];
          $dummy_user->user_info['user_photo'] = $row['user_photo'];
          $dummy_user->user_info['user_fname'] = $row['user_fname'];
          $dummy_user->user_info['user_lname'] = $row['user_lname'];
          $dummy_user->user_info['user_displayname'] = $row['user_displayname'];
          $dummy_user->user_displayname();
          $row['user_displayname'] = $dummy_user->user_displayname;
          $row['user_photo'] = $dummy_user->user_photo( './images/nophoto.gif', true );
          $up_topusers[] = $row;
        }

        $smarty->assign('up_topusers', $up_topusers);

      }

    $smarty->assign('userpoints_enable_topusers', $userpoints_enable_topusers);

    break;

}




?>