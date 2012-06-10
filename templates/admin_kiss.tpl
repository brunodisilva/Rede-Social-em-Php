{include file='admin_header.tpl'}
<h2>{lang_print id=90000040}</h2>
{lang_print id=90000041}

<br><br>

{if $result != 0}
  <div class='success'><img src='../images/success.gif' class='icon' border='0'> {lang_print id=90000042}</div>
{/if}

<form action='admin_kiss.php' method='POST' name='info' name='kissForm'>


<table cellpadding='0' cellspacing='0' width='600'>
<tr>
<td class='header'>{lang_print id=90000047}</td>
</tr>
<td class='setting1'>
  {lang_print id=90000048}
</td>
</tr>
<tr>
<td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td width='80'>{lang_print id=90000049}</td>
  <td><input type='text' class='text' size='30' name='setting_email_kissrequest_subject' value='{$setting_email_kissrequest_subject}' maxlength='200'></td>
  </tr><tr>
  <td valign='top'>{lang_print id=90000050}</td>
  <td><textarea rows='6' cols='80' class='text' name='setting_email_kissrequest_message'>{$setting_email_kissrequest_message}</textarea><br>{lang_print id=90000051}</td>
  </tr>
  </table>
</td>
</tr>
</table>

<br>

<input type='submit' class='button' value='{lang_print id=90000052}'>
<input type='hidden' name='task' value='dosave'>
</form>


{include file='admin_footer.tpl'}