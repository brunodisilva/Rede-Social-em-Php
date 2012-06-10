{*  $Id: user_fbconnect_editprofile_photo.tpl 1 2009-07-04 09:36:11Z SocialEngineAddOns $ *}
{* SHOW FACEBOOK CONNECT ALERT MESSAGE *}
{if !empty($fbconnect_alert_editphoto_msg)}
  <div class='center'>
    <table cellpadding='0' cellspacing='0'>
      <tr>
        <td class='result'>
          <div class='error'>
           	<div class="fleft"><img src='./images/icon/facebook-alert.gif' class='icon' border='0' /></div>
            <div class="fleft fbconnect-error-message" style="width:822px;"><span>{lang_print id=650001026}</span> &nbsp; {lang_print id=$fbconnect_alert_editphoto_msg}</div>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <br />
{/if}