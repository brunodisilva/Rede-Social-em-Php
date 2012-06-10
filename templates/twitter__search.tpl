	{assign var='search' value=$SEP_TwitterUser->search($twitter_q, null, 20, $search_page)}

	{if $search}
		<div id="search_page_{$search_page}">
			
			<table border="0" class="SEP_Twitter_Table" cellpadding="0" cellspacing="0" width="100%">
			{foreach from=$search->results item=elm}
				<tr id="search_{$elm->id}">
					<td width="46" valign="top"><a href="http://twitter.com/{$elm->from_user}" target="_blank"><img src="{$elm->profile_image_url}" border="0" width="38" height="38" class="SEP_Twitter_ProfileImg"></a></td>
					<td valign="top" class="SEP_Twitter_TextBig"><div class="SEP_Twitter_Tweet_Options">{if 1==2}{if $SEP_TwitterUser->account_verify_credentials()}{if $elm->favorited == 'false'}<img src="./images/icons/twitter_icon_star_empty.gif" border="0" onClick="SEP_Twitter_favorites_create('{$elm->id}', this)">{else}<img src="./images/icons/twitter_icon_star_full.gif" border="0" style="visibility:visible" onClick="SEP_Twitter_favorites_destroy('{$elm->id}', this)">{/if}<br>{if $elm->user->screen_name == $SEP_TwitterUser->screen_name}<img src="./images/icons/twitter_icon_trash.gif" border="0" onClick="SEP_Twitter_statuses_destroy('{$elm->id}', this, 'search_{$elm->id}')">{else}<img src="./images/icons/twitter_icon_reply.gif" border="0" onClick="SEP_Twitter_reply('{$elm->id}', '{$elm->user->screen_name}', {$instance_name}_instance)">{/if}{/if}{/if}</div><a href="http://twitter.com/{$elm->from_user}" target="_blank"><b>{$elm->from_user}</b></a> {$elm->text}<div class="SEP_Twitter_Small">{$SEP_TwitterUser->timeAgo($elm->created_at)} {lang_print id=18910021} {$elm->source|SEP_Twitter_convert2html}</div></td>
				</tr>
			{/foreach}		
			</table>	
				
		</div>	
		
		{* PREPARE DIV FOR NEXT PAGE BOX *}
		<div id="search_page_{$search_page+1}">
			<center>
			<br>[<b><a href="javascript:void(0)" onClick="SEP_Twitter_NextPage('search_page', {$search_page+1}, 'search', '{$instance_name}', null, '{$twitter_q|escape:'javascript'}'); this.blur();">{lang_print id=18910022}</a></b>]
			<br><br>
			<div id="search_page_{$search_page+1}_spinner" style="display:none">
				<img src="./images/icons/twitter_spinner.gif" border="0">
			</div>
			</center>
		</div>	
		
	{else}
	
		{lang_print id=18910023}
					
	{/if}
