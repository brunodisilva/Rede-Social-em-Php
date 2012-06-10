  {* SHOW PUBLIC VERSION OF ACTIVITY LIST *}  
  
  {if $actions|@count > 0}
    <div class='portal_spacer'></div>
    
    <div class='portal_whatsnew'>

      {* SHOW ACTIONS *}
      {section name=actions_loop loop=$actions max=60}
        <div id='action_{$actions[actions_loop].action_id}' class='portal_action{if $smarty.section.actions_loop.first}_top{/if}'>
	  <table cellpadding='0' cellspacing='0'>
	  <tr>
	  <td nowrap width='50' align='left'>
<img src='{$user->user_photo2($actions[actions_loop].user_photo,$actions[actions_loop].action_user_id,'./images/nophoto.gif', TRUE)}' class='photo' width='40' height='40' border='0'>
</td>
	  <td valign='top'><img src='./images/icons/{$actions[actions_loop].action_icon}' border='0' class='icon'></td>
	  <td valign='top' width='100%'>
	    {assign var='action_date' value=$datetime->time_since($actions[actions_loop].action_date)}
	    <div class='portal_action_date'>{lang_sprintf id=$action_date[0] 1=$action_date[1]}</div>
	    {if $actions[actions_loop].action_media !== FALSE}{capture assign='action_media'}{section name=action_media_loop loop=$actions[actions_loop].action_media}<a href='{$actions[actions_loop].action_media[action_media_loop].actionmedia_link}'><img src='{$actions[actions_loop].action_media[action_media_loop].actionmedia_path}' border='0' width='{$actions[actions_loop].action_media[action_media_loop].actionmedia_width}' class='recentaction_media'></a>{/section}{/capture}{/if}
	    {capture assign='action_text'}{lang_sprintf id=$actions[actions_loop].action_text 1=$actions[actions_loop].action_vars[0] 2=$actions[actions_loop].action_vars[1] 3=$actions[actions_loop].action_vars[2] 4=$actions[actions_loop].action_vars[3] 5=$actions[actions_loop].action_vars[4] 6=$actions[actions_loop].action_vars[5] 7=$actions[actions_loop].action_vars[6]}{/capture}
	    {$action_text|replace:"[media]":$action_media|choptext:50:"<br>"}
          </td>
	  </tr>
	  </table>
        </div>
      {/section}
         
    </div>
  {/if}