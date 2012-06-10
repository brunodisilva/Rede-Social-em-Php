{if $total_friends neq 0}
<div>
	<link rel="stylesheet" href="templates/mootabs1.2.css" type="text/css" media="screen" charset="utf-8">

	{literal}
	<script type="text/javascript" src="include/js/carousel/loader.js" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		window.addEvent('domready', init);
		function init() {
			myTabs1 = new mootabs('myTabs', {height: '90px', width: '610px', margin:'0 auto', useAjax: true, ajaxUrl: 'carousel_friends.php', ajaxLoadingText: '<div class="slideloading"><img src="images/slideloading.gif"> Loading...</div>', user_id: '{/literal}{$owner->user_info.user_id}{literal}'});
	}
	</script>
	{/literal}
<div class='profile_headline' style="margin-top:10px"><b>{lang_print id=653} ({$total_friends})</b></div>
<div class="slidebuttons">
	{if $total_friends > 7}
	<div style="margin-top:28px; float:left;"><a href="{$selflink}#" onclick="myTabs1.previous()"><img src="images/slide_left3.png" alt="Previous" title="Previous" style="float:left; border:0;"></a></div>
	<div style="margin-top:28px; float:right;"><a href="{$selflink}#" onclick="myTabs1.next()"><img src="images/slide_right3.png" alt="Next" title="Next" style="float:left; border:0"></a></div>
	{/if}
	
	<div id="myTabs" style="height: 89px; width: 600px; margin:0 auto;">	
		<ul class="mootabs_title" style="display:none">
			<li title="0" class="active">{$pages_count}</li>

			{section name=foo start=1 loop=$pages_count step=1}
			 <li title="{$smarty.section.foo.index}" class=""></li>
			{/section }
		</ul>


<div id="0" class="mootabs_panel active"></div>
		{section name=foo start=1 loop=$pages_count step=1}
			 <div id="{$smarty.section.foo.index}" class="mootabs_panel"></div>
		{/section }
	</div></div>
</div>
{/if}