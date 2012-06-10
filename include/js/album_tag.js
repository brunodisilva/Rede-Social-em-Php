
/* $Id: album_tag.js 2 2009-01-10 20:53:09Z john $ */


var newtag;
var current_user;
var isTagging = false;
var tags = [];


// THIS FUNCTION CREATES A NEW TAGGING INSTANCE
function addTag() {
  if(!isTagging) {
    isTagging = true;
    newtag = new MooCrop('media_photo');

    var indicator = $('media_photo_tagform').inject(newtag.wrapper);
    indicator.setStyles({'top' : newtag.crop.bottom+10, 'left' : newtag.crop.right+10, 'display' : 'block'});

    newtag.addEvent('onBegin', function(imgsrc, crop, bound, hanlde) { indicator.setStyle('display', 'none'); });
    newtag.addEvent('onCrop', function(imgsrc, crop, bound, hanlde) { indicator.setStyles({'top' : crop.bottom+10, 'left' : crop.right+10, 'display' : 'none'}); });
    newtag.addEvent('onComplete', function(imgsrc, crop, bound, hanlde) { indicator.setStyle('display', 'block'); });
  }
}

// THIS FUNCTION CANCELS THE TAGGING INSTANCE
function cancelTag() {
  if(isTagging) {
    $('media_photo_tag').value = '';
    $('media_photo_tagform').inject('media_photo_div').setStyle('display', 'none');
    var stopTagging = newtag.removeOverlay.bind(newtag);
    stopTagging();
    isTagging = false;
  }
}

// THIS FUNCTION ATTEMPTS TO SAVE A NEW TAG
function saveTag(owner_username, album_id, media_id, mediatag_user_id) {
  if(isTagging) {

    var url = 'album_file.php?task=tag&user='+owner_username;
    url += '&album_id='+album_id;
    url += '&media_id='+media_id;
    url += '&mediatag_user_id='+mediatag_user_id;
    url += '&mediatag_text='+encodeURIComponent($('media_photo_tag').value);
    url += '&mediatag_x='+newtag.crop.top;
    url += '&mediatag_y='+newtag.crop.left;
    url += '&mediatag_height='+newtag.crop.height;
    url += '&mediatag_width='+newtag.crop.width;
    $('ajaxframe').src = url;

    $('media_photo_tag').value = '';
    $('media_photo_tagform').inject('media_photo_div').setStyle('display', 'none');
    var stopTagging = newtag.removeOverlay.bind(newtag);
    stopTagging();
    isTagging = false;
  }
}

// THIS FUNCTION CONSTRUCTS A NEW TAG ONCE CREATION IS CONFIRMED
function insertTag(tag_id, tag_link, tag_text, tag_x, tag_y, tag_width, tag_height, owner_username, album_id, media_id, tagged_user) {

  var newHTML = '';

  if($('media_tags').style.display == 'none') {
    $('media_tags').style.display = 'block';
  } else if(tags.length != 0) {
    newHTML = '<span id="tag_comma_'+tag_id+'">, </span>';
  }

  var newSpan = new Element("span", {'id' : 'full_tag_'+tag_id, 'html' : newHTML});
  if(tag_link != '') {
    var newAnchor = new Element("a", {'href' : tag_link, 
			'id' : 'tag_link_'+tag_id,
			'html' : tag_text
		}).inject(newSpan);
  } else {
    var newAnchor = new Element("span", {'id' : 'tag_link_'+tag_id,
				'html' : tag_text,
				'styles' : {'cursor' : 'pointer'}
			}).inject(newSpan);
  }

  if(current_user == tagged_user || current_user == owner_username) {
    var media_tags_text = $(newSpan).get('html');
    $(newSpan).set('html', media_tags_text, ' (<a href=\'javascript:void(0);\' id=\'new_removetag_link\'>'+SELanguage.Translate(1000173)+'</a>)');
  }

  newSpan.inject($('media_tags'));

  createTag(tag_id, tag_text, tag_x, tag_y, tag_width, tag_height);

  $('tag_link_'+tag_id).addEvent('mouseover', function() { showTag(tag_id); });
  $('tag_link_'+tag_id).addEvent('mouseout', function() { hideTag(tag_id); });

  if($('new_removetag_link')) {
    $('new_removetag_link').addEvent('click', function() { removeTag(tag_id, owner_username, album_id, media_id); });
    $('new_removetag_link').set('id', 'removetag_link_'+tag_id);
  }

  tags.push(tag_id);

}


// THIS FUNCTION CREATES A TAG FOR LATER VIEWING
function createTag(tag_id, label_text, tag_x, tag_y, tag_width, tag_height) {

  // CREATE TAG AND LABEL
  new Element("div", {'id' : 'tag_'+tag_id, 'html' : '<img src="./images/trans.gif" width="100%" height="100%" />', 'class' : 'tag_div_hidden', 'styles' : {'width' : (parseInt(tag_width)-4)+'px', 'height' : (parseInt(tag_height)-4)+'px', 'top' : tag_x+'px', 'left' : tag_y+'px'}}).inject($('media_photo_div'));
  new Element("div", {'id' : 'tag_label_'+tag_id, 'html' : label_text, 'class' : 'tag_label', 'styles' : {'display' : 'none', 'top' : (parseInt(tag_x)+parseInt(tag_height)+10)+'px', 'left' : tag_y+'px'}}).inject($('media_photo_div'));

  // ADD MOUSEOVER/MOUSEOUT EVENTS
  $('tag_'+tag_id).addEvent('mouseover', function() { showTag(tag_id); });
  $('tag_'+tag_id).addEvent('mouseout', function() { hideTag(tag_id); });
}

// THIS FUNCTION DISPLAYS A TAG
function showTag(tag_id) {
  $('tag_'+tag_id).className = 'tag_div';
  $('tag_label_'+tag_id).style.display = 'block';
}

// THIS FUNCTION HIDES A TAG FROM VIEW
function hideTag(tag_id) {
  $('tag_'+tag_id).className = 'tag_div_hidden';
  $('tag_label_'+tag_id).style.display = 'none';
}


// THIS FUNCTION REMOVES A TAG COMPLETELY
function removeTag(tag_id, owner_username, album_id, media_id) {

  var url = 'album_file.php?task=tag_remove&user='+owner_username;
  url += '&album_id='+album_id;
  url += '&media_id='+media_id;
  url += '&mediatag_id='+tag_id;
  $('ajaxframe').src = url;

  $('tag_'+tag_id).destroy();
  $('tag_label_'+tag_id).destroy();

  $('full_tag_'+tag_id).destroy();

  if(tags.indexOf(tag_id) == 0 && $('tag_comma_'+tags[1])) {
    $('tag_comma_'+tags[1]).destroy();
  }

  tags.splice(tags.indexOf(tag_id), 1);

  if(tags.length == 0) {
    $('media_tags').style.display = 'none';
  }
}


// THIS FUNCTION SENDS A JSON REQUEST FOR THE FRIENDLIST
function getFriendList(owner_username, album_id, media_id) {
  var request = new Request.JSON({secure: false, url: 'misc_js.php?task=friends_all',
		onComplete: function(jsonObj) { 
			addFriendToList(jsonObj.friends, owner_username, album_id, media_id);
		}
  }).send();
}


// THIS FUNCTION TAKES A JSON RESULT AND POPULATES THE FRIENDLIST
function addFriendToList(friends, owner_username, album_id, media_id) {
  if($('media_photo_friendlist')) {
    friends.each(function(friend) {
      for(var x in friend) {

	var newDiv = new Element("div", {'id' : 'friend_div_'+x});
	var newAnchor = new Element("a", {'href' : 'javascript:void(0)', 
			'id' : 'friend_link_'+x,
			'html' : friend[x]
		}).inject(newDiv);

	newDiv.inject($('media_photo_friendlist'));

	$('friend_link_'+x).addEvent('click', function() { saveTag(owner_username, album_id, media_id, x); });
      }
    });
  }
}





/***
 * MooCrop (v. rc-1 - 2007-10-24 )
 *
 * @version			rc-1
 * @license			BSD-style license
 * @author			nwhite - < nw [at] nwhite.net >
 * @infos			http://www.nwhite.net/MooCrop/
 * @copyright		Author
 * 

 */
var MooCrop = new Class({

	calculateHandles : true,
	current : {},

	options : {
		maskColor : 'black',
		maskOpacity : '.3',
		handleColor : '#FFFFFF',
		handleWidth : '5px',
		handleHeight : '5px',
		cropBorder : '1px dashed #FFFFFF',
		min : { 'width' : 50, 'height' : 50 },
		showMask : true, // false to remove, helps on slow machines
		showHandles : false // hide handles on drag
	},

	initialize: function(el, options){
		this.setOptions(options);
		this.img = $(el);
		if ( this.img.get('tag') != 'img') return false;

		this.resizeFunc = this.refresh.bindWithEvent(this);
		this.removeFunc = this.removeListener.bind(this);

		this.buildOverlay();
		this.setup();
	},

	setup: function(){
		$(this.cropArea).setStyles({
			'width': this.options.min.width, 
			'height': this.options.min.height,
			'top' : (this.img.height - this.options.min.height)/2,
			'left': (this.img.width - this.options.min.width) / 2 
		});

		this.current.crop = this.crop = this.getCropArea();
		this.handleWidthOffset = this.options.handleWidth.toInt() / 2;
		this.handleHeightOffset = this.options.handleHeight.toInt() /2;

		this.fixBoxModel();
		this.drawMasks();
		this.positionHandles();
	},

	getCropArea : function(){
		var crop = this.cropArea.getCoordinates();
		crop.left -= this.offsets.x; crop.right -= this.offsets.x; // calculate relative (horizontal)
		crop.top -= this.offsets.y; crop.bottom  -= this.offsets.y; // calculate relative (vertical)
		return crop;
	},

	fixBoxModel : function(){
		var diff = this.boxDiff = (this.crop.width - this.options.min.width)/2;

		var b = this.bounds = { 'top' : diff, 'left' : diff, 
			'right' : this.img.width+(diff*2), 'bottom' : this.img.height+(diff*2),
			'width' : this.options.min.width+(diff*2), 'height' : this.options.min.height+(diff*2) };

		this.wrapper.setStyles({
			'width' : b.right, 'height' : b.bottom,
			'background' : 'url('+this.img.src+') no-repeat '+diff+'px '+diff+'px'
		});

		this.north.setStyle('width',b.right);
		this.south.setStyle('width',b.right);
	},

	activate : function(event,handle){
		event.stop();
		this.current = { 'x' : event.page.x, 'y' : event.page.y, 'handle' : handle, 'crop' : this.current.crop };
		if(this.current.handle == 'NESW' && !this.options.showHandles) this.hideHandles();
		this.fireEvent('onBegin',[this.img.src,this.getCropInfo(),this.bounds,handle]);
		document.addEvent('mousemove', this.resizeFunc);
		document.addEvent('mouseup', this.removeFunc);
	},

	removeListener : function(){
		if( this.current.handle == 'NESW' && !this.options.showHandles) this.showHandles();
		document.removeEvent('mousemove', this.resizeFunc);
		document.removeEvent('mouseup', this.removeFunc);
		this.crop = this.current.crop;
		this.fireEvent('onComplete',[this.img.src,this.getCropInfo(),this.bounds,this.current.handle]);
	},

	refresh : function(event){
		var xdiff = this.current.x - event.page.x;
		var ydiff = this.current.y - event.page.y;

		var b = this.bounds;  var c = this.crop;  var handle = this.current.handle; var styles = {}; //saving bytes
		var dragging = (handle.length > 2) ? true : false;
		
		if( handle.contains("S") ){//SOUTH
			if(c.bottom - ydiff > b.bottom ) ydiff = c.bottom - b.bottom; // box south
			if(!dragging){
				if( (c.height - ydiff) < b.height ) ydiff = c.height - b.height; // size south
				styles['height'] = c.height - ydiff; // South handles only
			}
		}
		if( handle.contains("N") ){//NORTH
			if(c.top - ydiff < b.top ) ydiff = c.top; //box north
			if(!dragging){
				if( (c.height + ydiff ) < b.height ) ydiff = b.height - c.height; // size north
				styles['height'] = c.height + ydiff; // North handles only
			}
			styles['top'] = c.top - ydiff; // both Drag and N handles
		}
		if( handle.contains("E") ){//EAST
			if(c.right - xdiff > b.right) xdiff = c.right - b.right; //box east
			if(!dragging){
				if( (c.width - xdiff) < b.width ) xdiff = c.width - b.width; // size east
				styles['width'] = c.width - xdiff;
			}
		}
		if( handle.contains("W") ){//WEST
			if(c.left - xdiff < b.left) xdiff = c.left; //box west
			if(!dragging){
				if( (c.width + xdiff) < b.width ) xdiff = b.width - c.width; //size west
				styles['width'] = c.width + xdiff;
			}
			styles['left'] = c.left - xdiff; // both Drag and W handles
		}
		var preCssStyles = $merge(styles);
		if( $defined(styles.width)) styles.width -= this.boxDiff*2;
		if( $defined(styles.height)) styles.height -= this.boxDiff*2;

		this.cropArea.setStyles(styles);
		this.getCurrentCoords(preCssStyles);
		this.drawMasks();
		this.positionHandles();
		this.fireEvent('onCrop',[this.img.src,this.getCropInfo(),b,handle]);
	},

	getCurrentCoords : function(changed){
		var current = $merge(this.crop);
		
		if($defined(changed.left)){
			current.left = changed.left;
			if($defined(changed.width)) current.width = changed.width;
			else current.right = current.left + current.width;
		}
		if($defined(changed.top)){
			current.top = changed.top;
			if($defined(changed.height)) current.height = changed.height;
			else current.bottom = current.top + current.height;
		}
		if($defined(changed.width) && !$defined(changed.left)){
			current.width = changed.width; current.right = current.left + current.width;
		}
		if($defined(changed.height) && !$defined(changed.top)){
			current.height = changed.height; current.bottom = current.top + current.height;
		}
		this.current.crop = current;
	},

	drawMasks : function(){
		if(!this.options.showMask) return;
		var b = this.bounds;  var c = this.current.crop; var handle = this.current.handle;

		this.north.setStyle('height', c.top + 'px' );
		this.south.setStyle('height', b.bottom  - c.bottom  + 'px');
		this.east.setStyles({ height: c.height + 'px', width: b.right  - c.right + 'px',  top: c.top  + 'px', left: c.right + 'px'});
		this.west.setStyles({ height: c.height + 'px', width: c.left + 'px', top: c.top + 'px'});
	},

	positionHandles: function(){
		if(!this.calculateHandles) return;
		var c = this.current.crop; var wOffset = this.handleWidthOffset; var hOffset = this.handleHeightOffset;

		this.handles.get('N').setStyles({'left' : c.width / 2 - wOffset + 'px', 'top' : - hOffset + 'px'});
		this.handles.get('NE').setStyles({'left' : c.width - wOffset + 'px', 'top' : - hOffset + 'px'});
		this.handles.get('E').setStyles({ 'left' : c.width - wOffset + 'px', 'top' : c.height / 2 - hOffset + 'px'});
		this.handles.get('SE').setStyles({'left' : c.width - wOffset + 'px', 'top' : c.height - hOffset + 'px'});
		this.handles.get('S').setStyles({'left' : c.width / 2 - wOffset + 'px', 'top' : c.height - hOffset + 'px'});
		this.handles.get('SW').setStyles({'left' : - wOffset + 'px', 'top' : c.height - hOffset + 'px'});
		this.handles.get('W').setStyles({'left' : - wOffset + 'px', 'top' : c.height / 2 - hOffset + 'px'});
		this.handles.get('NW').setStyles({'left' : - wOffset + 'px', 'top' : - hOffset + 'px'});
	},

	hideHandles: function(){
		this.calculateHandles = false;
		this.handles.each(function(handle){
			handle.setStyle('display','none');
		});
	},

	showHandles: function(){
		this.calculateHandles = true;
		this.positionHandles();
		this.handles.each(function(handle){
			handle.setStyle('display','block');
		});
	},

	buildOverlay: function(){
		var o = this.options;

		this.wrapper = new Element("div", {
			'styles' : {'z-index' : 100, 'position' : 'relative', 'width' : this.img.width, 'height' : this.img.height, 'background' : 'url('+this.img.src+') no-repeat' , 'float' : this.img.getStyle('float') , 'margin-left' : 'auto' , 'margin-right' : 'auto'  }
		}).injectBefore(this.img);

		this.img.setStyle('display','none');

		this.offsets = { x : this.wrapper.getLeft(), y : this.wrapper.getTop() };

		// SET WRAPPER MOUSEOVER TO STOP PROPAGATION OF MOUSEOVER EVENT
		this.wrapper.addEvent('mouseover', function(event) { return false; });

		if(this.options.showMask){		// optional masks
			var maskStyles = { 'position' : 'absolute', 'overflow' : 'hidden', 'background-color' : o.maskColor, 'opacity' : o.maskOpacity};
			this.north = new Element("div", {'styles' : $merge(maskStyles,{'left':'0px'})}).injectInside(this.wrapper);
			this.south = new Element("div", {'styles' : $merge(maskStyles,{'bottom':'0px', 'left':'0px'})}).injectInside(this.wrapper);
			this.east =  new Element("div", {'styles' : maskStyles}).injectInside(this.wrapper);
			this.west =  new Element("div", {'styles' : $merge(maskStyles,{'left':'0px'})}).injectInside(this.wrapper);
		}

		this.cropArea = new Element("div", { 'styles' : { 'position' : 'absolute', 'top' : '0px', 'left' : '0px', 'border' : o.cropBorder, 'cursor' : 'move' },
		'events' : {
			'dblclick' : function(){ this.fireEvent('onDblClk',[this.img.src,this.getCropInfo(),this.bounds])}.bind(this),
			'mousedown' : this.activate.bindWithEvent(this,'NESW')}
		}).injectInside(this.wrapper);

		this.handles = new Hash();
		['N','NE','E','SE','S','SW','W','NW'].each(function(handle){
			this.handles.set(handle, new Element("div", {
			'styles' : { 'position' : 'absolute', 'background-color' : o.handleColor, 
						 'width' : o.handleWidth, 'height' : o.handleHeight, 'overflow' : 'hidden', 'cursor' : (handle.toLowerCase()+'-resize')},
			'events' : {'mousedown' : this.activate.bindWithEvent(this,handle)}
			}).injectInside(this.cropArea));
		},this);
	},

	getCropInfo : function(){
		var c = $merge(this.current.crop);
		c.width -= this.boxDiff*2; c.height -= this.boxDiff*2;
		return c;
	},

	removeOverlay: function(){
		this.wrapper.destroy();
		this.img.setStyle('display','');
	}

});
MooCrop.implement(new Events, new Options);