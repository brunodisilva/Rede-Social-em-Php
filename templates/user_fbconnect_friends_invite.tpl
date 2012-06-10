{include file='header.tpl'}

{* $Id: user_fbconnect_friends_invite.tpl 21 2009-06-25 08:46:59Z SocialEngineAddOns $ *}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_fbconnect_friends_invite.php'>{lang_print id=650000051}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_fbconnect_friends.php'>{lang_print id=650001025}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_fbconnect_settings.php'>{lang_print id=650000052}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>

<img src='./images/icons/fbconnect-invite.gif' border='0' class='icon_big'>
<div class='page_header'> {lang_print id=650000059}  {$var_site_name}
</div>
<br />

{$friends_invite_form}

<br />

{include file='footer.tpl'}