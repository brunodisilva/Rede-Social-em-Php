var mootabs = new Class({
	Implements: Options,
	options: {
		width:				'400px',
		height:				'75px',
		changeTransition:	Fx.Transitions.Bounce.easeOut,
		duration:			1000,
		mouseOverClass:		'active',
		activateOnLoad:		'first',
		useAjax: 			false,
		ajaxUrl: 			'',
		ajaxOptions: 		{method:'get'},
		ajaxLoadingText: 	'<img src="images/slideloading.gif">Loading...',
		user_id:			''
	},
	initialize: function(element, options) {
		this.setOptions(options);
		
		this.el = $(element);
		this.elid = element;
		
		this.el.setStyles({
			height: this.options.height,
			width: this.options.width
		});
		
		this.titles = $$('#' + this.elid + ' ul.mootabs_title li');
		this.panelWidth = this.el.getSize().x - (this.titles[0].getSize().x + 10);
		this.panels = $$('#' + this.elid + ' .mootabs_panel');

		
		this.panels.setStyle('width', this.panelWidth);
		
		this.titles.each(function(item) {
			item.addEvent('click', function(){
					item.removeClass(this.options.mouseOverClass);
					this.activate(item);
				}.bind(this)
			);
			
		}.bind(this));
		
		
		if(this.options.activateOnLoad != 'none')
		{
			if(this.options.activateOnLoad == 'first')
			{
				this.activate(this.titles[0], true);
			}
			else
			{
				this.activate(this.options.activateOnLoad, true);
			}
		}
	},
	
	activate: function(tab, skipAnim){
		if(! $defined(skipAnim))
		{
			skipAnim = false;
		}
		if($type(tab) == 'string') 
		{
			myTab = $$('#' + this.elid + ' ul li').filter('[title='+tab+']');
			tab = myTab;
		}
		
		if($type(tab) == 'element')
		{
			var newTab = tab.get('title');
			this.panels.removeClass('active');
			
			this.activePanel = this.panels.filter('[id='+newTab+']');
			this.activePanel.addClass('active');
			
			if(this.options.changeTransition != 'none' && skipAnim==false)
			{
				this.panels.filter('[id='+newTab+']').setStyle('width', 0);
				var changeEffect = new Fx.Elements(this.panels.filter('[id='+newTab+']'), {duration: this.options.duration, transition: this.options.changeTransition});
				changeEffect.start({
					'0': {
						'width': [0, 600]
						//'width': [0, this.panelWidth]
					}
				});
			}
			
			this.titles.removeClass('active');
			
			tab.addClass('active');
			
			this.activeTitle = tab;
			
			if(this.options.useAjax)
			{
				this._getContent();
			}
		}
	},
	
	_getContent: function(){
		this.activePanel.set('html', this.options.ajaxLoadingText);
		var panel = this.activePanel;
		var requestURL = this.options.ajaxUrl + '?tab=' + this.activeTitle.get('title') + '&id=' + this.options.user_id;
		var tabRequest = new Request.HTML( { url: requestURL, 
				onSuccess: function(html) {
					panel.set('html','');
					panel.adopt(html);
				},
				onFailure: function() {
					panel.set('text', 'The request failed.');
				}
		});
		tabRequest.send();
	},
	
	addTab: function(title, label, content){
		//the new title
		var newTitle = new Element('li', {
			'title': title
		});
		newTitle.appendText(label);
		this.titles.include(newTitle);
		$$('#' + this.elid + ' ul').adopt(newTitle);
		newTitle.addEvent('click', function() {
			this.activate(newTitle);
		}.bind(this));
		
		newTitle.addEvent('mouseover', function() {
			if(newTitle != this.activeTitle)
			{
				newTitle.addClass(this.options.mouseOverClass);
			}
		}.bind(this));
		newTitle.addEvent('mouseout', function() {
			if(newTitle != this.activeTitle)
			{
				newTitle.removeClass(this.options.mouseOverClass);
			}
		}.bind(this));
		//the new panel
		var newPanel = new Element('div', {
			'style': {'width': this.options.panelWidth},
			'id': title,
			'class': 'mootabs_panel'
		});
		if(!this.options.useAjax)
		{
			newPanel.set('html', content);
		}
		this.panels.include(newPanel);
		this.el.adopt(newPanel);
	},
	
	removeTab: function(title){
		if(this.activeTitle.title == title)
		{
			this.activate(this.titles[0]);
		}
		$$('#' + this.elid + ' ul li').filter('[title='+title+']').remove();
		
		$$('#' + this.elid + ' .mootabs_panel').filter('[id='+title+']').remove();
	},
	
	next: function(){
		var nextTab = this.activeTitle.getNext();
		if(!nextTab) {
			nextTab = this.titles[0];
		}
		this.activate(nextTab);
	},
	
	previous: function(){
		var previousTab = this.activeTitle.getPrevious();
		if(!previousTab) {
			previousTab = this.titles[this.titles.length - 1];
		}
		this.activate(previousTab);
	}
});