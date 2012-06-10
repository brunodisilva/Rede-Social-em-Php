{include file='header.tpl'}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_winks_incoming.php'>{lang_print id=14000060}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_winks_outgoing.php'>{lang_print id=14000061}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_winks_settings.php'>{lang_print id=14000062}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_winks_topusers.php'>{lang_print id=14000063}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>

<img src='./images/icons/wink_big.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=14000064}</div>
<div>{lang_print id=14000065}</div>

<br><br>

{* DISPLAY MESSAGE IF NO WINKS *}
{if $total_winks == 0}

  <table cellpadding='0' cellspacing='0' align='center'>
  <tr><td class='result'><img src='./images/icons/bulb16.gif' border='0' class='icon'>{lang_print id=14000065}</td></tr>
  </table>

{* DISPLAY WINKS *}
{else}

  {* DISPLAY PAGINATION MENU IF APPLICABLE *}
  {if $maxpage > 1}
    <br>
    <div class='center'>
    {if $p != 1}<a href='user_winks_incoming.php?p={math equation='p-1' p=$p}'>&#171; {lang_print id=14000066}</a>{else}<font class='disabled'>&#171; {lang_print id=14000066}</font>{/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_print id=14000067} {$p_start} {lang_print id=14000068} {$total_winks} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_print id=14000069} {$p_start}-{$p_end} {lang_print id=14000068} {$total_winks} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}<a href='user_winks_incoming.php?p={math equation='p+1' p=$p}'>{lang_print id=14000070} &#187;</a>{else}<font class='disabled'>{lang_print id=14000070}&#187;</font>{/if}
    </div>
  {/if}

  {section name=wink_loop loop=$winks}
  {* LOOP THROUGH WINKS *}
    <div class='friends_result'>
    <table cellpadding='0' cellspacing='0'>
    <tr>
    <td class='friends_result0'><a href='{$url->url_create('profile', $winks[wink_loop].wink_user->user_info.user_username)}'><img src='{$winks[wink_loop].wink_user->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($winks[wink_loop].wink_user->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0' alt="{$winks[wink_loop].wink_user->user_info.user_username}{lang_print id=14000065}"></a></td>
    <td class='friends_result1' width='100%'>
      <div><font class='big'><a href='{$url->url_create('profile', $winks[wink_loop].wink_user->user_info.user_username)}'>{$winks[wink_loop].wink_user->user_info.user_username}</a></div></font><br>
      <table cellpadding='0' cellspacing='0'>
    <td class='messages_message' width='150' nowrap='nowrap'>
      <div class='messages_date'><b>{lang_print id=14000109}</b> {$datetime->cdate("`$setting.setting_timeformat` `$setting.setting_dateformat`", $datetime->timezone($winks[wink_loop].wink_date, $global_timezone))}</div>
    </td>      </table>
    </td>
    <td class='friends_result2' NOWRAP>
    <a href='user_winks_confirm_incoming.php?user={$winks[wink_loop].wink_user->user_info.user_username}&task=wink_back'>{lang_print id=14000073}</a><br>
	<a href='user_winks_confirm_incoming.php?user={$winks[wink_loop].wink_user->user_info.user_username}&task=remove'>{lang_print id=14000074}</a><br>
    
    </td>
    </tr>
    </table>
    </div>
  {/section}

  {* DISPLAY PAGINATION MENU IF APPLICABLE *}
  {if $maxpage > 1}
    <br>
    <div class='center'>
    {if $p != 1}<a href='user_winks_incoming.php?p={math equation='p-1' p=$p}'>&#171; {lang_print id=14000066}</a>{else}<font class='disabled'>&#171; {lang_print id=14000066}</font>{/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_print id=14000067} {$p_start} {lang_print id=14000068} {$total_winks} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_print id=14000069} {$p_start}-{$p_end} {lang_print id=14000068} {$total_winks} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}<a href='user_winks_incoming.php?p={math equation='p+1' p=$p}'>{lang_print id=14000070} &#187;</a>{else}<font class='disabled'>{lang_print id=14000070} &#187;</font>{/if}
    </div>
  {/if}
  
{/if}  

{include file='footer.tpl'}