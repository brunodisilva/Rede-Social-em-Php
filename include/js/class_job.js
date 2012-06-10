
/* $Id: class_job.js 7 2009-01-11 06:01:49Z john $ */

// Required language vars: 6400121,6400123,6400142

SupremeEditionAPI.Job = new Class({
  
  Base: {},
  
  
  options: {
    'ajaxURL' : 'job_ajax.php'
  },
  
  
  currentConfirmDeleteID: 0,
  
  imagePreviewAttached: false,
  
  

  // Delete
  deleteJob: function(jobID)
  {
    // Display
    this.currentConfirmDeleteID = jobID;
    TB_show(this.Base.Language.Translate(6400121), '#TB_inline?height=100&width=300&inlineId=confirmjobdelete', '', '../images/trans.gif');
  },
  
  deleteJobConfirm: function()
  {
    jobID = this.currentConfirmDeleteID;
    
    $('seJob_'+jobID).destroy();
    
    // Ajax
    var bind = this;
    var request = new Request.JSON({
      'method' : 'post',
      'url' : this.options.ajaxURL,
      'data' : {
        'task' : 'deletejob',
        'job_id' : jobID
      },
      'onComplete':function(responseObject)
      {
        if( $type(responseObject)!="object" || !responseObject.result || responseObject.result=="failure" )
        {
          alert(bind.Base.Language.Translate(6400123));
        }
        
        // Display no job message
        if( !$$('.seJob').length )
        {
          $('seJobNullMessage').style.display = 'block';
        }
      }
    });
    
    request.send();
    
    // Reset
    this.currentConfirmDeleteID = 0;
  },
  
  

  // Preview
  imagePreviewJob: function(imageSource, imageWidth, imageHeight)
  {
    var imageElement = $('seJobImageFull');
    var bind = this;
    
    // Try event (or timeout?)
    imageElement.removeEvents('load');
    imageElement.addEvent('load', function()
    {
      bind.imagePreviewJobComplete();
    });
    
    // Set src attrib
    if( imageElement.src!=imageSource )
      imageElement.src = imageSource;
  },
  
  

  // Preview
  imagePreviewJobComplete: function()
  {
    var imageElement = $('seJobImageFull');
    
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
      
      $('seJobImageFull').style.width  = imageWidth.toString() + 'px';
      $('seJobImageFull').style.height = imageHeight.toString() + 'px';
      
      popupWidth  = imageWidth  + 10;
      popupHeight = imageHeight + 20;
    }
    */
    
    // Display
    TB_show(this.Base.Language.Translate(6400142), '#TB_inline?height='+popupHeight+'&width='+popupWidth+'&inlineId=seJobImagePreview', '', '../images/trans.gif');
  }
  
  
});