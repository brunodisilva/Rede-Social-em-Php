
	{assign var='profile_statuses_friends' value=$SEP_TwitterOwner->statuses_friends($SEP_TwitterOwner->screen_name, null, $profile_statuses_friends_page)}

	{if $profile_statuses_friends->user}
		<div id="profile_statuses_friends_page_{$profile_statuses_friends_page}">
			
			{foreach from=$profile_statuses_friends->user item=elm}
				<a href="http://twitter.com/{$elm->screen_name}" target="_blank" title="{$elm->name} ({$elm->screen_name}): {$elm->status->text}"><img src="{$elm->profile_image_url}" border="0" width="32" height="32"></a>
			{/foreach}		
				
		</div>
		
		{* PREPARE DIV FOR NEXT PAGE BOX *}
		<div id="profile_statuses_friends_page_{$profile_statuses_friends_page+1}">
			<center>
			<br>[<b><a href="javascript:void(0)" onClick="SEP_Twitter_NextPage('profile_statuses_friends_page', {$profile_statuses_friends_page+1}, 'profile_statuses_friends', '{$instance_name}', {$owner->user_info.user_id}); this.blur();">{lang_print id=18910033}</a></b>]
			<br><br>
			<div id="profile_statuses_friends_page_{$profile_statuses_friends_page+1}_spinner" style="display:none">
				<img src="./images/icons/twitter_spinner.gif" border="0">
			</div>
			</center>
		</div>		
	{/if}
