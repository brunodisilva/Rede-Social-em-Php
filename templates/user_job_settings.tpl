{include file='header.tpl'}

{* $Id: user_job_settings.tpl 7 2009-01-11 06:01:49Z john $ *}

<table cellpadding='0' cellspacing='0' width='100%'>
  <tr>
    <td valign='top'>
      
      <img src='./images/icons/job_job48.gif' border='0' class='icon_big' />
      <div class='page_header'>{lang_print id=6400115}</div>
      <div>{lang_print id=6400116}</div>
      
    </td>
    <td valign='top' align='right'>
      
      <table cellpadding='0' cellspacing='0'>
        <tr>
          <td class='button' nowrap='nowrap'>
            <a href='user_job.php'><img src='./images/icons/back16.gif' border='0' class='button' />{lang_print id=6400102}</a>
          </td>
        </tr>
      </table>
      
    </td>
  </tr>
</table>
<br />


{* SHOW SUCCESS MESSAGE *}
{if $result != 0}
  <table cellpadding='0' cellspacing='0'>
    <tr>
      <td class='success'>
        <img src='./images/success.gif' border='0' class='icon' />
        {lang_print id=191}
      </td>
    </tr>
  </table>
{/if}
<br />


<form action='user_job_settings.php' method='post'>

{* STYLE SETTINGS *}
{if $user->level_info.level_job_style}
  <div><b>{lang_print id=6400117}</b></div>
  <div class='form_desc'>{lang_print id=6400118}</div>
  <textarea name='style_job' rows='17' cols='50' style='width: 100%; font-family: courier, serif;'>{$style_job}</textarea>
  <br />
  <br />
{/if}

{* NOTIFICATION SETTINGS *}
<div><b>{lang_print id=6400119}</b></div>
<br />

{assign var="comment_options" value=$user->level_info.level_blog_comments|unserialize}
{if !("0"|in_array:$comment_options) || $comment_options|@count != 1}
  <table cellpadding='0' cellspacing='0' class='editprofile_options'>
    <tr>
      <td><input type='checkbox' value='1' id='jobcomment' name='usersetting_notify_jobcomment'{if $user->usersetting_info.usersetting_notify_jobcomment} checked{/if}></td>
      <td><label for='jobcomment'>{lang_print id=6400120}</label></td>
    </tr>
  </table>
  <br />
  <br />
{/if}

{lang_block id=173 var=langBlockTemp}<input type='submit' class='button' value='{$langBlockTemp}' />{/lang_block}
<input type='hidden' name='task' value='dosave' />
</form>



{include file='footer.tpl'}