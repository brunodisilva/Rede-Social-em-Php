{include file='admin_header.tpl'}

{literal}
<script>
function up_edit_inplace(id) {
  document.getElementById("upshow_"+id).style.display='none';
  document.getElementById("upedit_"+id).style.display='block';
  document.getElementById("upinput_"+id).focus();
}
</script>
{/literal}

<h2>{lang_print id=100016535}</h2>
{lang_print id=100016536}

<br><br>

{if $result != 0}

  {if empty($error)}
  <div class='success'><img src='../images/success.gif' class='icon' border='0'> {lang_print id=100016540}</div>
  {else}
    <div class='error'><img src='../images/error.gif' class='icon' border='0'> {$error} </div>
  {/if}

{/if}

<br>

<form action='admin_userpoints_assign.php' method='POST'>

  {* LOOP THROUGH ACTIONS *}
  {section name=action_loop loop=$actions}
  
  <div style="font-weight: bold; width: 600px; text-align: center; padding-bottom: 2px"> {$action_types[action_loop]}</div>
  <table cellpadding='0' cellspacing='0' class='list' style="width:600px;" Xwidth='100%'>
  <tr>
  <td class='header' Xwidth='200' Xstyle='padding-left: 0px;'>{lang_print id=100016537}</td>
  <td class='header'>{lang_print id=100016538}</td>
  <td class='header'>{lang_print id=100016542}</td>
  <td class='header'>{lang_print id=100016543}</td>
  </tr>
  
  
  {section name=action_gloop loop=$actions[action_loop]}

  {if (is_null($actions[action_loop][action_gloop].actiontype_name) && !is_null($actions[action_loop][action_gloop].action_requiredplugin))}
  {assign var=unavailable value=true}
  {else}
  {assign var=unavailable value=false}
  {/if}
    <tr class='{cycle values="background1,background2"}'>
    <td class='item' Xstyle='padding-left: 0px;' Xwidth="100%">  <div id='upedit_{$smarty.section.action_loop.index}{$smarty.section.action_gloop.index}' style="display:none;width:260px"><input id='upinput_{$smarty.section.action_loop.index}{$smarty.section.action_gloop.index}' {if $unavailable}disabled{/if} type='text' class='text' size=40 name='actionsname[{$actions[action_loop][action_gloop].action_id}{$actions[action_loop][action_gloop].actiontype_name}]' value='{$actions[action_loop][action_gloop].action_name}'></div>  <div id='upshow_{$smarty.section.action_loop.index}{$smarty.section.action_gloop.index}' style="display:block;width:260px" {if !$unavailable}onclick="up_edit_inplace('{$smarty.section.action_loop.index}{$smarty.section.action_gloop.index}')"{/if}> {$actions[action_loop][action_gloop].action_name}&nbsp;{if $unavailable}<br><font color="red"> {lang_print id=100016541} {$actions[action_loop][action_gloop].action_requiredplugin}</font> {/if} </div></td>
    <td class='item' width="20px"><input {if $unavailable}disabled{/if}  type='text' class='text' size=5 name='actions[{$actions[action_loop][action_gloop].action_id}{$actions[action_loop][action_gloop].actiontype_name}]' value='{$actions[action_loop][action_gloop].action_points}'></td>
    <td class='item' width="20px"><input {if $unavailable}disabled{/if}  type='text' class='text' size=5 name='actionsmax[{$actions[action_loop][action_gloop].action_id}{$actions[action_loop][action_gloop].actiontype_name}]' value='{$actions[action_loop][action_gloop].action_pointsmax}'></td>
    <td class='item' width="100px"><input {if $unavailable}disabled{/if}  type='text' class='text' size=5 name='actionsrollover[{$actions[action_loop][action_gloop].action_id}{$actions[action_loop][action_gloop].actiontype_name}]' value='{$actions[action_loop][action_gloop].action_rolloverperiod}'> {lang_print id=100016544}</td>
    </tr>
  {/section}
    
  
  </table>

  <br><br>

  {/section}


<input type='submit' class='button' value='{lang_print id=100016539}'>
<input type='hidden' name='task' value='dosave'>
</form>

<br><br>
  

{include file='admin_footer.tpl'}