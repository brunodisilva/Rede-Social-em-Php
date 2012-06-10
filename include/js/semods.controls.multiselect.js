
if(!SEMods.Controls) SEMods.Controls = function() { };


SEMods.Controls.MultiSelect = function(id) {
//  this.obj = e.srcElement ? e.srcElement : e.target;
  this.objDiv = SEMods.B.ge(id+"_div");
  this.objInput = SEMods.B.ge(id+"_input");
  this.objIframe = SEMods.B.ge(id+"_iframe");
  this.objAll = SEMods.B.ge(id+"_all");
  SEMods.Controls.MultiSelect.elements.push ( this );
  this.init()
}

SEMods.Controls.MultiSelect.elements = [];

SEMods.Controls.MultiSelect.prototype = {
  objDiv : null,
  objInput : null,
  objIframe : null,
  objAll : null,
  objSelectAll : null,
  selectAll : false,
  selectAllText : '',
  active : false,
  allChecked : true,
  
  
  init : function() {
    this.objDiv._multiselect = this;
    SEMods.Browser.addEvent(this.objInput, "mouseover", this.onMouseOver.bind(this) );
    SEMods.Browser.addEvent(this.objInput, "mouseout", this.onMouseOut.bind(this) );
    SEMods.Browser.addEvent(this.objInput, "click", this.onClick.bind(this) );
    SEMods.Browser.addEvent(this.objInput, "focus", this.onFocus.bind(this) );
    SEMods.Browser.addEvent(this.objInput, "blur", this.onBlur.bind(this) );

    SEMods.Browser.addEvent(this.objDiv, "mouseover", this.onMouseOptionsEnter.bind(this) );
    SEMods.Browser.addEvent(this.objDiv, "mouseout", this.onMouseOptionsLeave.bind(this) );

    var elems = this.objDiv.getElementsByTagName("INPUT");

    for(var i = 0; i < elems.length; i++) {
        if( elems[i].className=="selectAll" ) {
          this.objSelectAll= elems[i];
          SEMods.Browser.addEvent(elems[i], "click", this.onOptionSelectAllClick.bind(this) );
        } else {
          SEMods.Browser.addEvent(elems[i], "click", this.onOptionClick.bind(this) );
          this.allChecked = this.allChecked && elems[i].checked;
          elems[i].parentNode.className = elems[i].checked ? "checked" : "";
        }
    }
    if(this.allChecked)
      this.objSelectAll.checked = "checked";

    this.objAll.value = this.allChecked*1;
  },
  
  onOptionClick : function(e) {

    var elems = this.objDiv.getElementsByTagName("INPUT");

    this.allChecked = true;
    for(var i = 0; i < elems.length; i++) {
        if( elems[i].className=="selectAll" )
          continue;
        this.allChecked = this.allChecked && elems[i].checked;
        elems[i].parentNode.className = elems[i].checked ? "checked" : "";
    }
    
    if(this.allChecked) {
      this.objSelectAll.checked = "checked";
      this.objAll.value = '1';
    } else {
      this.objSelectAll.checked = "";
      this.objAll.value = '0';
    }

  },
  
  onOptionSelectAllClick : function() {
    this.allChecked = this.objSelectAll.checked;
    var elems = this.objDiv.getElementsByTagName("INPUT");
    for(var i = 0; i < elems.length; i++) {
        elems[i].checked = this.allChecked ? "checked" : "";
        elems[i].parentNode.className = elems[i].checked ? "checked" : "";
    }
    this.objAll.value = this.allChecked*1;
  },
  
  hide : function() {
    SEMods.B.hide( this.objDiv );
    SEMods.B.hide( this.objIframe );
    this.active = false;

    // classname
    this.objInput.className = "multiSelect";
  },
  
  show : function() {
    // Hide any open option boxes
    for(i=0;i<SEMods.Controls.MultiSelect.elements.length;i++) {
      SEMods.Controls.MultiSelect.elements[i].hide();
    }
    
    // Position it
    // tbd: outerheight -> total height( padding+margin+border+height )
    this.objDiv.style.top = SEMods.B.findY( this.objInput ) + this.objInput.offsetHeight + 0 + 'px';
    this.objDiv.style.left = SEMods.B.findX( this.objInput ) + 'px';

    
    this.objIframe.style.top = SEMods.B.findY( this.objInput ) + this.objInput.offsetHeight + 0 + 'px';
    this.objIframe.style.left = SEMods.B.findX( this.objInput ) + 'px';

    
    // classname
    this.objInput.className = "multiSelect active";

    SEMods.B.show( this.objDiv );

    this.objIframe.style.width = this.objDiv.offsetWidth + 0 + 'px';
    this.objIframe.style.height = this.objDiv.offsetHeight + 0 + 'px';
    SEMods.B.show( this.objIframe );

    this.active = true;
  },

  onClick : function() {
    this.active ? this.hide() : this.show();
  },
  
  onMouseOver : function(e) {
    // classname
    this.objInput.className = "multiSelect hover";
    return this._cancelEvent(e);
  },

  onMouseOut : function(e) {
    // classname
    this.objInput.className = "multiSelect";
    return this._cancelEvent(e);
  },


  onMouseOptionsEnter : function(e) {
    clearTimeout(this.timer);
    // classname
//    this.objInput.style.className = "multiSelect hover";
    return this._cancelEvent(e);
  },

  onMouseOptionsLeave : function(e) {
    src = e.srcElement ? e.srcElement : e.target;
    // classname
  //  this.objInput.style.className = "multiSelect hover";
    this.timer = setTimeout( this.hide.bind(this), 350 );
    return this._cancelEvent(e);
    
  },
  
  onFocus : function(e) {
    
  },
  
  onBlur : function(e) {
    
  },

  _cancelEvent : function(e) {
      var e = e ? e : window.event;
          
      if(e.preventDefault)
          e.preventDefault();
  
      if(e.stopPropagation) 
          e.stopPropagation(); 
  
      e.cancelBubble = true;
  
      e.returnValue = false;
      return false;
  }
  
}