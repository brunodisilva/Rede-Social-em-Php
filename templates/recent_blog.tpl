{* RECENT BLOGS *}
   
   <div class='portal_content'>
{section name=blogentry_loop loop=$blogentries}
    <div style='float:left; margin-right:5px; margin-bottom:5px;'>
                  <a href='{$url->url_create("blog_entry", $blogentries[blogentry_loop].blogentry_author->user_info.user_username, $blogentries[blogentry_loop].blogentry_id)}'><img src='{$blogentries[blogentry_loop].blogentry_author->user_photo("./images/nophoto.gif", TRUE)}' style='border: 1px solid #a0a0a0; padding: 1px;'  class='photo' width='40' height='40' title='Posted by: {$blogentries[blogentry_loop].blogentry_author->user_displayname}'></a>
        </div>
            <div style=' font-weight: bold; font-size: 8pt;'>
              <a href='{$url->url_create("blog_entry", $blogentries[blogentry_loop].blogentry_author->user_info.user_username, $blogentries[blogentry_loop].blogentry_id)}'>
                {$blogentries[blogentry_loop].blogentry_title|truncate:30:"...":true}
              </a>
            </div>
            <div class='blogs_browse_date'>
              {assign var='blogentry_date' value=$datetime->time_since($blogentries[blogentry_loop].blogentry_date)}{capture assign="created"}{lang_sprintf id=$blogentry_date[0] 1=$blogentry_date[1]}{/capture}
              {lang_sprintf id=1500039 1=$created 2=$url->url_create("profile", $blogentries[blogentry_loop].blogentry_author->user_info.user_username) 3=$blogentries[blogentry_loop].blogentry_author->user_displayname}
            </div>
            <div style='margin-top: 2px; font-size: 7pt;'>
              {$blogentries[blogentry_loop].blogentry_body|truncate:140:"...":true}
            </div>
<div style='text-align: right; font-size:8pt; margin-top:10px;'>
              <a href='{$url->url_create("blog_entry", $blogentries[blogentry_loop].blogentry_author->user_info.user_username, $blogentries[blogentry_loop].blogentry_id)}'>Read This</a> |             <a href='{$url->url_create("blog", $blogentries[blogentry_loop].blogentry_author->user_info.user_username)}'>View {$blogentries[blogentry_loop].blogentry_author->user_displayname}'s Blog</a> | <a href='{$url->url_create('profile', $blogentries[blogentry_loop].blogentry_author->user_info.user_username)}'>View {$blogentries[blogentry_loop].blogentry_author->user_displayname}'s Profile Page</a>
</div>
               <div style='margin:10px; border-bottom:1px dotted #e0e0e0;'></div>
  {/section}

</div>