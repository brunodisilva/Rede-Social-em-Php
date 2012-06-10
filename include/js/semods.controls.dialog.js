
if(!SEMods.Controls) SEMods.Controls = function() { };


SEMods.Controls.Dialog = function( params ) {
  
  this.title = params.title ? params.title : '';
  this.text = params.text;
  
  if(params.yesButtonText) this.yesButtonText = params.yesButtonText;
  if(params.noButtonText) this.noButtonText = params.noButtonText;
  if(params.onYes) this.onYes= params.onYes;
  if(params.onNo) this.onNo= params.onNo;
  if(params.width) this.width = params.width;
  if(params.height) this.height = params.height;
  if(params.maxwidth) this.maxwidth = params.maxwidth;
  if(params.maxheight) this.maxheight = params.maxheight;
  
  extracontent = params.content ? params.content : null;
  
  this.init(extracontent);
  this.show();
  
}

SEMods.Controls.Dialog.close = function(id) {
  var dlg = SEMods.B.ge("semods_dlg" + id);
  dlg._obj.onNoClick();
}

SEMods.Controls.Dialog.prototype = {
  title : null,
  text : null,
  yesButtonText : 'Yes',
  noButtonText : 'No',
  width : null,
  height : null,
  maxwidth : 0,
  maxheight : 0,
  dlgElem : null,
  onYes : null,
  onNo : null,
  id : null,
  
  init : function(extracontent) {

    this.id = Math.floor(Math.random()*1000000);
    this.dlgElem = document.createElement("DIV");
    this.dlgElem._obj = this;
    this.dlgElem.className = "semods_dialog";
    this.dlgElem.id = "semods_dlg" + this.id;
    this.dlgElem.style.left = "200px";
    this.dlgElem.style.top = "200px";
    this.dlgElem.style.display = "none";

    this.dlgElem.style.width = this.width ? this.width : this.maxwidth + "px";
    this.dlgElem.style.height = this.height ? this.height : this.maxheight + "px";

    var content = "<div id=semods_dlgcontent style='display:none'>";
    content += "<div id=semods_dlgheader><div style='float:right'><a href=javascript:SEMods.Controls.Dialog.close('" + this.id + "')>close</a></div></div>";
    content += "<div id=semods_dlgtitle> " + this.title + "</div>";
    content += "<div id=semods_dlgtext>"
    content += this.text;

    if(extracontent)
      content += "<div>" + extracontent + "</div>";

    content += "</div>";
    content += "<div id=semods_dlgbuttons><input type='button' class='button' id='semods_dlgbtnyes'><input type='button' class='button' id='semods_dlgbtnno'></div>";
    content += "</div>";

    this.dlgElem.innerHTML = content;

    var inputs = this.dlgElem.getElementsByTagName("INPUT");
    SEMods.B.addEvent( inputs[0], "click", this.onYesClick.bind(this) );
    inputs[0].value = this.yesButtonText;
    SEMods.B.addEvent( inputs[1], "click", this.onNoClick.bind(this) );
    inputs[1].value = this.noButtonText;

    document.body.appendChild( this.dlgElem );
    
  },
  
  show : function() {
    setTimeout( this.resizeToContent.bind(this), 1 );
  },
  
  resizeToContent : function() {
    SEMods.B.show( this.dlgElem );
    SEMods.B.show( this.dlgElem.firstChild );
    
    this.dlgElem.style.height = ((this.maxheight && ( this.dlgElem.firstChild.offsetHeight > this.maxheight)) ? this.maxheight : this.dlgElem.firstChild.offsetHeight) + 'px';
    this.dlgElem.style.width = ((this.maxwidth && ( this.dlgElem.firstChild.offsetWidth > this.maxwidth)) ? this.maxwidth : this.dlgElem.firstChild.offsetWidth) + 'px';

  },
  
  hide : function() {
    SEMods.B.hide( this.dlgElem );
  },
  
  onYesClick : function() {
    this.hide();
    if(this.onYes)
      this.onYes();
    this.destroy();
  },
  
  destroy : function() {
    this.dlgElem.parentNode.removeChild( this.dlgElem );
  },

  onNoClick : function() {
    this.hide();
    if(this.onNo)
      this.onNo();
    this.destroy();
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