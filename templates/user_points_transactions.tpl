{include file='header.tpl'}

{literal}
<style>
div.up_searchbox {
  border:1px solid #CCCCCC;
  font-weight:bold;
  margin: 10px auto;
  padding:10px;
  width:475px;
}

td.up_tblheader1 {
  background:#DFECF8 none repeat scroll 0%;
  font-weight:bold;
  padding:5px;
}

td.up_tblitem1 {
  background:#FFFFFF none repeat scroll 0%;
  border-top:1px solid #DDDDDD;
  padding:5px;
  vertical-align:middle;
}
</style>
{/literal}

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_vault.php'>{lang_print id=100016680}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_points_transactions.php'>{lang_print id=100016681}</a></td>
{if $semods_settings.setting_userpoints_enable_offers}
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_offers.php'>{lang_print id=100016682}</a></td>
{/if}
{if $semods_settings.setting_userpoints_enable_shop}
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_shop.php'>{lang_print id=100016683}</a></td>
{/if}
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_faq.php'>{lang_print id=100016684}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>

<img style="margin-left:5px;margin-right:10px;" src='./images/icons/userpoints_money48.png' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=100016685}</div>
<div>{lang_print id=100016686}</div>


{if !empty($success_message)}
<br><br>
<div style="background-color: #F3FFF3; padding: 7px; font-weight: bold">
  <table cellpadding=0 cellspacing=0>
    <tr>
    <td valign=top><img src='./images/success.gif' class='icon' border='0'></td>
    <td>{$success_message}</td>
    </tr>
  </table>
</div>
<br>
{elseif !empty($error_message)}
<br><br>
<div style="background-color: #FFEBE8; padding: 7px; font-weight: bold">
  <table cellpadding=0 cellspacing=0>
    <tr>
    <td valign=top><img src='./images/error.gif' class='icon' border='0'></td>
    <td>{$error_message}</td>
    </tr>
  </table>
</div>
<br>
{/if}

<br>

{* SHOW SEARCH FIELD IF ANY ENTRIES EXIST *}
{if ($search != "" OR $total_items > 0)}
  <form action='user_points_transactions.php' name='searchform' method='POST'>
  <div class='up_searchbox' id='up_searchbox' {* {if $search == ""}style='display: none;'{/if} *}>
    <table cellpadding='0' cellspacing='0' align='center'>
    <tr>
    <td><b>{lang_print id=100016687}</b>&nbsp;&nbsp;</td>
    <td><input type='text' name='search' maxlength='100' size='30' value='{$search}'>&nbsp;</td>
    <td><input type='submit' class='button' value='{lang_print id=100016699}'></td>
    </tr>
    </table>
    <input type='hidden' name='s' value='{$s}'>
    <input type='hidden' name='p' value='{$p}'>
  </div>
  </form>
{/if}

<br>
  
{* DISPLAY PAGINATION MENU IF APPLICABLE *}
{if $maxpage > 1}
  <div class='center'>
  {if $p != 1}<a href='user_points_transactions.php?s={$s}&search={$search}&p={math equation='p-1' p=$p}'>&#171; {lang_print id=100016688}</a>{else}<font class='disabled'>&#171; {lang_print id=100016688}</font>{/if}
  {if $p_start == $p_end}
    &nbsp;|&nbsp; {lang_print id=100016689} {$p_start} {lang_print id=100016690} {$total_items} &nbsp;|&nbsp; 
  {else}
    &nbsp;|&nbsp; {lang_print id=100016691} {$p_start}-{$p_end} {lang_print id=100016690} {$total_items} &nbsp;|&nbsp; 
  {/if}
  {if $p != $maxpage}<a href='user_points_transactions.php?s={$s}&search={$search}&p={math equation='p+1' p=$p}'>{lang_print id=100016692} &#187;</a>{else}<font class='disabled'>{lang_print id=100016692} &#187;</font>{/if}
  </div>
<br>
{/if}

{* DISPLAY MESSAGE IF NO ITEMS *}
{if $total_items == 0}
  <table cellpadding='0' cellspacing='0' align='center'><tr>
  <td class='result'>
     
    {if $search != ""}
      <img src='./images/icons/bulb16.gif' border='0' class='icon'>{lang_print id=100016693}
    {else}
      <img src='./images/icons/bulb16.gif' border='0' class='icon'>{lang_print id=100016694}
    {/if}

  </td></tr></table>

{* DISPLAY ITEMS *}
{else}
<br>

  <table cellpadding='0' cellspacing='0' style='border: 1px solid #CCC'>
  <tr>
  <td class='up_tblheader1'><a href='user_points_transactions.php?search={$search}&p={$p}&s={$d}'>{lang_print id=100016695}</a></td>
  <td class='up_tblheader1'>{lang_print id=100016696}</td>
  <td class='up_tblheader1'><a href='user_points_transactions.php?search={$search}&p={$p}&s={$st}'>{lang_print id=100016697}</a></td>
  <td class='up_tblheader1'><a href='user_points_transactions.php?search={$search}&p={$p}&s={$a}'>{lang_print id=100016698}</a></td>
  </tr>

  {* LIST ITEMS *}
  {section name=item_loop loop=$items}
    <tr>
    <td class='up_tblitem1' nowrap='nowrap'>{$datetime->cdate("`$setting.setting_dateformat` `$setting.setting_timeformat`", $datetime->timezone($items[item_loop].transaction_date, $global_timezone))}</td>
    <td class='up_tblitem1' width='100%'>{$items[item_loop].transaction_desc}</td>
    <td class='up_tblitem1' nowrap='nowrap'>{lang_print id=$items[item_loop].transaction_status}</td>
    <td class='up_tblitem1' nowrap='nowrap'>{$items[item_loop].transaction_amount}</td>
    </tr>
  {/section}
  </table>

  <br>
{/if}

</td></tr></table>

{include file='footer.tpl'}