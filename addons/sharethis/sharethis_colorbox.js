function openCallBack(publisherID)
{
	if( ! jQuery('#cboxContent .st_sharethis_hcount').length )
	{
		var switchTo5x = false;
		jQuery.getScript("http://w.sharethis.com/button/buttons.js", function() {
			stLight.options({publisher: publisherID, doNotHash: false, doNotCopy: false, hashAddressBar: false});
		});

		jQuery('#cboxContent').append('<span class="st_sharethis_hcount" displayText="ShareThis"></span><span class="st_facebook_hcount" displayText="Facebook"></span>');
	}
	
}