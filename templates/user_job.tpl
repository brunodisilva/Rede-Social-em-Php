{include file='header.tpl'}

{* $Id: user_job.tpl 7 2009-01-11 06:01:49Z john $ *}

<img src='./images/icons/job_job48.gif' border='0' class='icon_big' style="margin-bottom: 15px;">
<div class='page_header'>{lang_print id=6400068}</div>
<div>
  {lang_print id=6400069}
</div>
<br />


{* SHOW BUTTONS *}
<div style='margin-top: 20px;'>
  <div class='button' style='float: left;'>
    <a href='user_job_listing.php'><img src='./images/icons/job_post16.gif' border='0' class='button' />{lang_print id=6400065}</a>
  </div>
  <div class='button' style='float: left; padding-left: 20px;'>
    <a href='user_job_settings.php'><img src='./images/icons/job_settings16.gif' border='0' class='button' />{lang_print id=6400066}</a>
  </div>
  <div class='button' style='float: left; padding-left: 20px;'>
    <a href="javascript:void(0);" onclick="$('job_search').style.display = ( $('job_search').style.display=='block' ? 'none' : 'block');this.blur();"><img src='./images/icons/search16.gif' border='0' class='button' />{lang_print id=6400067}</a>
  </div>
  <div style='clear: both; height: 0px;'></div>
</div>


{* SEARCH FIELD *}
<div id='job_search' class="seJobSearch" style='margin-top: 10px;{if empty($search)} display: none;{/if}'>
  <div style='padding: 10px;'>
    <form action='user_job.php' name='searchform' method='post'>
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
{lang_javascript ids=861,6400121,6400123}
<script type="text/javascript" src="./include/js/class_job.js"></script>
<script type="text/javascript">
  
  SupremeEdition.Job = new SupremeEditionAPI.Job();
  SupremeEdition.RegisterModule(SupremeEdition.Job);
  
</script>


{* HIDDEN DIV TO DISPLAY DELETE CONFIRMATION MESSAGE *}
<div style='display: none;' id='confirmjobdelete'>
  <div style='margin-top: 10px;'>
    {lang_print id=6400122}
  </div>
  <br />
  {lang_block id=175 var=langBlockTemp}<input type='button' class='button' value='{$langBlockTemp}' onClick='parent.TB_remove();parent.SupremeEdition.Job.deleteJobConfirm();' />{/lang_block}
  {lang_block id=39 var=langBlockTemp}<input type='button' class='button' value='{$langBlockTemp}' onClick='parent.TB_remove();' />{/lang_block}
</div>


{* DISPLAY MESSAGE IF NO JOB ENTRIES *}
<div id="seJobNullMessage"{if $total_jobs} style="display: none;"{/if}>
  <table cellpadding='0' cellspacing='0' align='center'>
    <tr>
      <td class='result'>
        {if !empty($search)}
          <img src='./images/icons/bulb16.gif' border='0' class='icon' />
          {lang_print id=6400070}
        {else}
          <img src='./images/icons/bulb16.gif' border='0' class='icon' />
          {lang_sprintf id=6400071 1='user_job_listing.php'}
        {/if}
      </td>
    </tr>
  </table>
</div>


{* DISPLAY PAGINATION MENU IF APPLICABLE *}
{if $maxpage > 1}
  <div class='center'>
    {if $p != 1}
      <a href='user_job.php?search={$search}&p={math equation="p-1" p=$p}'>&#171; {lang_print id=182}</a>
    {else}
      <font class='disabled'>&#171; {lang_print id=182}</font>
    {/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_sprintf id=184 1=$p_start 2=$total_jobs} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_sprintf id=185 1=$p_start 2=$p_end 3=$total_jobs} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}
      <a href='user_job.php?search={$search}&p={math equation="p+1" p=$p}'>{lang_print id=183} &#187;</a>
    {else}
      <font class='disabled'>{lang_print id=183} &#187;</font>
    {/if}
  </div>
  <br />
{/if}


{* DISPLAY JOB LISTINGS *}
{section name=job_loop loop=$jobs}
<div id='seJob_{$jobs[job_loop].job->job_info.job_id}' class="seJob {cycle values='seJob1,seJob2'}">

  <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      <td class='seJobLeft' width='1'>
        <div class='seJobPhoto' style='width: 140px;'>
          <table cellpadding='0' cellspacing='0' width='140'>
            <tr>
              <td>
                <a href='{$url->url_create("job", $user->user_info.user_username, $jobs[job_loop].job->job_info.job_id)}'>
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
          {if !$jobs[job_loop].job->job_info.job_title}<i>{lang_print id=589}</i>{else}{$jobs[job_loop].job->job_info.job_title|truncate:70:"...":false|choptext:40:"<br>"}{/if}
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
        <div class='seJobBody' style='margin-top: 8px; margin-bottom: 8px;'>
          {$jobs[job_loop].job->job_info.job_body|strip_tags|truncate:197:"...":true}
        </div>
        
        {* SHOW JOB OPTIONS *}
        <div class='seJobOptions'>
          {* VIEW *}
          <div class="seJobOption1">
            <a href='{$url->url_create("job", $user->user_info.user_username, $jobs[job_loop].job->job_info.job_id)}'>
              <img src='./images/icons/job_job16.gif' border='0' class='button' />
              {lang_print id=6400073}
            </a>
          </div>
          
          {* EDIT *}
          <div class="seJobOption2">
            <a href='user_job_listing.php?job_id={$jobs[job_loop].job->job_info.job_id}'>
              <img src='./images/icons/job_edit16.gif' border='0' class='button' />
              {lang_print id=6400074}
            </a>
          </div>
          
          {* MEDIA *}
          <div class="seJobOption2">
            <a href='user_job_media.php?job_id={$jobs[job_loop].job->job_info.job_id}'>
              <img src='./images/icons/job_editmedia16.gif' border='0' class='button' />
              {lang_print id=6400075}
            </a>
          </div>
          
          {* DELETE *}
          <div class="seJobOption2">
            <a href='javascript:void(0);' onclick="SupremeEdition.Job.deleteJob({$jobs[job_loop].job->job_info.job_id});">
              <img src='./images/icons/job_delete16.gif' border='0' class='button' />
              {lang_print id=6400076}
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
      <a href='user_job.php?search={$search}&p={math equation="p-1" p=$p}'>&#171; {lang_print id=182}</a>
    {else}
      <font class='disabled'>&#171; {lang_print id=182}</font>
    {/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_sprintf id=184 1=$p_start 2=$total_jobs} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_sprintf id=185 1=$p_start 2=$p_end 3=$total_jobs} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}
      <a href='user_job.php?search={$search}&p={math equation="p+1" p=$p}'>{lang_print id=183} &#187;</a>
    {else}
      <font class='disabled'>{lang_print id=183} &#187;</font>
    {/if}
  </div>
  <br />
{/if}

{include file='footer.tpl'}