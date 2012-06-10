{include file='header.tpl'}


{* $Id: user_fbconnect_settings.tpl 21 2009-04-26 08:46:59Z SocialEngineAddOns $ *}

{if !empty($error_message_facebook_connect)}
  <div class='error'><img src='./images/error.gif' border='0' class='icon'> {lang_print id=$error_message_facebook_connect}</div>
{/if}

{if $fbconnect_user_status == 2 && empty($error_message_facebook_connect)} {* user has not associated with FB Connect *}

   	<span style="line-height:22px;">
	   	{lang_print id=650000066}<br />
	   	{lang_print id=650000067}
   	</span>
   	<br />
   	<br />
     {$fb_connect_button}
     
{elseif $fbconnect_user_status == 3 && empty($error_message_facebook_connect)} {* User is already associated his profile with facebook but currently he is not logged in as a facebook connect *}
   	<span style="line-height:22px;">
   	<b>{lang_print id=650001027}</b></span><br /><br />
     {$fb_connect_button}
     
{elseif empty($error_message_facebook_connect) }

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_fbconnect_friends_invite.php'>{lang_print id=650000051}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_fbconnect_friends.php'>{lang_print id=650001025}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_fbconnect_settings.php'>{lang_print id=650000052}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>



	<div class="width-full fleft">
		<img src="./images/icons/facebook-import.gif" alt="Import" align="left" class="facebook-import" />
		<div class="fleft margin-left-10">
			<div class="page_header fleft">
				{lang_print id=650000062}<br />
			</div>
			<div class="fleft clr">
				{lang_print id=650000063}
			</div>
			
		  {if !empty($success_message) }
	     <br /><br /><div class='success-message-fbconnect fleft clr'><img src = './images/success.gif' border = '0' class = 'icon'>{lang_print id=$success_message}</div>
	  {/if}
		</div>
	

		
		<form method="POST" action="user_fbconnect_settings.php">
			<div class="fleft width-full margin-top-20">
				<div class="signup_header" style="width:550px;">
					{lang_print id=650000054} {$fbname}
				</div>
				<div class="fleft margin-left-10">
					<div class='portal_login' style="padding:10px;">
						{$avatar}
					</div>
				</div>
				<div class="fleft margin-left-10 checkbox-row">
					<input type="hidden" name="visibility_hid" value="{$user_visibility}" />
					<input type="checkbox" name="visibility" value="1" align="bottom"{if $user_visibility == 1} checked="checked"{/if} />&nbsp; {lang_print id=650000055} {$var_site_name}.<br />
					<span class="message-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{lang_print id=650000056}</span><br />
					
					<input type="hidden" name="fb_avatar_hid" value="{$user_fb_avatar}" />
					<div class="fleft margin-top-10 checkbox-row">
						<input type="checkbox" name="fb_avatar" value="1" align="bottom"{if $user_fb_avatar == 1} checked="checked"{/if} />&nbsp;{lang_print id=650000057} {$var_site_name}.
					</div><br />
				
				{foreach from=$checkboxes_options key=profile_key item=profile_value}
				
					<div class="fleft margin-top-10 checkbox-row">
						<input type="checkbox" name="{$profile_key}" value="{$profile_key}" align="bottom"{if $profile_key|in_array:$default_value} checked="checked"{/if} />
						{$profile_value}
					</div>
					
				{/foreach}	
					
					<div class="fleft clr margin-top-10">
						<input class="button" value="Submit" type="submit" name="op" border="0" />
					</div>
				</div>
			</div>
		</form>
	</div>

{/if}

{include file='footer.tpl'}