<?php # -*- coding: utf-8 -*-
/**
 * Example showing how to manipulate the base plugin in other plugins.
 */


\add_filter( 'wpkrauts_base_plugin_data', function( $data )
{
	$data->stop = TRUE;
});