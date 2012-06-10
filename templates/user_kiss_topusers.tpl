{include file='header.tpl'}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_kiss_incoming.php'>{lang_print id=90000060}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_kiss_outgoing.php'>{lang_print id=90000061}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_kiss_settings.php'>{lang_print id=90000062}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_kiss_topusers.php'>{lang_print id=90000063}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>


<img src='./images/icons/kiss_big.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=90000063}</div>
<div>{lang_print id=90000088}</div>

<br><br>

{* DISPLAY MESSAGE IF NO kiss YET *}
{if $is_kiss == 1}
  <table cellpadding='0' cellspacing='0' align='center'>
  <tr><td class='result'><img src='./images/icons/bulb16.gif' border='0' class='icon'>{lang_print id=90000089}</td></tr>
  </table>
{else}

{* DISPLAY TOP kiss *}

<div class='friends_result'>
  <table cellpadding='0' cellspacing='0' class='portal_table' align='center' width='100%'>
  <tr><td></td></tr>
  <tr>
  <td>{php}$count = 0;{/php}
      {section name=kiss_loop loop=$kiss max=100}
        {if $kiss[kiss_loop].kiss_total == 1}
  {php} $kiss_count = "";{/php}{/if}
  {if $kiss[kiss_loop].kiss_total > 1}
  {php} $kiss_count = "s";{/php}{/if}
	  {* START NEW ROW *}
      {cycle name="startrow2" values="<table cellpadding='0' cellspacing='0' align='center'><tr>,,,,"}
      <td class='portal_member'><a href='{$url->url_create('profile',$kiss[kiss_loop].kiss->user_info.user_username)}'>{$kiss[kiss_loop].kiss->user_info.user_username|truncate:15}<br><img src='{$kiss[kiss_loop].kiss->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($kiss[kiss_loop].kiss->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'></a><br>#{php}$count = $count + 1;{/php}{php}echo "$count";{/php}<br> {$kiss[kiss_loop].kiss_total} {lang_print id=90000090}{php}echo "$kiss_count";{/php}</td>
      {* END ROW AFTER 5 RESULTS *}
      {if $smarty.section.kiss_loop.last == true}
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