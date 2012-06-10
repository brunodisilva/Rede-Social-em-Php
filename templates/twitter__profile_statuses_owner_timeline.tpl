	{* Timeline for Profile Owner *}
	{assign var='profile_statuses_user_timeline' value=$SEP_TwitterOwner->statuses_user_timeline($SEP_TwitterOwner->screen_name, null, null, null, null, $profile_statuses_user_timeline_page)}

	{if $profile_statuses_user_timeline->status}
		<div id="profile_statuses_user_timeline_page_{$profile_statuses_user_timeline_page}">
			
			<table border="0" class="SEP_Twitter_Table" cellpadding="0" cellspacing="0" width="100%">
			{foreach from=$profile_statuses_user_timeline->status item=elm}
				<tr id="profile_statuses_user_timeline_{$elm->id}">
					<td width="46" valign="top"><a href="http://twitter.com/{$elm->user->screen_name}" target="_blank"><img src="{$elm->user->profile_image_url}" border="0" width="38" height="38" class="SEP_Twitter_ProfileImg"></a></td>
					<td valign="top" class="SEP_Twitter_TextBig"><div class="SEP_Twitter_Tweet_Options">{if $elm->favorited == 'false'}<img src="./images/icons/twitter_icon_star_empty.gif" border="0" onClick="SEP_Twitter_favorites_create('{$elm->id}', this)">{else}<img src="./images/icons/twitter_icon_star_full.gif" border="0" style="visibility:visible" onClick="SEP_Twitter_favorites_destroy('{$elm->id}', this)">{/if}<br>{if $elm->user->screen_name == $SEP_TwitterUser->screen_name}<img src="./images/icons/twitter_icon_trash.gif" border="0" onClick="SEP_Twitter_statuses_destroy('{$elm->id}', this, 'profile_statuses_user_timeline_{$elm->id}')">{else}<img src="./images/icons/twitter_icon_reply.gif" border="0" onClick="SEP_Twitter_reply('{$elm->id}', '{$elm->user->screen_name}', {$instance_name}_instance)">{/if}</div><a href="http://twitter.com/{$elm->user->screen_name}" target="_blank"><b>{$elm->user->screen_name}</b></a> {$elm->text|SEP_Twitter_wordWrap}<div class="SEP_Twitter_Small">{$SEP_TwitterUser->timeAgo($elm->created_at)} {lang_print id=18910021} {$elm->source}</div></td>
				</tr>
			{/foreach}		
			</table>	
				
		</div>
		
		{* PREPARE DIV FOR NEXT PAGE BOX *}
		<div id="profile_statuses_user_timeline_page_{$profile_statuses_user_timeline_page+1}">
			<center>
			<br>[<b><a href="javascript:void(0)" onClick="SEP_Twitter_NextPage('profile_statuses_user_timeline_page', {$profile_statuses_user_timeline_page+1}, 'profile_statuses_user_timeline', '{$instance_name}', {$owner->user_info.user_id}); this.blur();">{lang_print id=18910022}</a></b>]
			<br><br>
			<div id="profile_statuses_user_timeline_page_{$profile_statuses_user_timeline_page+1}_spinner" style="display:none">
				<img src="./images/icons/twitter_spinner.gif" border="0">
			</div>
			</center>
		</div>		
	{/if}
