{include file='admin_header.tpl'}

<h2>{lang_print id=11120301}</h2>
{lang_print id=11120302}

<br><br>

{if $is_error != 0}
  <div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
{/if}

{if $result != ''}
  <div class='success'><img src='../images/success.gif' class='icon' border='0'> {lang_print id=$result}</div>
{/if}



<br>

<table cellpadding='0' cellspacing='0' class='list'>
<tr>
<td class='header' width='10'>{lang_print id=11120304}</td>
<td class='header'>{lang_print id=11120305}</td>
<td class='header'>{lang_print id=11120306}</td>
<td class='header'>{lang_print id=11120318}</td>
<td class='header'>{lang_print id=11120315}</td>
<td class='header' width='100'>{lang_print id=11120307}</td>
</tr>
{section name=theme_loop loop=$themes}
  <tr class='{cycle values="background1,background2"}'>
  <td class='item'>{$themes[theme_loop].theme_id}</td>
  <td class='item'>{$themes[theme_loop].theme_name}</td>
  <td class='item'>{$themes[theme_loop].theme_desc}</td>
  <td class='item' align='center'><a href='admin_viewthemes.php?task=setstatus&theme_id={$themes[theme_loop].theme_id}&value={if $themes[theme_loop].theme_status == 1}0{else}1{/if}'><img src='../images/icons/{if $themes[theme_loop].theme_status == 1}admin_checkbox2.gif{else}admin_checkbox1.gif{/if}' border='0' class='icon'></a></td>  
  <td class='item' align='center'><a href='admin_viewthemes.php?task=setdefault&theme_id={$themes[theme_loop].theme_id}'><img src='../images/icons/{if $themes[theme_loop].theme_default == 1}admin_checkbox2.gif{else}admin_checkbox1.gif{/if}' border='0' class='icon'></a></td>  
  <td class='item'>[ <a href='admin_viewthemes_edit.php?theme_id={$themes[theme_loop].theme_id}'>{lang_print id=11120308}</a> ] [ <a href='admin_viewthemes.php?task=delete&confirm=1&theme_id={$themes[theme_loop].theme_id}' onclick='return confirm("Are you sure?");'>{lang_print id=11120309}</a> ]</td>
  </tr>
{/section}
</table>

<br />
<form action='admin_viewthemes_add.php' method='GET'>
<input type='submit' class='button' value='{lang_print id=11120303}'>
</form>

{include file='admin_footer.tpl'}