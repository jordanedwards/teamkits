// set document domain to allow iframe to work
try {
 document.domain = "ubc.ca";
} catch (e) {}

// function to get the various values from the main window
// then print the window
function printWindow() {
    if (parent.StudName != null) {
        $("#studName").html(parent.StudName);
        $("#studNo").html(parent.StudNo);
        $("#currentDate").html(parent.CurrentDate);
    } else {
        $("#printingInfo").addClass("noPrint");
    }
    window.print();
}

function keepParentAlive() {
    try {
        var b = parent == window;
        if (!b) {
          parent.$.post("SRVKeepAlive");
        }
    } catch (e) {};
}

// on load function to add the print div as well as the non-visible stuff for the print page.
try {
  $(document).ready(function() {
	try {
		keepParentAlive(); // this will post a message to the SSC when this page is reloaded / saved
		headerHTML = '<div id="printer" onclick="printWindow()" class="noPrint">Print</div><div id="header-invisible" class="onlyPrint"><div><img src="/static/ssc/images/ubc_print_header.png" alt="UBC"></div><div id="printingInfo" style="color: black; top:2px; right:0; text-align: right;"><b>Name:</b><span id="studName">&nbsp;</span>&nbsp;&nbsp;&nbsp;<b>#:</b><span id="studNo">&nbsp;</span>&nbsp;&nbsp;&nbsp;<b>Date:</b><span id="currentDate">&nbsp;</span></div></div>';
		$(document.body).prepend(headerHTML);
		$(window.parent).scrollTop(0);
		parent.resizeIframeDiv();
	} catch (e) { }
  });
} catch (e) {};

// add the indexof function to IE6...
if (!Array.prototype.indexOf)
{
  Array.prototype.indexOf = function(elt /*, from*/)
  {
    var len = this.length;

    var from = Number(arguments[1]) || 0;
    from = (from < 0)
         ? Math.ceil(from)
         : Math.floor(from);
    if (from < 0)
      from += len;

    for (; from < len; from++)
    {
      if (from in this &&
          this[from] === elt)
        return from;
    }
    return -1;
  };


}

  function sscTitleToggle() {
  // actions for ssc headlines
  // hide content
  $('.ssc-content').each(function() {

      // $(this).css('height', $(this).height());

      $(this).hide();

  });

  // add click event
  $('.ssc-title').each(function() {

      $(this).click(function() {

          if (!ssc_anim_lock)
          {
              ssc_anim_lock = true;

              active_ssc_link = this;

              if ($('#' + $(this).attr('id') + '_text').css('display') == 'none')
              {
                  // close open text
                  $(open_ssc_text).slideUp(400, function() {

                      id_arr = $(this).attr('id').split('_');

                      $('#' + id_arr[0] + '_' + id_arr[1]).addClass('toggleOpen');
                      $('#' + id_arr[0] + '_' + id_arr[1]).removeClass('toggleClosed');

                  });

                  // alert(open_ssc_text);

                  $('#' + $(this).attr('id') + '_text').slideDown(400, function() {

                      // change icon
                      id_arr = $(this).attr('id').split('_');

                      $('#' + id_arr[0] + '_' + id_arr[1]).addClass('toggleClosed');
                      $('#' + id_arr[0] + '_' + id_arr[1]).removeClass('toggleOpen');

                      open_ssc_text = this;

                      // unlock
                      ssc_anim_lock = false;

                  });
              }
              else
              {
                  $('#' + $(this).attr('id') + '_text').slideUp(400, function() {

                      // change icon
                      id_arr = $(this).attr('id').split('_');

                      $('#' + id_arr[0] + '_' + id_arr[1]).addClass('toggleOpen');
                      $('#' + id_arr[0] + '_' + id_arr[1]).removeClass('toggleClosed');

                      // unlock
                      ssc_anim_lock = false;

                  });
              }
          }

      });

  });

}