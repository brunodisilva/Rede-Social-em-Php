{include file='header.tpl'}

{include file='user_twitter__tabs.tpl'}

{if $SEP_TwitterUser->account_verify_credentials()}
	{include file='twitter__tweet.tpl' instance_name='SEP_Twitter_Tweet_1' show=true}
	
	{lang_print id=18910043}<br><br>
	
	<form name="twitter_connect_form1" action="user_twitter_settings.php" method="post">
		<input type="hidden" name="task" value="disconnect">
		<input type="submit" value="{lang_print id=18910044}" class="button">
	</form>
		
{else}
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top"><img src="./images/twitter_bird.gif" border="0" title="{lang_print id=18910045}" alt="{lang_print id=18910045}"></td>
		<td width="60">&nbsp;</td>
		<td valign="top">

			{if $error}<div class="SEP_Twitter_Error">{lang_print id=18910046}</div>{/if}

			{if $setting.setting_twitter_authentication == 'oauth'}
				{* OAuth Connect *}
				<br>
				<img src="./images/icons/twitter.gif" class="icon" border="0" style="margin-top:-2px;"> <a href="{$twitter_auth_url}" style="font-size:1.1em"><b>{lang_print id=18910047}</b></a> <a href="{$twitter_auth_url}" style="font-size:1.1em"><img src="./images/icons/twitter_connect.png" class="icon" border="0" style="margin-top:-4px;" title="{lang_print id=18910047}"></a>
				<div style="margin-top:6px;width:200px;">{lang_print id=18910048}</div>
				
				
			{else}
				{* Standard Connect *}	
				
				<img src="./images/icons/twitter.gif" class="icon" border="0" style="margin-top:-2px;"> <b>{lang_print id=18910049}</b>
				<form name="twitter_connect_form1" action="user_twitter_settings.php" method="post" style="margin-top:5px;">
					<input type="hidden" name="task" value="connect">
					{lang_print id=18910050}<br>
					<input type="text" class="text" name="screen_name" value="{$screen_name}"><br>
					<br>
					{lang_print id=18910051}<br>
					<input type="password" class="text" name="password" value="{$password}"><br>
					<br>
					<input type="submit" value="{lang_print id=18910052}" class="button">
				</form>
		
			{/if}
		</td>
	</tr>
	</table>

{/if}

{include file='footer.tpl'}