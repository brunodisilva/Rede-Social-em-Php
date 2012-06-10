{include file='admin_header.tpl'}

<h2>{lang_print id=11120501}</h2>
{lang_print id=11120502}

<br><br>

{if $is_error != 0}
<div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
{/if}

{if $result != ""}
  <div class='success'><img src='../images/success.gif' border='0' class='icon'> {lang_print id=$result}</div>
{/if}


<table cellpadding='0' cellspacing='0'>
<form action='admin_viewthemes_edit.php' method='POST'>
<input type='hidden' name='theme_id' value='{$theme.theme_id}' />
<tr>
<td class='form1'>{lang_print id=11120505}</td>
<td class='form2'><input type='text' class='text' name='theme_name' value='{$theme.theme_name}' size='40'></td>
</tr>
<tr>
<td class='form1'>{lang_print id=11120506}</td>
<td class='form2'><textarea name='theme_desc' rows='3' cols='80' class='text'>{$theme.theme_desc}</textarea></td>
</tr>
<tr>
<td class='form1'>{lang_print id=11120722}</td>
<td class='form2'><input type='text' class='text' name='theme_stylesheet' value='{$theme.theme_stylesheet}' size='83' ></td>
</tr>
<tr>
<td class='form1'>{lang_print id=11120507}</td>
<td class='form2'><textarea name='theme_css' rows='10' cols='80' class='text'>{$theme.theme_css}</textarea></td>
</tr>
{section name=blocks_loop loop=$blocks}
<tr>
<td class='form1'>{$blocks[blocks_loop].themeblock_title}</td>
<td class='form2'><textarea name='theme_block_{$blocks[blocks_loop].themeblock_id}' rows='5' cols='80' class='text'>{$blocks[blocks_loop].template}</textarea>
<br>{ldelim}include file='theme:block_{$blocks[blocks_loop].themeblock_id}/theme_{$theme.theme_id}'{rdelim}</td>
</tr>
{/section}
<tr>
<td class='form1'>&nbsp;</td>
<td class='form2'>
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td><input type='submit' class='button' value='{lang_print id=11120503}'>&nbsp;</td>
  <input type='hidden' name='task' value='edittheme'>
  </form>
  <form action='admin_viewthemes.php' method='GET'>
  <td><input type='submit' class='button' value='{lang_print id=11120504}'></td>
  </tr>
  </form>
  </table>
</td>
</tr>
</table>




{include file='admin_footer.tpl'}