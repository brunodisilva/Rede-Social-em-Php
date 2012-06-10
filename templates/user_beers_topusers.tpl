{include file='header.tpl'}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_beers_incoming.php'>{lang_print id=16000060}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_beers_outgoing.php'>{lang_print id=16000061}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_beers_settings.php'>{lang_print id=16000062}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_beers_topusers.php'>{lang_print id=16000063}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>


<img src='./images/icons/beer_big.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=16000063}</div>
<div>{lang_print id=16000088}</div>

<br><br>

{* DISPLAY MESSAGE IF NO beerS YET *}
{if $is_beer == 1}
  <table cellpadding='0' cellspacing='0' align='center'>
  <tr><td class='result'><img src='./images/icons/bulb16.gif' border='0' class='icon'>{lang_print id=16000089}</td></tr>
  </table>
{else}

{* DISPLAY TOP beerS *}

<div class='friends_result'>
  <table cellpadding='0' cellspacing='0' class='portal_table' align='center' width='100%'>
  <tr><td></td></tr>
  <tr>
  <td>{php}$count = 0;{/php}
      {section name=beers_loop loop=$beers max=100}
        {if $beers[beers_loop].beer_total == 1}
  {php} $beer_count = "";{/php}{/if}
  {if $beers[beers_loop].beer_total > 1}
  {php} $beer_count = "s";{/php}{/if}
	  {* START NEW ROW *}
      {cycle name="startrow2" values="<table cellpadding='0' cellspacing='0' align='center'><tr>,,,,"}
      <td class='portal_member'><a href='{$url->url_create('profile',$beers[beers_loop].beer->user_info.user_username)}'>{$beers[beers_loop].beer->user_info.user_username|truncate:15}<br><img src='{$beers[beers_loop].beer->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($beers[beers_loop].beer->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'></a><br>#{php}$count = $count + 1;{/php}{php}echo "$count";{/php}<br> {$beers[beers_loop].beer_total} {lang_print id=16000090}{php}echo "$beer_count";{/php}</td>
      {* END ROW AFTER 5 RESULTS *}
      {if $smarty.section.beers_loop.last == true}
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