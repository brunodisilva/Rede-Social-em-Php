
/* $Id: class_classified.js 7 2009-01-11 06:01:49Z john $ */

// Required language vars: 4500121,4500123,4500142

SupremeEditionAPI.Classified = new Class({
  
  Base: {},
  
  
  options: {
    'ajaxURL' : 'classified_ajax.php'
  },
  
  
  currentConfirmDeleteID: 0,
  
  imagePreviewAttached: false,
  
  

  // Delete
  deleteClassified: function(classifiedID)
  {
    // Display
    this.currentConfirmDeleteID = classifiedID;
    TB_show(this.Base.Language.Translate(4500121), '#TB_inline?height=100&width=300&inlineId=confirmclassifieddelete', '', '../images/trans.gif');
  },
  
  deleteClassifiedConfirm: function()
  {
    classifiedID = this.currentConfirmDeleteID;
    
    $('seClassified_'+classifiedID).destroy();
    
    // Ajax
    var bind = this;
    var request = new Request.JSON({
      'method' : 'post',
      'url' : this.options.ajaxURL,
      'data' : {
        'task' : 'deleteclassified',
        'classified_id' : classifiedID
      },
      'onComplete':function(responseObject)
      {
        if( $type(responseObject)!="object" || !responseObject.result || responseObject.result=="failure" )
        {
          alert(bind.Base.Language.Translate(4500123));
        }
        
        // Display no classified message
        if( !$$('.seClassified').length )
        {
          $('seClassifiedNullMessage').style.display = 'block';
        }
      }
    });
    
    request.send();
    
    // Reset
    this.currentConfirmDeleteID = 0;
  },
  
  

  // Preview
  imagePreviewClassified: function(imageSource, imageWidth, imageHeight)
  {
    var imageElement = $('seClassifiedImageFull');
    var bind = this;
    
    // Try event (or timeout?)
    imageElement.removeEvents('load');
    imageElement.addEvent('load', function()
    {
      bind.imagePreviewClassifiedComplete();
    });
    
    // Set src attrib
    if( imageElement.src!=imageSource )
      imageElement.src = imageSource;
  },
  
  

  // Preview
  imagePreviewClassifiedComplete: function()
  {
    var imageElement = $('seClassifiedImageFull');
    
    var imageWidth  = imageElement.getSize().x;
    var imageHeight = imageElement.getSize().y;
    
    var popupWidth  = imageWidth  + 20;
    var popupHeight = imageHeight + 20;
    
    var windowWidth  = window.getSize().x - 50;
    var windowHeight = window.getSize().y - 75;
    
    if( popupWidth>windowWidth )
      popupWidth  = windowWidth;
      
    if( popupHeight>windowHeight )
      popupHeight = windowHeight;
    
    /*
    var popupWidth  = 400;
    var popupHeight = 300;
    
    imageWidth  = parseInt(imageWidth);
    imageHeight = parseInt(imageHeight);
    
    // User default size
    if( !imageWidth || !imageHeight )
    {
      imageWidth = 400;
      imageHeight = 300;
    }
    
    // Calculate size
    else
    {
      var reductionRatioX = 1, reductionRatioY = 1;
      var windowWidth  = window.getSize().x - 50;
      var windowHeight = window.getSize().y - 75;
      
      if( imageWidth>windowWidth )
        reductionRatioX = (windowWidth / imageWidth);
      if( imageHeight>windowHeight )
        reductionRatioY = (windowHeight / imageHeight);
      
      if( reductionRatioX!=1 && reductionRatioX<reductionRatioY )
        reductionRatioY = reductionRatioX;
      else if( reductionRatioY!=1 && reductionRatioY<reductionRatioX )
        reductionRatioX = reductionRatioY;
      
      imageWidth  = Math.round(imageWidth  * reductionRatioX);
      imageHeight = Math.round(imageHeight * reductionRatioY);
      
      $('seClassifiedImageFull').style.width  = imageWidth.toString() + 'px';
      $('seClassifiedImageFull').style.height = imageHeight.toString() + 'px';
      
      popupWidth  = imageWidth  + 10;
      popupHeight = imageHeight + 20;
    }
    */
    
    // Display
    TB_show(this.Base.Language.Translate(4500142), '#TB_inline?height='+popupHeight+'&width='+popupWidth+'&inlineId=seClassifiedImagePreview', '', '../images/trans.gif');
  }
  
  
});