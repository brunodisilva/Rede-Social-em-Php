{include file='header.tpl'}

{literal}
<style>
.clearfix:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
} 

.clearfix {
	display: inline-block;
}

/* Hide from IE Mac \*/
.clearfix {
	display: block;
}  /* End hide from IE Mac */ /* --- a /begin --- */

.uptopusers .entry {
	padding: 10px 15px 0 15px;
	border-bottom: 1px solid #F6F6F6;
}

.uptopusers .entry h2 {
	font-size: 14px;
  font-weight:bold;
    margin: 0px;
    padding: 0px;
	margin-bottom: 5px;
}

.uptopusers .entry h2 span {
	float: left;
}
  
.uptopusers .entry .entry_body {
	margin-bottom: 10px;
}

.uptopusers .entry .image-wrap {
  width: 100px;
	float: left;
	margin: 4px 15px 15px 0;
}

.upcontent1 a {
	color: #4b4b4b;
	text-decoration: underline;
}

.upcontent1 a:hover {
	text-decoration: none;
}

.uptopusers .entry .upcontent1 {
	width: 200px;
	float: left;
}

.uptopusers .text {
	padding-bottom: 10px;
}

.uptopusers .entry .options {
	width: 255px;
	float: left;
    line-height: 17px;
}

.uptopusers .options ul {
	padding: 0 0 5px 25px;
	margin: 10px 0 0 0;
	list-style: none;
	font-size: 14px;
}

.uptopusers ul {
	margin-left: 15px;
}

.uptopusers p,.uptopusers ol,.uptopusers ul {
	padding-bottom: 15px;
	font-size: 12px;
}

.uptopusers .options ul li {
	padding: 1px 0 1px 12px;
}
  
.uptopusers ul li,.uptopusers ol li {
	padding: 1px 0 1px 0;
}

</style>
{/literal}


<!-- TOP USERS -->

<div style="font-weight: bold; font-size: 20px; width: 200px; padding-top: 10px"> {lang_print id=100016806} </div>

<table cellpadding='0' cellspacing='0' width='100%' style="margin-top: 20px">
<tr>
<td style='padding-right: 10px; vertical-align: top;'>

  <div style="width: 640px; border: 1px solid #DDD" class="uptopusers">

  {* LOOP USERS *}
  {section name=item_loop loop=$items}

    <div class="entry clearfix">
      <div class="image-wrap">
        <a title="{$items[item_loop].user_displayname}" href="{$url->url_create('profile',$items[item_loop].user_username)}">
          <img border=0 class='photo' width='{$misc->photo_size($items[item_loop].user_photo,'100','100','w')}' alt="{$items[item_loop].user_displayname}" src="{$items[item_loop].user_photo}"/></a>
      </div>
      <div class="upcontent1" {if $smarty.section.item_loop.index == 0}style="width: 150px"{/if}>
        <h2><a title="{$items[item_loop].user_displayname}" href="{$url->url_create('profile',$items[item_loop].user_username)}">{$items[item_loop].user_displayname}</a></h2>
        <div class="text clearfix">
          {lang_print id=100016857} {$items[item_loop].userpoints_totalearned}<br/>
          {assign var=user_points value=$items[item_loop].userpoints_totalearned}
          {assign var=user_points_totalearned value=$items[item_loop].userpoints_totalearned}
          {lang_print id=100016858} {include file='user_points_staticrank.tpl'}<br/>
          {lang_print id=100016859} {$items[item_loop].profileview_views}
      </div>
      </div>
      {if $smarty.section.item_loop.index == 0}
      <div style="padding-top: 18px; float: left; width: 50px">
      <img src="./images/MemberQuarter-large.gif">
      </div>
      {/if}
      <div class="options">
        <ul>
          <!--<li><a href="">Send A Message to {$items[item_loop].user_displayname}</a></li>-->
          <li><a href="{$url->url_create('profile',$items[item_loop].user_username)}">{lang_print id=100016860} {$items[item_loop].user_displayname}{lang_print id=100016861}</a></li>
          <li><a href="{$url->url_create('blog',$items[item_loop].user_username)}">{lang_print id=100016862} {$items[item_loop].user_displayname}{lang_print id=100016863}</a></li>
        </ul>
      </div>
    </div>

  {/section}
  
  </div>

</td>


<td valign='top'>

<div style='padding: 5px; background: #F9F9F9; border: 1px solid #DDDDDD;'>

<div style="text-align:center; font-weight: bold"> {lang_print id=100016808} </div>
<br>
{lang_print id=100016809}
<ol style="list-style: square; padding: 0px;margin-left: 20px">
<li> <a href="user_album_add.php"> {lang_print id=100016810} </a>
<li> {lang_print id=100016811}
<li> <a href="invite.php"> {lang_print id=100016812} </a>
<li> <a href="user_group_add.php">{lang_print id=100016813}</a> {lang_print id=100016814}
<li> {lang_print id=100016814}

</ol>

<a href="user_points_faq.php"> {lang_print id=100016816} </a>

<br>
</div>

</td>

</tr>
</table>

{include file='footer.tpl'}