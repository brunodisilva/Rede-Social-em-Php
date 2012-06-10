{include file='admin_header.tpl'}

<h2>{lang_print id=11120401}</h2>
{lang_print id=11120402}

<br><br>

{if $is_error != 0}
<div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
{/if}

<table cellpadding='0' cellspacing='0'>
<form action='admin_viewthemes_add.php' method='POST'>
<tr>
<td class='form1'>{lang_print id=11120405}</td>
<td class='form2'><input type='text' class='text' name='theme_name' value='{$theme.theme_name}' size='40' maxlength='50'></td>
</tr>
<tr>
<td class='form1'>{lang_print id=11120406}</td>
<td class='form2'><textarea name='theme_desc' rows='3' cols='80' class='text'>{$theme.theme_desc}</textarea></td>
</tr>
<tr>
<td class='form1'>{lang_print id=11120722}</td>
<td class='form2'><input type='text' class='text' name='theme_stylesheet' value='{$theme.theme_stylesheet}' size='83' ></td>
</tr>
<tr>
<td class='form1'>{lang_print id=11120407}</td>
<td class='form2'><textarea name='theme_css' rows='10' cols='80' class='text'>{$theme.theme_css}</textarea></td>
</tr>
{section name=blocks_loop loop=$blocks}
<tr>
<td class='form1'>{$blocks[blocks_loop].themeblock_title}</td>
<td class='form2'><textarea name='theme_block_{$blocks[blocks_loop].themeblock_id}' rows='5' cols='80' class='text'>{$blocks[blocks_loop].template}</textarea></td>
</tr>
{/section}
<tr>
<td class='form1'>&nbsp;</td>
<td class='form2'>
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td><input type='submit' class='button' value='{lang_print id=11120403}'>&nbsp;</td>
  <input type='hidden' name='task' value='createtheme'>
  </form>
  <form action='admin_viewthemes.php' method='POST'>
  <td><input type='submit' class='button' value='{lang_print id=11120404}'></td>
  </tr>
  </form>
  </table>
</td>
</tr>
</table>




{include file='admin_footer.tpl'}