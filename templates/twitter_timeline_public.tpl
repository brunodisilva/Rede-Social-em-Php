{include file='header.tpl'}

{include file='user_twitter__tabs.tpl'}

{if $SEP_TwitterUser->account_verify_credentials()}
	{include file='twitter__tweet.tpl' instance_name='SEP_Twitter_Tweet_1'}
{/if}

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	
		<td valign="top" width="45%">
		
			{if $twitter_q}
				{* SEARCH RESULT *}
				<div class="SEP_Twitter_Header">{lang_print id=18910024} &laquo; {$twitter_q} &raquo;</div>
				{SEP_Twitter_Include_Cached file='twitter__search.tpl' instance_name='SEP_Twitter_Tweet_1' search_page=1 twitter_q=$twitter_q cache_lifetime=$SEP_Twitter_Config.cache_lifetime_search user_id=$user->user_info.user_id search_hash=$twitter_q}		
			{else}
				{* PUBLIC TIMELINE *}			
				<div class="SEP_Twitter_Header">{lang_print id=18910025}</div>
				{SEP_Twitter_Include_Cached file='twitter__statuses_public_timeline.tpl' instance_name='SEP_Twitter_Tweet_1' cache_lifetime=$SEP_Twitter_Config.cache_lifetime_statuses_public_timeline user_id=$user->user_info.user_id}		
			{/if}
			
		</td>
	
		<td width="10%">&nbsp;</td>
	
		<td valign="top" width="45%">
			<div class="SEP_Twitter_Header">{lang_print id=18910026}</div>
			{SEP_Twitter_Include_Cached file='twitter__search_trends.tpl' cache_lifetime=$SEP_Twitter_Config.cache_lifetime_trends search_hash='__trends' site_search=true}
			
			<br><br>
			
			<div class="SEP_Twitter_Header">{lang_print id=18910027}</div>
			<form action="twitter_timeline_public.php" method="get">
			<input type="text" class="text" name="q" id="SEP_Twitter_Tweet_Search1" value="{$twitter_q}" style="padding:3px;"> <input type="submit" value="{lang_print id=18910028}" class="button">
			</form>
			
			<br><br>
			
			<div class="SEP_Twitter_Header">{lang_print id=18910029}</div>
			{$random_members}
						
		</td>
	
	</tr>
	</table>


{include file='footer.tpl'}