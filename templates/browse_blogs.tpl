{include file='header.tpl'}

<div class='page_header'>{lang_print id=1500031}</div>

<form method="get" name="seBrowseBlogs">
<input type="hidden" name="p" value="{$p|default:1}" />

<div style='padding: 7px 10px 7px 10px; background: #F2F2F2; border: 1px solid #BBBBBB; margin: 10px 0px 10px 0px; font-weight: bold;'>
  <table cellpadding='0' cellspacing='0'>
    <tr>
      <td style='padding-right: 3px;'>
        {lang_print id=643}
      </td>
      <td>
        <input type="text" name="blog_search" value="{$blog_search}" class="text" onblur="document.seBrowseBlogs.submit();"style="width:120px;" />
      </td>
      <td style='padding-left: 3px;'>
        <input type='submit' class='button' value='{lang_print id=646}' />
      </td>
    
      <td style='padding-left: 10px; padding-right: 3px;'>
        {lang_print id=1500032}
      </td>
      <td>
        <select class='small' name='v' onchange="document.seBrowseBlogs.submit();">
        <option value='0'{if $v == "0"} SELECTED{/if}>{lang_print id=1500116}</option>
        {if $user->user_exists}<option value='1'{if $v == "1"} SELECTED{/if}>{lang_print id=1500117}</option>{/if}
        </select>
      </td>
      
      <td style='padding-left: 10px; padding-right: 3px;'>
        {lang_print id=1500034}
      </td>
      <td>
        <select class='small' name='c' onchange="document.seBrowseBlogs.submit();">
          <option value='-1'> </option>
          {section name=blogentrycat_loop loop=$blogentrycats}
          <option value='{$blogentrycats[blogentrycat_loop].blogentrycat_id}'{if $c==$blogentrycats[blogentrycat_loop].blogentrycat_id} SELECTED{/if}>
            {$blogentrycats[blogentrycat_loop].blogentrycat_title|truncate:24}
          </option>
          {/section}
          <option value='0'{if isset($c) && $c==0} SELECTED{/if}>{lang_print id=1500035}</option>
        </select>
      </td>
      
      <td style='padding-left: 10px; padding-right: 3px;'>
        {lang_print id=1500033}
      </td>
      <td>
        <select class='small' name='s' onchange="document.seBrowseBlogs.submit();">
        <option value='blogentry_date DESC'{if $s == "blogentry_date DESC"} SELECTED{/if}>{lang_print id=1500036}</option>
        <option value='blogentry_views DESC'{if $s == "blogentry_views DESC"} SELECTED{/if}>{lang_print id=1500037}</option>
        <option value='total_comments DESC'{if $s == "total_comments DESC"} SELECTED{/if}>{lang_print id=1500038}</option>
        </select>
      </td>
      
    </tr>
  </table>
</div>

</form>


{* DISPLAY PAGINATION MENU IF APPLICABLE *}
{if $maxpage > 1}
  <div style='text-align: center; padding-bottom: 10px;'>
    {if $p != 1}
      <a href='java script:void(0);' onclick='document.seBrowseBlogs.p.value={math equation="p-1" p=$p};document.seBrowseBlogs.submit();'>? {lang_print id=182}</a>
    {else}
      ? {lang_print id=182}
    {/if}
    &nbsp;|&nbsp;&nbsp;
    {if $p_start == $p_end}
      <b>{lang_sprintf id=184 1=$p_start 2=$total_blogentries}</b>
    {else}
      <b>{lang_sprintf id=185 1=$p_start 2=$p_end 3=$total_blogentries}</b>
    {/if}
    &nbsp;&nbsp;|&nbsp;
    {if $p != $maxpage}
      <a href='java script:void(0);' onclick='document.seBrowseBlogs.p.value={math equation="p+1" p=$p};document.seBrowseBlogs.submit();'>{lang_print id=183} ?</a>
    {/if}
  </div>
{/if}



<div>

  {section name=blogentry_loop loop=$blogentries}
    
    <div class='blogs_browse_item {cycle name="blogmg" values="blogs_browse_item_left, blogs_browse_item_right"} {cycle name="blogbg" values="blogs_browse1,blogs_browse2,blogs_browse2,blogs_browse1"}' style='width: 435px; height:115px; float: left;'>
      <table cellpadding='0' cellspacing='0'>
        <tr>
<td valign='top'>
<div style='border: 1px solid #e0e0e0; padding: 3px; margin: 10px;'>
        <a href="{$url->url_create('profile', $blogentries[blogentry_loop].blogentry_author->user_info.user_username)}"><img class='photo' src='{$blogentries[blogentry_loop].blogentry_author->user_photo('./images/nophoto.gif','80','80','w')}' height='77' border='0' /></a>
</div>
</td>
          <td style='vertical-align: top; padding: 10px;'>
            <div style='font-weight: bold; font-size: 10pt;'>
              <img src="./images/icons/blog_blog16.gif" class='button' style='float: left;'>
              <a href='{$url->url_create("blog_entry", $blogentries[blogentry_loop].blogentry_author->user_info.user_username, $blogentries[blogentry_loop].blogentry_id)}'>
                {$blogentries[blogentry_loop].blogentry_title|truncate:30:"...":true}
              </a>
            </div>
            <div class='blogs_browse_date'>
              {assign var='blogentry_date' value=$datetime->time_since($blogentries[blogentry_loop].blogentry_date)}{capture assign="created"}{lang_sprintf id=$blogentry_date[0] 1=$blogentry_date[1]}{/capture}
              {lang_sprintf id=1500039 1=$created 2=$url->url_create("profile", $blogentries[blogentry_loop].blogentry_author->user_info.user_username) 3=$blogentries[blogentry_loop].blogentry_author->user_displayname}
            </div>
            <div style='margin-top: 5px;'>
              {lang_sprintf id=1500041 1=$blogentries[blogentry_loop].blogentry_views},
              {lang_sprintf id=1500042 1=$blogentries[blogentry_loop].total_comments}
            </div>
            <div style='margin-top: 8px; font-size: 7pt;'>
              {$blogentries[blogentry_loop].blogentry_body|truncate:140:"...":true}
            </div>
          </td>
        </tr>
      </table>
    </div>
    
    {cycle name="blogret" values=",<div style='clear: both; height: 10px;'></div>"}
  {/section}
  
  <div style='clear: both;'></div>
  
</div>


{include file='footer.tpl'}