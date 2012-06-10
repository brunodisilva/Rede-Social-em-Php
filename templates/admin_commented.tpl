{include file='admin_header.tpl'}

<h2>{lang_print id=11130201}</h2>
{lang_print id=11130202}

<br><br>

{if $is_error != 0}
<div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
{/if}

{if $result != ""}
  <div class='success'><img src='../images/success.gif' border='0' class='icon'> {$result}</div>
{/if}


<form action='admin_commented.php' method='POST' name='info'>

<!-- Nulled by TrioxX
<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11130209}</td></tr>
  <tr><td class='setting1'>{lang_print id=11130210}</td></tr>
  <tr><td class='setting2'><input type='text' name='setting_commented_license' value='{$setting_commented_license}' size='30' maxlength="200" /> {lang_print id=11130211}</td>
  </tr>
</table>

<br>
// -->

<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11130205}</td></tr>
  <tr><td class='setting1'>{lang_print id=11130206}</td></tr>
  <tr>
    <td class='setting2'>

  <table cellpadding='2' cellspacing='0'>
  <tr>
  <td><input type='radio' name='setting_permission_commented' id='permission_commented_1' value='1'{if $setting_permission_commented == 1} CHECKED{/if}></td>
  <td><label for='permission_commented_1'>{lang_print id=11130207}</label></td>
  </tr>
  <tr>
  <td><input type='radio' name='setting_permission_commented' id='permission_commented_0' value='0'{if $setting_permission_commented == 0} CHECKED{/if}></td>
  <td><label for='permission_commented_0'>{lang_print id=11130208}</label></td>
  </tr>
  </table>

    </td>
  </tr>
</table>


<br>


<input type='submit' class='button' value='{lang_print id=11130204}'>
<input type='hidden' name='task' value='dosave'>
</form>


{include file='admin_footer.tpl'}