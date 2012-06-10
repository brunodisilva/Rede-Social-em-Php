{* RECENT PICTURES *}
{if !empty($files)}
<table cellpadding='0' cellspacing='0' class='portal_table' width='100%'>

<tr>
<td class='portal_box'>
{literal}

<div align='left'><table cellpadding='4' cellspacing='0' width='100%'><tr>
<td style='filter:alpha(opacity=95)' onMouseOver='nereidFade(this,100,20,40)' onMouseOut='nereidFade(this,30,10,5)'>
{/literal}

{* SHOW FILES IN THIS ALBUM *}
{section name=files_loop loop=$files max=8}
{* START NEW ROW *}
{cycle name="startrowREPIC" values="<table cellpadding='0' cellspacing='0' align='middle'><tr>,,,"}
{* SHOW THUMBNAIL *}
<td style='padding: 15px 15px 15px 0px; text-align: center; vertical-align: middle;'>
{$files[files_loop].media_title|truncate:20:"...":true}&nbsp;
<div class='album_thumb2' style='width: 120; text-align: center; vertical-align: middle;'>
<a href='{$url->url_create("album_file", $files[files_loop].media_username, $files[files_loop].media_album_id,$files[files_loop].media_id)}' style="display:block;">
{if $files[files_loop].media_ext == "jpg" OR
$files[files_loop].media_ext == "jpeg" OR
$files[files_loop].media_ext == "gif" OR
$files[files_loop].media_ext == "png" OR
$files[files_loop].media_ext == "tif" OR
$files[files_loop].media_ext == "bmp"}
<img src='{$files[files_loop].media_path}' onMouseover="tip('&nbsp;{$files[files_loop].media_title|truncate:20:"...":true|default:""}<br>:&nbsp;{$files[files_loop].media_username|truncate:15}<br>&nbsp;{$files[files_loop].media_desc|truncate:20:"...":true|default:"нет"}<br>:&nbsp;{$files[files_loop].media_views}<br>:&nbsp;{$files[files_loop].media_comment}', 150)"; onMouseout="hidetip()" class='photo' style="border:none; margin:3px;" border='0' width='{$misc->photo_size($files[files_loop].media_path,"90","90","w")}'>
{else}
<img src='./images/icons/file_big.gif' valign="center" class='photo' border='0' width='{$misc->photo_size("./images/icons/file_big.gif","90","90","w")}'>
{/if}
</a></div>
<div align="middle">
Comments {$files[files_loop].media_views}{$files[files_loop].media_comment}
</div>
</td>
{* END ROW AFTER n RESULTS *}

{cycle name="endrow_recent_photos" values=",,,</tr></table>"}
{/section}
{else}
No photo.
{/if}
</td></tr></table></div>

</td>
</tr>
</table>
</div>