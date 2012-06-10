{* EVENTS ON MAIN PAGE *}
{* NO EVENTS AT ALL *}
  {if $events|@count == 0}
    <br>
    <table cellpadding='0' cellspacing='0' align='center'>
      <tr>
        <td class='result'>
          <img src='./images/icons/bulb16.gif' border='0' class='icon' />
          {lang_print id=3000214}
        </td>
      </tr>
    </table>
  {/if}

  {* DISPLAY PAGINATION MENU IF APPLICABLE *}
  {if $maxpage > 1}
    <div class='event_pages_top'>
    {if $p != 1}<a href='browse_events.php?s={$s}&v={$v}&eventcat_id={$eventcat_id}&p={math equation="p-1" p=$p}'>« {lang_print id=182}</a>{else}« {lang_print id=182}{/if}
    &nbsp;|&nbsp;&nbsp;
    {if $p_start == $p_end}
      <b>{lang_sprintf id=184 1=$p_start 2=$total_events}</b>
    {else}
      <b>{lang_sprintf id=185 1=$p_start 2=$p_end 3=$total_events}</b>
    {/if}
    &nbsp;&nbsp;|&nbsp;
    {if $p != $maxpage}<a href='browse_events.php?s={$s}&v={$v}&eventcat_id={$eventcat_id}&p={math equation="p+1" p=$p}'>{lang_print id=183} »</a>{else}{lang_print id=183} »{/if}
    </div>
  {/if}

  {section name=event_loop loop=$events}
    <div style='padding: 10px; border: 1px solid #CCCCCC; margin-bottom: 10px;'>
      <table cellpadding='0' cellspacing='0'>
      <tr>
      <td>
        <a href='event.php?event_id={$events[event_loop].event->event_info.event_id}'>
          <img class='photo' src='{$events[event_loop].event->event_photo("./images/nophoto.gif", TRUE)}' border='0' width='60' height='60' />
        </a>
      </td>
      <td style='vertical-align: top; padding-left: 10px;'>
        <div style='font-weight: bold; font-size: 10pt;'>
          <a href='event.php?event_id={$events[event_loop].event->event_info.event_id}'>
            {$events[event_loop].event->event_info.event_title}
          </a>
        </div>
        <div style='color: #777777; font-size: 7pt; margin-bottom: 2px;'>
          {* SHOW EVENT STATS *}
          <div class='seEventStats'>
            {assign var=event_date_start value=$datetime->timezone($events[event_loop].event->event_info.event_date_start, $global_timezone)}
            {assign var=event_date_end value=$datetime->timezone($events[event_loop].event->event_info.event_date_end, $global_timezone)}
            
            {lang_print id=3000105}
            
            {* NO END DATE *}
            {if !$events[event_loop].event->event_info.event_date_end}
              {lang_sprintf id=3000203 1=$datetime->cdate($setting.setting_dateformat, $event_date_start) 2=$datetime->cdate($setting.setting_timeformat, $event_date_start)}
            
            {* SAME-DAY EVENT *}
            {elseif $datetime->cdate("F j, Y", $event_date_start)==$datetime->cdate("F j, Y", $event_date_end)}
              {lang_sprintf id=3000202 1=$datetime->cdate($setting.setting_dateformat, $event_date_start) 2=$datetime->cdate($setting.setting_timeformat, $event_date_start) 3=$datetime->cdate($setting.setting_timeformat, $event_date_end)}
            
            {* MULTI-DAY EVENT *}
            {else}
              {lang_sprintf id=3000204 1=$datetime->cdate("`$setting.setting_dateformat` `$setting.setting_timeformat`", $event_date_start) 2=$datetime->cdate("`$setting.setting_dateformat` `$setting.setting_timeformat`", $event_date_end)}
            {/if}
          </div>
          
          {assign var='event_dateupdated' value=$datetime->time_since($events[event_loop].event->event_info.event_dateupdated)}{capture assign="updated"}{lang_sprintf id=$event_dateupdated[0] 1=$event_dateupdated[1]}{/capture}
          {lang_sprintf id=3000215 1=$events[event_loop].num_members} -
          {lang_sprintf id=3000216 1=$events[event_loop].event_creator->user_displayname 2=$url->url_create("profile", $events[event_loop].event_creator->user_info.user_username)} -
          {lang_sprintf id=3000217 1=$updated}
          
        </div>
        <div>
          {$events[event_loop].event->event_info.event_desc|truncate:300:"...":true}
        </div>
      </td>
      </tr>
      </table>
    </div>
  {/section}
