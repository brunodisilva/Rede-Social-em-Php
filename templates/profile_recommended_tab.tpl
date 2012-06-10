{if $owner->level_info.level_recommended_allow != 0}

  <div class='profile_headline'>
    {lang_print id=11140101}
  </div>
 
    <div class='recommended_stat'>{lang_print id=11140104} <b>{$recommended_total_recommenders}</b> {lang_print id=11140105}</div>
    {if $recommended_recommenders|@count > 0}
      <table cellpadding='0' cellspacing='0' class='recommended_profile_entry'>
      {foreach from=$recommended_recommenders item=recommended}
        {assign var='status_date' value=$datetime->time_since($recommended->vote_date)}
        <tr>
          <td width='50'><a href='{$url->url_create('profile',$recommended->rc_user->user_username)}'><img src='{$recommended->rc_user->se_user->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($recommended->rc_user->se_user->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'></a></td>
          <td valign='top'><a href='{$url->url_create('profile',$recommended->rc_user->user_username)}'><b>{$recommended->rc_user->user_displayname}</b></a>
            <br>{lang_sprintf id=$status_date[0] 1=$status_date[1]}
            <div class='recommended_comment'>{$recommended->vote_comment|nl2br}</div>
          </tr>
        </tr>
      {/foreach}
      </table>
    {/if}    
    <div class='recommended_link'><a href="recommended_recommenders.php?user={$owner->user_info.user_username}">{lang_print id=11140106}</a></div>
    <div class='recommended_stat'>{lang_print id=11140107} <b>{$recommended_total_recommendees}</b> {lang_print id=11140108}</div>
    {if $recommended_recommendees|@count > 0}
      <table cellpadding='0' cellspacing='0' class='recommended_profile_entry'>
      {foreach from=$recommended_recommendees item=recommended}
        {assign var='status_date' value=$datetime->time_since($recommended->vote_date)}
        <tr>
          <td width='50'><a href='{$url->url_create('profile',$recommended->rc_user->user_username)}'><img src='{$recommended->rc_user->se_user->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($recommended->rc_user->se_user->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'></a></td>
          <td valign='top'><a href='{$url->url_create('profile',$recommended->rc_user->user_username)}'><b>{$recommended->rc_user->user_displayname}</b></a>
            <br>{lang_sprintf id=$status_date[0] 1=$status_date[1]}
            <div class='recommended_comment'>{$recommended->vote_comment|nl2br}</div>
          </tr>
        </tr>
      {/foreach}
      </table>
    {/if}   
    <div class='recommended_link'><a href="recommended_recommendees.php?user={$owner->user_info.user_username}">{lang_print id=11140109}</a></div>

{/if}