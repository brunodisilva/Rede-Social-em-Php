{* INCLUDE HEADER CODE *}
{include file="header_global.tpl"}

{* DISPLAY RESULT *}
{if $result != ""}

  {* SET ICON FOR RESULT MESSAGE *}
  {if $is_error != ""}
    {assign var="icon" value="error.gif"}
  {else}
    {assign var="icon" value="success.gif"}
  {/if}

<img src='./images/icons/wink_big.gif' border='0' class='icon_big'>
{if $is_error != ""}
<div class='page_header' align="left">{lang_print id=14000091}</div>
<div align="left">
{lang_print id=14000092} <b><a href='{$url->url_create('profile', $owner->user_info.user_username)}'>{$owner->user_info.user_username}</a> {lang_print id=14000093}</b>
</div>
{else}
<div class='page_header' align="left">{lang_print id=14000108}</div>
<div align="left">
{lang_print id=14000094} <b><a href='{$url->url_create('profile', $owner->user_info.user_username)}'>{$owner->user_info.user_username}</a> {lang_print id=14000093}</b>
</div>
{/if}
<br><br>
  
  <table cellpadding='0' cellspacing='0' align="center">
  <tr><td class='result'>
    <img src='./images/{$icon}' class='icon'> 
    {if $result == 1} {lang_print id =14000096}{/if}
	{if $result == 2} {lang_print id =14000097}{/if}
  </td></tr>
  </table>
  {* JAVASCRIPT FOR CLOSING BOX *}
  {literal}
  <script type="text/javascript">
  <!-- 
  setTimeout("window.parent.TB_remove();", "7000");

  //-->
  </script>
  {/literal}

{* SHOW SEND WINK FORM *}
{elseif $subpage == "add"}

  <div style='text-align: center; padding-left: 10px; padding-top: 10px;'>
    <b><font size='3'>{lang_print id=14000055} {$owner->user_info.user_username}?</font></b>
    <br><br>
   <form action='user_winks_wink.php' method='POST' name='addform'>
      <table cellpadding='0' cellspacing='0'>

        <tr>
        <td class='photo' valign='top' width='20' style='padding-right: 10px;'><img class='photo' src='{$owner->user_photo("./images/nophoto.gif")}' class='photo' width='{$misc->photo_size($owner->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'></td>
         <td align='left'><font color="#000000">{lang_print id=14000056} {$owner->user_info.user_username}. {lang_print id=14000057}</font><td>
		</tr>
      </table>
      <br>

  <table align='center' cellpadding='0' cellspacing='0'>
  <tr><td colspan='2'>&nbsp;</td></tr>
  <tr>
  <td colspan='2'>
     <table cellpadding='0' cellspacing='0'>
     <tr>
     <td>
     <input type='submit' class='button' value='{lang_print id=14000058}'>&nbsp;
     <input type='hidden' name='task' value='wink'>
     <input type='hidden' name='user' value='{$owner->user_info.user_username}'>
     </form>
     </td>
     <td>
      &nbsp; <input type='button' class='button' value='{lang_print id=14000059}' onClick='window.parent.TB_remove();'>
     </td>
     </tr>
     </table>
  </td>
  </tr>
  </table>

  </div>

{/if}



</body>
</html>