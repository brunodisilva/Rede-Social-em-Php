	{assign var='search_trends' value=$SEP_TwitterUser->trends()}

	{if $search_trends->trends}
			<div style="line-height:2em;">
			{if $site_search}
				{foreach from=$search_trends->trends item=elm}
					<a href="twitter_timeline_public.php?q={$elm->name|escape:'url'}">{$elm->name}</a> &nbsp;&nbsp;  
				{/foreach}				
			{else}
				{foreach from=$search_trends->trends item=elm}
					<a href="{$elm->url}">{$elm->name}</a> &nbsp;&nbsp;  
				{/foreach}	
			{/if}
			</div>	
	{/if}
