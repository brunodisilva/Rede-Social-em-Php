{* SHOW MOST POPULAR USERS (MOST FRIENDS) *}
  {if $setting.setting_connection_allow != 0}
    
    {section name=friends_loop loop=$friends max=10}
        {* START NEW ROW *}
        {cycle name="startrow2" values="<table cellpadding='0' cellspacing='0' align='middle'><tr>,,,,"}
        <td class='portal_member'><a href='{$url->url_create('profile',$friends[friends_loop].friend->user_info.user_username)}'>{$friends[friends_loop].friend->user_displayname|truncate:15:"...":true}<br><img src='{$friends[friends_loop].friend->user_photo('./images/nophoto.gif', TRUE)}' class='photo' width='60' height='60' border='0'></a><br>{lang_sprintf id=669 1=$friends[friends_loop].total_friends}</td>
        {* END ROW AFTER 5 RESULTS *}
        {if $smarty.section.friends_loop.last == true}
          </tr></table>
        {else}
          {cycle name="endrow2" values=",,,,</tr></table>"}
        {/if}
      {sectionelse}
        {lang_print id=670}
      {/section}
    </div>
    <div class='portal_spacer'></div>
  {/if}

</div>