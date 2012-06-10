{include file='header.tpl'}

<link rel="stylesheet" href="./templates/styles_userpoints.css" title="stylesheet" type="text/css">  

{* JAVASCRIPT FOR ADDING COMMENT *}
{literal}
<script type='text/javascript'>
<!--
var comment_changed = 0;
var last_comment = {/literal}{$comments|@count}{literal};
var next_comment = last_comment+1;
var total_comments = {/literal}{$total_comments}{literal};

function removeText(commentBody) {
  if(comment_changed == 0) {
    commentBody.value='';
    commentBody.style.color='#000000';
    comment_changed = 1;
  }
}

function addText(commentBody) {
  if(commentBody.value == '') {
    commentBody.value = '{/literal}{lang_print id=100016652}{literal}';
    commentBody.style.color = '#888888';
    comment_changed = 0;
  }
}

function checkText() {
  if(comment_changed == 0) { 
    var commentBody = document.getElementById('comment_body');
    commentBody.value=''; 
  }
  var commentSubmit = document.getElementById('comment_submit');
  commentSubmit.value = '{/literal}{lang_print id=100016653}{literal}';
  commentSubmit.disabled = true;
  
}

function addComment(is_error, comment_body, comment_date) {
  if(is_error == 1) {
    var commentError = document.getElementById('comment_error');
    commentError.style.display = 'block';
    if(comment_body == '') {
      commentError.innerHTML = '{/literal}{lang_print id=100016654}{literal}';
    } else {
      commentError.innerHTML = '{/literal}{lang_print id=100016655}{literal}';
    }
    var commentSubmit = document.getElementById('comment_submit');
    commentSubmit.value = '{/literal}{lang_print id=100016656}{literal}';
    commentSubmit.disabled = false;
  } else {
    var commentError = document.getElementById('comment_error');
    commentError.style.display = 'none';
    commentError.innerHTML = '';

    var commentBody = document.getElementById('comment_body');
    commentBody.value = '';
    addText(commentBody);

    var commentSubmit = document.getElementById('comment_submit');
    commentSubmit.value = '{/literal}{lang_print id=100016656}{literal}';
    commentSubmit.disabled = false;

    if(document.getElementById('comment_secure')) {
      var commentSecure = document.getElementById('comment_secure');
      commentSecure.value=''
      var secureImage = document.getElementById('secure_image');
      secureImage.src = secureImage.src + '?' + (new Date()).getTime();
    }

    total_comments++;
    var totalComments = document.getElementById('total_comments');
    totalComments.innerHTML = total_comments;

    var newComment = document.createElement('div');
    var divIdName = 'comment_'+next_comment;
    newComment.setAttribute('id',divIdName);
    var newTable = "<table cellpadding='0' cellspacing='0' width='100%'><tr><td class='userpoints_item1' width='80'>";
    {/literal}
      {if $user->user_info.user_id != 0}
        newTable += "<a href='{$url->url_create('profile',$user->user_info.user_username)}'><img src='{$user->user_photo('./images/nophoto.gif')}' class='photo' border='0' width='{$misc->photo_size($user->user_photo('./images/nophoto.gif'),'75','75','w')}'></a></td><td class='userpoints_item2'><table cellpadding='0' cellspacing='0' width='100%'><tr><td class='userpoints_comment_author'><b><a href='{$url->url_create('profile',$user->user_info.user_username)}'>{$user->user_displayname}</a></b> - {$datetime->cdate("`$setting.setting_timeformat` `$user_points_shop_item30` `$setting.setting_dateformat`", $datetime->timezone($smarty.now, $global_timezone))}</td><td class='userpoints_comment_author' align='right' nowrap='nowrap'>&nbsp;[ <a href='user_messages_new.php?to={$user->user_info.user_username}'>{lang_print id=100016658}</a> ]</td>";
      {else}
        newTable += "<img src='./images/nophoto.gif' class='photo' border='0' width='75'></td><td class='userpoints_item2'><table cellpadding='0' cellspacing='0' width='100%'><tr><td class='userpoints_comment_author'><b>{lang_print id=100016650}</b> - {$datetime->cdate("`$setting.setting_timeformat` `$user_points_shop_item30` `$setting.setting_dateformat`", $datetime->timezone($smarty.now, $global_timezone))}</td><td class='userpoints_comment_author' align='right' nowrap='nowrap'>&nbsp;</td>";
      {/if}
      newTable += "</tr><tr><td colspan='2' class='userpoints_comment_body'>"+comment_body+"</td></tr></table></td></tr></table>";
    {literal}
    newComment.innerHTML = newTable;
    var userpointsComments = document.getElementById('userpoints_comments');
    var prevComment = document.getElementById('comment_'+last_comment);
    userpointsComments.insertBefore(newComment, prevComment);
    next_comment++;
    last_comment++;
  }
}

//-->
</script>
{/literal}

<br>
<br>


{if !empty($error_message) }
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td class='error'>
    <img src='./images/error.gif' border='0' class='icon'> {lang_print id=$error_message}
  </td>
  </tr>
  </table>
  <br><br>
{/if}


{* SHOW ITEM *}


  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr>
  <td valign='top' style='padding-right: 10px; text-align: center;' width='1'><img src='{$upspender->public_photo('./images/nophoto.gif')}' border='0' class='photo' Xwidth='{$misc->photo_size($upspender->upspender_info.userpointspender_photo,'100','100','w')}'></td>
  <td valign='top'>
	<div style='padding: 5px; background: #EEEEEE; font-weight: bold'>
	  {$upspender->upspender_info.userpointspender_title}
	</div>
	<div style='padding: 5px; vertical-align: top;'>
  
	{if $upspender->upspender_info.userpointspender_cost > 0}
	<div style="float: right">
	  
	<form action="user_points_shop_item.php" method="post">  
	<input id="subscribe_now" type="submit" id="submit_button" name="submit_button" value="{lang_print id=100016660}" />
	<input type='hidden' name='shopitem_id' value='{$upspender->upspender_info.userpointspender_id}'>
	<input type='hidden' name='task' value='dobuy'>
	</form>
  
	</div>
  {/if}
  
	  <div style='color: #888888;'>
		{assign var='upspender_date' value=$upspender->upspender_info.userpointspender_date}
		{lang_print id=100016647}&nbsp;{$datetime->cdate("`$setting.setting_dateformat`", $datetime->timezone($upspender_date, $global_timezone))}, 
		{$upspender->upspender_info.userpointspender_comments} {lang_print id=100016646},
		{$upspender->upspender_info.userpointspender_views} {lang_print id=100016645}
	  </div>
	<div style='padding-top: 10px;'>
	  {$upspender->upspender_info.userpointspender_body}
	  <br><br>
	  {lang_print id=100016644}&nbsp;<strong>{$upspender->upspender_info.userpointspender_cost}</strong>&nbsp;{lang_print id=100016659}
	
	</div>
	</div>
  </td>
  </tr>
  </table>
  
  
  
  <br>

<div>
  <a href='./user_points_shop.php'><img src='./images/icons/back16.gif' border='0' class='icon'>{lang_print id=100016648}</a>
</div>
	
	<br>
  
  
  
  {* BEGIN COMMENTS *}
  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr>  
  <td class='header'>
	{lang_print id=100016649} (<span id='total_comments'>{$total_comments}</span>)
  </td>
  </tr>
  {if $allowed_to_comment != 0}
	<tr id='userpoints_postcomment'>
	<td class='userpoints_postcomment'>
	  <form action='user_points_shop_item.php' method='post' target='AddCommentWindow' onSubmit='checkText()'>
	  <textarea name='comment_body' id='comment_body' rows='2' cols='65' onfocus='removeText(this)' onblur='addText(this)' style='color: #888888; width: 100%;'>{lang_print id=100016652}</textarea>
  
	  <table cellpadding='0' cellspacing='0' width='100%'>
	  <tr>
	  {if $setting.setting_comment_code == 1}
		<td width='75' valign='top'><img src='./images/secure.php' id='secure_image' border='0' height='20' width='67' class='signup_code'></td>
		<td width='68' style='padding-top: 4px;'><input type='text' name='comment_secure' id='comment_secure' class='text' size='6' maxlength='10'></td>
		<td width='10'><img src='./images/icons/tip.gif' border='0' class='icon' onMouseover="tip('{lang_print id=100016657}')"; onMouseout="hidetip()"></td>
	  {/if}
	  <td align='right' style='padding-top: 5px;'>
	  <input type='submit' id='comment_submit' class='button' value='{lang_print id=100016656}'>
	  <input type='hidden' name='shopitem_id' value='{$upspender->upspender_info.userpointspender_id}'>
	  <input type='hidden' name='task' value='dopost'>
	  </form>
	  </td>
	  </tr>
	  </table>
	  <div id='comment_error' style='color: #FF0000; display: none;'></div>
	  <iframe name='AddCommentWindow' style='display: none' src=''></iframe>
	</td>
	</tr>
  {/if}
  <tr>
  <td class='userpoints' id='userpoints_comments'>
  
	{* LOOP THROUGH COMMENTS *}
	{section name=comment_loop loop=$comments}
	  <div id='comment_{math equation='t-c' t=$comments|@count c=$smarty.section.comment_loop.index}'>
	  <table cellpadding='0' cellspacing='0' width='100%'>
	  <tr>
	  <td class='userpoints_item1' width='80'>
		{if $comments[comment_loop].comment_author->user_info.user_id != 0}
		  <a href='{$url->url_create('profile',$comments[comment_loop].comment_author->user_info.user_username)}'><img src='{$comments[comment_loop].comment_author->user_photo('./images/nophoto.gif')}' class='photo' border='0' width='{$misc->photo_size($comments[comment_loop].comment_author->user_photo('./images/nophoto.gif'),'75','75','w')}'></a>
		{else}
		  <img src='./images/nophoto.gif' class='photo' border='0' width='75'>
		{/if}
	  </td>
	  <td class='userpoints_item2'>
		<table cellpadding='0' cellspacing='0' width='100%'>
		<tr>
		<td class='userpoints_comment_author'><b>{if $comments[comment_loop].comment_author->user_info.user_id != 0}<a href='{$url->url_create('profile',$comments[comment_loop].comment_author->user_info.user_username)}'>{$comments[comment_loop].comment_author->user_displayname}</a>{else}{lang_print id=100016650}{/if}</b> - {$datetime->cdate("`$setting.setting_timeformat` `$user_points_shop_item30` `$setting.setting_dateformat`", $datetime->timezone($comments[comment_loop].comment_date, $global_timezone))}</td>
		<td class='userpoints_comment_author' align='right' nowrap='nowrap'>&nbsp;[ <a href='user_messages_new.php?to={$comments[comment_loop].comment_author->user_info.user_username}'>{lang_print id=100016658}</a> ]</td>
		</tr>
		<tr>
		<td colspan='2' class='userpoints_comment_body'>{$comments[comment_loop].comment_body}</td>
		</tr>
		</table>
	  </td>
	  </tr>
	  </table>
	  </div>
	{/section}
  
  </td>
  </tr>
  </table>
  {* END COMMENTS *}



{include file='footer.tpl'}