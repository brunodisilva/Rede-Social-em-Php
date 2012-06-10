{* INCLUDE HEADER CODE *}
{include file="header_global.tpl"}
{literal}
<style type="text/css">
body { background-image:url(./images/index_bg.gif); background-repeat:repeat-x; }
</style>
{/literal}
{if $smarty.const.SE_DEBUG && $admin->admin_exists}{include file="header_debug.tpl"}{/if}

<div id="smoothbox_container"></div>

{* BEGIN CENTERING TABLE *}
<table cellpadding='0' cellspacing='0' class='body' align='center'>
<tr>
<td>

{* START TOPBAR *}


<div class="top_bar_index">

  {* LOGO AND SEARCH *}
<table cellpadding='0' cellspacing='0' style='width:965px; padding-top:1px; height:29.5px;' align='center'>
<tr>
    <td style="background-image:url(./images/top_left_index.gif); height:85.5px; padding-left:5px;" align='left' width="4px">&nbsp;</td>
    <td width="75"><a href='./'><img style="vertical-align:middle" src='./images/logo_index.gif' border='0'></a></td>
	
	
	

	
	
{*-------------------------------------------*}
	<td style="padding-left:10px;" align="right"; width="auto">
<div style="text-align:right; float:right; color:#98a9ca; margin-top:20px;">

        <form action='login.php' method='post'>
        <input style="vertical-align:middle;" type='checkbox' name='persistent' value='1' id='rememberme'> <label style="color:#98a9ca; margin-right:70px; vertical-align:middle; cursor:pointer;" for='rememberme'>{lang_print id=660}</label>  <a style="color:#98a9ca; margin-right:65px; " href="lostpass.php">{lang_print id=675}</a>
		<br>
<input type='text' class='text_index' name='email' size='23' maxlength='100' value='{$prev_email}'>
<input type='password' class='text_index' name='password' size='23' maxlength='100'>

        <input type='submit' class='button_index' value='{lang_print id=30}'>&nbsp;
        <NOSCRIPT><input type='hidden' name='javascript_disabled' value='1'></NOSCRIPT>
        <input type='hidden' name='task' value='dologin'>
        <input type='hidden' name='ip' value='{$ip}'>
        </form>


</div>

	</td>
{*-------------------------------------------*}

	{*-------------------------------------------*}
    <td style="background-image:url(./images/top_right_index.gif); height:85.5px; padding-left:5px;" align='left' width="13px">&nbsp;</td>
</tr>
</table>
</div>
{* END TOP BAR *}</td>
  </tr>
  </table>


<div style="height:380px; width:960px; margin-right:auto; margin-left:auto;">

	<div style="width:555px; padding-top:10px; float:left;">
			<div style="float:left; width:500px; height:75px; padding-left:10px; font-size:20px; text-align:left; font-weight:bold; color:#203360;">Facecook helps you connect and share with the people in your life.
			</div>
			<div style="float:left; background-image:url(./images/home_pic.gif); width:550px; height:200px;">
			</div>
	</div>
	
	<div style="width:375px; float:right; padding-top:20px; text-align:left;">
<font style="font-size:20px; text-align:left; font-weight:bold; color:#203360;">Signup</font><br><br>
<font color="#203360;" style="font-size:20px; text-align:left; color:#203360;">It's free and anyone can join</font><br>&nbsp;
<br>



{*-------------------------------------------------------*}
{* SHOW STEP ONE *}


  <form action='signup.php' method='POST'>
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td class='form1' width='100'>{lang_print id=37}</td>
  <td class='form2'>
    <input name='signup_email' type='text' class='text' maxlength='70' value='{$signup_email}' style="width:230px;" />
  </td>
  </tr>
  {if $setting.setting_signup_randpass == 0}
    <tr>
    <td class='form1'>{lang_print id=29}:</td>
    <td class='form2'>
      <input name='signup_password' type='password' class='text' maxlength='50' value='{$signup_password}' style="width:230px;" />
    </td>
    </tr>
    <tr>
    <td class='form1'>{lang_print id=266}:</td>
    <td class='form2'>
      <input name='signup_password2' type='password' class='text' maxlength='50' value='{$signup_password2}' style="width:230px;" />
    </td>
    </tr>
  {else}
    <input type='hidden' name='signup_password' value=''>
    <input type='hidden' name='signup_password2' value=''>
  {/if}
  </table>

  <table cellpadding='0' cellspacing='0'>
  {if $setting.setting_username}
    <tr>
    <td class='form1'>{lang_print id=28}:</td>
    <td class='form2'>
      <input name='signup_username' type='text' class='text' maxlength='50' value='{$signup_username}' style="width:230px;" />
      {capture assign=tip}{lang_print id=685}{/capture}
      <img src='./images/icons/tip.gif' border='0' class='Tips1' title='{$tip|replace:"'":"&#039;"}'>
    </td>
    </tr>
  {/if}
  {if $cats|@count > 1}
    <tr>
    <td class='form1'>{lang_print id=709}:</td>
    <td class='form2'>
      <select name='signup_cat'>
      {section name=cat_loop loop=$cats}
	<option value='{$cats[cat_loop].cat_id}'{if $signup_cat == $cats[cat_loop].cat_id} selected='selected'{/if}>{lang_print id=$cats[cat_loop].cat_title}</option>
      {/section}
      </select>
    </td>
    </tr>
  {/if}
  <tr>
  <td class='form1' width='100'></td>
  <td class='form2'>

  </td>
  </tr>
  </table>

  {if $setting.setting_signup_code == 1 || $setting.setting_signup_tos == 1 || $setting.setting_signup_invite != 0}
    <table cellpadding='0' cellspacing='0'>
  {/if}

  {if $setting.setting_signup_invite != 0}
    <tr>
    <td class='form1' width='100'>{lang_print id=689}</td>
    <td class='form2'><input type='text' name='signup_invite' value='{$signup_invite}' class='text' size='10' maxlength='10''></td>
    </tr>
  {/if}

  {if $setting.setting_signup_code == 1}
    <tr>
    <td class='form1' width='100'>{lang_print id=690}</td>
    <td class='form2'>
      <table cellpadding='0' cellspacing='0'>
      <tr>
      <td><input type='text' name='signup_secure' class='text' size='6' maxlength='10'>&nbsp;</td>
      <td>
        <table cellpadding='0' cellspacing='0'>
        <tr>
        <td align='center'><img src='./images/secure.php' id='secure_image' border='0' height='20' width='67' class='signup_code'><br><a href="javascript:void(0);" onClick="javascript:$('secure_image').src = $('secure_image').src + '?' + (new Date()).getTime();">{lang_print id=975}</a></td>
        <td>{capture assign=tip}{lang_print id=691}{/capture}<img src='./images/icons/tip.gif' border='0' class='Tips1' title='{$tip|replace:"'":"&#039;"}'></td>
        </tr>
        </table>
      </td>
      </tr>
      </table>
    </td>
    </tr>
  {/if}

  {if $setting.setting_signup_tos == 1}
    <tr>
    <td class='form1' width='100'>&nbsp;</td>
    <td class='form2'><input type='checkbox' name='signup_agree' id='tos' value='1'{if $signup_agree == 1} CHECKED{/if}><label for='tos'> {lang_print id=692}</label></td>
    </tr>
  {/if}

  <tr></tr>
  <tr>
  <td class='form1'>&nbsp;</td>
  <td class='form2'><input type='submit' class='button_signup' value='{lang_print id=693}'></td>
  </tr>
  </table>
  <input type='hidden' name='task' value='{$next_task}'>
  <input type='hidden' name='step' value='{$step}'>
  </form>
{*-----------------------------------------------------------------------------------------------------*}

	</div>

</div>
  
  
{include file='ifooter.tpl'}