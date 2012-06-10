{if $users}
	{section name=user_loop loop=$users}
	<a href='{$url->url_create('profile',$users[user_loop]->user_info.user_username)}'><img src='{$users[user_loop]->user_photo('./images/nophoto.gif', TRUE)}' class='photo' style='' height='60' border='0' alt="{lang_sprintf id=509 1=$users[user_loop]->user_displayname_short}" title="{$users[user_loop]->user_displayname|truncate:20:"...":true}"></a>
	{/section}
{else}
	<p>{lang_print id=18910074}</p>
{/if}