{include file='header.tpl'}

{* $Id: classified.tpl 7 2009-01-11 06:01:49Z john $ *}

<div class='page_header'>
  {lang_sprintf id=4500056 1=$owner->user_displayname 2=$url->url_create("profile", $owner->user_info.user_username) 3=$url->url_create("classifieds", $owner->user_info.user_username)}
</div>


{* JAVASCRIPT *}
{lang_javascript ids=861,4500121,4500123,4500142}
<script type="text/javascript" src="./include/js/class_classified.js"></script>
<script type="text/javascript">
  
  SupremeEdition.Classified = new SupremeEditionAPI.Classified();
  SupremeEdition.RegisterModule(SupremeEdition.Classified);
  
</script>


{if isset($page_is_preview)}<table cellspacing='0' cellpadding='0' id='classifiedpreview' style='width:100%'><tr><td>&nbsp;</td><td class='content' style='width:100%'>{/if}


{* SHOW THIS ENTRY *}
<div class='seClassifiedListing'>
  <table cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      {assign var=classified_photo value=$classified->classified_photo("./images/nophoto.gif")}
      {assign var=classified_thumb value=$classified->classified_photo("./images/nophoto.gif", TRUE)}
      <td class='seClassifiedLeft' width='1'>
        <div class="seClassifiedPhoto" style="width: 140px;">
        {if $classified_photo!="./images/nophoto.gif" && $classified_photo!=$classified_thumb}
          <a href="javascript:void(0);" class="seClassifiedPhotoLink" onclick="SupremeEdition.Classified.imagePreviewClassified('{$classified_photo}', {$files[file_loop].classifiedmedia_width|default:0}, {$files[file_loop].classifiedmedia_height|default:0});">
            <img src='{$classified_thumb}' border='0' width='{$misc->photo_size($classified_thumb,"140","140","w")}' />
          </a>
        {else}
          <img src='{$classified_photo}' border='0' width='{$misc->photo_size($classified_photo,"140","140","w")}' />
        {/if}
        </div>
      </td>
      <td class='seClassifiedRight' width='100%' valign="top">
        <div class='seClassifiedTitle'>
          {if !$classified->classified_info.classified_title}<i>{lang_print id=589}</i>{else}{$classified->classified_info.classified_title|truncate:75:"...":true}{/if}
        </div>
        
        <div class='seClassifiedStats'>
          {assign var="classified_datecreated" value=$datetime->time_since($classified->classified_info.classified_date)}
          {capture assign="datecreated"}{lang_sprintf id=$classified_datecreated[0] 1=$classified_datecreated[1]}{/capture}
          {lang_sprintf id=4500057 1=$datecreated}
        </div>
        
        {* SHOW ENTRY CATEGORY *}
        {if $cat_info.classifiedcat_title != ""}
          <div class='seClassifiedCategory'>
            {lang_sprintf id=4500058 1=$cat_info.classifiedcat_title 2="browse_classifieds.php?classifiedcat_id=`$classified->classified_info.classified_classifiedcat_id`"}
          </div>
        {/if}
        
        {* SHOW CLASSIFIED FIELDS *}
        <div class='seClassifiedFields'>
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
        
        <div class='seClassifiedBody'>
          {$classified->classified_info.classified_body|choptext:75:"<br />"}
        </div>
        
        {if $total_files>0}<br />{/if}
        
        {* SHOW FILES IN THIS ALBUM *}
        {section name=file_loop loop=$files}

          {* IF IMAGE, GET THUMBNAIL *}
          {if $files[file_loop].classifiedmedia_ext == "jpeg" OR $files[file_loop].classifiedmedia_ext == "jpg" OR $files[file_loop].classifiedmedia_ext == "gif" OR $files[file_loop].classifiedmedia_ext == "png" OR $files[file_loop].classifiedmedia_ext == "bmp"}
            {assign var='file_dir' value=$classified->classified_dir($classified->classified_info.classified_id)}
            {assign var='file_src_full' value="`$file_dir``$files[file_loop].classifiedmedia_id`.`$files[file_loop].classifiedmedia_ext`"}
            {assign var='file_src' value="`$file_dir``$files[file_loop].classifiedmedia_id`_thumb.jpg"}
          {* SET THUMB PATH FOR AUDIO *}
          {elseif $files[file_loop].classifiedmedia_ext == "mp3" OR $files[file_loop].classifiedmedia_ext == "mp4" OR $files[file_loop].classifiedmedia_ext == "wav"}
            {assign var='file_src' value='./images/icons/audio_big.gif'}
          {* SET THUMB PATH FOR VIDEO *}
          {elseif $files[file_loop].classifiedmedia_ext == "mpeg" OR $files[file_loop].classifiedmedia_ext == "mpg" OR $files[file_loop].classifiedmedia_ext == "mpa" OR $files[file_loop].classifiedmedia_ext == "avi" OR $files[file_loop].classifiedmedia_ext == "swf" OR $files[file_loop].classifiedmedia_ext == "mov" OR $files[file_loop].classifiedmedia_ext == "ram" OR $files[file_loop].classifiedmedia_ext == "rm"}
            {assign var='file_src' value='./images/icons/video_big.gif'}
          {* SET THUMB PATH FOR UNKNOWN *}
          {else}
            {assign var='file_src' value='./images/icons/file_big.gif'}
          {/if}

          {* START NEW ROW *}
          {cycle name="startrow" values="<table cellpadding='0' cellspacing='0'><tr>,"}
          {* SHOW THUMBNAIL *}
          <td style='padding: 5px 10px 5px 0px; text-align: center; vertical-align: middle;'>
            {$files[file_loop].classifiedmedia_title|truncate:20:"...":true}
            <div class='album_thumb2' style='text-align: center; vertical-align: middle;'>
              <a href="javascript:void(0);" class="seClassifiedPhotoLink" onclick="SupremeEdition.Classified.imagePreviewClassified('{$file_src_full}', {$files[file_loop].classifiedmedia_width|default:0}, {$files[file_loop].classifiedmedia_height|default:0});">
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
    <a href='{$url->url_create("classifieds", $owner->user_info.user_username)}'><img src='./images/icons/back16.gif' border='0' class='button' />{lang_sprintf id=4500059 1=$owner->user_displayname}</a>
  </div>
  <div class='button' style='float: left; padding-left: 20px;'>
    <a href="javascript:TB_show(SupremeEdition.Language.Translate(861), 'user_report.php?return_url={$url->url_current()}&TB_iframe=true&height=300&width=450', '', './images/trans.gif');"><img src='./images/icons/report16.gif' border='0' class='button'>{lang_print id=861}</a>
  </div>
  <div style='clear: both; height: 0px;'></div>
</div>
<br />


{* COMMENTS *}
<div id="classified_{$classified->classified_info.classified_id}_postcomment"></div>
<div id="classified_{$classified->classified_info.classified_id}_comments" style='margin-left: auto; margin-right: auto;'></div>

{lang_javascript ids=39,155,175,182,183,184,185,187,784,787,829,830,831,832,833,834,835,854,856,891,1025,1026,1032,1034,1071}

<script type="text/javascript">
  
  SupremeEdition.ClassifiedComments = new SupremeEditionAPI.Comments({ldelim}
    'canComment' : {if $allowed_to_comment}true{else}false{/if},
    'commentHTML' : '{$setting.setting_comment_html}',
    'commentCode' : {if $setting.setting_comment_code}true{else}false{/if},
    
    'type' : 'classified',
    'typeIdentifier' : 'classified_id',
    'typeID' : {$classified->classified_info.classified_id},
    'typeTab' : 'classifieds',
    'typeCol' : 'classified',
    
    'initialTotal' : {$total_comments|default:0},
    'paginate' : true,
    'cpp' : 5
  {rdelim});
  
  SupremeEdition.RegisterModule(SupremeEdition.ClassifiedComments);
  
  // Backwards
  function addComment(is_error, comment_body, comment_date)
  {ldelim}
    SupremeEdition.ClassifiedComments.addComment(is_error, comment_body, comment_date);
  {rdelim}
  
  function getComments(direction)
  {ldelim}
    SupremeEdition.ClassifiedComments.getComments(direction);
  {rdelim}
  
</script>


<div style="width:1px; height:1px; visibility: hidden; overflow:hidden;" id="seClassifiedImagePreview">
  <table cellpadding='0' cellspacing='0'  style="width: 100%; height: 100%; padding-top: 5px;"><tr>
    <td valign="middle" align="center"><img id="seClassifiedImageFull" src="./images/icons/file_big.gif" style="vertical-align: middle;" valign="middle" align="center" /></td>
  </tr></table>
</div>

{include file='footer.tpl'}