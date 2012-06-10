{include file='admin_header.tpl'}

{literal}
<script>
function addPointsRow() {
    var el = document.getElementById("pointsrow").cloneNode(true);
    el.id = '';
    var moreRow = document.getElementById("addmorerow");
    moreRow.parentNode.insertBefore(el, moreRow)
}
</script>
<style>
A.addmorerow {
  text-decoration: none;
  font-size: 10pt;
}

A.addmorerow:hover {
  text-decoration: underline;
}
</style>
{/literal}
<h2>{lang_print id=100016128}</h2>
{lang_print id=100016129}

<br><br>

{if $result != 0}

  {if empty($error)}
  <div class='success'><img src='../images/success.gif' class='icon' border='0'> {lang_print id=100016130}</div>
  {else}
    <div class='error'><img src='../images/error.gif' class='icon' border='0'> {$error} </div>
  {/if}

{/if}

  <div style="display:none">
    <table>
    <tr id="pointsrow" name="pointsrow">
    <td class='form1'> <input name="point_rank_points[]" value="" type="text" class="text"> </td>
    <td class='form2'> <input name="point_rank_text[]" value="" type="text" class="text"> </td>
    </tr>
    </table>
  </div>


<form action='admin_userpoints_pointranks.php' method='POST'>

  <table cellpadding='0' cellspacing='0' width='600px'>
  <tr><td class='header'>{lang_print id=100016131}</td></tr>
  <tr><td class='setting1'>
  {lang_print id=100016132}
  </td></tr><tr><td class='setting2'>
    <table cellpadding='0' cellspacing='0'>
    <tr><td><input type='radio' name='setting_userpoints_enable_pointrank' id='setting_userpoints_enable_pointrank_1' value='1'{if $setting_userpoints_enable_pointrank == 1} CHECKED{/if}>&nbsp;</td><td><label for='charge_classified_1'>{lang_print id=100016133}</label></td></tr>
    <tr><td><input type='radio' name='setting_userpoints_enable_pointrank' id='setting_userpoints_enable_pointrank_0' value='0'{if $setting_userpoints_enable_pointrank == 0} CHECKED{/if}>&nbsp;</td><td><label for='charge_classified_0'>{lang_print id=100016134}</label></td></tr>
    </table>
  </td></tr>
  
  </table>

<br>

  <table cellpadding='0' cellspacing='0' width='600px'>
  <tr><td class='header'>{lang_print id=100016135}</td></tr>
  <tr><td class='setting1'>
  {lang_print id=100016136}
  </td></tr><tr><td class='setting2'>

    <table cellpadding='0' cellspacing='0'>
    <tr><td> {lang_print id=100016137} </td><td> {lang_print id=100016138} </td></tr>

    {section name=p_loop loop=$point_ranks}
    <tr>
    <td class='form1'> <input name="point_rank_points[]" {if $smarty.section.p_loop.index == 0} readonly style="color: #AAA; background-color: #CCC" {/if} value="{$point_ranks[p_loop].userpointrank_amount}" type="text" class="text"> </td>
    <td class='form2'> <input name="point_rank_text[]" value="{$point_ranks[p_loop].userpointrank_text}" type="text" class="text"> </td>
    </tr>
    {/section}

    <tr id="addmorerow" name="addmorerow">
    <td style="padding-left: 10px"> <a class="addmorerow" href="" onclick="addPointsRow(); return false;"> {lang_print id=100016139} </a> </td>
    <td>  </td>
    </tr>

    </table>


  </td></tr>
  
  </table>

<br>


<input type='submit' class='button' value='{lang_print id=100016140}'>
<input type='hidden' name='point_ranks_count' value='{$point_ranks_count}'>
<input type='hidden' name='task' value='dosave'>
</form>


{include file='admin_footer.tpl'}