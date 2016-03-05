<?php

class socialshare_sharethis extends sharethis_pluginAddOn
{
	static $_supported_services = array(
		'sharethis',
		'facebook', 'twitter', 'email', 'pinterest', 'linkedin',
		'googleplus', 'digg', 'stumbleupon', 'reddit', 'tumblr',
		'adfty', 'allvoices', 'amazon_wishlist', 'arto', 'att',
		'baidu', 'blinklist', 'blip', 'blogmarks', 'blogger',
		'buddymarks', 'buffer', 'care2', 'chiq', 'citeulike',
		'corkboard', 'dealsplus', 'delicious', 'diigo', 'dzone',
		'edmodo', 'embed_ly', 'evernote', 'fark', 'fashiolista',
		'flipboard', 'folkd', 'foodlve', 'fresqui', 'friendfeed',
		'funp', 'fwisp', 'google', 'google_bmarks', 'google_reader',
		'google_translate', 'hatena', 'instapaper', 'jumptags', 'kaboodle',
		'linkagogo', 'livejournal', 'mail_ru', 'meneame', 'messenger',
		'mister_wong', 'moshare', 'myspace', 'n4g', 'netlog',
		'netvouz', 'newsvine', 'nujij', 'odnoklassniki', 'oknotizie',
		'pocket', 'print', 'raise_your_voice', 'segnalo', 'sina',
		'sonico', 'startaid', 'startlap', 'stumpedia', 'typepad',
		'viadeo', 'virb', 'vkontakte', 'voxopolis', 'weheartit',
		'wordpress', 'xerpi', 'xing', 'yammer',
	);

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
			'sharethis_usechicklets' => array(
				'label' => T_('Use chicklets'),
				'type' => 'checkbox',
			),
			'sharethis_iconsstyle' => array(
				'label' => T_('Style'),
				'type' => 'select',
				'options' => array('standard' => T_('Standard'), 'large' => T_('Large'), 'button' => T_('Button')),
				'defaultvalue' => 'standard'

			),
			'sharethis_services' => array(
				'label' => T_('Services'),
				'type' => 'text',
				'defaultvalue' => 'sharethis,twitter,facebook,pinterest,linkedin',
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
		$icons_style = ($this->coll_settings['sharethis_iconsstyle'] == 'standard') ? '' : '_'.$this->coll_settings['sharethis_iconsstyle']; // TODO: support custom images

		if ( $this->coll_settings['sharethis_usechicklets'] )
		{
			$services = (!empty($this->coll_settings['sharethis_services'])) ? str_getcsv($this->coll_settings['sharethis_services'], ',') : self::$_supported_services;

			$content .= '<div>';
			foreach ( $services as $service )
			{
				$service = trim($service);
				if( in_array( $service, self::$_supported_services ) )
				{
					$displayText = ($icons_style == '_button') ? 'displayText="'.ucfirst($service).'"' : '';

					$content .= '<span class="st_'.$service.$icons_style.'" st_url="'.$url.'" st_title="'.$title.'" '.$displayText.'></span>';
				}
			}
			$content .= '</div>';
		}
		else
		{
			$content .= "\n".'<div class="st_sharethis" displayText="ShareThis" st_url="'.$url.'" st_title="'.$title.'" style="margin-bottom:5px;"></div>' . "\n";
		}



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
