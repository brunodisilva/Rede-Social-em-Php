{if $user->user_info.user_id == $owner->user_info.user_id}
	{* OWNER VIEW *}
		
	{if $SEP_TwitterOwner->account_verify_credentials()}
		{* USER HAS TWITTER INFORMATION SAVED AND IS LOGGED IN *}
		
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td colspan="3" valign="top" align="left">
					{include file='twitter__tweet.tpl' instance_name='SEP_Twitter_Tweet_1'}
				</td>
			</tr>
			<tr>
				<td valign="top">
					{* DISPLAY OWNER TIMELINE *}
					{SEP_Twitter_Include_Cached file='twitter__profile_statuses_owner_timeline.tpl' instance_name='SEP_Twitter_Tweet_1' profile_statuses_owner_timeline_page=1 cache_lifetime=$SEP_Twitter_Config.cache_lifetime_profile_statuses_owner_timeline user_id=$owner->user_info.user_id}
				</td>
				<td width="25">&nbsp;</td>
				<td width="175" valign="top" align="right">
					{* DISPLAY FRIENDS *}
					<div class="SEP_Twitter_Header">{lang_print id=18910060} ({$SEP_TwitterOwner->account_obj->friends_count})</div>			
					{SEP_Twitter_Include_Cached file='twitter__profile_statuses_friends.tpl' instance_name='SEP_Twitter_Tweet_1' profile_statuses_friends_page=1 cache_lifetime=$SEP_Twitter_Config.cache_lifetime_profile_statuses_owner_friends user_id=$owner->user_info.user_id}
					
					<br><br>
					
					{* DISPLAY FOLLOWERS *}
					<div class="SEP_Twitter_Header">{lang_print id=18910061} ({$SEP_TwitterOwner->account_obj->followers_count})</div>						
					{SEP_Twitter_Include_Cached file='twitter__profile_statuses_followers.tpl' instance_name='SEP_Twitter_Tweet_1' profile_statuses_followers_page=1 cache_lifetime=$SEP_Twitter_Config.cache_lifetime_profile_statuses_owner_followers user_id=$owner->user_info.user_id}						
				</td>
				
			</tr>
			</table>

	
	{else}
		{* USER HAS NO TWITTER INFORMATION SAVED *}
		<b>{lang_print id=18910036}</b>
		<br><br>
		<img src="./images/icons/twitter.gif" border="0" class="icon"> <a href="user_twitter_settings.php">{lang_print id=18910065}</a>
			
	{/if}	
	
{else}
	{* GUEST VIEW *}

	{if $SEP_TwitterOwner->account_verify_credentials()}
		{* USER HAS TWITTER INFORMATION SAVED AND IS LOGGED IN *}
			
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td colspan="3" valign="top" align="left">
					{include file='twitter__tweet.tpl' instance_name='SEP_Twitter_Tweet_1'}
				</td>
			</tr>
			<tr>
				<td valign="top">
					{* DISPLAY USER TIMELINE *}
					{SEP_Twitter_Include_Cached file='twitter__profile_statuses_user_timeline.tpl' instance_name='SEP_Twitter_Tweet_1' profile_statuses_user_timeline_page=1 cache_lifetime=$SEP_Twitter_Config.cache_lifetime_profile_statuses_guest_timeline user_id=$user->user_info.user_id}					
				</td>
				<td width="25">&nbsp;</td>
				<td width="175" valign="top" align="right">
					{* DISPLAY FRIENDS *}
					<div class="SEP_Twitter_Header">{lang_print id=18910062} ({$SEP_TwitterOwner->account_obj->friends_count})</div>			
					{SEP_Twitter_Include_Cached file='twitter__profile_statuses_friends.tpl' instance_name='SEP_Twitter_Tweet_1' profile_statuses_friends_page=1 cache_lifetime=$SEP_Twitter_Config.cache_lifetime_profile_statuses_guest_friends user_id=$owner->user_info.user_id}
					
					<br><br>
					
					{* DISPLAY FOLLOWERS *}
					<div class="SEP_Twitter_Header">{lang_print id=18910063} ({$SEP_TwitterOwner->account_obj->followers_count})</div>						
					{SEP_Twitter_Include_Cached file='twitter__profile_statuses_followers.tpl' instance_name='SEP_Twitter_Tweet_1' profile_statuses_followers_page=1 cache_lifetime=$SEP_Twitter_Config.cache_lifetime_profile_statuses_guest_followers user_id=$owner->user_info.user_id}						
				</td>
				
			</tr>
			</table>
	
	{else}
		{lang_print id=18910064}
	{/if}


{/if}


<br style="clear:both">
	