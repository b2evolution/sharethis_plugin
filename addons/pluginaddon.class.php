<?php

class sharethis_pluginAddOn
{
	var $coll_settings;

	function __construct( $plugin )
	{
		$this->plugin = $plugin;
		$this->load_coll_settings();
	}

	function extract_addon_name() 
	{
		$name = explode('_', get_class($this));
		return $name[1];
	}

	function get_addon_url()
	{
		return $this->plugin->get_plugin_url() . 'addons/' . $this->extract_addon_name(); 
	}

	function load_coll_settings()
	{
		if( is_array( $this->coll_settings ) )
		{
			return true;
		}

		global $Blog;

		$this->coll_settings = array();

		$coll_setting_definitions = $this->get_coll_setting_definitions();

		foreach( $coll_setting_definitions as $setting_name => $setting_value )
		{
			$this->coll_settings[$setting_name] = $this->plugin->get_coll_setting( $setting_name, $Blog );
		}

		return;
	}

	/**
	 * To be developed
	 */
	/*function ColorboxInit( & $params )
	{
		$publisher_id = $this->plugin->Settings->get('publisher_id');

		require_css( $this->plugin->get_plugin_url() . '/socialmodal.css', true );

		require_js( '#addthis#', 'rsc_url', true );
		require_js( $this->get_addon_url() . '/addthis_colorbox.js', true );

		$content = & $params['data'];
		$content = ',
						onOpen:function(){ openCallBack("'.$publisher_id.'") }';

		return true;
	}*/
}