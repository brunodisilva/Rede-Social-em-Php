	{assign var='statuses_friends' value=$SEP_TwitterUser->statuses_friends(null, null, $statuses_friends_page)}

	{if $statuses_friends->user}
		<div id="statuses_friends_page_{$statuses_friends_page}">
			
			<table border="0" class="SEP_Twitter_Table" cellpadding="0" cellspacing="0" width="100%">
			{foreach from=$statuses_friends->user item=elm}
				<tr id="statuses_friends_{$elm->id}">
					<td width="46" valign="top"><a href="http://twitter.com/{$elm->screen_name}" target="_blank"><img src="{$elm->profile_image_url}" border="0" width="38" height="38" class="SEP_Twitter_ProfileImg"></a></td>
					<td valign="top" class="SEP_Twitter_TextBig"><div class="SEP_Twitter_Tweet_Options">{if $elm->status->text}{if $elm->status->favorited == 'false'}<img src="./images/icons/twitter_icon_star_empty.gif" border="0" onClick="SEP_Twitter_favorites_create('{$elm->status->id}', this)">{else}<img src="./images/icons/twitter_icon_star_full.gif" border="0" style="visibility:visible" onClick="SEP_Twitter_favorites_destroy('{$elm->status->id}', this)">{/if}{/if}<br>{if $elm->screen_name == $SEP_TwitterUser->screen_name}<img src="./images/icons/twitter_icon_trash.gif" border="0" onClick="SEP_Twitter_statuses_destroy('{$elm->status->id}', this, 'statuses_friends_{$elm->status->id}')">{else}<img src="./images/icons/twitter_icon_reply.gif" border="0" onClick="SEP_Twitter_reply('{$elm->status->id}', '{$elm->screen_name}', {$instance_name}_instance)">{/if}</div><a href="http://twitter.com/{$elm->screen_name}" target="_blank"><b>{$elm->screen_name}</b></a> {$elm->status->text|SEP_Twitter_wordWrap}<div class="SEP_Twitter_Small">{if $elm->status->text}{$SEP_TwitterUser->timeAgo($elm->status->created_at)} {lang_print id=18910021} {$elm->status->source}{/if} <span class="SEP_Twitter_OptionsLink" id="SEP_Twitter_Options_{$elm->status->id}"><a href="javascript:void(0)" onClick="SEP_Twitter_friendships_destroy('{$elm->screen_name}', 'SEP_Twitter_Options_Spinner_{$elm->status->id}', 'SEP_Twitter_Options_{$elm->status->id}')">{lang_print id=18910032}</a></span> <span id="SEP_Twitter_Options_Spinner_{$elm->status->id}" style="display:none"><img src="./images/icons/twitter_icon_throbber.gif" border="0" class="icon"></span></div></td>
				</tr>
			{/foreach}		
			</table>	
				
		</div>
		
		{* PREPARE DIV FOR NEXT PAGE BOX *}
		<div id="statuses_friends_page_{$statuses_friends_page+1}">
			<center>
			<br>[<b><a href="javascript:void(0)" onClick="SEP_Twitter_NextPage('statuses_friends_page', {$statuses_friends_page+1}, 'statuses_friends', '{$instance_name}'); this.blur();">{lang_print id=18910033}</a></b>]
			<br><br>
			<div id="statuses_friends_page_{$statuses_friends_page+1}_spinner" style="display:none">
				<img src="./images/icons/twitter_spinner.gif" border="0">
			</div>
			</center>
		</div>		
	{/if}
