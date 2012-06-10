{include file='header.tpl'}

{* $Id: jobs.tpl 7 2009-01-11 06:01:49Z john $ *}

<div class='page_header'>
  {lang_sprintf id=6400060 1=$owner->user_displayname 2=$url->url_create("profile", $owner->user_info.user_username)}
</div>


{* SHOW NO ENTRIES MESSAGE IF NECESSARY *}
{if !$total_jobs }

  <table cellpadding='0' cellspacing='0'>
    <tr>
      <td class='result'>
        <img src='./images/icons/bulb22.gif' border='0' class='icon' />
        {lang_sprintf id=6400061 1=$owner->user_displayname 2=$url->url_create("profile", $owner->user_info.user_username)}
      </td>
    </tr>
  </table>
  
{/if}

{* SHOW job ENTRIES *}
{section name=job_loop loop=$jobs}
<div id='seJob_{$jobs[job_loop].job->job_info.job_id}' class="seJob {cycle values='seJob1,seJob2'}">

  <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      <td class='seJobLeft' width='1'>
        <div class='seJobPhoto' style='width: 140px;'>
          <table cellpadding='0' cellspacing='0' width='140'>
            <tr>
              <td>
                <a href='{$url->url_create("job", $owner->user_info.user_username, $jobs[job_loop].job->job_info.job_id)}'>
                  <img src='{$jobs[job_loop].job->job_photo("./images/nophoto.gif")}' border='0' width='{$misc->photo_size($jobs[job_loop].job->job_photo("./images/nophoto.gif"),"140","140","w")}' />
                </a>
              </td>
            </tr>
          </table>
        </div>
      </td>
      <td class='seJobRight' width='100%'>
      
        {* SHOW JOB TITLE *}
        <div class='seJobTitle'>
          <a href='{$url->url_create("job", $owner->user_info.user_username, $jobs[job_loop].job->job_info.job_id)}'>
            {if !$jobs[job_loop].job->job_info.job_title}<i>{lang_print id=589}</i>{else}{$jobs[job_loop].job->job_info.job_title|truncate:70:"...":false|choptext:40:"<br>"}{/if}
          </a>
        </div>
        
        {* SHOW JOB CATEGORY *}
        {if !empty($jobs[job_loop].job->job_info.main_category_title)}
        <div class='seJobCategory'>
          {lang_print id=6400058}
          {* SHOW PARENT CATEGORY *}
          {if !empty($jobs[job_loop].job->job_info.parent_category_title)}
            <a href="browse_jobs.php?jobcat_id={$jobs[job_loop].job->job_info.parent_category_id}">{lang_print id=$jobs[job_loop].job->job_info.parent_category_title}</a>
            -
          {/if}
          <a href="browse_jobs.php?jobcat_id={$jobs[job_loop].job->job_info.main_category_id}">{lang_print id=$jobs[job_loop].job->job_info.main_category_title}</a>
        </div>
        {/if}
        
        {* SHOW JOB STATS *}
        <div class='seJobStats'>
          {assign var='job_datecreated' value=$datetime->time_since($jobs[job_loop].job->job_info.job_date)}
          {capture assign="created"}{lang_sprintf id=$job_datecreated[0] 1=$job_datecreated[1]}{/capture}
          {assign var='job_dateupdated' value=$datetime->time_since($jobs[job_loop].job->job_info.job_dateupdated)}
          {capture assign="updated"}{lang_sprintf id=$job_dateupdated[0] 1=$job_dateupdated[1]}{/capture}
          
          {lang_sprintf id=6400072 1=$jobs[job_loop].job->job_info.job_views}
          - {lang_sprintf id=507 1=$jobs[job_loop].job->job_info.total_comments}
          - {lang_sprintf id=6400135 1=$created}
          {if $jobs[job_loop].job->job_info.job_dateupdated && $created!=$updated}
            - {lang_sprintf id=6400136 1=$updated}
          {/if}
        </div>
        
        {* SHOW JOB DESCRIPTION *}
        <div class='seJobDescription' style='margin-top: 8px; margin-bottom: 8px;'>
          {$jobs[job_loop].job->job_info.job_body|strip_tags|truncate:197:"...":true}
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
      <a href='{$url->url_create("jobs", $owner->user_info.user_username)}&p={math equation="p-1" p=$p}'>&#171; {lang_print id=182}</a>
    {else}
      <font class='disabled'>&#171; {lang_print id=182}</font>
    {/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_sprintf id=184 1=$p_start 2=$total_jobs} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_sprintf id=185 1=$p_start 2=$p_end 3=$total_jobs} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}
      <a href='{$url->url_create("jobs", $owner->user_info.user_username)}&p={math equation="p+1" p=$p}'>{lang_print id=183} &#187;</a>
    {else}
      <font class='disabled'>{lang_print id=183} &#187;</font>
    {/if}
  </div>
{/if}

{include file='footer.tpl'}