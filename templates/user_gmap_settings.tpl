{include file='header.tpl'}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_gmap_settings.php'>{lang_print id=11080703}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='search_gmap.php'>{lang_print id=11080704}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>

<img src='./images/icons/gmap48.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=11080701}</div>
<div>{lang_print id=11080702}</div>

<br>

{* SHOW SUCCESS MESSAGE *}
{if $result != 0}
  <table cellpadding='0' cellspacing='0'><tr><td class='result'>
  <div class='success'><img src='./images/success.gif' border='0' class='icon'> {lang_print id=11080707}</div>
  </td></tr></table>
{/if}

<br>

<form action='user_gmap_settings.php' method='POST'>

<div><b>{lang_print id=11080709}</b></div>
<div class='form_desc'>{lang_print id=11080710}</div>

<table cellpadding='0' cellspacing='0'>
<tr><td><input type='checkbox' value='1' id='usersetting_permission_gmap' name='usersetting_permission_gmap'{if $user->usersetting_info.usersetting_permission_gmap == 1} CHECKED{/if}></td><td><label for='usersetting_permission_gmap'>{lang_print id=11080711}</label></td></tr>
</table>
<br>
<div><b>{lang_print id=11080712}</b></div>
<div class='form_desc'>{lang_print id=11080713}</div>

{foreach from=$location_fields item=location_field key=field_key}
<table cellpadding='0' cellspacing='0'>
<tr><td><input type='checkbox' value='1' id='{$field_key}' name='{$field_key}'{if $location_field.field_value == 1} CHECKED{/if}></td><td><label for='{$field_key}'>{lang_print id=$location_field.field_title}</label></td></tr>
</table>
{/foreach}

<br>

<input type='submit' class='button' value='{lang_print id=11080708}'>
<input type='hidden' name='task' value='dosave'>
</form>


{include file='footer.tpl'}