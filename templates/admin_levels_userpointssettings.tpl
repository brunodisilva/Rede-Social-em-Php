{include file='admin_header.tpl'}

<h2>{lang_print id=100016642} {$level_name}</h2>
{lang_print id=100016643}

<table cellspacing='0' cellpadding='0' width='100%' style='margin-top: 20px;'>
<tr>
<td class='vert_tab0'>&nbsp;</td>
<td valign='top' class='pagecell' rowspan='{math equation='x+5' x=$level_menu|@count}'>

  <h2>{lang_print id=100016627}</h2>
  {lang_print id=100016628}

  <br><br>

  {* SHOW SUCCESS MESSAGE *}
  {if $result != 0}
    <div class='success'><img src='../images/success.gif' class='icon' border='0'> {lang_print id=100016641}</div>
  {/if}

  {* SHOW ERROR MESSAGE *}
  {if $is_error != 0}
    <div class='error'><img src='../images/error.gif' class='icon' border='0'> {$error_message}</div>
  {/if}

  <table cellpadding='0' cellspacing='0' width='100%'>
  <form action='admin_levels_userpointssettings.php' method='POST'>
  <tr><td class='header'>{lang_print id=100016629}</td></tr>
  <tr><td class='setting1'>
  {lang_print id=100016630}
  </td></tr><tr><td class='setting2'>
    <table cellpadding='0' cellspacing='0'>
    <tr><td><input type='radio' name='level_userpoints_allow' id='userpoints_allow_1' value='1'{if $level_userpoints_allow == 1} CHECKED{/if}>&nbsp;</td><td><label for='userpoints_allow_1'>{lang_print id=100016631}</label></td></tr>
    <tr><td><input type='radio' name='level_userpoints_allow' id='userpoints_allow_0' value='0'{if $level_userpoints_allow == 0} CHECKED{/if}>&nbsp;</td><td><label for='userpoints_allow_0'>{lang_print id=100016632}</label></td></tr>
    </table>
  </td></tr></table>

  <br>

  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr><td class='header'>{lang_print id=100016633}</td></tr>
  <tr><td class='setting1'>
  {lang_print id=100016634}
  </td></tr><tr><td class='setting2'>
    <table cellpadding='0' cellspacing='0'>
    <tr><td><input type='radio' name='level_userpoints_allow_transfer' id='userpointst_allow_1' value='1'{if $level_userpoints_allow_transfer == 1} CHECKED{/if}>&nbsp;</td><td><label for='userpointst_allow_1'>{lang_print id=100016635}</label></td></tr>
    <tr><td><input type='radio' name='level_userpoints_allow_transfer' id='userpointst_allow_0' value='0'{if $level_userpoints_allow_transfer == 0} CHECKED{/if}>&nbsp;</td><td><label for='userpointst_allow_0'>{lang_print id=100016636}</label></td></tr>
    </table>
  </td></tr>
  <tr><td class='setting1'>
  {lang_print id=100016637}
  </td></tr><tr><td class='setting2'>
    <table cellpadding='0' cellspacing='0'>
    <tr><td>{lang_print id=100016638}&nbsp;&nbsp;</td><td><input type='text' name='level_userpoints_max_transfer' value='{$level_userpoints_max_transfer}' maxlength='5' size='5'>&nbsp;{lang_print id=100016639}</tr>
    </table>
  </td></tr>
  </table>

  <br>
  
  <input type='submit' class='button' value='{lang_print id=100016640}'>
  <input type='hidden' name='task' value='dosave'>
  <input type='hidden' name='level_id' value='{$level_id}'>
  </form>

</td>
</tr>

{* DISPLAY MENU *}
<tr><td width='100' nowrap='nowrap' class='vert_tab'><div style='width: 100px;'><a href='admin_levels_edit.php?level_id={$level_info.level_id}'>{lang_print id=285}</a></div></td></tr>
<tr><td width='100' nowrap='nowrap' class='vert_tab' style='border-top: none;'><div style='width: 100px;'><a href='admin_levels_usersettings.php?level_id={$level_info.level_id}'>{lang_print id=286}</a></div></td></tr>
<tr><td width='100' nowrap='nowrap' class='vert_tab' style='border-top: none;'><div style='width: 100px;'><a href='admin_levels_messagesettings.php?level_id={$level_info.level_id}'>{lang_print id=287}</a></div></td></tr>
{section name=level_plugin_loop loop=$global_plugins}
{section name=level_page_loop loop=$global_plugins[level_plugin_loop].plugin_pages_level}
  <tr><td width='100' nowrap='nowrap' class='vert_tab' style='border-top: none;{if $global_plugins[level_plugin_loop].plugin_pages_level[level_page_loop].page == $page} border-right: none;{/if}'><div style='width: 100px;'><a href='{$global_plugins[level_plugin_loop].plugin_pages_level[level_page_loop].link}?level_id={$level_info.level_id}'>{lang_print id=$global_plugins[level_plugin_loop].plugin_pages_level[level_page_loop].title}</a></div></td></tr>
{/section}
{/section}


<tr>
<td class='vert_tab0'>
  <div style='height: 300px;'>&nbsp;</div>
</td>
</tr>
</table>


{include file='admin_footer.tpl'}