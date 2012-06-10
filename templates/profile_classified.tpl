
{* $Id: profile_classified.tpl 7 2009-01-11 06:01:49Z john $ *}

{* BEGIN CLASSIFIED LISTINGS *}
{if $owner->level_info.level_classified_allow && $total_classifieds}

  <div class='profile_headline'>{lang_print id=4500007} ({$total_classifieds})</div>
  <div>
    {* LOOP THROUGH FIRST 5 BLOG ENTRIES *}
    {section name=classified_loop loop=$classifieds max=5}
    <div class='profile_classified'>
      <table cellpadding='0' cellspacing='0'>
        <tr>
          <td valign='top'>
            <a href='{$url->url_create("classified", $owner->user_info.user_username, $classifieds[classified_loop].classified->classified_info.classified_id)}'>
              <img src='./images/icons/classified_classified16.gif' border='0' class='icon' />
            </a>
          </td>
          <td valign='top'>
            <div class='profile_classified_title'><a href='{$url->url_create("classified", $owner->user_info.user_username, $classifieds[classified_loop].classified->classified_info.classified_id)}'>{$classifieds[classified_loop].classified->classified_info.classified_title|truncate:35:"...":true}</a></div>
            <div class='profile_classified_date'>{lang_print id=4500064} {assign var="classified_date" value=$datetime->time_since($classifieds[classified_loop].classified->classified_info.classified_date)}{lang_sprintf id=$classified_date[0] 1=$classified_date[1]}</div>
            <div class='profile_classified_body'>{$classifieds[classified_loop].classified->classified_info.classified_body|strip_tags|truncate:160:"...":true}</div>
          </td>
        </tr>
      </table>
    </div>
    {/section}
    {* IF MORE THAN 5 ENTRIES, SHOW VIEW MORE LINKS *}
    {if $total_classifieds > 5}
    <div style='border-top: 1px solid #DDDDDD; padding-top: 10px;'>
      <div style='float: left;'>
        <a href='{$url->url_create("classifieds", $owner->user_info.user_username)}'>
          <img src='./images/icons/classified_classified16.gif' border='0' class='button' style='float: left;' />
          {lang_print id=1500121}
        </a>
      </div>
      <div style='clear: both; height: 0px;'></div>
    </div>
    {/if}
  </div>
  
{/if}