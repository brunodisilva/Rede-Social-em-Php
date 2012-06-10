
Element.implement({
	sep_t_show: function() {
		this.setStyle('display','');
	},
	sep_t_hide: function() {
		this.setStyle('display','none');
	},
	sep_t_visible: function() {
		if(this.getStyle('display') == 'none') {
			return false;	
		}
		else {
			return true;
		}	
	},
	sep_t_toggle: function() {
		if(this.getStyle('display') == 'none') {
			this.sep_nfa_show();
		}
		else {
			this.sep_nfa_hide();
		}				
	},
	sep_t_update: function(html) {
		this.innerHTML = html;
	}
});	    
   
   
function SEP_Twitter_friendships_create(screen_name, ajax_spinner, update_div) { 	
	$(ajax_spinner).sep_t_show();
		
	myreq2 = new Request({
		method: 'post',
		url: './user_twitter_ajax.php',
		data: '_ajaxReq=1&task=friendships_create&screen_name='+screen_name,
		onComplete: function() { $(ajax_spinner).sep_t_hide(); },
		onSuccess: function(html){ $(update_div).sep_t_update(html); },
		onFailure: function() { alert('Error! Please try again later!'); }
	}).send();	 			
}

function SEP_Twitter_friendships_destroy(screen_name, ajax_spinner, update_div) { 		
	$(ajax_spinner).sep_t_show();
	
	myreq2 = new Request({
		method: 'post',
		url: './user_twitter_ajax.php',
		data: '_ajaxReq=1&task=friendships_destroy&screen_name='+screen_name,
		onComplete: function() { $(ajax_spinner).sep_t_hide(); },
		onSuccess: function(html){ $(update_div).sep_t_update(html); },
		onFailure: function() { alert('Error! Please try again later!'); }
	}).send();	 			
}


function SEP_Twitter_favorites_create(id, elm) {
	elm.src = './images/icons/twitter_icon_throbber.gif';
	
	myreq2 = new Request({
		method: 'post',
		url: './user_twitter_ajax.php',
		data: '_ajaxReq=1&task=favorites_create&id='+id,
		onSuccess: function(){ elm.src = './images/icons/twitter_icon_star_full.gif'; 
								if(Browser.Engine.trident) { /* work-around for IE */ elm.style.visibility = 'visible';	}
								else { elm.setStyle('visibility', 'visible'); }
								elm.onclick = function(){ SEP_Twitter_favorites_destroy(id, elm) } },
		onFailure: function() { elm.src = './images/icons/twitter_icon_star_empty.gif'; alert('Error! Please try again later!'); }
	}).send();		
}


function SEP_Twitter_favorites_destroy(id, elm) {	
	elm.src = './images/icons/twitter_icon_throbber.gif';
	
	myreq2 = new Request({
		method: 'post',
		url: './user_twitter_ajax.php',
		data: '_ajaxReq=1&task=favorites_destroy&id='+id,
		onSuccess: function(){ elm.src = './images/icons/twitter_icon_star_empty.gif'; 
								if(Browser.Engine.trident) { /* work-around for IE */ elm.style.visibility = '';	}
								else { elm.setStyle('visibility', ''); }
								elm.onclick = function(){ SEP_Twitter_favorites_create(id, elm) } },
		onFailure: function() { elm.src = './images/icons/twitter_icon_star_full.gif'; alert('Error! Please try again later!'); }
	}).send();		
}

function SEP_Twitter_statuses_destroy(id, elm, recordRow) {	
	elm.src = './images/icons/twitter_icon_throbber.gif';

	if(Browser.Engine.trident) { /* work-around for IE */ elm.style.visibility = 'visible';	}
	else { elm.setStyle('visibility', 'visible'); }
	
	if(!window.confirm('Are you sure you want to delete this tweet?')) {
		elm.src = './images/icons/twitter_icon_trash.gif';
		if(Browser.Engine.trident) { /* work-around for IE */ elm.style.visibility = '';	}
		else { elm.setStyle('visibility', ''); }		
		return false;	
	}	
	
	myreq2 = new Request({
		method: 'post',
		url: './user_twitter_ajax.php',
		data: '_ajaxReq=1&task=statuses_destroy&id='+id,
		onSuccess: function(){ $(recordRow).destroy(); },
		onFailure: function() { elm.src = './images/icons/twitter_icon_trash.gif'; 
								if(Browser.Engine.trident) { /* work-around for IE */ elm.style.visibility = '';	}
								else { elm.setStyle('visibility', ''); }				
								alert('Error! Please try again later!'); }
	}).send();		
}





function SEP_Twitter_NextPage(div, page, tpl, instance_name, owner_id, twitter_q) {
	$(div+'_'+page+'_spinner').sep_t_show();
	
	if(owner_id == null) {
		owner_id = '';
	}
	myreq2 = new Request({
		method: 'post',
		url: './user_twitter_ajax.php',
		data: '_ajaxReq=1&task=next_page&tpl='+tpl+'&instance_name='+instance_name+'&page='+page+'&user_id='+owner_id+'&twitter_q='+twitter_q,
		onSuccess: function(html){ $(div+'_'+page).sep_t_update(html); },
		onFailure: function() { alert('Error! Please try again later!'); }
	}).send();	
}




var SEP_Twitter_Tweet = new Class({
	initialize: function(tweet_form) {
		this.timer = null;
		
		this.max_characters = 140;
		
		this.tweet_form = $(tweet_form);
		
		this.link = $(tweet_form+'_Link');
		this.textarea = this.tweet_form.getElement('textarea');	
		this.in_reply_to_status_id = this.tweet_form.getElements('input')[0];
		this.submit = this.tweet_form.getElements('input')[1];
		this.sending_div = this.tweet_form.getElement('div.SEP_Twitter_Tweet_Sending');
		this.success_div = this.tweet_form.getElement('div.SEP_Twitter_Tweet_Success');		
		this.form_div = this.tweet_form.getElement('div.SEP_Twitter_Tweet_Form');	
		this.counter = this.tweet_form.getElement('div.SEP_Twitter_Tweet_Characters_Counter');
		this.heading = this.tweet_form.getElement('span.SEP_Twitter_Tweet_Heading');
	},
	
	start: function() {
		this.check();
		this.timer = this.check.bind(this).periodical(300);
	},
	
	stop: function() {
		$clear(this.timer);
	},
	
	check: function() {
		// check max length
		counter = this.max_characters - this.textarea.value.length;
		if(counter < 10) {
			this.counter.addClass('counterNegative');
		}
		else {
			this.counter.removeClass('counterNegative');			
		}
		this.counter.sep_t_update(counter);
		
		// hide submit button?
		if(counter < 0) {
			this.submit.sep_t_hide();	
		}
		else {
			this.submit.sep_t_show();				
		}
		
		// check heading
		if(this.textarea.value.charAt(0) == '@') {
			this.heading.sep_t_update('Reply to tweet ...');
			this.submit.value = 'Reply';
		}
		else {
			this.heading.sep_t_update('What are you doing?');
			this.submit.value = 'Update';			
		}
	},
	
	show_form: function() {
		this.check();
		this.link.sep_t_hide();		
		this.sending_div.sep_t_hide();
		this.success_div.sep_t_hide();
		this.tweet_form.sep_t_show();
		this.form_div.sep_t_show();		
	},
	
	show_sending: function() {
		this.link.sep_t_hide();		
		this.success_div.sep_t_hide();
		this.form_div.sep_t_hide();	
		this.tweet_form.sep_t_show();		
		this.sending_div.sep_t_show();				
	},
	show_success: function() {
		this.link.sep_t_hide();
		this.sending_div.sep_t_hide();
		this.form_div.sep_t_hide();	
		this.tweet_form.sep_t_show();		
		this.success_div.sep_t_show();				
	},
	show_link: function() {
		this.sending_div.sep_t_hide();
		this.form_div.sep_t_hide();	
		this.success_div.sep_t_hide();			
		this.tweet_form.sep_t_hide();		
		this.link.sep_t_show();		
		this.reset();
	},
	
	
	send: function() {
		// check max length
		if(this.max_characters - this.textarea.value.length < 0) {
			return false;	
		} 	
		
		this.show_sending();
		
		myreq2 = new Request({
			method: 'post',
			url: './user_twitter_ajax.php',
			data: '_ajaxReq=1&task=statuses_update&'+this.tweet_form.toQueryString(),
			onSuccess: function() { this.reset(); this.stop(); this.show_success(); }.bind(this),
			onFailure: function() { this.show_form(); alert('Error! Please try again later!'); }.bind(this)
		}).send();	
			
	},
	
	reset: function() {
		this.textarea.value = '';
		this.check();	
	}
	
});


function SEP_Twitter_reply(id, screen_name, tweet_obj) {
	if(tweet_obj) {
		tweet_obj.textarea.value = '@'+screen_name+' ';
		tweet_obj.in_reply_to_status_id.value = id;
		tweet_obj.check();	
		tweet_obj.show_form();
		tweet_obj.textarea.focus();

		// scroll to textarea
		offset = 500;
		div_position_y = tweet_obj.tweet_form.getPosition().y;
		window.scrollTo(0, div_position_y-offset);		
		
	}
}

