{include file='admin_header.tpl'}

<h2>{lang_print id=11120201}</h2>
{lang_print id=11120202}

<br><br>

{literal}
<script language='JavaScript'>
<!--
function showdiv(id1, id2) {
  document.getElementById(id1).style.display='block';
  document.getElementById(id2).style.display='none';
}
//-->
</script>
{/literal}

<div id='button1' style='display: block;'>
  [ <a href="javascript:showdiv('help', 'button1')">{lang_print id=11120209}</a> ]
  <br><br>
</div>

<div id='help' style='display: none;'>
  {lang_print id=11120210}
  <br><br>
</div>

{if $is_error != 0}
<div class='error'><img src='../images/error.gif' border='0' class='icon'> {$error_message}</div>
{/if}

{if $result != ""}
  <div class='success'><img src='../images/success.gif' border='0' class='icon'> {lang_print id=$result}</div>
{/if}

<form action='admin_theme.php' method='POST' name='info'>

  {* JAVASCRIPT FOR ADDING THEME BLOCKS *}
  {literal}
  <script type="text/javascript">
  {/literal}
  <!-- Begin
  var themeblock_id = {$num_blocks}+1;
  {literal}
  function addInput(fieldname) {
    var ni = document.getElementById(fieldname);
    var newdiv = document.createElement('div');
    var divIdName = 'my'+themeblock_id+'Div';
    newdiv.setAttribute('id',divIdName);
    newdiv.innerHTML = "<input type='text' name='themeblock_titles[" + themeblock_id +"]' class='text' size='30' maxlength='50'>&nbsp;<br>";
    ni.appendChild(newdiv);
    themeblock_id++;
    window.document.info.num_themeblocks.value=themeblock_id;
  }
  // End -->
  </script>
  {/literal}

<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11120211}</td></tr>
  <tr><td class='setting1'>{lang_print id=11120212}</td></tr>
  <tr><td class='setting2'><input name='setting_theme_license' type='text' disabled="disabled" value='Nulled' size='30' maxlength="200" readonly="true" />
{lang_print id=11120213}</td>
  </tr>
</table>

<br>  
  
<table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11120214}</td></tr>
  <tr><td class='setting1'>{lang_print id=11120215}</td></tr>
  <tr><td class='setting2'>
  
    <table cellpadding='2' cellspacing='0'>
      <tr>
        <td><input type='radio' name='setting_theme_type' id='setting_theme_type_0' value='0'{if $setting_theme_type == 0} CHECKED{/if}></td>
        <td><label for='setting_theme_type_0'>{lang_print id=11120216}</label></td>
      </tr>
      <tr>
        <td><input type='radio' name='setting_theme_type' id='setting_theme_type_3' value='3'{if $setting_theme_type == 3} CHECKED{/if}></td>
        <td><label for='setting_theme_type_3'>{lang_print id=11120219}</label></td>
      </tr>      
      <tr>
        <td><input type='radio' name='setting_theme_type' id='setting_theme_type_1' value='1'{if $setting_theme_type == 1} CHECKED{/if}></td>
        <td><label for='setting_theme_type_1'>{lang_print id=11120217}</label></td>
      </tr>
      <tr>
        <td><input type='radio' name='setting_theme_type' id='setting_theme_type_2' value='2'{if $setting_theme_type == 2} CHECKED{/if}></td>
        <td><label for='setting_theme_type_2'>{lang_print id=11120218}</label></td>
      </tr>            
    </table>
  
  </td>
  </tr>
  
  <tr><td class='setting1'>{lang_print id=11120220}</td></tr>
  <tr><td class='setting2'>
  
    <table cellpadding='2' cellspacing='0'>
      <tr>
        <td><input type='radio' name='setting_theme_user_overwrite' id='setting_theme_user_overwrite_1' value='1'{if $setting_theme_user_overwrite == 1} CHECKED{/if}></td>
        <td><label for='setting_theme_user_overwrite_1'>{lang_print id=11120221}</label></td>
      </tr>
      <tr>
        <td><input type='radio' name='setting_theme_user_overwrite' id='setting_theme_user_overwrite_0' value='0'{if $setting_theme_user_overwrite == 0} CHECKED{/if}></td>
        <td><label for='setting_theme_user_overwrite_0'>{lang_print id=11120222}</label></td>
      </tr>           
    </table>
  
  </td>
  </tr>  
  
</table>

<br>   
  
  <table cellpadding='0' cellspacing='0' width='600'>
  <tr><td class='header'>{lang_print id=11120205}</td></tr>
  <tr><td class='setting1'>
  {lang_print id=11120206}
  </td></tr><tr><td class='setting2'>
    <table cellpadding='0' cellspacing='0'>
    <tr><td><b>{lang_print id=11120208}</b></td></tr>
    {section name=themeblocks_loop loop=$themeblocks}
      <tr><td><input type='text' class='text' name='themeblock_titles[{$themeblocks[themeblocks_loop].themeblock_id}]' value='{$themeblocks[themeblocks_loop].themeblock_title}' size='30' maxlength='100'> #{$themeblocks[themeblocks_loop].themeblock_id} {$smarty.ldelim}include file='theme:block_{$themeblocks[themeblocks_loop].themeblock_id}'{$smarty.rdelim}</td></tr>
    {/section}
    <tr><td><p id='newtype'></p></td></tr>
    <tr><td><a href="javascript:addInput('newtype')">{lang_print id=11120207}</a></td><input type='hidden' name='num_themeblocks' value='{$num_cats}'></tr>
    </table>
  </td></tr>
  </table>
 
<br>

<input type='submit' class='button' value='{lang_print id=11120204}'>
<input type='hidden' name='task' value='dosave'>
</form>


{include file='admin_footer.tpl'}