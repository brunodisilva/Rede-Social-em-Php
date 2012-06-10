{include file='header.tpl'}

{* $Id: user_fbconnect_friends.tpl 21 2009-06-25 08:46:59Z SocialEngineAddOns $ *}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_fbconnect_friends_invite.php'>{lang_print id=650000051}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_fbconnect_friends.php'>{lang_print id=650001025}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_fbconnect_settings.php'>{lang_print id=650000052}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>

<img src='./images/icons/facebook-friend.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=650001021} {$fbconnect_setting.site_name}</div>
<div>{lang_print id=650001022}</div>
<br />
<br />

{* DISPLAY FRIENDS *}
{if $total_facebook_friends != 0}
  <div class="fleft" style="margin-left:10px;clear:both;"> 
		{section name=friend_loop loop=$user_fbconnected_friends}
			<div align="center" class="fleft" style="text-align:center;padding: 5px; width: 100px;" id='thumb_user_fbconnected_friend{$smarty.section.friend_loop.iteration}'>					
				<a href='{$url->url_create('profile',$user_fbconnected_friends[friend_loop]->user_info.user_username)}'>
					<img src='{$user_fbconnected_friends[friend_loop]->user_photo('./images/nophoto.gif', TRUE)}' border='0' class='photo' style='margin-left: auto; margin-right: auto;display: block; width='60' height='60' border='0' alt="{lang_sprintf id=509 1=$user_fbconnected_friends[friend_loop]->user_displayname_short}">
				</a>
		 		<a href='{$url->url_create('profile',$user_fbconnected_friends[friend_loop]->user_info.user_username)}'>
					{$user_fbconnected_friends[friend_loop]->user_displayname}
				</a>
      </div>
     {/section}
  </div>

{else}
  {* DISPLAY MESSAGE IF NO FRIENDS ON LIST *}
    <br>
    <table cellpadding='0' cellspacing='0' align='center'>
    <tr><td class='result'>
      <img src='./images/icons/bulb16.gif' border='0' class='icon'>{lang_print id=650001023}
    </td></tr>
    </table>

{/if}

{include file='footer.tpl'}