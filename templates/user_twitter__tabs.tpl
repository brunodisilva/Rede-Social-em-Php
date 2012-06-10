
<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
{* 1=active, 2=inactive *}
<td class='tab0'>&nbsp;</td>

{if $SEP_TwitterUser->account_verify_credentials()}
	<td class='tab{if $_active_tab == 'timeline_public'}1{else}2{/if}' NOWRAP><a href="twitter_timeline_public.php">{lang_print id=18910010}</a></td>
	<td class='tab'>&nbsp;</td>
	
	<td class='tab{if $_active_tab == 'timeline_friends'}1{else}2{/if}' NOWRAP><a href="user_twitter_timeline_friends.php">{lang_print id=18910011}</a></td>
	<td class='tab'>&nbsp;</td>
	
	<td class='tab{if $_active_tab == 'timeline_user'}1{else}2{/if}' NOWRAP><a href="user_twitter_timeline_user.php">{lang_print id=18910012}</a></td>
	<td class='tab'>&nbsp;</td>
	
	<td class='tab{if $_active_tab == 'people'}1{else}2{/if}' NOWRAP><a href="user_twitter_people.php">{lang_print id=18910013}</a></td>
	<td class='tab'>&nbsp;</td>
	
	<td class='tab{if $_active_tab == 'settings'}1{else}2{/if}' NOWRAP><a href="user_twitter_settings.php">{lang_print id=18910014}</a></td>
	<td class='tab'>&nbsp;</td>
{else}
	<td class='tab{if $_active_tab == 'timeline_public'}1{else}2{/if}' NOWRAP><a href="twitter_timeline_public.php">{lang_print id=18910015}</a></td>
	<td class='tab'>&nbsp;</td>

	<td class='tab{if $_active_tab == 'settings'}1{else}2{/if}' NOWRAP><a href="user_twitter_settings.php">{lang_print id=18910016}</a></td>
	<td class='tab'>&nbsp;</td>	
{/if}

<td class='tab3'>&nbsp;</td>
</tr>
</table>
<br>