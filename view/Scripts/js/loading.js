
var opts = {
  lines: 30, // The number of lines to draw
  length: 0, // The length of each line
  width: 10, // The line thickness
  radius: 52, // The radius of the inner circle
  corners: 2, // Corner roundness (0..1)
  rotate: 20, // The rotation offset
  direction: 1, // 1: clockwise, -1: counterclockwise
  color: '#2D2D2D', // #rgb or #rrggbb or array of colors
  speed: 1.2, // Rounds per second
  trail: 60, // Afterglow percentage
  shadow: false, // Whether to render a shadow
  hwaccel: true, // Whether to use hardware acceleration
  className: 'spinner', // The CSS class to assign to the spinner
  zIndex: 2e9, // The z-index (defaults to 2000000000)
  top: 5, // Top position relative to parent in px
  left: 'auto' // Left position relative to parent in px
};

$(function(){
    $("a").click(function(e){ 
        var attrId = $(this).attr('id');
        var href = $(this).attr('href');
        
        if ($(this).hasClass('noload')) {
            
            if (typeof href === 'undefined') {}
	    else if ($(this).hasClass('confirmation')){}
            else {
                document.location.href = href;
            }
        }
        else {
            if (typeof attrId === 'undefined'){
                
                $("#winpopupload").dialog({
                    draggable:  false,
                    modal:      true,
                    autoOpen:   false,
                    height:     'auto',
                    width:      'auto',
                    resizable:  false,
                    title:      'Loading...',
                    position:   'center'
                });
        
                
                $("#winpopupload").html('<center id="SuperSpinner"><div class="popuploadingspinner"><img style="width:128px;height:128px;" src="/img/spinner.gif" /></div></center>');
                var target = document.getElementById('SuperSpinner');
                var spinner = new Spinner(opts).spin(target);
                $("#winpopupload").dialog("open");
    
    
                document.location.href = $(this).attr('href');
            }
        }
        return false;
        
    });
});

