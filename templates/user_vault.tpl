{include file='header.tpl'}

<script type="text/javascript" src="./include/js/semods.js"></script>
<script type="text/javascript" charset="iso-8859-1" src="./include/js/semods_autocomplete.js"></script>

<link rel="stylesheet" href="./templates/styles_userpoints.css" title="stylesheet" type="text/css">  

{literal}
<script>
var recipient_id = 0;
var up_ajax_endpoint = "user_vault.php";

function up_sendpoints_to_friend() {
  SEMods.B.hide("up_sendpoints_success");
  SEMods.B.hide("up_sendpoints_fail");
  SEMods.B.show("up_sendpoints_main");
  SEMods.B.toggle("up_sendpoints");
}

function up_select_friend(obj) {
  recipient_id = obj.i;
}

function up_sendpoints() {
  SEMods.B.show("up_sendpoints_progress");
  SEMods.B.hide("up_sendpoints_main");
  SEMods.B.hide("up_sendpoints_success");
  SEMods.B.hide("up_sendpoints_fail");

  var ajax = new SEMods.Ajax( up_sendpoints_onajaxsuccess, up_sendpoints_onajaxfail );
  var params = "task=sendpoints&points_recipient_id="+recipient_id+"&points_amount="+SEMods.B.ge("points_amount").value;
  ajax.post( up_ajax_endpoint, params );
}

function up_sendpoints_onajaxsuccess(a, responseText) {
  var r = [];
  try {
    r = eval('('+responseText+')');
    
  } catch(e) {
    r.status = 1;
    r.msg = 'Internal Error';
  };

  SEMods.B.hide("up_sendpoints_progress");
  if(r.status == 0) {
    SEMods.B.ge("up_sendpoints_success_inner").innerHTML = r.msg;
    SEMods.B.ge("userpoints_balance").innerHTML = r.balance;
    SEMods.B.show("up_sendpoints_success");
  } else {
    SEMods.B.ge("up_sendpoints_fail_inner").innerHTML = r.msg;
    SEMods.B.show("up_sendpoints_fail");
    SEMods.B.show("up_sendpoints_main");
  }
    
}

function up_sendpoints_onajaxfail(a, responseText) {
  
  
}
</script>
{/literal}


<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='user_vault.php'>{lang_print id=100016700}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_transactions.php'>{lang_print id=100016701}</a></td>
{if $semods_settings.setting_userpoints_enable_offers}
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_offers.php'>{lang_print id=100016702}</a></td>
{/if}
{if $semods_settings.setting_userpoints_enable_shop}
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_shop.php'>{lang_print id=100016703}</a></td>
{/if}
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_points_faq.php'>{lang_print id=100016704}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>

<!--
<SPAN style="DISPLAY: inline-block; FILTER: progid:DXImageTransform .Microsoft.AlphaImageLoader (src='./images/icons/userpoints_myvault48.png', sizingMethod='scale'); VISIBILITY: hidden; WIDTH: 149px; HEIGHT: 95px"></SPAN>
-->

<img style="margin-left:5px;margin-right:10px;" src='./images/icons/userpoints_myvault48.png' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=100016705}</div>
<div>{lang_print id=100016706}</div>

<br>

{* SHOW SUCCESS OR ERROR MESSAGE *}
{if $result != 0}
  <div class='success'><img src='./images/success.gif' border='0' class='icon'> {$success_message}</div><br>
{elseif $is_error != 0}
  <div class='error'><img src='./images/error.gif' border='0' class='icon'> {$error_message}</div><br>
{/if}


<br>

<div style="padding-left: 10px;">

  <div style="width: 300px; float: left; padding: 10px; padding-bottom: 40px; background-color: #f6f6f6;">
    <div style="padding-bottom: 12px">
      <strong style="color: #777"> {lang_print id=100016720} </strong> <br>
    </div>
    
    <img style="float: left; margin-right: 8px" src="./images/userpoints_coins32.png">
    <div style="font-size: 24px; color: #777 !important">
      <span id='userpoints_balance' style="font-weight: bold">{$user_points}</span> {lang_print id=100016708}
    </div>
    <br>

{if (($user->level_info.level_userpoints_allow_transfer != 0) && ($user->user_info.user_userpoints_allowed != 0))}

    <a href="#" onclick="up_sendpoints_to_friend();return false" alt="{lang_print id=100016709}"> {lang_print id=100016709} </a>
    
    <div id="up_sendpoints" style="display:none;padding-top: 3px;">
    
    <div id="up_sendpoints_success" style="display:none;background-color: #F3FFF3; padding: 7px; font-weight: bold">
      <img src='./images/success.gif' class='icon' border='0'>
        <span id="up_sendpoints_success_inner"></span>
    </div>
    
    <div id="up_sendpoints_fail" style="display:none; background-color: #FFEBE8; padding: 7px; font-weight: bold">
      <img src='./images/error.gif' class='icon' border='0'>
        <span id="up_sendpoints_fail_inner"></span>
    </div>
    
    <div id="up_sendpoints_progress" style="display:none;padding: 7px">
        <table cellpadding='0' cellspacing='0' style="padding-left: 5px">
        <tr><td><img src="./images/semods_ajaxprogress1.gif"></td><td style="padding-left:5px">{lang_print id=100016719}</td></tr>
        </table>
    </div>
    
    <div id="up_sendpoints_main" style="display:block">
      
       
        <table cellpadding='0' cellspacing='0' style="padding-left: 5px">
        <tr>
          <td>
            <span style="font-weight: bold"> {lang_print id=100016710} </span> 
          </td>
          <td class='browse_field'>
            <input style="width: 200px" Xsize='40' type="text" id="recipient_id" class="text" value="" size="20" autocomplete=off onfocus="var source=new SEMods.Controls.Autocompleter.friend_source('');source.text_placeholder='{lang_print id=100016712}';source.text_nomatch='{lang_print id=100016713}';source.text_noinput='{lang_print id=100016714}'; var ac=new SEMods.Controls.Autocompleter(this, source); ac.onselect = up_select_friend; "/>
            <input type="hidden" name="points_recipient_id" id="points_recipient_id">
          </td>
        </tr>
        
        <tr>
          <td>
            <span style="font-weight: bold"> {lang_print id=100016711} </span>
          </td>
          <td class='browse_field'>
            
            <input type="text" class="text" name="points_amount">
        </td>
        </tr>
    
        <tr>
          <td>
          </td>
          <td class='browse_field'>
            <input type="button" class="button" value="{lang_print id=100016715}" onclick="up_sendpoints()">
        </td>
        </tr>
    
        </table>
        <input type="hidden" name="task" value="sendpoints">
        
        </div>
      
    </div>
      
{/if}
      
  </div>

  <div style="width: 300px; float: left; padding: 10px; padding-left: 40px">
    <div style="padding-bottom: 12px">
      <strong style="color: #777"> {lang_print id=100016721} </strong> <br>
    </div>
    
    <img style="float: left; margin-right: 8px" src="./images/userpoints_coins32.png">
    <div style="font-size: 24px; color: #777">
      <strong> {$user_points_totalearned} </strong> {lang_print id=100016708}
    </div>
  </div>
  
<div style="clear:both"> </div>

</div>


{if $userpoints_enable_topusers}

<div style="padding-left: 10px; padding-top: 40px">

  <div style="width: 300px; float: left">
    <div style="padding-bottom: 12px">
      <strong style="color: #777"> {lang_print id=100016722} </strong> <br>
    </div>
    
    <img style="float: left; margin-right: 8px" src="./images/star-32x32.png">
    <div style="font-size: 24px">
      {if $user_points_totalearned != 0}
      <strong style="color: #777"> {$user_rank} </strong> <a href="topusers.php"> {lang_print id=100016723} </a>
      {else}
        {lang_print id=100016724}
      {/if}
    </div>
  </div>

<div style="clear:both"> </div>

</div>


{/if}

  <!--

<div style="padding-left: 10px">
{lang_print id=100016717}  <strong>{$user_rank}</strong> </a>
<br><br>
{lang_print id=100016707} <strong>{$user_points}</strong> {lang_print id=100016708}
<br><br>
{lang_print id=100016716} <strong>{$user_points_totalearned}</strong> {lang_print id=100016708}

<br><br>


</div>
-->

{include file='footer.tpl'}