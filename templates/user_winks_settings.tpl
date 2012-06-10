{include file='header.tpl'}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_winks_incoming.php'>{lang_print id=14000060}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_winks_outgoing.php'>{lang_print id=14000061}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_winks_settings.php'>{lang_print id=14000062}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_winks_topusers.php'>{lang_print id=14000063}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>


<img src='./images/icons/wink_big.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=14000062}</div>
<div>{lang_print id=14000080}</div>

<br><br>

{* SHOW SUCCESS MESSAGE *}
{if $result != 0}
  <table cellpadding='0' cellspacing='0'><tr><td class='result'>
  <div class='success'><img src='./images/success.gif' border='0' class='icon'> {lang_print id=14000081}</div>
  </td></tr></table>
{/if}

<br>

<form action='user_winks_settings.php' method='POST'>

{if $privacy_winks_options|@count > 1}
  <div><b>{lang_print id=14000082}</b></div>
  <div class='form_desc'>{lang_print id=14000083}</div>
  <table cellpadding='0' cellspacing='0' class='editprofile_options'>
  {* LIST PRIVACY OPTIONS *}
  {section name=privacy_winks_loop loop=$privacy_winks_options}
  <tr><td><input type='radio' name='privacy_winks' id='{$privacy_winks_options[privacy_winks_loop].privacy_id}' value='{$privacy_winks_options[privacy_winks_loop].privacy_value}'{if $privacy_winks == $privacy_winks_options[privacy_winks_loop].privacy_value} CHECKED{/if}></td><td><label for='{$privacy_winks_options[privacy_winks_loop].privacy_id}'>{$privacy_winks_options[privacy_winks_loop].privacy_option}</label></td></tr>
  {/section}
  </table>
  <br>
  {/if}

  <div><b>{lang_print id=14000084}</b></div>
  <div class='form_desc'>{lang_print id=14000085}</div>
  <table cellpadding='0' cellspacing='0' class='editprofile_options'>
  <tr><td><input type='checkbox' value='1' id='wink_notify' name='usersetting_notify_winks' {if $user->usersetting_info.usersetting_notify_winks == 1} CHECKED{/if}></td><td><label for='wink_notify'>{lang_print id=14000086}</label></td></tr>
  </table>
  <br>


<input type='submit' class='button' value='{lang_print id=14000087}'>
<input type='hidden' name='task' value='dosave'>
</form>

{include file='footer.tpl'}