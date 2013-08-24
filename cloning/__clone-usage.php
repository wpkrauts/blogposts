<?php # -*- coding: utf-8 -*-
error_reporting( E_ALL );
header( 'Content-Type: text/plain;charset=utf-8' );

// see http://wpkrauts.com/?p=573

class Original
{
	protected $var;

	public function __construct()
	{
		$this->var = new stdClass;
	}

	public function change_var( $name, $value )
	{
		$this->var->name = $value;
	}

	public function get_var( $name )
	{
		return $this->var->name;
	}

	public function __clone()
	{
		$this->var = clone $this->var;
	}
}

$a2 = new Original;
$a2->change_var( 'foo', 'hello' );
print $a2->get_var( 'foo' ) . PHP_EOL; // hello

$b2 = clone $a2;
$b2->change_var( 'foo', 'world' );

print $a2->get_var( 'foo' ) . PHP_EOL; // hello