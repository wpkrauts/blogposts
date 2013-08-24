<?php # -*- coding: utf-8 -*-
error_reporting( E_ALL );
header( 'Content-Type: text/plain;charset=utf-8' );

// see http://wpkrauts.com/?p=573

class Singleton
{
	protected static $instance;

	public static function get_instance()
	{
		if ( NULL === self::$instance )
			self::$instance = new self;

		return self::$instance;
	}

	final private function __construct() {
	}

	final private function __clone() {
	}
}


$a = Singleton::get_instance();
$b = clone $a;
// Fatal error:  Call to private Singleton::__clone()