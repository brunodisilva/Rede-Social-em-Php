<!-- RECENT POLLS -->

<div class='portal_spacer'></div>
<div class='header'>Recent Polls</div>
<div class='portal_content'>
{if $total_polls == 0}
<div class='error'><img src='./images/error.gif' class='icon' border='0'> No Recent Polls</div>
{/if}
{section name=poll_loop loop=$polls}
<table cellpadding='0' cellspacing='0'>
<tr>
<td style='vertical-align: top; padding-right: 8px;'>
<a href='{$url->url_create("poll", $polls[poll_loop]->poll_owner->user_info.user_username, $polls[poll_loop]->poll_info.poll_id)}'><img src='{$polls[poll_loop]->poll_owner->user_photo("./images/nophoto.gif")}' border='0' class='photo' width='50' height='50'></a></td><td>
<div style='font-weight: bold; font-size: 8pt;'><a href='{$url->url_create("poll", $polls[poll_loop]->poll_owner->user_info.user_username, $polls[poll_loop]->poll_info.poll_id)}'>{$polls[poll_loop]->poll_info.poll_title|truncate:19:"...":true}</a></div> 
<div style='font-size: 9px;'>
{assign var='poll_datecreated' value=$datetime->time_since($polls[poll_loop]->poll_info.poll_datecreated)}{capture assign="created"}{lang_sprintf id=$poll_datecreated[0] 1=$poll_datecreated[1]}{/capture}
{lang_sprintf id=2500108 1=$created 2=$url->url_create("profile", $polls[poll_loop]->poll_owner->user_info.user_username) 3=$polls[poll_loop]->poll_owner->user_displayname}
</div>
<div style='font-size: 9px;'>
{lang_sprintf id=2500028 1=$polls[poll_loop]->poll_info.poll_totalvotes},
{lang_sprintf id=949 1=$polls[poll_loop]->poll_info.poll_views}
</div>
</td>
</tr>
</table> 
{if $smarty.section.poll_loop.last != true}<div style='height: 5px;'>&nbsp;</div>{/if}
<div style='margin:10px; border-bottom:1px dotted #e0e0e0;'></div>
{/section}
</div>


<!-- END RECENT POLLS -->
