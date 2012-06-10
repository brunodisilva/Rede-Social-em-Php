
{* $Id: profile_job.tpl 7 2009-01-11 06:01:49Z john $ *}

{* BEGIN JOB LISTINGS *}
{if $owner->level_info.level_job_allow && $total_jobs}

  <div class='profile_headline'>{lang_print id=6400007} ({$total_jobs})</div>
  <div>
    {* LOOP THROUGH FIRST 5 BLOG ENTRIES *}
    {section name=job_loop loop=$jobs max=5}
    <div class='profile_job'>
      <table cellpadding='0' cellspacing='0'>
        <tr>
          <td valign='top'>
            <a href='{$url->url_create("job", $owner->user_info.user_username, $jobs[job_loop].job->job_info.job_id)}'>
              <img src='./images/icons/job_job16.gif' border='0' class='icon' />
            </a>
          </td>
          <td valign='top'>
            <div class='profile_job_title'><a href='{$url->url_create("job", $owner->user_info.user_username, $jobs[job_loop].job->job_info.job_id)}'>{$jobs[job_loop].job->job_info.job_title|truncate:35:"...":true}</a></div>
            <div class='profile_job_date'>{lang_print id=6400064} {assign var="job_date" value=$datetime->time_since($jobs[job_loop].job->job_info.job_date)}{lang_sprintf id=$job_date[0] 1=$job_date[1]}</div>
            <div class='profile_job_body'>{$jobs[job_loop].job->job_info.job_body|strip_tags|truncate:160:"...":true}</div>
          </td>
        </tr>
      </table>
    </div>
    {/section}
    {* IF MORE THAN 5 ENTRIES, SHOW VIEW MORE LINKS *}
    {if $total_jobs > 5}
    <div style='border-top: 1px solid #DDDDDD; padding-top: 10px;'>
      <div style='float: left;'>
        <a href='{$url->url_create("jobs", $owner->user_info.user_username)}'>
          <img src='./images/icons/job_job16.gif' border='0' class='button' style='float: left;' />
          {lang_print id=1500121}
        </a>
      </div>
      <div style='clear: both; height: 0px;'></div>
    </div>
    {/if}
  </div>
  
{/if}