<?php

class socialshare_sharethis extends sharethis_pluginAddOn
{
	/** 
	 * Get current collection settings
	 */
	static function get_coll_setting_definitions()
	{
		return array(
			'sharethis_publisher_id' => array(
				'label' => 'Sharethis ' . T_('Publisher ID'),
				'size' => 70,
				'defaultvalue' => '',
				'size' => 36,
				'maxlength' => 36,
				'note' => T_( 'This is you publisher ID in the format &laquo;xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx&raquo;; which you can find in the code provided by ShareThis' ),
				'valid_pattern' => '#\w{8}-(\w{4}-){3}\w{12}#'
			),
			'sharethis_services' => array(
				'label' => 'Sharethis ' . T_('Services'),
				'defaultvalue' => 'twitter,facebook,pinterest,linkedin',
				'note' => T_( 'Comma &laquo;,&raquo; separated list of services (social/sharing sites). Leave empty to use the default list provided by Sharethis' ),
				'size' => 100 
			),
		);
	}

	/** Misc methods **/

	/**
	 * Inserts required html markup and javascript code
	 */
	function insert_code_block( & $params )
	{
		$content = & $params['data'];
		$item = & $params['Item'];

		//TODO: allow per post-type inclusion

		$title = $item->dget( 'title', 'htmlattr' );
		$url   = $item->get_permanent_url();

		$content .= "\n".'<div class="st_sharethis" displayText="ShareThis" st_url="'.$url.'" st_title="'.$title.'" style="margin-bottom:5px;"></div>' . "\n";
		return true;
	}


	/** Plugin HOOKS **/

	function SkinBeginHtmlHead( & $params )
	{
		$url = 'http://w.sharethis.com/button/buttons.js';
		require_js( $url );
		add_headline('<script type="text/javascript">stLight.options({publisher:"'.$this->coll_settings['sharethis_publisher_id'].'",onhover: false});</script>');
		return true;
	}

	function RenderItemAsHtml( & $params )
	{
		$this->insert_code_block( $params );
	}

}
