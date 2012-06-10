{include file='header.tpl'}


<img src='./images/icons/employment48.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=11050601}</div>
<div>{lang_print id=11050602}</div>

<br><br>

{* SHOW RESULT MESSAGE *}
{if $result != ""}
<table cellpadding='0' cellspacing='0'><tr><td class='result'>
<div class='success'><img src='./images/success.gif' border='0' class='icon'> {$result}</div>
</td></tr></table>
<br>
{/if}

{* SHOW ERROR MESSAGE *}
{if $is_error != 0}
<table cellpadding='0' cellspacing='0'><tr><td class='result'>
<div class='error'><img src='./images/error.gif' border='0' class='icon'> {$error_message}</div>
</td></tr></table>
<br>
{/if}


<form action='search_employment.php' method='POST' name='profile'>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="browse_header">{lang_print id=11050603}</td>
  </tr>
  <tr>
    <td class="browse_fields">
      {* START SEARCH FIELDS *}
      <table cellpadding='0' cellspacing='0' class='form'>
        <tr>
          <td class='form1'>{lang_print id=11050606}</td>
          <td class='form2'><input type="text" class="text" id="edu{$eid}" name="search[employment_employer]" value="{$search.employment_employer}" size="45" />
          </td>
        </tr> 
        <tr>
          <td class='form1'>{lang_print id=11050607}</td>
          <td class='form2'><input type="text" class="text" name="search[employment_position]" value="{$search.employment_position}" size="45" /></td>
        </tr>
        <tr>
          <td class='form1'>{lang_print id=11050608}</td>
          <td class='form2'><input type="text" class="text" name="search[employment_description]" value="{$search.employment_description}" size="45" /></td>
        </tr>
        <tr>
          <td class='form1'>{lang_print id=11050609}</td>
          <td class='form2'><input type="text" class="text" name="search[employment_location]" value="{$search.employment_location}" size="45" /></td>
        </tr> 
        <tr>
          <td class='form1'>{lang_print id=11050620}</td>
          <td class='form2'><label><input type="checkbox" name="search[employment_is_current]" value="1" {if $search.employment_is_current == '1'}checked="checked"{/if} />{lang_print id=11050611}</label></td>
        </tr>
        <tr>
          <td class='form1'>{lang_print id=11050618}</td>
          <td class='form2'>
            <select name="search[employment_from_month]" size="1">
              <option value="">{lang_print id=11050615}</option>
              {foreach from=$monthoptions key=monthoptionkey item=monthoptionval}
              <option value="{$monthoptionkey}" {if $monthoptionkey == $search.employment_from_month}selected="selected"{/if}>{$monthoptionval}</option>
              {/foreach}
            </select>
            <select name="search[employment_from_year]" size="1">
              <option value="">{lang_print id=11050616}</option>
              {foreach from=$yearoptions key=yearoptionkey item=yearoptionval}
              <option value="{$yearoptionkey}" {if $yearoptionkey == $search.employment_from_year}selected="selected"{/if}>{$yearoptionval}</option>
              {/foreach}
            </select>
          </td>
        </tr>
        <tr>
          <td class='form1'>{lang_print id=11050619}</td>
          <td class='form2'>
            <select name="search[employment_to_month]" size="1">
              <option value="">{lang_print id=11050615}</option>
              {foreach from=$monthoptions key=monthoptionkey item=monthoptionval}
              <option value="{$monthoptionkey}" {if $monthoptionkey == $search.employment_to_month}selected="selected"{/if}>{$monthoptionval}</option>
              {/foreach}
            </select>
            <select name="search[employment_to_year]" size="1">
              <option value="">{lang_print id=11050616}</option>
              {foreach from=$yearoptions key=yearoptionkey item=yearoptionval}
              <option value="{$yearoptionkey}" {if $yearoptionkey == $search.employment_to_year}selected="selected"{/if}>{$yearoptionval}</option>
              {/foreach}
            </select>
          </td>
        </tr>        
        <tr><td colspan="2"><hr noshade size="0" /></td></tr> 
      </table>   
      {* END SEARCH FIELDS *}
      <table cellpadding='0' cellspacing='0' class='form'>
        <tr>
        <td class='form1'>&nbsp;</td>
        <td class='form2'><input type='submit' class='button' value='{lang_print id=11050613}'></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<input type='hidden' name='task' value='search'>
</form>

{if $total_employments == 0}
  <br>
  <table cellpadding='0' cellspacing='0' align='center'>
  <tr><td class='result'><img src='./images/icons/bulb22.gif' border='0' class='icon'> {lang_print id=11050604}</td></tr>
  </table>
{else}

      {* DISPLAY PAGINATION MENU IF APPLICABLE *}
      {if $maxpage > 1}
        <br><br>
        <div class='center'>
        <b>
        {if $p != 1}<a href='search_employment.php?{$search_query}task={$task}&p={math equation='p-1' p=$p}'>&#171; {lang_print id=11050621}</a>{else}<font class='disabled'>&#171; {lang_print id=11050621}</font>{/if}
        {if $p_start == $p_end}
          &nbsp;|&nbsp; {lang_print id=11050622} {$p_start} {lang_print id=11050624} {$total_employments} &nbsp;|&nbsp; 
        {else}
          &nbsp;|&nbsp; {lang_print id=11050623} {$p_start}-{$p_end} {lang_print id=11050624} {$total_employments} &nbsp;|&nbsp; 
        {/if}
        {if $p != $maxpage}<a href='search_employment.php?{$search_query}task={$task}&p={math equation='p+1' p=$p}'>{lang_print id=11050625} &#187;</a>{else}<font class='disabled'>{lang_print id=11050625} &#187;</font>{/if}
        </b>
        </div>
        <br>
      {/if}  

  <table cellpadding='0' cellspacing='0' class='employment_search_results'>
  {foreach from=$employments item=employment key=eid}
    <tr>
      <td class='user_thumb'>
        <a href='{$url->url_create('profile',$employment.user->user_info.user_username)}'>{$employment.user->user_info.user_username|truncate:20:"...":true}<br>
        <img src='{$employment.user->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($employment.user->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0' alt="{$employment.user->user_info.user_username}"></a>
      </td>
      <td class='user_employment'>
        <h3 class="employment_header">{$employment.search_employment_employer}</h3>
        <table cellpadding='0' cellspacing='0' class="employment">
          <tr><td width="130">{lang_print id=11050105}</td><td>{$employment.search_employment_position}</td></tr>
          <tr><td width="130">{lang_print id=11050108}</td><td>{$employment.time_period}</td></tr>
          <tr><td width="130">{lang_print id=11050107}</td><td>{$employment.search_employment_location}</td></tr>
          <tr><td width="130">{lang_print id=11050106}</td><td>{$employment.search_employment_description|nl2br}</td></tr>
        </table>
      </td>
    </tr>
  {/foreach}  
  </table>
  
      {* DISPLAY PAGINATION MENU IF APPLICABLE *}
      {if $maxpage > 1}
        <br><br>
        <div class='center'>
        <b>
        {if $p != 1}<a href='search_employment.php?{$search_query}task={$task}&p={math equation='p-1' p=$p}'>&#171; {lang_print id=11050621}</a>{else}<font class='disabled'>&#171; {lang_print id=11050621}</font>{/if}
        {if $p_start == $p_end}
          &nbsp;|&nbsp; {lang_print id=11050622} {$p_start} {lang_print id=11050624} {$total_employments} &nbsp;|&nbsp; 
        {else}
          &nbsp;|&nbsp; {lang_print id=11050623} {$p_start}-{$p_end} {lang_print id=11050624} {$total_employments} &nbsp;|&nbsp; 
        {/if}
        {if $p != $maxpage}<a href='search_employment.php?{$search_query}task={$task}&p={math equation='p+1' p=$p}'>{lang_print id=11050625} &#187;</a>{else}<font class='disabled'>{lang_print id=11050625} &#187;</font>{/if}
        </b>
        </div>
        <br>
      {/if} 
  
{/if}


{include file='footer.tpl'}
