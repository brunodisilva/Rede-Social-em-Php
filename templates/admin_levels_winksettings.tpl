{include file='admin_header.tpl'}

<h2>{lang_print id=14000025} {$level_info.level_name}</h2>
{lang_print id=14000026}

<table cellspacing='0' cellpadding='0' width='100%' style='margin-top: 20px;'>
<tr>
<td class='vert_tab0'>&nbsp;</td>
<td valign='top' class='pagecell' rowspan='{math equation="x+5" x=$level_menu|@count}'>



  <h2>{lang_print id=14000027}</h2>
  {lang_print id=14000028}

  <br><br>

  {if $result != 0}
  <div class='success'><img src='../images/success.gif' class='icon' border='0'> {lang_print id=14000029}</div>
  {/if}

  {if $is_error != 0}
  <div class='error'><img src='../images/error.gif' class='icon' border='0'> {$error_message}</div>
  {/if}

  <form action='admin_levels_winksettings.php' name='info' method='POST'>
  <table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=14000030}</td></tr>
  <tr><td class='setting1'>
  {lang_print id=14000031}
  </td></tr><tr><td class='setting2'>
    <table cellpadding='0' cellspacing='0'>
    <tr><td><input type='radio' name='level_winks_allow' id='winks_allow_1' value='1'{if $winks_allow == 1} CHECKED{/if}>&nbsp;</td><td><label for='winks_allow_1'>{lang_print id=14000032}</label></td></tr>
    <tr><td><input type='radio' name='level_winks_allow' id='winks_allow_0' value='0'{if $winks_allow == 0} CHECKED{/if}>&nbsp;</td><td><label for='winks_allow_0'>{lang_print id=14000033}</label></td></tr>
	</table>
  </td></tr></table>



  <table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=14000034}</td></tr>
 </tr><tr><td class='setting1'>
  {lang_print id=14000035}
  </td></tr><tr><td class='setting2'>
    <table cellpadding='0' cellspacing='0'>
    {section name=privacy_loop loop=$winks_privacy}
      <tr><td><input type='checkbox' name='{$winks_privacy[privacy_loop].privacy_name}' id='{$winks_privacy[privacy_loop].privacy_name}' value='{$winks_privacy[privacy_loop].privacy_value}'{if $winks_privacy[privacy_loop].privacy_selected == 1} CHECKED{/if}></td><td><label for='{$winks_privacy[privacy_loop].privacy_name}'>{$winks_privacy[privacy_loop].privacy_option}</label>&nbsp;&nbsp;</td></tr>
    {/section}
    </table>
  </td></tr>
  

  
  </td></tr>
  </table>
 
  <br>
  
  <input type='submit' class='button' value='{lang_print id=14000036}'>
  <input type='hidden' name='task' value='dosave'>
  <input type='hidden' name='level_id' value='{$level_id}'>
  </form>
  
</td>
</tr>
{* DISPLAY MENU *}
<tr><td width='100' nowrap='nowrap' class='vert_tab'><div style='width: 100px;'><a href='admin_levels_edit.php?level_id={$level_info.level_id}'>{lang_print id=285}</a></div></td></tr>
<tr><td width='100' nowrap='nowrap' class='vert_tab' style='border-top: none;'><div style='width: 100px;'><a href='admin_levels_usersettings.php?level_id={$level_info.level_id}'>{lang_print id=286}</a></div></td></tr>
<tr><td width='100' nowrap='nowrap' class='vert_tab' style='border-right: none; border-top: none;'><div style='width: 100px;'><a href='admin_levels_messagesettings.php?level_id={$level_info.level_id}'>{lang_print id=287}</a></div></td></tr>
{section name=level_plugin_loop loop=$global_plugins}
{section name=level_page_loop loop=$global_plugins[level_plugin_loop].plugin_pages_level}
  <tr><td width='100' nowrap='nowrap' class='vert_tab' style='border-top: none;{if $global_plugins[level_plugin_loop].plugin_pages_level[level_page_loop].page == $page} border-right: none;{/if}'><div style='width: 100px;'><a href='{$global_plugins[level_plugin_loop].plugin_pages_level[level_page_loop].link}?level_id={$level_info.level_id}'>{lang_print id=$global_plugins[level_plugin_loop].plugin_pages_level[level_page_loop].title}</a></div></td></tr>
{/section}
{/section}

<tr>
<td class='vert_tab0'>
  <div style='height: 350px;'>&nbsp;</div>
</td>
</tr>
</table>


{include file='admin_footer.tpl'}