{*  $Id: fbconnect_header_logout.tpl 1 2009-07-04 09:36:11Z SocialEngineAddOns $ * }

{if !empty($fbuid)}
	<a href="javascript:{literal}FB.Connect.logout(function() { window.location='{/literal}{$url->url_base}{literal}user_logout.php'; }){/literal}" class='top_menu_item'>{lang_print id=26}</a>
{else}
	<a href='user_logout.php' class='top_menu_item'>{lang_print id=26}</a>
{/if}