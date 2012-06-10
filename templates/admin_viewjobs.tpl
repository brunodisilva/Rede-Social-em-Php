{include file='admin_header.tpl'}

{* $Id: admin_viewjobs.tpl 7 2009-01-11 06:01:49Z john $ *}

<h2>{lang_print id=6400002}</h2>
{lang_print id=6400049}
<br />
<br />

<form action='admin_viewjobs.php' method='POST'>
<table cellpadding='0' cellspacing='0' width='400' align='center'>
  <tr>
    <td align='center'>
      <div class='box'>
        <table cellpadding='0' cellspacing='0' align='center'>
          <tr>
            <td>
              {lang_print id=6400052}<br />
              <input type='text' class='text' name='f_title' value='{$f_title}' size='15' maxlength='100' />
              &nbsp;
            </td>
            <td>
              {lang_print id=6400053}<br />
              <input type='text' class='text' name='f_owner' value='{$f_owner}' size='15' maxlength='50' />
              &nbsp;
            </td>
            <td>
              &nbsp;
              {lang_block id=1002 var=langBlockTemp}<input type='submit' class='button' value='{$langBlockTemp}' />{/lang_block}
            </td>
          <input type='hidden' name='s' value='{$s}' />
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</form>
<br />


{* IF THERE ARE NO job ENTRIES *}
{if !$total_jobs}

  <table cellpadding='0' cellspacing='0' width='400' align='center'>
    <tr>
      <td align='center'>
        <div class='box' style='width: 300px;'><b>{lang_print id=6400050}</b></div>
      </td>
    </tr>
  </table>
  <br />

{* IF THERE ARE job ENTRIES *}
{else}

  {* JAVASCRIPT FOR CHECK ALL *}
  {literal}
  <script language='JavaScript'> 
  <!---
  var checkboxcount = 1;
  function doCheckAll() {
    if(checkboxcount == 0) {
      with (document.items) {
      for (var i=0; i < elements.length; i++) {
      if (elements[i].type == 'checkbox') {
      elements[i].checked = false;
      }}
      checkboxcount = checkboxcount + 1;
      }
    } else
      with (document.items) {
      for (var i=0; i < elements.length; i++) {
      if (elements[i].type == 'checkbox') {
      elements[i].checked = true;
      }}
      checkboxcount = checkboxcount - 1;
      }
  }
  // -->
  </script>
  {/literal}

  <div class='pages'>
    {lang_sprintf id=6400051 1=$total_jobs}
    &nbsp;|&nbsp;
    {lang_print id=1005}
    {section name=page_loop loop=$pages}
      {if $pages[page_loop].link}
        {$pages[page_loop].page}
      {else}
        <a href='admin_viewjobs.php?s={$s}&p={$pages[page_loop].page}&f_title={$f_title}&f_owner={$f_owner}'>{$pages[page_loop].page}</a>
      {/if}
    {/section}
  </div>
  
  <form action='admin_viewjobs.php' method='post' name='items'>
  <table cellpadding='0' cellspacing='0' class='list'>
    <tr>
      <td class='header' width='10'><input type='checkbox' name='select_all' onClick='javascript:doCheckAll()'></td>
      <td class='header' width='10' style='padding-left: 0px;'><a class='header' href='admin_viewjobs.php?s={$i}&p={$p}&f_title={$f_title}&f_owner={$f_owner}'>{lang_print id=87}</a></td>
      <td class='header'><a class='header' href='admin_viewjobs.php?s={$t}&p={$p}&f_title={$f_title}&f_owner={$f_owner}'>{lang_print id=6400052}</a></td>
      <td class='header'><a class='header' href='admin_viewjobs.php?s={$o}&p={$p}&f_title={$f_title}&f_owner={$f_owner}'>{lang_print id=6400053}</a></td>
      <td class='header' align='center'><a class='header' href='admin_viewjobs.php?s={$v}&p={$p}&f_title={$f_title}&f_owner={$f_owner}'>{lang_print id=396}</a></td>
      <td class='header' width='100'><a class='header' href='admin_viewjobs.php?s={$d}&p={$p}&f_title={$f_title}&f_owner={$f_owner}'>{lang_print id=88}</a></td>
      <td class='header' width='100'>{lang_print id=153}</td>
    </tr>
    
    {section name=job_loop loop=$jobs}
    {assign var='job_url' value=$url->url_create('job', $jobs[job_loop].job_author->user_info.user_username, $jobs[job_loop].job->job_info.job_id)}
    
    <tr class='{cycle values="background1,background2"}'>
      <td class='item' style='padding-right: 0px;'>
        <input type='checkbox' name='delete_jobs[]' value='{$jobs[job_loop].job->job_info.job_id}' />
      </td>
      <td class='item' style='padding-left: 0px;'>
        {$entries[job_loop].job_id}
      </td>
      <td class='item'>
        {if !$jobs[job_loop].job->job_info.job_title}
          <i>{lang_print id=589}</i>
        {else}
          {$jobs[job_loop].job->job_info.job_title}
        {/if}
      </td>
      <td class='item'>
        <a href='{$url->url_create("profile", $jobs[job_loop].job_author->user_info.user_username)}' target='_blank'>
          {$jobs[job_loop].job_author->user_displayname}
        </a>
      </td>
      <td class='item' align='center'>
        {$jobs[job_loop].job->job_info.job_views}
      </td>
      <td class='item'>
        {* $datetime->cdate($setting.setting_dateformat, $datetime->timezone($jobs[job_loop].job->job_info.job_date, $setting.setting_timezone)) *}
      </td>
      <td class='item'>
        [ <a href='admin_loginasuser.php?user_id={$jobs[job_loop].job_author->user_info.user_id}&return_url={$url->url_encode($job_url)}' target='_blank'>{lang_print id=6400054}</a> ]
        [ <a href='javascript:void(0);' onClick="if(confirm('{lang_print id=6400055}')) {literal}{{/literal} location.href='admin_viewjobs.php?task=deleteentries&delete_jobs[]={$jobs[job_loop].job->job_info.job_id}'; {literal}}{/literal}">{lang_print id=155}</a> ]
      </td>
    </tr>
    {/section}
  </table>
  <br />
  
  
  <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      <td>
        {lang_block id=788 var=langBlockTemp}<input type='submit' class='button' value='{$langBlockTemp}' />{/lang_block}
      </td>
      <td align='right' valign='top'>
        <div class='pages2'>
          {lang_sprintf id=6400051 1=$total_jobs}
          &nbsp;|&nbsp;
          {lang_print id=1005}
          {section name=page_loop loop=$pages}
            {if $pages[page_loop].link}
              {$pages[page_loop].page}
            {else}
              <a href='admin_viewjobs.php?s={$s}&p={$pages[page_loop].page}&f_title={$f_title}&f_owner={$f_owner}'>{$pages[page_loop].page}</a>
            {/if}
          {/section}
        </div>
      </td>
    </tr>
  </table>

  <input type='hidden' name='task' value='deleteentries' />
  <input type='hidden' name='p' value='{$p}' />
  <input type='hidden' name='s' value='{$s}' />
  <input type='hidden' name='f_title' value='{$f_title}' />
  <input type='hidden' name='f_owner' value='{$f_owner}' />
  </form>
  
{/if}

{include file='admin_footer.tpl'}