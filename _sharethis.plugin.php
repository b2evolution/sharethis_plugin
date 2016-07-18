<?php 

class sharethis_plugin extends Plugin
{
	/**
	 * Variables below MUST be overriden by plugin implementations,
	 * either in the subclass declaration or in the subclass constructor.
	 */

	var $name = 'sharethis';
	var $code = 'evo_sharethis';
	var $priority = 50;
	var $version = '1.6';
	var $author = 'The b2evo Group';
	var $group = 'rendering';

    private $_supported_services = array(
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
	 * Init
	 */
	function PluginInit( & $params )
	{
		$this->name = T_( 'ShareThis' );
		$this->short_desc = T_('Share contents to your favorite social networks using the ShareThis service.');
		$this->long_desc = T_('Share contents to your favorite social networks using the ShareThis service.');
	}


	function get_coll_setting_definitions( & $params )
	{
		$default_params = array_merge(
            $params,
            array(
				'default_post_rendering' => 'opt-out'
			)
        );

		$plugin_settings = array(
            'sharethis_enabled' => array(
                    'label' => T_('Enabled'),
                    'type' => 'checkbox',
                    'note' => 'Is the plugin enabled for this collection?',
                ),
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

		return array_merge( $plugin_settings, parent::get_coll_setting_definitions( $default_params ) );
			
	}


	/** Plugin Hooks **/
	function SkinBeginHtmlHead( & $params )
	{
        global $Blog;

        if ( $this->get_coll_setting( 'sharethis_enabled', $Blog ) )
        {
            $url = 'http://w.sharethis.com/button/buttons.js';
            require_js( $url );
            add_headline('<script type="text/javascript">stLight.options({publisher:"'.$this->get_coll_setting('sharethis_publisher_id', $Blog).'",onhover: false});</script>');
        }
	}

	function RenderItemAsHtml( & $params )
	{
        global $Blog;

        if ( $this->get_coll_setting( 'sharethis_enabled', $Blog ) )
        {
            $content = & $params['data'];
            $item = & $params['Item'];

            //TODO: allow per post-type inclusion

            $title = $item->dget( 'title', 'htmlattr' );
            $url   = $item->get_permanent_url();
            $icons_style = ( $this->get_coll_setting('sharethis_iconsstyle', $Blog) == 'standard' ) ? '' : '_' . $this->get_coll_setting('sharethis_iconsstyle', $Blog); // TODO: support custom images

            if ( $this->get_coll_setting('sharethis_usechicklets', $Blog) )
            {
                $_selected_services = $this->get_coll_setting('sharethis_services', $Blog);
                $services = ( ! empty( $_selected_services ) ) ? str_getcsv( $this->get_coll_setting('sharethis_services', $Blog), ',') : $this->_supported_services;

                $content .= '<div>';
                foreach ( $services as $service )
                {
                    $service = trim($service);
                    if( in_array( $service, $this->_supported_services ) )
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
	}
}