{include file='header.tpl'}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_kiss_incoming.php'>{lang_print id=90000060}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_kiss_outgoing.php'>{lang_print id=90000061}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_kiss_settings.php'>{lang_print id=90000062}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_kiss_topusers.php'>{lang_print id=90000063}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>


<img src='./images/icons/kiss_big.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=90000062}</div>
<div>{lang_print id=90000080}</div>

<br><br>

{* SHOW SUCCESS MESSAGE *}
{if $result != 0}
  <table cellpadding='0' cellspacing='0'><tr><td class='result'>
  <div class='success'><img src='./images/success.gif' border='0' class='icon'> {lang_print id=90000081}</div>
  </td></tr></table>
{/if}

<br>

<form action='user_kiss_settings.php' method='POST'>

{if $privacy_kiss_options|@count > 1}
  <div><b>{lang_print id=90000082}</b></div>
  <div class='form_desc'>{lang_print id=90000083}</div>
  <table cellpadding='0' cellspacing='0' class='editprofile_options'>
  {* LIST PRIVACY OPTIONS *}
  {section name=privacy_kiss_loop loop=$privacy_kiss_options}
  <tr><td><input type='radio' name='privacy_kiss' id='{$privacy_kiss_options[privacy_kiss_loop].privacy_id}' value='{$privacy_kiss_options[privacy_kiss_loop].privacy_value}'{if $privacy_kiss == $privacy_kiss_options[privacy_kiss_loop].privacy_value} CHECKED{/if}></td><td><label for='{$privacy_kiss_options[privacy_kiss_loop].privacy_id}'>{$privacy_kiss_options[privacy_kiss_loop].privacy_option}</label></td></tr>
  {/section}
  </table>
  <br>
  {/if}

  <div><b>{lang_print id=90000084}</b></div>
  <div class='form_desc'>{lang_print id=90000085}</div>
  <table cellpadding='0' cellspacing='0' class='editprofile_options'>
  <tr><td><input type='checkbox' value='1' id='kiss_notify' name='usersetting_notify_kiss' {if $user->usersetting_info.usersetting_notify_kiss == 1} CHECKED{/if}></td><td><label for='kiss_notify'>{lang_print id=90000086}</label></td></tr>
  </table>
  <br>


<input type='submit' class='button' value='{lang_print id=90000087}'>
<input type='hidden' name='task' value='dosave'>
</form>

{include file='footer.tpl'}