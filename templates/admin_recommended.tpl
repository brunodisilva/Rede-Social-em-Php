  {include file='admin_header.tpl'}

<h2>{lang_print id=11140301}</h2>
{lang_print id=11140302}

<br><br>

{if $is_error != 0}
<div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
{/if}

{if $result != ""}
  <div class='success'><img src='../images/success.gif' border='0' class='icon'> {lang_print id=$result}</div>
{/if}


<form action='admin_recommended.php' method='POST' name='info'>


<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11140305}</td></tr>
  <tr><td class='setting1'>{lang_print id=11140306}</td></tr>
  <tr><td class='setting2'><input type='text' name='setting_recommended_license' value='{$setting_recommended_license}' size='30' maxlength="200" /> {lang_print id=11140307}</td>
  </tr>
</table>

<br>

<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11140309}</td></tr>
  <tr><td class='setting1'>{lang_print id=11140310}</td></tr>
  <tr>
    <td class='setting2'>
    
  <table cellpadding='2' cellspacing='0'>
  <tr>
  <td><input type='radio' name='setting_permission_recommended' id='permission_recommended_1' value='1'{if $setting_permission_recommended == 1} CHECKED{/if}></td>
  <td><label for='permission_recommended_1'>{lang_print id=11140311}</label></td>
  </tr>
  <tr>
  <td><input type='radio' name='setting_permission_recommended' id='permission_recommended_0' value='0'{if $setting_permission_recommended == 0} CHECKED{/if}></td>
  <td><label for='permission_recommended_0'>{lang_print id=11140312}</label></td>
  </tr>
  </table>
    
    </td>
  </tr>
</table>

<br>

<table cellpadding='0' cellspacing='0' width='600'>
<tr>
<td class='header'>{lang_print id=11140313}</td>
</tr>
<td class='setting1'>
  {lang_print id=11140314}
</td>
</tr>
<tr>
<td class='setting2'>
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td width='80'>{lang_print id=11140315}</td>
  <td><input type='text' class='text' size='30' name='setting_email_recommendedcomment_subject' value='{$setting_email_recommendedcomment_subject}' maxlength='200'></td>
  </tr><tr>
  <td valign='top'>{lang_print id=11140316}</td>
  <td><textarea rows='6' cols='80' class='text' name='setting_email_recommendedcomment_message'>{$setting_email_recommendedcomment_message}</textarea><br>{lang_print id=11140317}</td>
  </tr>
  </table>
</td>
</tr>
</table>

<br>


<input type='submit' class='button' value='{lang_print id=11140304}'>
<input type='hidden' name='task' value='dosave'>
</form>


{include file='admin_footer.tpl'}