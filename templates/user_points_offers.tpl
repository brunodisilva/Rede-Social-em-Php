{include file='header.tpl'}

<link rel="stylesheet" href="./templates/styles_userpoints.css" title="stylesheet" type="text/css">  

<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_vault.php'>{lang_print id=100016763}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_transactions.php'>{lang_print id=100016764}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_points_offers.php'>{lang_print id=100016765}</a></td>
{if $semods_settings.setting_userpoints_enable_shop}
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_shop.php'>{lang_print id=100016766}</a></td>
{/if}
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_faq.php'>{lang_print id=100016767}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>

<img style="margin-left:5px;margin-right:10px;" src='./images/icons/userpoints_coins48.png' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=100016773}</div>
<div>{lang_print id=100016774}</div>

<br>
<br>

<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td style='padding-right: 10px; vertical-align: top;'>

  {* SHOW SEARCH BOX *}
  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr>
  <td style='padding: 10px; border: 1px solid #DDDDDD; background: #F5F5F5;'>
    <form action='user_points_offers.php' method='GET'>
    <table cellpadding='0' cellspacing='0'>
    <tr>
    <td class='browse_field'>
        {lang_print id=100016775}<br>
      <input type='text' class='text' size='40' name='search' value='{$search}'>
      <input type='submit' class='button' value='Search'>
    </td>
    </tr>
    </table>

    </form>

  </td>
  </tr>
  </table>

  <br>

  {* DISPLAY PAGINATION MENU IF APPLICABLE *}
  {if $maxpage > 1}
    <div class='center'>
    {if $p != 1}<a href='user_points_offers.php?search={$search}&tag={$tag}&p={math equation='p-1' p=$p}'>&#171; {lang_print id=100016768}</a>{else}<font class='disabled'>&#171; {lang_print id=100016768}</font>{/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_print id=100016769} {$p_start} {lang_print id=100016770} {$total_items} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_print id=100016771} {$p_start}-{$p_end} {lang_print id=100016770} {$total_items} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}<a href='user_points_offers.php?search={$search}&tag={$tag}&p={math equation='p+1' p=$p}'>{lang_print id=100016772} &#187;</a>{else}<font class='disabled'>{lang_print id=100016772} &#187;</font>{/if}
    </div>
    <br>
  {/if}

  {* DISPLAY MESSAGE IF NO ITEMS *}
  {if $total_items == 0}
	<table cellpadding='0' cellspacing='0' align='center'><tr>
	<td class='result'>
	   
	  {if $search != ""}
		<img src='./images/icons/bulb16.gif' border='0' class='icon'>{lang_print id=100016780}
	  {else}
		<img src='./images/icons/bulb16.gif' border='0' class='icon'>{lang_print id=100016781}
	  {/if}
  
	</td></tr></table>
  
  {* DISPLAY ITEMS *}
  {/if}


  {* DISPLAY ITEMS *}
  {section name=item_loop loop=$items}

    <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
    <td valign='top' style='padding-right: 10px; text-align: center;' width='100px'><a href='user_points_offers_item.php?item_id={$items[item_loop].userpointearner_id}'><img src='{$items[item_loop].userpointearner_photo}' border='0' class='photo' width='{$misc->photo_size($items[item_loop].userpointearner_photo,'100','100','w')}'></a></td>
    <td valign='top'>
      <div style='padding: 5px; background: #EEEEEE; font-weight: bold'>
        <div style='float: left;'><a href='user_points_offers_item.php?item_id={$items[item_loop].userpointearner_id}'>{$items[item_loop].userpointearner_title|truncate:70:"...":false|choptext:40:"<br>"}</a></div>
		<div style='text-align: right;'> <a href='#' onclick="return false"> &nbsp;<!--Buy--></a></div>
      </div>
      <div style='padding: 5px; vertical-align: top;'>
        <div style='color: #888888;'>
	  {lang_print id=100016776} {$datetime->cdate("`$setting.setting_dateformat`", $datetime->timezone($items[item_loop].userpointearner_date, $global_timezone))}, 
          <a href='user_points_offers_item.php?item_id={$items[item_loop].userpointearner_id}#comments'>{$items[item_loop].userpointearner_comments} {lang_print id=100016778}</a>,
          {$items[item_loop].userpointearner_views} {lang_print id=100016777}
        </div>
	<div style='padding-top: 5px;'>{$items[item_loop].userpointearner_body|html_substr:250}</div>
      </div>
    </td>
    </tr>
    </table>

    {* ADD SPACER *}
    {if $smarty.section.item_loop.last != true}
      <div class='xxspacer'>&nbsp;</div>
    {/if}

  {/section}

  <br><br>
  
  {* DISPLAY PAGINATION MENU IF APPLICABLE *}
  {if $maxpage > 1}
    <div class='center'>
    {if $p != 1}<a href='user_points_offers.php?search={$search}&tag={$tag}&p={math equation='p-1' p=$p}'>&#171; {lang_print id=100016768}</a>{else}<font class='disabled'>&#171; {lang_print id=100016768}</font>{/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_print id=100016769} {$p_start} {lang_print id=100016770} {$total_items} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_print id=100016771} {$p_start}-{$p_end} {lang_print id=100016770} {$total_items} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}<a href='user_points_offers.php?search={$search}&tag={$tag}&p={math equation='p+1' p=$p}'>{lang_print id=100016772} &#187;</a>{else}<font class='disabled'>{lang_print id=100016772} &#187;</font>{/if}
    </div>
    <br>
  {/if}

</td>
<td style='width: 190px; padding: 5px; background: #F5F5F5; border: 1px solid #DDDDDD;' valign='top'>

<div style="text-align:center;padding-bottom: 10px">
  {lang_print id=100016779}
</div>

<div style="line-height:200%">
  {$tagcloud}
</div>

</td>
</tr>
</table>

{include file='footer.tpl'}