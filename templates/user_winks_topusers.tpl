{include file='header.tpl'}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_winks_incoming.php'>{lang_print id=14000060}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_winks_outgoing.php'>{lang_print id=14000061}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_winks_settings.php'>{lang_print id=14000062}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_winks_topusers.php'>{lang_print id=14000063}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>


<img src='./images/icons/wink_big.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=14000063}</div>
<div>{lang_print id=14000088}</div>

<br><br>

{* DISPLAY MESSAGE IF NO WINKS YET *}
{if $is_wink == 1}
  <table cellpadding='0' cellspacing='0' align='center'>
  <tr><td class='result'><img src='./images/icons/bulb16.gif' border='0' class='icon'>{lang_print id=14000089}</td></tr>
  </table>
{else}

{* DISPLAY TOP WINKS *}

<div class='friends_result'>
  <table cellpadding='0' cellspacing='0' class='portal_table' align='center' width='100%'>
  <tr><td></td></tr>
  <tr>
  <td>{php}$count = 0;{/php}
      {section name=winks_loop loop=$winks max=100}
        {if $winks[winks_loop].wink_total == 1}
  {php} $wink_count = "";{/php}{/if}
  {if $winks[winks_loop].wink_total > 1}
  {php} $wink_count = "s";{/php}{/if}
	  {* START NEW ROW *}
      {cycle name="startrow2" values="<table cellpadding='0' cellspacing='0' align='center'><tr>,,,,"}
      <td class='portal_member'><a href='{$url->url_create('profile',$winks[winks_loop].wink->user_info.user_username)}'>{$winks[winks_loop].wink->user_info.user_username|truncate:15}<br><img src='{$winks[winks_loop].wink->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($winks[winks_loop].wink->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'></a><br>#{php}$count = $count + 1;{/php}{php}echo "$count";{/php}<br> {$winks[winks_loop].wink_total} {lang_print id=14000090}{php}echo "$wink_count";{/php}</td>
      {* END ROW AFTER 5 RESULTS *}
      {if $smarty.section.winks_loop.last == true}
        </tr></table>
      {else}
        {cycle name="endrow2" values=",,,,</tr></table>"}
      {/if}
    {/section}
  </td>
  </tr>
  </table>
  </div>
  {/if}
{include file='footer.tpl'}