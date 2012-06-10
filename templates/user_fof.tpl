{include file='header.tpl'}
{literal}
<script type="text/javascript">

function updateContainerFof(user_id) {
	new Request.HTML({
		method: 'get',
		url: 'user_fof_ajax.php?user_id='+user_id,
		data: { 'do': '1' },
		update:'fof_container',
		onRequest:function(){$('fof_container').fade('0')},
		onComplete:function(){$('fof_container').fade('1')}
	}).send();
}

</script>
{/literal}

<table cellpadding='0' cellspacing='0'>
<tr>
<td class='messages_left'>
	<img src='./images/icons/friend_add48.gif' border='0' class='icon_big'>
  	<div class='page_header' style="width:300px">{lang_print id=17000500}</div>
  	<div>{lang_print id=17000501}</div>
</td>
</tr>
</table
<br>
<div style="width: 700px; border-top: 1px dashed #cccccc; overflow: hidden; padding-top: 5px">
	{if $p_friends|@count > 0}
		<div id="fof_container" style="visibility: visible; opacity: 1; ">
		{section name=friend_loop loop=$p_friends}
		<div style="float:left; width:215px; padding:5px">
			<div style="float:left; padding-right:5px">
			<a href='{$url->url_create('profile',$p_friends[friend_loop]->user_info.user_username)}' title="{$p_friends[friend_loop]->user_info.user_username}">
				<img src='{$p_friends[friend_loop]->user_photo('./images/nophoto.gif', true)}' width='60' height='60' border='0' />
			</a>
			</div>
			<div>
			<a href='{$url->url_create('profile',$p_friends[friend_loop]->user_info.user_username)}' title="{$p_friends[friend_loop]->user_info.user_username}"><b>{$p_friends[friend_loop]->user_displayname|truncate:20:"...":true}</b></a><br/>
			<a href="javascript:TB_show('Add to My Friends', 'user_friends_manage.php?user={$p_friends[friend_loop]->user_info.user_username}&TB_iframe=true&height=300&width=450', '', './images/trans.gif');">{lang_print id=876}</a><br/>
			<a href="javascript:TB_show('Compose New Message', 'user_messages_new.php?to_user={$p_friends[friend_loop]->user_displayname}&to_id={$p_friends[friend_loop]->user_info.user_username}&TB_iframe=true&height=400&width=450', '', './images/trans.gif');">{lang_print id=791}</a><br/>
			<a href="javascript:updateContainerFof({$p_friends[friend_loop]->user_info.user_id});">{lang_print id=17000502}</a>
			</div>
		</div>
		{/section}
		</div>
	{else}
		<div style='margin:5px'>
			{lang_print id=17000503}	
		</div>
	{/if}
</div>

{include file='footer.tpl'}