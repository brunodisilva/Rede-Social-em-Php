{include file='header.tpl'}

{* $Id: job.tpl 7 2009-01-11 06:01:49Z john $ *}

<div class='page_header'>
  {lang_sprintf id=6400056 1=$owner->user_displayname 2=$url->url_create("profile", $owner->user_info.user_username) 3=$url->url_create("jobs", $owner->user_info.user_username)}
</div>


{* JAVASCRIPT *}
{lang_javascript ids=861,6400121,6400123,6400142}
<script type="text/javascript" src="./include/js/class_job.js"></script>
<script type="text/javascript">
  
  SupremeEdition.Job = new SupremeEditionAPI.Job();
  SupremeEdition.RegisterModule(SupremeEdition.Job);
  
</script>


{if isset($page_is_preview)}<table cellspacing='0' cellpadding='0' id='jobpreview' style='width:100%'><tr><td>&nbsp;</td><td class='content' style='width:100%'>{/if}


{* SHOW THIS ENTRY *}
<div class='seJobListing'>
  <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      {assign var=job_photo value=$job->job_photo("./images/nophoto.gif")}
      {assign var=job_thumb value=$job->job_photo("./images/nophoto.gif", TRUE)}
      <td class='seJobLeft' width='1'>
        <div class="seJobPhoto" style="width: 140px;">
        {if $job_photo!="./images/nophoto.gif" && $job_photo!=$job_thumb}
          <a href="javascript:void(0);" class="seJobPhotoLink" onclick="SupremeEdition.Job.imagePreviewJob('{$job_photo}', {$files[file_loop].jobmedia_width|default:0}, {$files[file_loop].jobmedia_height|default:0});">
            <img src='{$job_thumb}' border='0' width='{$misc->photo_size($job_thumb,"140","140","w")}' />
          </a>
        {else}
          <img src='{$job_photo}' border='0' width='{$misc->photo_size($job_photo,"140","140","w")}' />
        {/if}
        </div>
      </td>
      <td class='seJobRight' width='100%' valign="top">
        <div class='seJobTitle'>
          {if !$job->job_info.job_title}<i>{lang_print id=589}</i>{else}{$job->job_info.job_title|truncate:75:"...":true}{/if}
        </div>
        
        <div class='seJobStats'>
          {assign var="job_datecreated" value=$datetime->time_since($job->job_info.job_date)}
          {capture assign="datecreated"}{lang_sprintf id=$job_datecreated[0] 1=$job_datecreated[1]}{/capture}
          {lang_sprintf id=6400057 1=$datecreated}
        </div>
        
        {* SHOW ENTRY CATEGORY *}
        {if $cat_info.jobcat_title != ""}
          <div class='seJobCategory'>
            {lang_sprintf id=6400058 1=$cat_info.jobcat_title 2="browse_jobs.php?jobcat_id=`$job->job_info.job_jobcat_id`"}
          </div>
        {/if}
        
        {* SHOW JOB FIELDS *}
        <div class='seJobFields'>
          {section name=cat_loop loop=$cats}
          
          <table cellpadding='0' cellspacing='0'>
          {section name=field_loop loop=$cats[cat_loop].fields}
            <tr>
              <td valign='top' style='padding-right: 10px;' nowrap='nowrap'>
                {lang_print id=$cats[cat_loop].fields[field_loop].field_title}:
              </td>
              <td>
              <div class='profile_field_value'>{$cats[cat_loop].fields[field_loop].field_value_formatted}</div>
              {*
              <div class='profile_field_value'>{if $cats[cat_loop].fields[field_loop].field_format}{$cats[cat_loop].fields[field_loop].field_value|string_format:$cats[cat_loop].fields[field_loop].field_format}{else}{$cats[cat_loop].fields[field_loop].field_value}{/if}</div>
                {if $cats[cat_loop].fields[field_loop].field_special == 1 && $cats[cat_loop].fields[field_loop].field_value|substr:0:4 != "0000"} ({lang_sprintf id=852 1=$datetime->age($cats[cat_loop].fields[field_loop].field_value)}){/if}
              *}
              </td>
            </tr>
          
          {/section}
          </table>
          
          {/section}
        </div>
        
        <div class='seJobBody'>
          {$job->job_info.job_body|choptext:75:"<br />"}
        </div>
        
        {if $total_files>0}<br />{/if}
        
        {* SHOW FILES IN THIS ALBUM *}
        {section name=file_loop loop=$files}

          {* IF IMAGE, GET THUMBNAIL *}
          {if $files[file_loop].jobmedia_ext == "jpeg" OR $files[file_loop].jobmedia_ext == "jpg" OR $files[file_loop].jobmedia_ext == "gif" OR $files[file_loop].jobmedia_ext == "png" OR $files[file_loop].jobmedia_ext == "bmp"}
            {assign var='file_dir' value=$job->job_dir($job->job_info.job_id)}
            {assign var='file_src_full' value="`$file_dir``$files[file_loop].jobmedia_id`.`$files[file_loop].jobmedia_ext`"}
            {assign var='file_src' value="`$file_dir``$files[file_loop].jobmedia_id`_thumb.jpg"}
          {* SET THUMB PATH FOR AUDIO *}
          {elseif $files[file_loop].jobmedia_ext == "mp3" OR $files[file_loop].jobmedia_ext == "mp4" OR $files[file_loop].jobmedia_ext == "wav"}
            {assign var='file_src' value='./images/icons/audio_big.gif'}
          {* SET THUMB PATH FOR VIDEO *}
          {elseif $files[file_loop].jobmedia_ext == "mpeg" OR $files[file_loop].jobmedia_ext == "mpg" OR $files[file_loop].jobmedia_ext == "mpa" OR $files[file_loop].jobmedia_ext == "avi" OR $files[file_loop].jobmedia_ext == "swf" OR $files[file_loop].jobmedia_ext == "mov" OR $files[file_loop].jobmedia_ext == "ram" OR $files[file_loop].jobmedia_ext == "rm"}
            {assign var='file_src' value='./images/icons/video_big.gif'}
          {* SET THUMB PATH FOR UNKNOWN *}
          {else}
            {assign var='file_src' value='./images/icons/file_big.gif'}
          {/if}

          {* START NEW ROW *}
          {cycle name="startrow" values="<table cellpadding='0' cellspacing='0'><tr>,"}
          {* SHOW THUMBNAIL *}
          <td style='padding: 5px 10px 5px 0px; text-align: center; vertical-align: middle;'>
            {$files[file_loop].jobmedia_title|truncate:20:"...":true}
            <div class='album_thumb2' style='text-align: center; vertical-align: middle;'>
              <a href="javascript:void(0);" class="seJobPhotoLink" onclick="SupremeEdition.Job.imagePreviewJob('{$file_src_full}', {$files[file_loop].jobmedia_width|default:0}, {$files[file_loop].jobmedia_height|default:0});">
                <img src='{$file_src}' border='0'  width='{$misc->photo_size($file_src,"300","240","w")}' class='photo' />
              </a>
            </div>
          </td>
          {* END ROW AFTER 3 RESULTS *}
          {if $smarty.section.file_loop.last == true}
            </tr></table>
          {else}
            {cycle name="endrow" values=",</tr></table>"}
          {/if}
          
        {/section}
        
      </td>
    </tr>
  </table>
</div>
<br />


<div style='margin-bottom: 20px;'>
  <div class='button' style='float: left;'>
    <a href='{$url->url_create("jobs", $owner->user_info.user_username)}'><img src='./images/icons/back16.gif' border='0' class='button' />{lang_sprintf id=6400059 1=$owner->user_displayname}</a>
  </div>
  <div class='button' style='float: left; padding-left: 20px;'>
    <a href="javascript:TB_show(SupremeEdition.Language.Translate(861), 'user_report.php?return_url={$url->url_current()}&TB_iframe=true&height=300&width=640', '', './images/trans.gif');"><img src='./images/icons/report16.gif' border='0' class='button'>{lang_print id=861}</a>
  </div>
  <div style='clear: both; height: 0px;'></div>
</div>
<br />


{* COMMENTS *}
<div id="job_{$job->job_info.job_id}_postcomment"></div>
<div id="job_{$job->job_info.job_id}_comments" style='margin-left: auto; margin-right: auto;'></div>

{lang_javascript ids=39,155,175,182,183,184,185,187,784,787,829,830,831,832,833,834,835,854,856,891,1025,1026,1032,1034,1071}

<script type="text/javascript">
  
  SupremeEdition.JobComments = new SupremeEditionAPI.Comments({ldelim}
    'canComment' : {if $allowed_to_comment}true{else}false{/if},
    'commentHTML' : '{$setting.setting_comment_html}',
    'commentCode' : {if $setting.setting_comment_code}true{else}false{/if},
    
    'type' : 'job',
    'typeIdentifier' : 'job_id',
    'typeID' : {$job->job_info.job_id},
    'typeTab' : 'jobs',
    'typeCol' : 'job',
    
    'initialTotal' : {$total_comments|default:0},
    'paginate' : true,
    'cpp' : 5
  {rdelim});
  
  SupremeEdition.RegisterModule(SupremeEdition.JobComments);
  
  // Backwards
  function addComment(is_error, comment_body, comment_date)
  {ldelim}
    SupremeEdition.JobComments.addComment(is_error, comment_body, comment_date);
  {rdelim}
  
  function getComments(direction)
  {ldelim}
    SupremeEdition.JobComments.getComments(direction);
  {rdelim}
  
</script>


<div style="width:1px; height:1px; visibility: hidden; overflow:hidden;" id="seJobImagePreview">
  <table cellpadding='0' cellspacing='0'  style="width: 100%; height: 100%; padding-top: 5px;"><tr>
    <td valign="middle" align="center"><img id="seJobImageFull" src="./images/icons/file_big.gif" style="vertical-align: middle;" valign="middle" align="center" /></td>
  </tr></table>
</div>

{include file='footer.tpl'}