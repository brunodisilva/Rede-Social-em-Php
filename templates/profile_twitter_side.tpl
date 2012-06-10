
<table cellpadding='0' cellspacing='0' width='100%' style='margin-bottom: 10px;'>
  <tr>
    <td class='header'>{lang_print id=18910045}</td>
  </tr>
  <tr>
    <td class='profile'>
		<div style="float:right;display:none" id="SEP_Twitter_Ajax_Spinner1"><img src="./images/icons/twitter_spinner.gif" border="0"></div>

		{if $user->user_info.user_id == $owner->user_info.user_id}
			{* OWNER VIEW *}
	
			{if $SEP_TwitterOwner->account_verify_credentials()}
				<table border="0" cellpadding="2" cellspacing="1">
				<tr><td>{lang_print id=18910066}</td><td align="right">{$SEP_TwitterOwner->account_obj->friends_count}</td><td align="right">[<a href="./user_twitter_people.php">{lang_print id=18910068}</a>]</td></tr>
				<tr><td>{lang_print id=18910067}</td><td align="right">{$SEP_TwitterOwner->account_obj->followers_count}</td><td align="right">[<a href="./user_twitter_people.php">{lang_print id=18910068}</a>]</td></tr>
				</table>
			{else}
				{lang_print id=18910069}
			{/if}
			
		{else}
			{* GUEST VIEW *}
		
			{if $SEP_TwitterOwner->account_verify_credentials()}
				<table border="0" cellpadding="2" cellspacing="1">
				<tr><td>{lang_print id=18910066}</td><td align="right">{$SEP_TwitterOwner->account_obj->friends_count}</td><td align="right">&nbsp;</td></tr>
				<tr><td>{lang_print id=18910067}</td><td align="right">{$SEP_TwitterOwner->account_obj->followers_count}</td><td align="right">&nbsp;</td></tr>
				</table>
				
				{if $SEP_TwitterUser->account_verify_credentials()}
					{* CHECK FRIENDSHIP BETWEEN USER AND PROFILE OWNER *}
					{if $SEP_TwitterUser->friendships_exists($SEP_TwitterUser->screen_name, $SEP_TwitterOwner->screen_name)}
						<div id="SEP_Twitter_profileSide_friendships_destroy">{lang_print id=18910070} <a href="javascript:void(0)" onClick="SEP_Twitter_friendships_destroy('{$SEP_TwitterOwner->screen_name}', 'SEP_Twitter_Ajax_Spinner1', 'SEP_Twitter_profileSide_friendships_destroy')">{lang_print id=18910071}</a></div>
					{else}
						<div id="SEP_Twitter_profileSide_friendships_create"><a href="javascript:void(0)" onClick="SEP_Twitter_friendships_create('{$SEP_TwitterOwner->screen_name}', 'SEP_Twitter_Ajax_Spinner1', 'SEP_Twitter_profileSide_friendships_create')">{lang_print id=18910072}</a></div>
					{/if}
					
				{/if}
					
			{else}
				{lang_print id=18910073}
			{/if}		
			
		{/if}

    </td>
  </tr>
</table>


