{include file='header.tpl'}

{include file='user_twitter__tabs.tpl'}


{if $SEP_TwitterUser->account_verify_credentials()}

	{include file='twitter__tweet.tpl' instance_name='SEP_Twitter_Tweet_1'}

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	
		<td valign="top" width="45%">
			<div class="SEP_Twitter_Header">{lang_print id=18910039}</div>
			{SEP_Twitter_Include_Cached file='twitter__statuses_user_timeline.tpl' instance_name='SEP_Twitter_Tweet_1' statuses_user_timeline_page=1 cache_lifetime=$SEP_Twitter_Config.cache_lifetime_statuses_user_timeline user_id=$user->user_info.user_id}		
		</td>
	
		<td width="10%">&nbsp;</td>
	
		<td valign="top" width="45%">
			<div class="SEP_Twitter_Header">{lang_print id=18910040}</div>
			{SEP_Twitter_Include_Cached file='twitter__favorites.tpl' instance_name='SEP_Twitter_Tweet_1' favorites_page=1 cache_lifetime=$SEP_Twitter_Config.cache_lifetime_favorites user_id=$user->user_info.user_id}		
		</td>
	
	</tr>
	</table>

{else}
	{lang_print id=18910036}
{/if}

{include file='footer.tpl'}