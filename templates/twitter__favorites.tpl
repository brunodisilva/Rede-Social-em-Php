		{assign var='favorites' value=$SEP_TwitterUser->favorites(null, null, $favorites_page)}

		{if $favorites->status}
			<div id="favorites_page_{$favorites_page}">
				
				<table border="0" class="SEP_Twitter_Table" cellpadding="0" cellspacing="0" width="100%">
				{foreach from=$favorites->status item=elm}
					<tr id="favorites_{$elm->id}">
						<td width="46" valign="top"><a href="http://twitter.com/{$elm->user->screen_name}" target="_blank"><img src="{$elm->user->profile_image_url}" border="0" width="38" height="38" class="SEP_Twitter_ProfileImg"></a></td>
						<td valign="top" class="SEP_Twitter_TextBig"><div class="SEP_Twitter_Tweet_Options">{if $elm->favorited == 'false'}<img src="./images/icons/twitter_icon_star_empty.gif" border="0" onClick="SEP_Twitter_favorites_create('{$elm->id}', this)">{else}<img src="./images/icons/twitter_icon_star_full.gif" border="0" style="visibility:visible" onClick="SEP_Twitter_favorites_destroy('{$elm->id}', this)">{/if}<br>{if $elm->user->screen_name == $SEP_TwitterUser->screen_name}<img src="./images/icons/twitter_icon_trash.gif" border="0" onClick="SEP_Twitter_statuses_destroy('{$elm->id}', this, 'favorites_{$elm->id}')">{else}<img src="./images/icons/twitter_icon_reply.gif" border="0" onClick="SEP_Twitter_reply('{$elm->id}', '{$elm->user->screen_name}', {$instance_name}_instance)">{/if}</div><a href="http://twitter.com/{$elm->user->screen_name}" target="_blank"><b>{$elm->user->screen_name}</b></a> {$elm->text|SEP_Twitter_wordWrap}<div class="SEP_Twitter_Small">{$SEP_TwitterUser->timeAgo($elm->created_at)} {lang_print id=18910021} {$elm->source}</div></td>
					</tr>
				{/foreach}		
				</table>	
					
			</div>
			
			{* PREPARE DIV FOR NEXT PAGE BOX *}
			<div id="favorites_page_{$favorites_page+1}">
				<center>
				<br>[<b><a href="javascript:void(0)" onClick="SEP_Twitter_NextPage('favorites_page', {$favorites_page+1}, 'favorites', '{$instance_name}'); this.blur();">{lang_print id=18910022}</a></b>]
				<br><br>
				<div id="favorites_page_{$favorites_page+1}_spinner" style="display:none">
					<img src="./images/icons/twitter_spinner.gif" border="0">
				</div>
				</center>
			</div>		
		{/if}