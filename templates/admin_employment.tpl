{include file='admin_header.tpl'}

<h2>{lang_print id=11050201}</h2>
{lang_print id=11050202}

<br><br>

{if $is_error != 0}
<div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
{/if}

{if $result != ""}
  <div class='success'><img src='../images/success.gif' border='0' class='icon'> {lang_print id=$result}</div>
{/if}


<form action='admin_employment.php' method='POST' name='info'>

<!-- Nulled by TrioxX
<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11050205}</td></tr>
  <tr><td class='setting1'>{lang_print id=11050206}</td></tr>
  <tr><td class='setting2'><input type='text' name='setting_employment_license' value='{$setting_employment_license}' size='30' maxlength="200" /> {lang_print id=11050207}</td>
  </tr>
</table>

<br>
// -->

<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11050209}</td></tr>
  <tr><td class='setting1'>{lang_print id=11050210}</td></tr>
  <tr>
    <td class='setting2'>

  <table cellpadding='2' cellspacing='0'>
  <tr>
  <td><input type='radio' name='setting_permission_employment' id='permission_employment_1' value='1'{if $setting_permission_employment == 1} CHECKED{/if}></td>
  <td><label for='permission_employment_1'>{lang_print id=11050211}</label></td>
  </tr>
  <tr>
  <td><input type='radio' name='setting_permission_employment' id='permission_employment_0' value='0'{if $setting_permission_employment == 0} CHECKED{/if}></td>
  <td><label for='permission_employment_0'>{lang_print id=11050212}</label></td>
  </tr>
  </table>

    </td>
  </tr>
</table>

<br>

<input type='submit' class='button' value='{lang_print id=11050204}'>
<input type='hidden' name='task' value='dosave'>
</form>


{include file='admin_footer.tpl'}