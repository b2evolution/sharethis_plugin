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
	var $version = '1.5';
	var $author = 'The b2evo Group';
	var $group = 'rendering';

	var $plugin;

	var $available_addons;
	var $enabled_addon;

	/**
	 * Init
	 */
	function PluginInit( & $params )
	{
		$this->name = T_( 'ShareThis' );
		$this->short_desc = T_('Share contents to your favorite social networks using the ShareThis service.');
		$this->long_desc = T_('Share contents to your favorite social networks using the ShareThis service.');

		$this->available_addons = $this->get_available_addons(true); // Get the available addons and load their code
	}


	function get_available_addons( $load = false )
	{
		require_once('addons/pluginaddon.class.php');

		$available_addons = array();

		$dir = dirname(__FILE__) . '/addons';
		$dir_content = scandir($dir);
		
		foreach ( $dir_content as $element )
		{
			$subdir_name = $dir . '/' . $element;

			if ( is_dir($subdir_name) )
			{
				$filepath = $subdir_name . '/' . $element . '.class.php';
				if ( is_file($filepath) )
				{
					$available_addons[] = array($element, ucfirst($element));

					if ( $load )
					{
						require_once($filepath);
					}
				}
			}
		}

		return $available_addons;
	}

	
	function get_coll_setting_definitions( & $params )
	{
		$default_params = array_merge( $params, array(
				'default_post_rendering' => 'opt-out'
			) );

		$plugin_settings = array(
							'sharethis_enabled' => array(
									'label' => T_('Enabled'),
									'type' => 'checkbox',
									'note' => 'Is the plugin enabled for this collection?',
								),
							);

		return array_merge( $plugin_settings, socialshare_sharethis::get_coll_setting_definitions(), parent::get_coll_setting_definitions( $default_params ) ); 
			
	}


	function call_method( $object, $method, & $params )
	{
		if( method_exists($object, $method) )
		{
			$object->$method( $params );
		}
	}


	function get_coll_enabled_addon()
	{
		if( $this->status != 'enabled' )
		{
			return false;
		}

		global $Blog;

		if( $this->get_coll_setting( 'sharethis_enabled', $Blog ) )
		{
			$this->enabled_addon = new socialshare_sharethis($this);

			return true;
		}
	}


	/** Plugin Hooks **/
	function SkinBeginHtmlHead( & $params )
	{
		if ( $this->get_coll_enabled_addon( $params ) )
		{ 	
			$this->call_method( $this->enabled_addon, 'SkinBeginHtmlHead', $params );
		}
	}

	function RenderItemAsHtml( & $params )
	{
		if ( $this->get_coll_enabled_addon( $params ) )
		{
			$this->call_method( $this->enabled_addon, 'RenderItemAsHtml', $params );
		}
	}

	function RenderItemAsXml ( & $params )
	{
		if ( $this->get_coll_enabled_addon( $params ) )
		{
			$this->call_method( $this->enabled_addon, 'RenderItemAsXml', $params );
		}
	}

	function ColorboxInit( & $params )
	{
		if ( $this->get_coll_enabled_addon( $params ) )
		{
			$this->call_method( $this->enabled_addon, 'ColorboxInit', $params );
		}
	}
}