{include file='header.tpl'}

{* $Id: classifieds.tpl 7 2009-01-11 06:01:49Z john $ *}

<div class='page_header'>
  {lang_sprintf id=4500060 1=$owner->user_displayname 2=$url->url_create("profile", $owner->user_info.user_username)}
</div>


{* SHOW NO ENTRIES MESSAGE IF NECESSARY *}
{if !$total_classifieds }

  <table cellpadding='0' cellspacing='0'>
    <tr>
      <td class='result'>
        <img src='./images/icons/bulb22.gif' border='0' class='icon' />
        {lang_sprintf id=4500061 1=$owner->user_displayname 2=$url->url_create("profile", $owner->user_info.user_username)}
      </td>
    </tr>
  </table>
  
{/if}

{* SHOW classified ENTRIES *}
{section name=classified_loop loop=$classifieds}
<div id='seClassified_{$classifieds[classified_loop].classified->classified_info.classified_id}' class="seClassified {cycle values='seClassified1,seClassified2'}">

  <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      <td class='seClassifiedLeft' width='1'>
        <div class='seClassifiedPhoto' style='width: 140px;'>
          <table cellpadding='0' cellspacing='0' width='140'>
            <tr>
              <td>
                <a href='{$url->url_create("classified", $owner->user_info.user_username, $classifieds[classified_loop].classified->classified_info.classified_id)}'>
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
          <a href='{$url->url_create("classified", $owner->user_info.user_username, $classifieds[classified_loop].classified->classified_info.classified_id)}'>
            {if !$classifieds[classified_loop].classified->classified_info.classified_title}<i>{lang_print id=589}</i>{else}{$classifieds[classified_loop].classified->classified_info.classified_title|truncate:70:"...":false|choptext:40:"<br>"}{/if}
          </a>
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
        <div class='seClassifiedDescription' style='margin-top: 8px; margin-bottom: 8px;'>
          {$classifieds[classified_loop].classified->classified_info.classified_body|strip_tags|truncate:197:"...":true}
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
      <a href='{$url->url_create("classifieds", $owner->user_info.user_username)}&p={math equation="p-1" p=$p}'>&#171; {lang_print id=182}</a>
    {else}
      <font class='disabled'>&#171; {lang_print id=182}</font>
    {/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_sprintf id=184 1=$p_start 2=$total_classifieds} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_sprintf id=185 1=$p_start 2=$p_end 3=$total_classifieds} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}
      <a href='{$url->url_create("classifieds", $owner->user_info.user_username)}&p={math equation="p+1" p=$p}'>{lang_print id=183} &#187;</a>
    {else}
      <font class='disabled'>{lang_print id=183} &#187;</font>
    {/if}
  </div>
{/if}

{include file='footer.tpl'}