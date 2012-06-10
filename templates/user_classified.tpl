{include file='header.tpl'}

{* $Id: user_classified.tpl 7 2009-01-11 06:01:49Z john $ *}

<img src='./images/icons/classified_classified48.gif' border='0' class='icon_big' style="margin-bottom: 15px;">
<div class='page_header'>{lang_print id=4500068}</div>
<div>
  {lang_print id=4500069}
</div>
<br />


{* SHOW BUTTONS *}
<div style='margin-top: 20px;'>
  <div class='button' style='float: left;'>
    <a href='user_classified_listing.php'><img src='./images/icons/classified_post16.gif' border='0' class='button' />{lang_print id=4500065}</a>
  </div>
  <div class='button' style='float: left; padding-left: 20px;'>
    <a href='user_classified_settings.php'><img src='./images/icons/classified_settings16.gif' border='0' class='button' />{lang_print id=4500066}</a>
  </div>
  <div class='button' style='float: left; padding-left: 20px;'>
    <a href="javascript:void(0);" onclick="$('classified_search').style.display = ( $('classified_search').style.display=='block' ? 'none' : 'block');this.blur();"><img src='./images/icons/search16.gif' border='0' class='button' />{lang_print id=4500067}</a>
  </div>
  <div style='clear: both; height: 0px;'></div>
</div>


{* SEARCH FIELD *}
<div id='classified_search' class="seClassifiedSearch" style='margin-top: 10px;{if empty($search)} display: none;{/if}'>
  <div style='padding: 10px;'>
    <form action='user_classified.php' name='searchform' method='post'>
    <table cellpadding='0' cellspacing='0' align='center'>
    <tr>
    <td><b>{lang_print id=1500049}</b>&nbsp;&nbsp;</td>
    <td><input type='text' name='search' maxlength='100' size='30' value='{$search}' />&nbsp;</td>
    <td>{lang_block id=646 var=langBlockTemp}<input type='submit' class='button' value='{$langBlockTemp}' />{/lang_block}</td>
    </tr>
    </table>
    <input type='hidden' name='s' value='{$s}' />
    <input type='hidden' name='p' value='{$p}' />
    </form>
  </div>
</div>


{* JAVASCRIPT *}
{lang_javascript ids=861,4500121,4500123}
<script type="text/javascript" src="./include/js/class_classified.js"></script>
<script type="text/javascript">
  
  SupremeEdition.Classified = new SupremeEditionAPI.Classified();
  SupremeEdition.RegisterModule(SupremeEdition.Classified);
  
</script>


{* HIDDEN DIV TO DISPLAY DELETE CONFIRMATION MESSAGE *}
<div style='display: none;' id='confirmclassifieddelete'>
  <div style='margin-top: 10px;'>
    {lang_print id=4500122}
  </div>
  <br />
  {lang_block id=175 var=langBlockTemp}<input type='button' class='button' value='{$langBlockTemp}' onClick='parent.TB_remove();parent.SupremeEdition.Classified.deleteClassifiedConfirm();' />{/lang_block}
  {lang_block id=39 var=langBlockTemp}<input type='button' class='button' value='{$langBlockTemp}' onClick='parent.TB_remove();' />{/lang_block}
</div>


{* DISPLAY MESSAGE IF NO CLASSIFIED ENTRIES *}
<div id="seClassifiedNullMessage"{if $total_classifieds} style="display: none;"{/if}>
  <table cellpadding='0' cellspacing='0' align='center'>
    <tr>
      <td class='result'>
        {if !empty($search)}
          <img src='./images/icons/bulb16.gif' border='0' class='icon' />
          {lang_print id=4500070}
        {else}
          <img src='./images/icons/bulb16.gif' border='0' class='icon' />
          {lang_sprintf id=4500071 1='user_classified_listing.php'}
        {/if}
      </td>
    </tr>
  </table>
</div>


{* DISPLAY PAGINATION MENU IF APPLICABLE *}
{if $maxpage > 1}
  <div class='center'>
    {if $p != 1}
      <a href='user_classified.php?search={$search}&p={math equation="p-1" p=$p}'>&#171; {lang_print id=182}</a>
    {else}
      <font class='disabled'>&#171; {lang_print id=182}</font>
    {/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_sprintf id=184 1=$p_start 2=$total_classifieds} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_sprintf id=185 1=$p_start 2=$p_end 3=$total_classifieds} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}
      <a href='user_classified.php?search={$search}&p={math equation="p+1" p=$p}'>{lang_print id=183} &#187;</a>
    {else}
      <font class='disabled'>{lang_print id=183} &#187;</font>
    {/if}
  </div>
  <br />
{/if}


{* DISPLAY CLASSIFIED LISTINGS *}
{section name=classified_loop loop=$classifieds}
<div id='seClassified_{$classifieds[classified_loop].classified->classified_info.classified_id}' class="seClassified {cycle values='seClassified1,seClassified2'}">

  <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      <td class='seClassifiedLeft' width='1'>
        <div class='seClassifiedPhoto' style='width: 140px;'>
          <table cellpadding='0' cellspacing='0' width='140'>
            <tr>
              <td>
                <a href='{$url->url_create("classified", $user->user_info.user_username, $classifieds[classified_loop].classified->classified_info.classified_id)}'>
                  <img src='{$classifieds[classified_loop].classified->classified_photo("./images/nophoto.gif")}' border='0' width='{$misc->photo_size($classifieds[classified_loop].classified->classified_photo("./images/nophoto.gif"),"140","140","w")}' />
                </a>
              </td>
            </tr>
          </table>
        </div>
      </td>
      <td class='seClassifiedRight' width='100%'>
      
        {* SHOW CLASSIFIED TITLE *}
        <div class='seClassifiedTitle'>
          {if !$classifieds[classified_loop].classified->classified_info.classified_title}<i>{lang_print id=589}</i>{else}{$classifieds[classified_loop].classified->classified_info.classified_title|truncate:70:"...":false|choptext:40:"<br>"}{/if}
        </div>
        
        {* SHOW CLASSIFIED CATEGORY *}
        {if !empty($classifieds[classified_loop].classified->classified_info.main_category_title)}
        <div class='seClassifiedCategory'>
          {lang_print id=4500058}
          {* SHOW PARENT CATEGORY *}
          {if !empty($classifieds[classified_loop].classified->classified_info.parent_category_title)}
            <a href="browse_classifieds.php?classifiedcat_id={$classifieds[classified_loop].classified->classified_info.parent_category_id}">{lang_print id=$classifieds[classified_loop].classified->classified_info.parent_category_title}</a>
            -
          {/if}
          <a href="browse_classifieds.php?classifiedcat_id={$classifieds[classified_loop].classified->classified_info.main_category_id}">{lang_print id=$classifieds[classified_loop].classified->classified_info.main_category_title}</a>
        </div>
        {/if}
        
        {* SHOW CLASSIFIED STATS *}
        <div class='seClassifiedStats'>
          {assign var='classified_datecreated' value=$datetime->time_since($classifieds[classified_loop].classified->classified_info.classified_date)}
          {capture assign="created"}{lang_sprintf id=$classified_datecreated[0] 1=$classified_datecreated[1]}{/capture}
          {assign var='classified_dateupdated' value=$datetime->time_since($classifieds[classified_loop].classified->classified_info.classified_dateupdated)}
          {capture assign="updated"}{lang_sprintf id=$classified_dateupdated[0] 1=$classified_dateupdated[1]}{/capture}
          
          {lang_sprintf id=4500072 1=$classifieds[classified_loop].classified->classified_info.classified_views}
          - {lang_sprintf id=507 1=$classifieds[classified_loop].classified->classified_info.total_comments}
          - {lang_sprintf id=4500135 1=$created}
          {if $classifieds[classified_loop].classified->classified_info.classified_dateupdated && $created!=$updated}
            - {lang_sprintf id=4500136 1=$updated}
          {/if}
        </div>
        
        {* SHOW CLASSIFIED DESCRIPTION *}
        <div class='seClassifiedBody' style='margin-top: 8px; margin-bottom: 8px;'>
          {$classifieds[classified_loop].classified->classified_info.classified_body|strip_tags|truncate:197:"...":true}
        </div>
        
        {* SHOW CLASSIFIED OPTIONS *}
        <div class='seClassifiedOptions'>
          {* VIEW *}
          <div class="seClassifiedOption1">
            <a href='{$url->url_create("classified", $user->user_info.user_username, $classifieds[classified_loop].classified->classified_info.classified_id)}'>
              <img src='./images/icons/classified_classified16.gif' border='0' class='button' />
              {lang_print id=4500073}
            </a>
          </div>
          
          {* EDIT *}
          <div class="seClassifiedOption2">
            <a href='user_classified_listing.php?classified_id={$classifieds[classified_loop].classified->classified_info.classified_id}'>
              <img src='./images/icons/classified_edit16.gif' border='0' class='button' />
              {lang_print id=4500074}
            </a>
          </div>
          
          {* MEDIA *}
          <div class="seClassifiedOption2">
            <a href='user_classified_media.php?classified_id={$classifieds[classified_loop].classified->classified_info.classified_id}'>
              <img src='./images/icons/classified_editmedia16.gif' border='0' class='button' />
              {lang_print id=4500075}
            </a>
          </div>
          
          {* DELETE *}
          <div class="seClassifiedOption2">
            <a href='javascript:void(0);' onclick="SupremeEdition.Classified.deleteClassified({$classifieds[classified_loop].classified->classified_info.classified_id});">
              <img src='./images/icons/classified_delete16.gif' border='0' class='button' />
              {lang_print id=4500076}
            </a>
          </div>
        </div>
      </td>
    </tr>
  </table>
  
</div>
{/section}

<div style='clear: both; height: 0px;'></div>



{* DISPLAY PAGINATION MENU IF APPLICABLE *}
{if $maxpage > 1}
  <div class='center'>
    {if $p != 1}
      <a href='user_classified.php?search={$search}&p={math equation="p-1" p=$p}'>&#171; {lang_print id=182}</a>
    {else}
      <font class='disabled'>&#171; {lang_print id=182}</font>
    {/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_sprintf id=184 1=$p_start 2=$total_classifieds} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_sprintf id=185 1=$p_start 2=$p_end 3=$total_classifieds} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}
      <a href='user_classified.php?search={$search}&p={math equation="p+1" p=$p}'>{lang_print id=183} &#187;</a>
    {else}
      <font class='disabled'>{lang_print id=183} &#187;</font>
    {/if}
  </div>
  <br />
{/if}

{include file='footer.tpl'}