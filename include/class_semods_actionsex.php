<?

//  THIS CLASS IS USED TO OUTPUT AND UPDATE RECENT ACTIVITY ACTIONS
//  METHODS IN THIS CLASS:
//  class se_actionsex




/******************  CLASS se_actionsex  ******************/


class se_actionsex extends se_actions {

    // actions_add hook
	function actions_add($user, $actiontype_name, $replace = Array(), $action_media = Array(), $timeframe = 0, $replace_media = false, $action_object_owner = "", $action_object_owner_id = 0, $action_object_privacy = 0) {

    /* ASSIGN ACTIVITY POINTS */

    

        // TBD - special treatments,
        // "newmedia" - updated in footer, because need to count amount of uploaded files
         $excluded_actions = array( "newmedia" );

         if(!in_array($actiontype_name, $excluded_actions))
            userpoints_update_points( $user->user_info['user_id'], $actiontype_name );


    /* UPLOAD FILES */
    
    if($actiontype_name == "newmedia") {
      global $file_result;
      
      $uploaded_files_count = 0;
      foreach($file_result as $file) {
        if($file['is_error'] == 0)
          $uploaded_files_count++;
      }
      
      if($uploaded_files_count)
        userpoints_update_points( $user->user_info['user_id'], 'newmedia', $uploaded_files_count );
      
    }


    /* CHARGING FOR ACTIONS */

    // this is hacky. TBD: generic mechanism?

    $chargeable_actiontypes = array( 'postclassified'   =>  1,
                                     'newevent'         =>  2,
                                     'newgroup'         =>  3,
                                     'newpoll'          =>  4 );

    if(array_key_exists($actiontype_name, $chargeable_actiontypes)) {
        if(semods::get_setting('userpoints_charge_' . $actiontype_name)) {
            $upspender = new semods_upspender( $chargeable_actiontypes[$actiontype_name] );
            $upspender->transact( $user );
        }
    }




    /* CUSTOM REWARDABLE ACTIONS */

    if( $actiontype_name == "votepoll") {
        // poll id is #3 in array
        // owner_username is #2 in array
        userpoints_reward_votepoll( $replace[3] );
    }



        

     /* CALL PARENTS */

     return parent::actions_add($user, $actiontype_name, $replace, $action_media, $timeframe, $replace_media, $action_object_owner, $action_object_owner_id, $action_object_privacy);


    } // END actions_add() METHOD


}

?>