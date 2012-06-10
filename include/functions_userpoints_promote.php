<?php


/*** USER POINT SPENDER - PROMOTION ***/



function upspender_promote_ontransactionstart( $params ) {
  global $functions_userpoints;

  $upspender = $params[0];
  $user = $params[1];

  $require_redirect = true;

  switch($upspender->upspender_info['userpointspender_type']) {

      // promote profile - username
      case 100:

        $require_redirect = false;
        break;

      // promote classified - classified_id, username
      case 101:

        // check if has classifieds
        $classified = new se_classified($user->user_info['user_id']);
        if( $classified->classified_total() == 0) {
          $params['err_msg'] = 100016053;
          return false;
        }

        break;

      // promote event - eventid
      case 102:
        // check if has events
        $event = new se_event($user->user_info['user_id']);
        if( $event->event_total() == 0) {
          $params['err_msg'] = 100016054;
          return false;
        }

        break;

      // promote group - group_id
      case 103:
        // check if has groups
        $group = new se_group($user->user_info['user_id']);
        if( $group->group_total() == 0) {
          $params['err_msg'] = 100016055;
          return false;
        }

        break;

      // promote poll - username, pollid
      case 104:
        // check if has polls
        $poll = new se_poll($user->user_info['user_id']);
        if( $poll->poll_total() == 0) {
          $params['err_msg'] = 100016056;
          return false;
        }

        break;

  }

  $gotvars = semods::getpost('gotvars', 0);

  // if don't have my required vars
  if(!$gotvars && $require_redirect) {
    $params['redirect'] = "user_points_shop_item_promote.php?shopitem_id=" . $upspender->upspender_info['userpointspender_id'];
    return false;
  }

  return true;
}


function upspender_promote_ontransactionfail( $params ) {

}


function upspender_promote_ontransactionsuccess( $params ) {
  global $url, $database, $misc, $setting, $functions_userpoints;

  $upspender = $params[0];
  $user = $params[1];
  $metadata = $params[2];


  $ad_template = semods::db_query_assoc("SELECT * FROM se_ads WHERE ad_id={$metadata['ad_id']}");

  if(!$ad_template) {
    // rollback
    $params['err_msg'] = 100016040;
    return false;
  }

  // GET BANNER HTML
  //$ad_html = $ad_template['ad_html'];
  //$ad_html = $metadata['html'];

  // TBD: strip html tags
  $promotion_message = $_POST['promotion_message'];

  $promotion_type = array( 100  =>  "Profile",
                           101  =>  "Classified",
                           102  =>  "Event",
                           103  =>  "Group",
                           104  =>  "Poll" );

  $ad_html = str_replace( array( '[userid]',
                                 '[username]',
                                 '[userprofile]',
                                 '[userphoto]',
                                 '[userphotowidth]',
                                 '[promotionmessage]'
                                 ),
                          array( $user->user_info['user_id'],
                                 $user->user_info['user_username'],
                                 $url->url_create('profile', $user->user_info['user_username']),
                                 $url->url_base . $user->user_photo('./images/nophoto.gif'),
                                 $misc->photo_size( $user->user_photo('./images/nophoto.gif'),'50','50','w'),
                                 $promotion_message
                                ),
                          html_entity_decode( $metadata['html'], ENT_QUOTES, 'UTF-8' )
                        );

  switch($upspender->upspender_info['userpointspender_type']) {

    // promote profile - username
    case 100:

      $ad_name = "Promoting <a href='" . $url->url_create('profile', $user->user_info['user_username']) . "'>" . $user->user_info['user_username'] . "</a>'s profile" .  " based on promotion template " . "<a href='admin_ads_edit.php?ad_id=" . $ad_template['ad_id'] . "'>" . $ad_template['ad_name'] . "</a>";
      $params['transaction_text'] = 100016039;

      break;



    // promote classified - classified_id, classified_photo, classified_photowidth, classified_title
    case 101:

      // TBD: check classified exist, allow only owned by user?
      $classified_id = semods::getpost('classified_id',0);

      $classified = new se_classified( $user->user_info['user_id'], $classified_id );

      if($classified->classified_exists == 0) {
        $params['err_msg'] = 100016041;
        return false;
      }

      $ad_name = "Promoting <a href='" . $url->url_create('profile', $user->user_info['user_username']) . "'>" . $user->user_info['user_username'] . "</a>'s " . "<a href='" . $url->url_create('classified', $user->user_info['user_username'], $classified_id) . "'>classified</a> " . "(<a href='admin_ads_edit.php?ad_id=" . $ad_template['ad_id'] . "'>template</a>)";

      $ad_html = str_replace( array( '[classified]',
                                     '[classifiedphoto]',
                                     '[classifiedphotowidth]'),
                              array( $url->url_create('classified', $user->user_info['user_username'], $classified_id),
                                     $url->url_base . $classified->classified_photo( './images/nophoto.gif' ),
                                     $misc->photo_size( $classified->classified_photo( './images/nophoto.gif' ),'50','50','w')
                                    ),
                              $ad_html );

      $params['transaction_text'] = semods::get_language_text(100016042) . " <a href='" . $url->url_create('classified', $user->user_info['user_username'], $classified_id) . "'>" . semods::get_language_text(100016043) . "</a>";

      break;



    // promote event - eventid, event_photo, event_photowidth, event_name,
    case 102:
      $event_id = semods::getpost('event_id');
      $event = new se_event( $user->user_info['user_id'], $event_id );

      if( $event->event_exists == 0 ) {
        $params['err_msg'] = 100016044;
        return false;
      }

      $ad_name = "Promoting <a href='" . $url->url_create('profile', $user->user_info['user_username']) . "'>" . $user->user_info['user_username'] . "</a>'s " . "<a href='" . "../event.php?event_id=" . $event_id . "'>event</a> " . " based on promotion template " . "<a href='admin_ads_edit.php?ad_id=" . $ad_template['ad_id'] . "'>" . $ad_template['ad_name'] . "</a>";

      $ad_html = str_replace( array( '[event]',
                                     '[eventphoto]',
                                     '[eventphotowidth]'),
                              array( $url->url_base . "event.php?event_id=" . $event_id,
                                     $url->url_base . $event->event_photo( './images/nophoto.gif' ),
                                     $misc->photo_size( $event->event_photo( './images/nophoto.gif' ),'50','50','w')
                                    ),
                              $ad_html );

      $params['transaction_text'] = semods::get_language_text(100016042) . " <a href='" . $url->url_base . "event.php?event_id=" . $event_id . "'>" . semods::get_language_text(100016045) . "</a>";
      break;



    // promote group - group_id, group_photo,group_photowidth, group_title
    case 103:

      $group_id = semods::getpost('group_id',0);
      $group = new se_group( $user->user_info['user_id'], $group_id );
      if($group->group_exists == 0) {
        $params['err_msg'] = 100016046;
        return false;
      }

      $ad_name = "Promoting <a href='" . $url->url_create('profile', $user->user_info['user_username']) . "'>" . $user->user_info['user_username'] . "</a>'s " . "<a href='" . "../group.php?group_id=" . $group_id . "'>group</a> " . " based on promotion template " . "<a href='admin_ads_edit.php?ad_id=" . $ad_template['ad_id'] . "'>" . $ad_template['ad_name'] . "</a>";

      $ad_html = str_replace( array( '[group]',
                                     '[groupphoto]',
                                     '[groupphotowidth]'),
                              array( $url->url_base . "group.php?group_id=" . $group_id,
                                     $url->url_base . $group->group_photo( './images/nophoto.gif' ),
                                     $misc->photo_size( $group->group_photo( './images/nophoto.gif' ),'50','50','w')
                                    ),
                              $ad_html );

      $params['transaction_text'] = semods::get_language_text(100016042) . " <a href='" . $url->url_base . "group.php?group_id=" . $group_id . "'>" . semods::get_language_text(100016047) . "</a>";
      break;



    // promote poll - username, pollid, poll_photo, poll_photowidth, poll_title
    case 104:
      $poll_id = semods::getpost('poll_id',0);
      $poll = new se_poll( $user->user_info['user_id'], $poll_id );
      if($poll->poll_exists == 0) {
        $params['err_msg'] = 100016048;
        return false;
      }

      $ad_name = "Promoting <a href='" . $url->url_create('profile', $user->user_info['user_username']) . "'>" . $user->user_info['user_username'] . "</a>'s " . "<a href='" . $url->url_create('poll', $user->user_info['user_username'], $poll_id) . "'>poll</a> " . " based on promotion template " . "<a href='admin_ads_edit.php?ad_id=" . $ad_template['ad_id'] . "'>" . $ad_template['ad_name'] . "</a>";

/*
      $ad_html = str_replace( array( '[poll]',
                                     '[pollphoto]',
                                     '[pollphotowidth]'),
                              array( $url->url_create('poll', $user->user_info['user_username'], $poll_id)  ,
                                     $url->url_base . $poll->poll_photo( './images/nophoto.gif' ),
                                     $misc->photo_size( $poll->poll_photo( './images/nophoto.gif' ),'50','50','w')
                                    ),
                              $ad_html );
*/

      $ad_html = str_replace( array( '[poll]'
                                   ),
                              array( $url->url_create('poll', $user->user_info['user_username'], $poll_id)
                                    ),
                              $ad_html );


      $params['transaction_text'] = semods::get_language_text(100016042) . " <a href='" . $url->url_create('poll', $user->user_info['user_username'], $poll_id) . "'>" . semods::get_language_text(100016049) . "</a>";
      break;

  }

  // Start date is today / tomorrow? need admin approval?

  $start_delay = $metadata['delaystart'];

  // Convert the time zone to a format compatible with
  //$adtz = ( $setting['setting_timezone'] ? $setting['setting_timezone'] . "00" : ' GMT' );
  //$adtz = preg_replace('/^([-]*)(\d\d\d)$/', '${1}0\2', $adtz);

  $timebase = time();
  //$ad_date_start = $start_delta == 0 ? time() : strtotime("+$start_delay days");
  $ad_date_start = strtotime("+$start_delay days", $timebase);

  $ad_date_end = strtotime( "+{$metadata['duration']} days", $ad_date_start );


  // TEMPLATE FIELDS

  $ad_limit_views = $ad_template['ad_limit_views'];
  $ad_limit_clicks = $ad_template['ad_limit_clicks'];
  $ad_limit_ctr = $ad_template['ad_limit_ctr'];
  $ad_position = $ad_template['ad_position'];
  $ad_levels_array = $ad_template['ad_levels'];
  $ad_subnets_array = $ad_template['ad_subnets'];
  $ad_public = $ad_template['ad_public'];
  //$ad_filename = '';
  $ad_paused = $metadata['reqapproval'];

  //$ad_name = htmlspecialchars( $ad_name, ENT_QUOTES );
  $ad_name = str_replace( "'", "\"",  $ad_name );
  $ad_html = addslashes( $ad_html );

  $database->database_query("INSERT INTO se_ads (ad_name,
                         ad_date_start,
                         ad_date_end,
                         ad_limit_views,
                         ad_limit_clicks,
                         ad_limit_ctr,
                         ad_public,
                         ad_position,
                         ad_levels,
                         ad_subnets,
                         ad_html,
                         ad_filename,
                         ad_paused
                         ) VALUES (
                         '$ad_name',
                         '$ad_date_start',
                         '$ad_date_end',
                         '$ad_limit_views',
                         '$ad_limit_clicks',
                         '$ad_limit_ctr',
                         '$ad_public',
                         '$ad_position',
                         '$ad_levels_array',
                         '$ad_subnets_array',
                         '$ad_html',
                         '$ad_filename',
                         $ad_paused
                         )");

  //$database->database_query("INSERT INTO se_semods_uppromotions (uppromotion_user_id, uppromotion_type)

  $transaction_message = 100016050;
  if($start_delta == 0) {
    $transaction_message .= 100016051;
  } else {
    $transaction_message .= 100016052;
  }

  if(is_numeric($transaction_message))
    $transaction_message = semods::get_language_text( $transaction_message );

  if(is_numeric($params['transaction_text']))
    $params['transaction_text'] = semods::get_language_text( $params['transaction_text'] );
    
  $params['transaction_message'] = $transaction_message;
  $params['transaction_text'] .=  " ({$upspender->upspender_info['userpointspender_title']})";
  $params['transaction_text'] = addslashes($params['transaction_text']);

  return true;

}



?>